const express = require('express');
const bcrypt = require('bcryptjs');
const rateLimit = require('express-rate-limit');
const { db } = require('../db');
const { pool } = require('../mysql');
const { requireSocio } = require('../auth/socioAuth');
const { addNotification } = require('../notify');

const router = express.Router();

const loginLimiter = rateLimit({
  windowMs: 15 * 60 * 1000,
  max: 10,
  standardHeaders: true,
  legacyHeaders: false,
  message: { error: 'Demasiados intentos, intenta de nuevo más tarde' },
});

router.get('/csrf', (req, res) => res.json({ token: req.session.csrfToken }));
router.get('/branding', (req, res) => {
  const c = db.prepare('SELECT nombre_negocio, logo_url FROM bot_config WHERE id = 1').get();
  res.json(c || { nombre_negocio: null, logo_url: null });
});

router.get('/estado', (req, res) => {
  const { token, reset } = req.query;
  const socio = db.prepare('SELECT * FROM socios WHERE token = ?').get(token);
  if (!socio) return res.status(404).json({ error: 'Link inválido' });

  if (reset) {
    const r = db
      .prepare("SELECT id FROM password_resets WHERE token = ? AND socio_id = ? AND usado = 0 AND expira_en > datetime('now')")
      .get(reset, socio.id);
    if (r) return res.json({ estado: 'reset_password', nombre: socio.nombre });
  }

  if (req.session.socioId === socio.id) {
    return res.json({ estado: 'autenticado', nombre: socio.nombre });
  }
  if (!socio.password_hash) {
    return res.json({ estado: 'crear_password', nombre: socio.nombre });
  }
  return res.json({ estado: 'login', nombre: socio.nombre });
});

router.post('/activar', (req, res) => {
  const { token, password } = req.body;
  const socio = db.prepare('SELECT * FROM socios WHERE token = ?').get(token);
  if (!socio) return res.status(404).json({ error: 'Link inválido' });
  if (socio.password_hash) return res.status(400).json({ error: 'Esta cuenta ya tiene contraseña' });
  if (!password || password.length < 6) return res.status(400).json({ error: 'La contraseña debe tener al menos 6 caracteres' });

  const hash = bcrypt.hashSync(password, 10);
  db.prepare('UPDATE socios SET password_hash = ? WHERE id = ?').run(hash, socio.id);
  req.session.socioId = socio.id;
  res.json({ ok: true });
});

router.post('/login', loginLimiter, (req, res) => {
  const { token, password } = req.body;
  const socio = db.prepare('SELECT * FROM socios WHERE token = ?').get(token);
  if (!socio || !socio.password_hash || !bcrypt.compareSync(password || '', socio.password_hash)) {
    return res.status(401).json({ error: 'Contraseña incorrecta' });
  }
  req.session.socioId = socio.id;
  res.json({ ok: true });
});

router.post('/logout', (req, res) => {
  req.session.destroy(() => res.json({ ok: true }));
});

// Real product price tiers confirmed from the CRM (precio_1/2/3 selected by the
// client's nivel_numero — see jspdv/pdv.js). Falls back to precio_1 (tier 1) when the
// socio isn't linked to a real cliente_fd3_id yet (shouldn't happen post-sync).
router.get('/productos', requireSocio, async (req, res) => {
  const { categoria, q } = req.query;
  let nivel = 1;
  if (req.socio.cliente_fd3_id) {
    const [[cliente]] = await pool.query('SELECT nivel_numero FROM clientes WHERE id = ?', [req.socio.cliente_fd3_id]);
    if (cliente) nivel = cliente.nivel_numero || 1;
  }
  const precioCol = `precio_${nivel}`;

  let sql = `SELECT id, clave, descripcion, stock, ${precioCol} AS precio, precio_1 AS precio_lista FROM productos WHERE status = 1`;
  const params = [];
  if (q && q.trim()) {
    sql += ' AND descripcion LIKE ?';
    params.push(`%${q.trim()}%`);
  }
  sql += ' ORDER BY descripcion';
  const [rows] = await pool.query(sql, params);

  const productos = rows.map((p) => ({
    id: p.id,
    nombre: p.descripcion,
    clave: p.clave,
    categoria: categoria || 'General',
    precio: p.precio ?? p.precio_lista,
    precio_lista: p.precio_lista,
    stock: p.stock,
    imagen: `/uploads/productos/${p.clave}.png`,
  }));

  const page = Math.max(1, parseInt(req.query.page, 10) || 1);
  const pageSize = Math.min(60, Math.max(1, parseInt(req.query.pageSize, 10) || 24));
  const total = productos.length;
  const totalPages = Math.max(1, Math.ceil(total / pageSize));
  const start = (page - 1) * pageSize;
  res.json({
    items: productos.slice(start, start + pageSize),
    total,
    page,
    pageSize,
    totalPages,
    categorias: ['General'],
  });
});

router.put('/carrito', requireSocio, (req, res) => {
  const { items } = req.body;
  if (!Array.isArray(items) || items.length === 0) {
    db.prepare('DELETE FROM carritos_activos WHERE socio_id = ?').run(req.socio.id);
    return res.json({ ok: true });
  }
  const clean = items
    .map((it) => ({ producto_id: Number(it.producto_id), cantidad: Math.max(1, parseInt(it.cantidad, 10) || 1) }))
    .filter((it) => Number.isFinite(it.producto_id));
  db.prepare(
    `INSERT INTO carritos_activos (socio_id, items, actualizado_en, notificado) VALUES (?,?,CURRENT_TIMESTAMP,0)
     ON CONFLICT(socio_id) DO UPDATE SET items = excluded.items, actualizado_en = CURRENT_TIMESTAMP, notificado = 0`
  ).run(req.socio.id, JSON.stringify(clean));
  res.json({ ok: true });
});

// Orders do NOT become real sales here — they land in pedidos_whatsapp as 'pendiente'
// and staff confirms them from the CRM's WhatsApp section (which reuses
// VentaController::registroVenta), per the owner's explicit decision.
router.post('/pedido', requireSocio, async (req, res) => {
  const { items } = req.body;
  if (!Array.isArray(items) || items.length === 0) {
    return res.status(400).json({ error: 'El carrito está vacío' });
  }
  if (!req.socio.cliente_fd3_id) {
    return res.status(400).json({ error: 'Tu cuenta aún no está vinculada a un cliente real, contacta a un asesor.' });
  }

  let nivel = 1;
  const [[cliente]] = await pool.query('SELECT nivel_numero FROM clientes WHERE id = ?', [req.socio.cliente_fd3_id]);
  if (cliente) nivel = cliente.nivel_numero || 1;
  const precioCol = `precio_${nivel}`;

  const resolved = [];
  let total = 0;
  for (const item of items) {
    const [[producto]] = await pool.query(
      `SELECT id, descripcion, stock, ${precioCol} AS precio, precio_1 AS precio_lista FROM productos WHERE id = ? AND status = 1`,
      [item.producto_id]
    );
    if (!producto) return res.status(400).json({ error: 'Producto no encontrado' });
    const cantidad = Math.max(1, parseInt(item.cantidad, 10) || 1);
    const precio = producto.precio ?? producto.precio_lista;
    total += precio * cantidad;
    resolved.push({ id: producto.id, nombre: producto.descripcion, cantidad, precio_venta: precio, servicio: 0 });
  }

  const [result] = await pool.query(
    "INSERT INTO pedidos_whatsapp (cliente_id, items, total, estado, created_at, updated_at) VALUES (?,?,?, 'pendiente', NOW(), NOW())",
    [req.socio.cliente_fd3_id, JSON.stringify(resolved), total]
  );

  addNotification(`Nuevo pedido WA-${result.insertId} recibido de ${req.socio.nombre}`);
  db.prepare('DELETE FROM carritos_activos WHERE socio_id = ?').run(req.socio.id);
  res.json({ ok: true, orderId: `WA-${result.insertId}` });
});

router.get('/pedido/:orderId', requireSocio, async (req, res) => {
  const id = String(req.params.orderId).replace(/^WA-/, '');
  const [[pedido]] = await pool.query(
    'SELECT id, total, estado, created_at FROM pedidos_whatsapp WHERE id = ? AND cliente_id = ?',
    [id, req.socio.cliente_fd3_id]
  );
  if (!pedido) return res.status(404).json({ error: 'Pedido no encontrado' });
  res.json({ orderId: `WA-${pedido.id}`, total: pedido.total, estado: pedido.estado, fecha: pedido.created_at });
});

module.exports = { router };
