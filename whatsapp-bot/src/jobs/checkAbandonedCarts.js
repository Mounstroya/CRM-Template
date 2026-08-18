const { db } = require('../db');
const { addNotification } = require('../notify');

const ABANDONED_CART_THRESHOLD_SECONDS = 60 * 60;

function checkAbandonedCarts() {
  const rows = db
    .prepare(
      `SELECT ca.socio_id, s.nombre
       FROM carritos_activos ca
       JOIN socios s ON s.id = ca.socio_id
       WHERE ca.notificado = 0 AND (strftime('%s','now') - strftime('%s', ca.actualizado_en)) > ?`
    )
    .all(ABANDONED_CART_THRESHOLD_SECONDS);

  for (const row of rows) {
    addNotification(`Carrito abandonado: ${row.nombre} dejó productos sin comprar`);
    db.prepare('UPDATE carritos_activos SET notificado = 1 WHERE socio_id = ?').run(row.socio_id);
  }
}

module.exports = { checkAbandonedCarts };
