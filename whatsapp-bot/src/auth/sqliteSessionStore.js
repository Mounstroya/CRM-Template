const session = require('express-session');
const { db } = require('../db');

db.exec(`
  CREATE TABLE IF NOT EXISTS express_sessions (
    sid TEXT PRIMARY KEY,
    session TEXT NOT NULL,
    expires INTEGER NOT NULL
  )
`);

const DEFAULT_TTL_MS = 24 * 60 * 60 * 1000;

function expiresFor(sessionData) {
  if (sessionData.cookie?.expires) return new Date(sessionData.cookie.expires).getTime();
  return Date.now() + DEFAULT_TTL_MS;
}

class SqliteSessionStore extends session.Store {
  get(sid, cb) {
    try {
      const row = db.prepare('SELECT session, expires FROM express_sessions WHERE sid = ?').get(sid);
      if (!row) return cb(null, null);
      if (row.expires < Date.now()) {
        db.prepare('DELETE FROM express_sessions WHERE sid = ?').run(sid);
        return cb(null, null);
      }
      cb(null, JSON.parse(row.session));
    } catch (err) {
      cb(err);
    }
  }

  set(sid, sessionData, cb) {
    try {
      db.prepare(
        `INSERT INTO express_sessions (sid, session, expires) VALUES (?,?,?)
         ON CONFLICT(sid) DO UPDATE SET session = excluded.session, expires = excluded.expires`
      ).run(sid, JSON.stringify(sessionData), expiresFor(sessionData));
      cb(null);
    } catch (err) {
      cb(err);
    }
  }

  destroy(sid, cb) {
    try {
      db.prepare('DELETE FROM express_sessions WHERE sid = ?').run(sid);
      cb(null);
    } catch (err) {
      cb(err);
    }
  }

  touch(sid, sessionData, cb) {
    try {
      db.prepare('UPDATE express_sessions SET expires = ? WHERE sid = ?').run(expiresFor(sessionData), sid);
      cb(null);
    } catch (err) {
      cb(err);
    }
  }
}

setInterval(() => {
  db.prepare('DELETE FROM express_sessions WHERE expires < ?').run(Date.now());
}, 60 * 60 * 1000).unref();

module.exports = SqliteSessionStore;
