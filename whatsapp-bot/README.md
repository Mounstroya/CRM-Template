# VentasBot — README y plan de reestructura

Bot de WhatsApp (Baileys) + panel + catálogo por socio para pedidos. Express + SQLite (`better-sqlite3`), todo en un solo proceso Node. Manual funcional completo (con diagrama del árbol del bot) en `public/panel/manual.html`.

## Estado actual (2026-08-05) — en transición

Este proyecto **todavía no sigue del todo** el patrón "código base + carpeta por cliente" que ya usan `Monitores_vinculacion/`, `Portabilidades/`, `Rutas/` y `SmartCrm/`. Está a medio camino:

- El código base vive en la raíz de este repo (`~/whatsapp-ventas-bot/`: `Dockerfile`, `src/`, `public/`, `package.json`).
- El contenedor de **Flores** (primer cliente real, en producción) sigue desplegado directo desde el `docker-compose.yml` de la raíz — **no se movió a una subcarpeta todavía**, decisión explícita para no tocar su subdominio/volumen mientras está en uso real.
- El contenedor **Demo** (`Demo/demo_ventasbot/`) sí sigue ya el patrón nuevo: carpeta propia con su `docker-compose.yml` + `.env`, `build: ../..` contra el código base de esta misma raíz.

| Cliente | Carpeta | Contenedor | Puerto | Subdominio | Notas |
|---|---|---|---|---|---|
| Flores (real, producción) | raíz del repo (`docker-compose.yml`) | `whatsapp_ventas_bot` | 2900 | `ventasbot.mounstroya.xyz` | Pendiente migrar a `Flores/flores_ventasbot/` |
| Demo (ficticio, para venta) | `Demo/demo_ventasbot/` | `demo_ventasbot` | 2901 | `ventasbotdemo.mounstroya.xyz` | Datos cargados desde `backups/demo_2026-07-31.tar.gz`, sin sesión de WhatsApp (QR limpio a propósito) |

## Plan: migrar Flores al patrón carpeta-por-cliente

Cuando se decida hacerlo (no ejecutado todavía — requiere parar brevemente el bot de Flores en producción):

1. `mkdir -p Flores/flores_ventasbot`
2. Copiar `docker-compose.yml` de la raíz a `Flores/flores_ventasbot/docker-compose.yml`, cambiar:
   - `build: .` → `build: ../..`
   - `container_name: whatsapp_ventas_bot` (dejarlo igual, no renombrar contenedores con datos en producción)
   - Volumen con nombre explícito (`flores_ventasbot_data`) en vez del volumen anónimo actual del proyecto raíz
   - Red con subred propia (`networks.default.ipam.config.subnet`, ver sección de subredes abajo) en vez de dejar que compose cree una red default sin especificar — el pool de Docker en este host ya está muy ajustado.
3. `docker compose down` en la raíz (para el contenedor viejo) → copiar/mover el volumen de datos (`whatsapp-ventas-bot_ventasbot_data`) al volumen nuevo con un contenedor `alpine` temporal (mismo patrón que backups, ver abajo) → `docker compose up -d` desde `Flores/flores_ventasbot/`.
4. Confirmar que seguimos apuntando el mismo puerto (2900) y el mismo subdominio (`ventasbot.mounstroya.xyz`) para no tener que tocar `/etc/cloudflared/config.yml`.
5. Una vez migrado y verificado, borrar el `docker-compose.yml`/`.env` de la raíz (el código base no debe tener su propio despliegue, solo servir de `build:` para las carpetas de cliente — igual que `wsned_test/`, `movistar_enrolamiento/`, etc. en Monitores_vinculacion).

## Cómo agregar un cliente nuevo (ya utilizable hoy para clientes reales, no solo Demo)

1. `mkdir -p <Cliente>/<cliente>_ventasbot`
2. Copiar `Demo/demo_ventasbot/docker-compose.yml` y `.env` como plantilla.
3. En el `docker-compose.yml` copiado: cambiar `container_name`, el nombre del volumen, el puerto de host, y la subred (`ipam.config.subnet`, ver abajo — no reutilizar una subred ya asignada a otro cliente/proyecto).
4. En `.env`: generar un `SESSION_SECRET` nuevo (`openssl rand -hex 32`) y poner el `CATALOG_BASE_URL` real del cliente (subdominio con https).
5. `docker compose build && docker compose up -d` desde la carpeta nueva.
6. Agregar el subdominio en `/etc/cloudflared/config.yml` (ver `~/add-cloudflare-tunnel.sh`, automatiza backup + inserción + `cloudflared tunnel route dns montoracle <hostname>` + restart del servicio) y confirmar con `curl -I https://<hostname>`.
7. El bot arranca sin sesión de WhatsApp — hay que escanear el QR (visible en `/panel` o en `docker logs <container>`) con el número real del cliente.
8. Rotar la contraseña del admin sembrado por defecto (`admin@negocio.com` / `admin123`, ver `src/db.js`) antes de entregar un cliente real — nunca dejar el default en una instancia con datos de producción.

## Subredes Docker — pool casi agotado en este host

Este servidor ya tiene ~40 redes `docker compose` acumuladas de todos los proyectos (Monitores_vinculacion, Portabilidades, Rutas, SmartCrm, etc.). Los esquemas de subred usados hasta ahora:

- `172.17.0.0/16` – `172.31.0.0/16`: **agotado casi por completo**, solo queda libre `172.16.0.0/16`.
- `192.168.0.0/20` – `192.168.240.0/20` (16 bloques /20 dentro de `192.168.0.0/16`): **completamente agotado**.
- `10.90.1.0/24` – `10.90.5.0/24`: esquema más nuevo, usado por `Monitores_vinculacion/Vari/*`. Mucho espacio libre (`10.90.0.0/24`, `10.90.6.0/24` en adelante).

**Por eso `Demo/demo_ventasbot` usa `10.90.6.0/24`** en vez de dejar que Docker asigne una red automática (eso falla con *"could not find an available, non-overlapping IPv4 address pool"*). Para el próximo cliente nuevo de VentasBot (o de cualquier proyecto en este host), usar el siguiente bloque libre de este mismo esquema (`10.90.7.0/24`, etc.) — **coordinar con `Monitores_vinculacion/Vari/`**, que es el otro consumidor de este rango, para no chocar. Verificar libres con:

```bash
docker network ls --format '{{.Name}}' | while read n; do
  echo "$n: $(docker network inspect "$n" --format '{{range .IPAM.Config}}{{.Subnet}} {{end}}')"
done
```

Arreglar el pool de raíz (podar redes viejas sin uso o configurar `default-address-pools` en `/etc/docker/daemon.json`) implicaría reiniciar el daemon de Docker, lo que reinicia *todos* los contenedores del host — no se ha hecho, pendiente de decidir con calma en otro momento.

## Backup / restore de datos (volumen `/app/data`)

Patrón usado para respaldar y restaurar (contenedor `alpine` temporal, sin tocar el contenedor de la app):

```bash
# Backup
docker run --rm -v <volumen>:/data -v ~/whatsapp-ventas-bot/backups:/backup \
  alpine tar czf /backup/<nombre>.tar.gz -C /data .

# Restore (con el contenedor de la app detenido, o antes de arrancarlo la primera vez)
docker run --rm -v <volumen>:/data -v ~/whatsapp-ventas-bot/backups:/backup \
  alpine sh -c "tar xzf /backup/<nombre>.tar.gz -C /data --exclude='baileys_auth' && chown -R 1000:1000 /data"
```

El `--exclude='baileys_auth'` es intencional al restaurar un backup en una instancia **nueva** (Demo o cliente nuevo): evita reutilizar la sesión vieja de WhatsApp de otro número — así arranca pidiendo un QR limpio. Si el restore es para recuperar la *misma* instancia (ej. rollback de un desastre), sí conviene incluir `baileys_auth` para no perder la vinculación.

`backups/demo_2026-07-31.tar.gz` es el respaldo del contenido que tenía el bot antes de dárselo a Flores (datos de prueba: 15 productos, 6 socios) — es el que se usó para poblar `Demo/demo_ventasbot`.
