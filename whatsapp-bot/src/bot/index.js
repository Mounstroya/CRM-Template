const path = require('path');
const fs = require('fs');
const qrcodeTerminal = require('qrcode-terminal');
const QRCode = require('qrcode');
const { handleIncoming } = require('./conversation');

const DATA_DIR = path.join(__dirname, '..', '..', 'data');
const AUTH_DIR = path.join(DATA_DIR, 'baileys_auth');

let botState = { status: 'iniciando', qr: null };
let currentSock = null;

function getBotState() {
  return botState;
}

// Normalizes a MX phone/jid to WhatsApp's JID format (E.164 digits + @s.whatsapp.net).
function toJid(phone) {
  const digits = String(phone).replace(/\D/g, '');
  if (digits.includes('@')) return phone;
  const withCountry = digits.length === 10 ? `52${digits}` : digits;
  return `${withCountry}@s.whatsapp.net`;
}

// Used by the internal API (Laravel's "Enviar catálogo" button, order-status pushes).
// Verification for this phase stops at "Baileys accepted the send" — real delivery can
// only be confirmed once the owner links a real number by scanning the QR himself.
async function sendMessage(phone, text) {
  // currentSock gets assigned as soon as makeWASocket() runs, well before pairing
  // finishes — checking botState.status (only 'conectado' after a real 'open' event)
  // is what actually tells us Baileys has an authenticated session, not just a socket
  // object. Without this check, calling sendMessage pre-pairing fails deep inside
  // Baileys with a confusing "Cannot read properties of undefined (reading 'id')"
  // instead of a clear "not connected" error.
  if (!currentSock || botState.status !== 'conectado') {
    throw new Error('El bot de WhatsApp no está conectado todavía (falta escanear el QR).');
  }
  const jid = toJid(phone);
  return currentSock.sendMessage(jid, { text });
}

async function startBot() {
  const {
    default: makeWASocket,
    useMultiFileAuthState,
    DisconnectReason,
    fetchLatestBaileysVersion,
  } = require('@whiskeysockets/baileys');
  const { state, saveCreds } = await useMultiFileAuthState(AUTH_DIR);
  const { version } = await fetchLatestBaileysVersion();

  const sock = makeWASocket({
    version,
    auth: state,
    printQRInTerminal: false,
    syncFullHistory: false,
  });
  currentSock = sock;

  sock.ev.on('creds.update', () => {
    saveCreds().catch((err) => console.error('Error guardando credenciales de WhatsApp:', err));
  });

  sock.ev.on('connection.update', async (update) => {
    try {
      const { connection, lastDisconnect, qr } = update;
      if (qr) {
        botState = { status: 'esperando_qr', qr: await QRCode.toDataURL(qr) };
        console.log('Escanea este código QR con WhatsApp (Dispositivos vinculados):');
        qrcodeTerminal.generate(qr, { small: true });
      }
      if (connection === 'close') {
        const statusCode = lastDisconnect?.error?.output?.statusCode ?? null;
        const isLoggedOut = statusCode === DisconnectReason.loggedOut;
        // restartRequired (515) es el paso normal justo después de escanear el QR con éxito:
        // en ese caso las credenciales ya quedaron registradas y solo hay que reabrir el socket.
        const isRestartRequired = statusCode === DisconnectReason.restartRequired;
        // Si el QR caducó sin escanearse (u otro fallo a medio pareo), las credenciales en
        // disco quedan incompletas: reconectar reusándolas produce un loop infinito de
        // errores 405/503 y nunca vuelve a salir un QR. Hay que detectarlo y limpiar solo.
        const pairingIncomplete = !isRestartRequired && !state.creds.registered;
        console.log('Conexión de WhatsApp cerrada.', { statusCode, isLoggedOut, pairingIncomplete });
        currentSock = null;

        if (isLoggedOut || pairingIncomplete) {
          botState = { status: isLoggedOut ? 'desconectado' : 'reconectando', qr: null };
          fs.rm(AUTH_DIR, { recursive: true, force: true }, (err) => {
            if (err) console.error('Error limpiando credenciales de WhatsApp:', err);
            setTimeout(() => {
              startBot().catch((err2) => console.error('Fallo al reiniciar el bot de WhatsApp:', err2));
            }, 3000);
          });
        } else {
          botState = { status: 'reconectando', qr: null };
          setTimeout(() => {
            startBot().catch((err) => console.error('Fallo al reconectar el bot de WhatsApp:', err));
          }, 3000);
        }
      } else if (connection === 'open') {
        botState = { status: 'conectado', qr: null };
        console.log('Conexión de WhatsApp establecida.');
      }
    } catch (err) {
      console.error('Error manejando actualización de conexión de WhatsApp:', err);
    }
  });

  sock.ev.on('messages.upsert', async ({ messages, type }) => {
    if (type !== 'notify') return;
    for (const msg of messages) {
      if (!msg.message || msg.key.fromMe) continue;
      const jid = msg.key.remoteJid;
      if (!jid || jid.endsWith('@g.us') || jid === 'status@broadcast') continue;

      const text =
        msg.message.conversation ||
        msg.message.extendedTextMessage?.text ||
        msg.message.imageMessage?.caption ||
        '';
      if (!text) continue;

      try {
        const replies = await handleIncoming(jid, text);
        for (const reply of replies) {
          await sock.sendMessage(jid, { text: reply });
        }
      } catch (err) {
        console.error('Error procesando mensaje del bot:', err);
      }
    }
  });

  return sock;
}

module.exports = { startBot, getBotState, sendMessage, toJid };
