const { db } = require('../db');
const { pool } = require('../mysql');

function normalize(text) {
  return (text || '')
    .normalize('NFD')
    .replace(/[̀-ͯ]/g, '')
    .toLowerCase()
    .trim();
}

function isOption(norm, options) {
  return options.some((o) => norm === normalize(o));
}

function isWithinSchedule(config) {
  const [startH, startM] = config.hora_inicio.split(':').map(Number);
  const [endH, endM] = config.hora_fin.split(':').map(Number);
  const now = new Date();
  const minutesNow = now.getHours() * 60 + now.getMinutes();
  const startMinutes = startH * 60 + startM;
  const endMinutes = endH * 60 + endM;
  return minutesNow >= startMinutes && minutesNow <= endMinutes;
}

function getSession(jid) {
  const row = db.prepare('SELECT * FROM bot_sessions WHERE jid = ?').get(jid);
  if (row) return row;
  return { jid, estado: 'inicio', socio_id: null, datos_temp: '{}' };
}

function setSession(jid, estado, socio_id = undefined, datos = undefined) {
  const current = getSession(jid);
  const nextSocioId = socio_id === undefined ? current.socio_id : socio_id;
  const nextDatos = datos === undefined ? current.datos_temp : JSON.stringify(datos);
  db.prepare(
    `INSERT INTO bot_sessions (jid, estado, socio_id, datos_temp, actualizado_en)
     VALUES (?,?,?,?, CURRENT_TIMESTAMP)
     ON CONFLICT(jid) DO UPDATE SET estado=excluded.estado, socio_id=excluded.socio_id, datos_temp=excluded.datos_temp, actualizado_en=CURRENT_TIMESTAMP`
  ).run(jid, estado, nextSocioId, nextDatos);
}

function getDatos(session) {
  try {
    return JSON.parse(session.datos_temp || '{}');
  } catch {
    return {};
  }
}

function catalogLink(token) {
  const base = process.env.CATALOG_BASE_URL || 'http://localhost:2900';
  return `${base}/catalogo?token=${token}`;
}

// jid looks like "5217771234567@s.whatsapp.net" — strip to digits, then to the 10-digit
// MX national number so it can match clientes.telefono (which was seeded as plain
// 10-digit strings, e.g. "7771275357").
function phoneFromJid(jid) {
  const digits = String(jid).split('@')[0].replace(/\D/g, '');
  if (digits.length > 10 && digits.startsWith('52')) return digits.slice(-10);
  return digits.slice(-10);
}

// Real-phone recognition first (owner's explicit priority), falling back to the
// existing typed "número de socio" flow only when there's no phone match — matches a
// mirrored `socios` row (synced from `clientes`) so bot-local state (carrito, etc.)
// still works normally afterward.
async function findSocioByJid(jid) {
  const phone = phoneFromJid(jid);
  if (!phone) return null;
  const [rows] = await pool.query('SELECT id FROM clientes WHERE telefono LIKE ? LIMIT 1', [`%${phone}`]);
  if (!rows.length) return null;
  return db.prepare('SELECT * FROM socios WHERE cliente_fd3_id = ?').get(rows[0].id) || null;
}

async function listGarantias(clienteId) {
  const [rows] = await pool.query(
    'SELECT id, producto, motivo, status FROM garantias WHERE cliente_id = ? ORDER BY id DESC LIMIT 10',
    [clienteId]
  );
  return rows;
}

async function findPedido(clienteId, orderRef) {
  const id = String(orderRef).replace(/^WA-/i, '').trim();
  if (!/^\d+$/.test(id)) return null;
  const [[row]] = await pool.query(
    'SELECT id, total, estado, created_at FROM pedidos_whatsapp WHERE id = ? AND cliente_id = ?',
    [id, clienteId]
  );
  return row || null;
}

async function listPedidos(clienteId) {
  const [rows] = await pool.query(
    'SELECT id, total, estado, created_at FROM pedidos_whatsapp WHERE cliente_id = ? ORDER BY id DESC LIMIT 5',
    [clienteId]
  );
  return rows;
}

async function handleIncoming(jid, rawText) {
  const config = db.prepare('SELECT * FROM bot_config WHERE id = 1').get();
  if (!config.bot_activo) return [];

  const text = (rawText || '').trim();
  const norm = normalize(text);
  if (!text) return [];

  if (config.horario_activo && !isWithinSchedule(config)) {
    return [
      config.mensaje_fuera_horario
        .replaceAll('{hora_inicio}', config.hora_inicio)
        .replaceAll('{hora_fin}', config.hora_fin),
    ];
  }

  const keyword = db
    .prepare('SELECT * FROM bot_keywords')
    .all()
    .find((k) => norm.includes(normalize(k.palabra)));
  if (keyword) return [keyword.respuesta];

  let session = getSession(jid);

  // First contact from this jid: try to recognize the real phone number against
  // clientes.telefono before falling back to the typed-in flow.
  if (session.estado === 'inicio' || !session.socio_id) {
    const socio = await findSocioByJid(jid);
    if (socio) {
      setSession(jid, 'menu_socio', socio.id);
      session = getSession(jid);
      if (session.estado === 'menu_socio') {
        return [
          `¡Hola ${socio.nombre}! Te reconocimos por tu número. ¿Qué necesitas?\n*1.* Ver catálogo\n*2.* Quejas o sugerencias\n*3.* Mis pedidos\n*4.* Mis garantías`,
        ];
      }
    }
  }

  // Order-status / garantía keywords work from anywhere once the customer is identified.
  if (session.socio_id && (norm.includes('pedido') || /^wa-?\d+$/i.test(norm))) {
    const socio = db.prepare('SELECT * FROM socios WHERE id = ?').get(session.socio_id);
    if (socio?.cliente_fd3_id) {
      if (/\d/.test(norm)) {
        const pedido = await findPedido(socio.cliente_fd3_id, text);
        if (pedido) {
          return [`Pedido WA-${pedido.id}: estado *${pedido.estado}*, total $${pedido.total} (${pedido.created_at}).`];
        }
      }
      const pedidos = await listPedidos(socio.cliente_fd3_id);
      if (!pedidos.length) return ['No encontramos pedidos a tu nombre todavía.'];
      return [pedidos.map((p) => `WA-${p.id}: ${p.estado} — $${p.total}`).join('\n')];
    }
  }
  if (session.socio_id && norm.includes('garantia')) {
    const socio = db.prepare('SELECT * FROM socios WHERE id = ?').get(session.socio_id);
    if (socio?.cliente_fd3_id) {
      const garantias = await listGarantias(socio.cliente_fd3_id);
      if (!garantias.length) return ['No encontramos garantías registradas a tu nombre.'];
      return [garantias.map((g) => `#${g.id} ${g.producto}: *${g.status}*`).join('\n')];
    }
  }

  switch (session.estado) {
    case 'esperando_tipo': {
      if (isOption(norm, ['1', 'socio', 'soy socio', 'cliente activo'])) {
        setSession(jid, 'esperando_numero_socio');
        return ['Perfecto, escribe tu número de socio:'];
      }
      if (isOption(norm, ['2', 'aun no', 'aun no soy cliente', 'no'])) {
        setSession(jid, 'esperando_nombre_prospecto', null, { motivo: 'aun_no_es_cliente' });
        return ['Sin problema. ¿Cuál es tu nombre para que un asesor te contacte?'];
      }
      return ['No entendí tu respuesta. Escribe *1* si ya eres socio, o *2* si aún no.'];
    }

    case 'esperando_numero_socio': {
      const socio = db.prepare('SELECT * FROM socios WHERE UPPER(numero_socio) = UPPER(?)').get(text);
      if (socio) {
        setSession(jid, 'menu_socio', socio.id);
        return [`¡Hola ${socio.nombre}! ¿Qué necesitas?\n*1.* Ver catálogo\n*2.* Quejas o sugerencias\n*3.* Mis pedidos\n*4.* Mis garantías`];
      }
      setSession(jid, 'esperando_nombre_prospecto', null, { motivo: `numero_invalido:${text}` });
      return ['No encontramos ese número de socio. ¿Cuál es tu nombre para que un asesor te contacte?'];
    }

    case 'esperando_nombre_prospecto': {
      const datos = getDatos(session);
      db.prepare('INSERT INTO prospectos (telefono_whatsapp, nombre, motivo) VALUES (?,?,?)').run(
        jid,
        text,
        datos.motivo || null
      );
      setSession(jid, 'inicio', null, {});
      return ['¡Gracias! Un asesor se pondrá en contacto contigo pronto.'];
    }

    case 'menu_socio': {
      if (isOption(norm, ['1', 'ver catalogo', 'catalogo'])) {
        const socio = db.prepare('SELECT * FROM socios WHERE id = ?').get(session.socio_id);
        setSession(jid, 'inicio', session.socio_id);
        return [`Aquí tienes tu catálogo personalizado:\n${catalogLink(socio.token)}`];
      }
      if (isOption(norm, ['2', 'quejas', 'quejas o sugerencias', 'sugerencias', 'queja', 'sugerencia'])) {
        setSession(jid, 'esperando_queja', session.socio_id);
        return ['Cuéntame tu queja o sugerencia:'];
      }
      if (isOption(norm, ['3', 'mis pedidos', 'pedidos'])) {
        const socio = db.prepare('SELECT * FROM socios WHERE id = ?').get(session.socio_id);
        const pedidos = socio?.cliente_fd3_id ? await listPedidos(socio.cliente_fd3_id) : [];
        setSession(jid, 'menu_socio', session.socio_id);
        if (!pedidos.length) return ['No encontramos pedidos a tu nombre todavía.'];
        return [pedidos.map((p) => `WA-${p.id}: ${p.estado} — $${p.total}`).join('\n')];
      }
      if (isOption(norm, ['4', 'mis garantias', 'garantias', 'garantía', 'garantías'])) {
        const socio = db.prepare('SELECT * FROM socios WHERE id = ?').get(session.socio_id);
        const garantias = socio?.cliente_fd3_id ? await listGarantias(socio.cliente_fd3_id) : [];
        setSession(jid, 'menu_socio', session.socio_id);
        if (!garantias.length) return ['No encontramos garantías registradas a tu nombre.'];
        return [garantias.map((g) => `#${g.id} ${g.producto}: *${g.status}*`).join('\n')];
      }
      return ['No entendí. Escribe *1* catálogo, *2* quejas, *3* mis pedidos, o *4* mis garantías.'];
    }

    case 'esperando_queja': {
      db.prepare('INSERT INTO quejas (socio_id, texto) VALUES (?,?)').run(session.socio_id, text);
      setSession(jid, 'menu_socio', session.socio_id);
      return ['Gracias por tu comentario, quedó registrado.\n\n¿Algo más?\n*1.* Ver catálogo\n*2.* Quejas o sugerencias\n*3.* Mis pedidos\n*4.* Mis garantías'];
    }

    case 'inicio':
    default: {
      setSession(jid, 'esperando_tipo');
      return [config.mensaje_bienvenida, 'Para continuar, dime:\n*1.* Soy socio (cliente activo)\n*2.* Aún no soy cliente'];
    }
  }
}

module.exports = { handleIncoming, phoneFromJid, findSocioByJid, listGarantias, listPedidos, findPedido };
