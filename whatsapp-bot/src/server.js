const path = require('path');
const express = require('express');
const helmet = require('helmet');

process.on('unhandledRejection', (err) => {
  console.error('Promesa rechazada sin manejar:', err);
});
process.on('uncaughtException', (err) => {
  console.error('Excepción no capturada:', err);
});

require('./db');
const { socioSession } = require('./auth/socioAuth');
const { ensureCsrfToken, requireCsrfToken } = require('./auth/csrf');
const { router: catalogoRouter } = require('./routes/catalogo');
const { router: internalRouter } = require('./routes/internal');
const { startBot } = require('./bot');
const { checkAbandonedCarts } = require('./jobs/checkAbandonedCarts');
const { syncSociosFromClientes } = require('./syncSocios');

const app = express();
app.set('trust proxy', 1);
app.use(
  helmet({
    crossOriginEmbedderPolicy: false,
    contentSecurityPolicy: {
      directives: {
        defaultSrc: ["'self'"],
        scriptSrc: ["'self'"],
        styleSrc: ["'self'", "'unsafe-inline'"],
        imgSrc: ["'self'", 'data:', 'https:'],
        fontSrc: ["'self'", 'data:'],
        connectSrc: ["'self'"],
        frameSrc: ["'self'", 'https://www.youtube.com', 'https://drive.google.com'],
        objectSrc: ["'none'"],
        baseUri: ["'self'"],
        frameAncestors: ["'self'"],
      },
    },
  })
);
app.use(express.json());

// Internal-only API for the Laravel CRM (protected by INTERNAL_API_SECRET, never
// exposed publicly — this container isn't published outside the docker network except
// the catalog port).
app.use('/internal', internalRouter);

app.use('/catalogo/api', socioSession, ensureCsrfToken, requireCsrfToken, catalogoRouter);

app.use('/shared', express.static(path.join(__dirname, '..', 'public', 'shared')));
app.use('/catalogo', express.static(path.join(__dirname, '..', 'public', 'catalogo')));
app.use(
  '/uploads/productos',
  express.static(process.env.PRODUCT_PHOTOS_DIR || path.join(__dirname, '..', 'data', 'uploads', 'productos'), {
    setHeaders: (res) => res.setHeader('X-Content-Type-Options', 'nosniff'),
  })
);

// The old /panel staff login is retired for good — one login now, in the Laravel CRM.
app.get('/panel', (req, res) => res.redirect('/catalogo'));
app.get('/', (req, res) => res.redirect('/catalogo'));

app.use('/catalogo/api', (err, req, res, next) => {
  console.error(err);
  res.status(400).json({ error: err.message || 'Error inesperado' });
});
app.use('/internal', (err, req, res, next) => {
  console.error(err);
  res.status(400).json({ error: err.message || 'Error inesperado' });
});

const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`VentasBot escuchando en el puerto ${PORT}`);
});

startBot().catch((err) => {
  console.error('No se pudo iniciar el bot de WhatsApp:', err);
});

checkAbandonedCarts();
setInterval(checkAbandonedCarts, 15 * 60 * 1000);

syncSociosFromClientes()
  .then((r) => console.log('Sincronización inicial de socios:', r))
  .catch((err) => console.error('Error en la sincronización inicial de socios:', err));
setInterval(() => {
  syncSociosFromClientes().catch((err) => console.error('Error sincronizando socios:', err));
}, 5 * 60 * 1000);
