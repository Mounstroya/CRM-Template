const { db } = require('./db');

function addNotification(texto) {
  db.prepare('INSERT INTO notificaciones (texto) VALUES (?)').run(texto);
}

module.exports = { addNotification };
