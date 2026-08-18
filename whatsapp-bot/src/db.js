const path = require('path');
const fs = require('fs');
const crypto = require('crypto');
const Database = require('better-sqlite3');

const DATA_DIR = path.join(__dirname, '..', 'data');
if (!fs.existsSync(DATA_DIR)) fs.mkdirSync(DATA_DIR, { recursive: true });

const db = new Database(path.join(DATA_DIR, 'ventasbot.db'));
db.pragma('journal_mode = WAL');
db.pragma('foreign_keys = ON');

db.exec(fs.readFileSync(path.join(__dirname, 'schema.sql'), 'utf8'));

function seed() {
  const noConfig = db.prepare('SELECT COUNT(*) c FROM bot_config').get().c === 0;
  if (noConfig) {
    db.prepare(
      `INSERT INTO bot_config (id, bot_activo, horario_activo, hora_inicio, hora_fin, mensaje_bienvenida, nombre_negocio)
       VALUES (1, 1, 1, '08:00', '20:00', ?, 'FusionD3')`
    ).run('¡Hola! Bienvenido a FusionD3, soy tu asistente automático.');
  }

  const noKeywords = db.prepare('SELECT COUNT(*) c FROM bot_keywords').get().c === 0;
  if (noKeywords) {
    const seedKeywords = [
      ['horario', 'Nuestro horario de atención es de 08:00 a 20:00.'],
      ['asesor', 'En un momento un asesor humano se pondrá en contacto contigo.'],
    ];
    const insert = db.prepare('INSERT INTO bot_keywords (palabra, respuesta) VALUES (?,?)');
    const tx = db.transaction((rows) => rows.forEach((r) => insert.run(...r)));
    tx(seedKeywords);
  }
}

seed();

function generateToken() {
  return crypto.randomBytes(24).toString('base64url');
}

module.exports = { db, generateToken, DATA_DIR };
