const session = require('express-session');
const { db } = require('../db');
const SqliteSessionStore = require('./sqliteSessionStore');

const sessionSecret = process.env.SESSION_SECRET || 'dev-secret-change-me';

const socioSession = session({
  name: 'socio_sid',
  secret: sessionSecret,
  resave: false,
  saveUninitialized: false,
  store: new SqliteSessionStore(),
  cookie: { httpOnly: true, sameSite: 'lax', maxAge: 30 * 24 * 60 * 60 * 1000 },
});

function requireSocio(req, res, next) {
  if (!req.session.socioId) return res.status(401).json({ error: 'No autenticado' });
  const socio = db.prepare('SELECT * FROM socios WHERE id = ?').get(req.session.socioId);
  if (!socio) return res.status(401).json({ error: 'No autenticado' });
  req.socio = socio;
  next();
}

module.exports = { socioSession, requireSocio };
