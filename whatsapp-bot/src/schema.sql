-- Bot-only local state. Productos/precios/clientes/pedidos live in the CRM's MySQL
-- (single source of truth) — this SQLite db only keeps what belongs to the bot itself:
-- conversation state, a lightweight mirror of clientes for phone/login lookups, and
-- staff-facing bot config (read via the CRM's WhatsApp section over the internal API).

CREATE TABLE IF NOT EXISTS socios (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  cliente_fd3_id INTEGER UNIQUE,
  numero_socio TEXT UNIQUE NOT NULL,
  nombre TEXT NOT NULL,
  telefono TEXT,
  token TEXT UNIQUE NOT NULL,
  password_hash TEXT,
  creado_en TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS prospectos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  telefono_whatsapp TEXT NOT NULL,
  nombre TEXT,
  motivo TEXT,
  fecha TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS quejas (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  socio_id INTEGER REFERENCES socios(id) ON DELETE SET NULL,
  texto TEXT NOT NULL,
  fecha TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS notificaciones (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  texto TEXT NOT NULL,
  leida INTEGER NOT NULL DEFAULT 0,
  fecha TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bot_config (
  id INTEGER PRIMARY KEY CHECK (id = 1),
  bot_activo INTEGER NOT NULL DEFAULT 1,
  horario_activo INTEGER NOT NULL DEFAULT 1,
  hora_inicio TEXT NOT NULL DEFAULT '08:00',
  hora_fin TEXT NOT NULL DEFAULT '20:00',
  mensaje_bienvenida TEXT NOT NULL DEFAULT '¡Hola! Bienvenido. Soy tu asistente automático.',
  mensaje_fuera_horario TEXT NOT NULL DEFAULT 'Gracias por escribir. Nuestro horario de atención es de {hora_inicio} a {hora_fin}. Te responderemos apenas abramos.',
  logo_url TEXT,
  nombre_negocio TEXT DEFAULT 'FusionD3'
);

CREATE TABLE IF NOT EXISTS bot_keywords (
  palabra TEXT PRIMARY KEY,
  respuesta TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS carritos_activos (
  socio_id INTEGER PRIMARY KEY REFERENCES socios(id) ON DELETE CASCADE,
  items TEXT NOT NULL,
  actualizado_en TEXT DEFAULT CURRENT_TIMESTAMP,
  notificado INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS password_resets (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  socio_id INTEGER NOT NULL REFERENCES socios(id) ON DELETE CASCADE,
  token TEXT UNIQUE NOT NULL,
  expira_en TEXT NOT NULL,
  usado INTEGER NOT NULL DEFAULT 0,
  creado_en TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bot_sessions (
  jid TEXT PRIMARY KEY,
  estado TEXT NOT NULL DEFAULT 'inicio',
  socio_id INTEGER REFERENCES socios(id) ON DELETE SET NULL,
  datos_temp TEXT,
  actualizado_en TEXT DEFAULT CURRENT_TIMESTAMP
);
