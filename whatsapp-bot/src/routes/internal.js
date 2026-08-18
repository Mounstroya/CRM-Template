const express = require('express');
const { db } = require('../db');
const { getBotState, sendMessage } = require('../bot');
const { syncSociosFromClientes } = require('../syncSocios');

const router = express.Router();

function requireInternalSecret(req, res, next) {
  const secret = req.header('X-Internal-Secret');
  if (!secret || secret !== process.env.INTERNAL_API_SECRET) {
    return res.status(403).json({ error: 'No autorizado' });
  }
  next();
}

router.use(requireInternalSecret);

router.get('/status', (req, res) => {
  res.json(getBotState());
});

router.post('/sync-socios', async (req, res) => {
  try {
    const result = await syncSociosFromClientes();
    res.json({ ok: true, ...result });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

router.get('/prospectos', (req, res) => {
  const rows = db.prepare('SELECT * FROM prospectos ORDER BY id DESC LIMIT 100').all();
  res.json({ prospectos: rows });
});

router.get('/quejas', (req, res) => {
  const rows = db
    .prepare(
      `SELECT q.id, q.texto, q.fecha, s.nombre AS socio_nombre, s.cliente_fd3_id
       FROM quejas q LEFT JOIN socios s ON s.id = q.socio_id
       ORDER BY q.id DESC LIMIT 100`
    )
    .all();
  res.json({ quejas: rows });
});

router.get('/notificaciones', (req, res) => {
  const rows = db.prepare('SELECT * FROM notificaciones ORDER BY id DESC LIMIT 100').all();
  res.json({ notificaciones: rows });
});

// Used by the CRM's "Enviar catálogo" button. Resolves the socio by cliente_fd3_id
// (preferred, sent by Laravel which already knows the real cliente) or by phone.
router.post('/enviar-catalogo', async (req, res) => {
  const { cliente_fd3_id, telefono } = req.body;
  let socio = null;
  if (cliente_fd3_id) {
    socio = db.prepare('SELECT * FROM socios WHERE cliente_fd3_id = ?').get(cliente_fd3_id);
  } else if (telefono) {
    socio = db.prepare('SELECT * FROM socios WHERE telefono LIKE ?').get(`%${String(telefono).replace(/\D/g, '').slice(-10)}`);
  }
  if (!socio) return res.status(404).json({ error: 'No encontramos a ese cliente en el bot (aún no sincronizado o sin teléfono).' });
  if (!socio.telefono) return res.status(400).json({ error: 'Ese cliente no tiene teléfono registrado.' });

  const base = process.env.CATALOG_BASE_URL || 'http://localhost:2900';
  const link = `${base}/catalogo?token=${socio.token}`;
  try {
    await sendMessage(socio.telefono, `Hola ${socio.nombre}, aquí tienes tu catálogo personalizado:\n${link}`);
    res.json({ ok: true, message: 'Baileys aceptó el mensaje para encolarlo.', link });
  } catch (err) {
    res.status(503).json({ error: err.message });
  }
});

// Used by WhatsappController when staff confirms/advances a pedido, to notify the
// customer of the new status.
router.post('/enviar-mensaje', async (req, res) => {
  const { telefono, texto } = req.body;
  if (!telefono || !texto) return res.status(400).json({ error: 'Falta telefono o texto' });
  try {
    await sendMessage(telefono, texto);
    res.json({ ok: true, message: 'Baileys aceptó el mensaje para encolarlo.' });
  } catch (err) {
    res.status(503).json({ error: err.message });
  }
});

module.exports = { router };
