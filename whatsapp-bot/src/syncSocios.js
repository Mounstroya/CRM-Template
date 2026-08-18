const { db, generateToken } = require('./db');
const { pool } = require('./mysql');

// One-way mirror: clientes (MySQL, source of truth) -> socios (SQLite, bot-local state
// like password_hash/token/session that doesn't belong in the CRM). Never writes back
// to MySQL. Upserts by cliente_fd3_id; new clientes get a fresh catalog token.
async function syncSociosFromClientes() {
  const [rows] = await pool.query('SELECT id, nombre, telefono FROM clientes');

  const existing = db.prepare('SELECT id, cliente_fd3_id, nombre, telefono FROM socios WHERE cliente_fd3_id IS NOT NULL').all();
  const byClienteId = new Map(existing.map((s) => [s.cliente_fd3_id, s]));

  const insert = db.prepare(
    'INSERT INTO socios (cliente_fd3_id, numero_socio, nombre, telefono, token) VALUES (?,?,?,?,?)'
  );
  const update = db.prepare('UPDATE socios SET nombre = ?, telefono = ? WHERE cliente_fd3_id = ?');

  let created = 0;
  let updated = 0;
  const tx = db.transaction(() => {
    for (const c of rows) {
      const current = byClienteId.get(c.id);
      if (!current) {
        insert.run(c.id, `C${c.id}`, c.nombre || `Cliente ${c.id}`, c.telefono || null, generateToken());
        created++;
      } else if (current.nombre !== c.nombre || current.telefono !== c.telefono) {
        update.run(c.nombre || current.nombre, c.telefono || null, c.id);
        updated++;
      }
    }
  });
  tx();

  return { total: rows.length, created, updated };
}

module.exports = { syncSociosFromClientes };
