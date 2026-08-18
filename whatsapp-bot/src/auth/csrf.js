const crypto = require('crypto');

const SAFE_METHODS = new Set(['GET', 'HEAD', 'OPTIONS']);

function ensureCsrfToken(req, res, next) {
  if (!req.session.csrfToken) {
    req.session.csrfToken = crypto.randomBytes(24).toString('hex');
  }
  next();
}

function requireCsrfToken(req, res, next) {
  if (SAFE_METHODS.has(req.method)) return next();
  const headerToken = req.get('X-CSRF-Token');
  if (!req.session.csrfToken || headerToken !== req.session.csrfToken) {
    return res.status(403).json({ error: 'Token CSRF inválido o faltante' });
  }
  next();
}

module.exports = { ensureCsrfToken, requireCsrfToken };
