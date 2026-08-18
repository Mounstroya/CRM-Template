const token = new URLSearchParams(location.search).get('token');
const resetToken = new URLSearchParams(location.search).get('reset');
const money = (n) => '$' + Number(n).toFixed(2);
const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
function emptyState(icon, title, sub, opts = {}) {
  const size = opts.small ? ' empty-state-sm' : '';
  return `<div class="empty-state${size}"><div class="empty-icon">${icon}</div><div class="empty-title">${escapeHtml(title)}</div><div class="empty-sub">${escapeHtml(sub)}</div>${opts.actionHtml || ''}</div>`;
}

function pagerHtml(page, totalPages) {
  if (totalPages <= 1) return '';
  return `<div class="pager">
    <button type="button" class="btn btn-secondary" data-page-prev ${page <= 1 ? 'disabled' : ''}>${icon('chevron-left', 15)} Anterior</button>
    <span class="pager-info">Página ${page} de ${totalPages}</span>
    <button type="button" class="btn btn-secondary" data-page-next ${page >= totalPages ? 'disabled' : ''}>Siguiente ${icon('chevron-right', 15)}</button>
  </div>`;
}

function bindPager(container, state, onChange) {
  container.querySelector('[data-page-prev]')?.addEventListener('click', () => {
    if (state.page > 1) {
      state.page--;
      onChange();
    }
  });
  container.querySelector('[data-page-next]')?.addEventListener('click', () => {
    state.page++;
    onChange();
  });
}

let productos = []; // productos de la página actual
const productCache = {}; // id -> producto, acumulado entre páginas (para que el carrito funcione entre páginas)
let allCategories = [];
let catalogState = { page: 1, pageSize: 24, category: 'Todos', q: '', total: 0, totalPages: 1 };

function debounce(fn, wait) {
  let t = null;
  return (...args) => {
    clearTimeout(t);
    t = setTimeout(() => fn(...args), wait);
  };
}
let cart = {}; // producto_id -> cantidad
let socioNombre = '';
let csrfToken = null;
let branding = null;

function show(id) {
  ['loading', 'invalid-view', 'auth-view', 'catalog-view', 'cart-view', 'confirm-view'].forEach((v) =>
    document.getElementById(v).classList.toggle('hidden', v !== id)
  );
}

const CSRF_ERROR = 'Token CSRF inválido o faltante';

async function api(path, opts = {}) {
  const { _retry, ...fetchOpts } = opts;
  const headers = { 'Content-Type': 'application/json' };
  if (csrfToken) headers['X-CSRF-Token'] = csrfToken;
  const res = await fetch('/catalogo/api' + path, {
    credentials: 'same-origin',
    headers,
    ...fetchOpts,
  });
  const data = await res.json().catch(() => ({}));
  if (res.status === 403 && data.error === CSRF_ERROR && !_retry && path !== '/csrf') {
    const fresh = await api('/csrf');
    csrfToken = fresh.token;
    return api(path, { ...opts, _retry: true });
  }
  if (!res.ok) throw new Error(data.error || 'Error de red');
  return data;
}

function showInvalid(err) {
  show('invalid-view');
  const isTokenError = err?.message === 'Link inválido';
  document.getElementById('invalid-title').textContent = isTokenError ? 'Link inválido' : 'No pudimos conectar';
  document.getElementById('invalid-sub').textContent = isTokenError
    ? 'Este enlace no es válido o ya expiró. Escríbenos por WhatsApp para obtener uno nuevo.'
    : 'Hubo un problema de conexión. Revisa tu internet e intenta de nuevo.';
  const retryBtn = document.getElementById('invalid-retry');
  retryBtn.hidden = isTokenError;
  retryBtn.onclick = boot;
}

function applyBranding() {
  if (!branding) return;
  const nombre = branding.nombre_negocio || 'Catálogo';
  const logoHtml = branding.logo_url
    ? `<img src="${escapeHtml(branding.logo_url)}" alt="${escapeHtml(nombre)}"/>`
    : '';
  const brandHtml = `<div class="auth-brand-icon">${logoHtml}</div><div class="auth-brand-name">${escapeHtml(nombre)}</div>`;
  document.getElementById('auth-brand').innerHTML = brandHtml;
  document.getElementById('invalid-brand').innerHTML = brandHtml;
  document.getElementById('catalog-topbar-title').textContent = nombre;
}

async function boot() {
  if (!token) return show('invalid-view');
  show('loading');
  try {
    const csrf = await api('/csrf');
    csrfToken = csrf.token;
  } catch {}
  try {
    branding = await api('/branding');
    applyBranding();
  } catch {}
  try {
    const qs = '/estado?token=' + encodeURIComponent(token) + (resetToken ? '&reset=' + encodeURIComponent(resetToken) : '');
    const estado = await api(qs);
    socioNombre = estado.nombre;
    if (estado.estado === 'autenticado') return loadCatalog();
    if (estado.estado === 'crear_password') return showAuth('crear');
    if (estado.estado === 'reset_password') return showAuth('reset');
    if (estado.estado === 'login') return showAuth('login');
  } catch (err) {
    showInvalid(err);
  }
}

function showAuth(mode) {
  show('auth-view');
  document.getElementById('auth-title').textContent =
    mode === 'crear' ? `¡Hola ${socioNombre}!` : mode === 'reset' ? 'Restablece tu contraseña' : `Bienvenido de nuevo, ${socioNombre}`;
  document.getElementById('auth-sub').textContent =
    mode === 'crear'
      ? 'Crea una contraseña para proteger tu catálogo personalizado.'
      : mode === 'reset'
        ? 'Escribe tu nueva contraseña para volver a entrar.'
        : 'Ingresa tu contraseña para ver tu catálogo.';
  document.getElementById('auth-label').textContent = mode === 'login' ? 'Contraseña' : 'Nueva contraseña (mín. 6 caracteres)';
  document.getElementById('auth-forgot').classList.toggle('hidden', mode !== 'login');
  if (mode === 'login') {
    document.getElementById('auth-forgot-link').classList.remove('hidden');
    document.getElementById('auth-forgot-sent').classList.add('hidden');
    document.getElementById('auth-forgot-link').onclick = async () => {
      document.getElementById('auth-forgot-link').classList.add('hidden');
      try {
        await api('/olvide-password', { method: 'POST', body: JSON.stringify({ token }) });
      } catch {}
      document.getElementById('auth-forgot-sent').classList.remove('hidden');
    };
  }

  const form = document.getElementById('auth-form');
  form.onsubmit = async (e) => {
    e.preventDefault();
    const password = new FormData(form).get('password');
    const errEl = document.getElementById('auth-error');
    errEl.textContent = '';
    try {
      if (mode === 'crear') await api('/activar', { method: 'POST', body: JSON.stringify({ token, password }) });
      else if (mode === 'reset') await api('/reset-password', { method: 'POST', body: JSON.stringify({ token, reset: resetToken, password }) });
      else await api('/login', { method: 'POST', body: JSON.stringify({ token, password }) });
      loadCatalog();
    } catch (err) {
      errEl.textContent = err.message;
    }
  };
}

async function loadCatalog() {
  catalogState.page = 1;
  await fetchAndRenderCatalog();
}

const onCatalogSearch = debounce((value) => {
  catalogState.q = value.trim();
  catalogState.page = 1;
  fetchAndRenderCatalog();
}, 350);
document.getElementById('catalog-search').addEventListener('input', (e) => onCatalogSearch(e.target.value));

async function fetchAndRenderCatalog() {
  const grid = document.getElementById('product-grid');
  show('catalog-view');
  grid.innerHTML = `<div class="text-muted" style="grid-column:1/-1;text-align:center;padding:40px;">Cargando productos…</div>`;
  document.getElementById('catalog-pager').innerHTML = '';
  try {
    const params = new URLSearchParams({ page: catalogState.page, pageSize: catalogState.pageSize });
    if (catalogState.category !== 'Todos') params.set('categoria', catalogState.category);
    if (catalogState.q) params.set('q', catalogState.q);
    const res = await api('/productos?' + params.toString());
    productos = res.items;
    productos.forEach((p) => {
      productCache[p.id] = p;
    });
    allCategories = res.categorias;
    catalogState.total = res.total;
    catalogState.totalPages = res.totalPages;
    renderCategoryChips();
    renderProductGrid();
    renderCartBar();
  } catch (err) {
    grid.innerHTML = emptyState(
      '⚠️',
      'No pudimos cargar el catálogo',
      'Hubo un problema de conexión. Intenta de nuevo en un momento.',
      { actionHtml: '<button type="button" class="btn btn-primary" id="retry-catalog">Reintentar</button>' }
    );
    document.getElementById('retry-catalog')?.addEventListener('click', fetchAndRenderCatalog);
  }
}

function renderCategoryChips() {
  const categories = ['Todos', ...allCategories];
  const el = document.getElementById('category-chips');
  el.innerHTML = categories
    .map((c) => `<button type="button" class="seg-opt${c === catalogState.category ? ' active' : ''}" data-cat="${escapeHtml(c)}">${escapeHtml(c)}</button>`)
    .join('');
  el.querySelectorAll('[data-cat]').forEach((chip) =>
    chip.addEventListener('click', () => {
      catalogState.category = chip.dataset.cat;
      catalogState.page = 1;
      fetchAndRenderCatalog();
    })
  );
}

function renderProductGrid() {
  const grid = document.getElementById('product-grid');
  const pagerEl = document.getElementById('catalog-pager');
  if (productos.length === 0) {
    grid.innerHTML =
      catalogState.total === 0 && catalogState.category === 'Todos' && !catalogState.q
        ? emptyState('🛍️', 'Tu catálogo está en camino', 'Tu vendedor todavía no ha agregado productos. Vuelve pronto — pronto tendrás novedades por aquí.')
        : catalogState.q
        ? emptyState('🔎', 'Sin resultados', `No encontramos productos que coincidan con "${escapeHtml(catalogState.q)}".`, {
            actionHtml: '<button type="button" class="btn btn-secondary" id="empty-back-todos">Ver todos los productos</button>',
          })
        : emptyState('🔎', 'Nada por aquí todavía', 'No hay productos en esta categoría por ahora. Prueba con otra categoría arriba.', {
            actionHtml: '<button type="button" class="btn btn-secondary" id="empty-back-todos">Ver todos los productos</button>',
          });
    pagerEl.innerHTML = '';
    document.getElementById('empty-back-todos')?.addEventListener('click', () => {
      catalogState.category = 'Todos';
      catalogState.q = '';
      catalogState.page = 1;
      document.getElementById('catalog-search').value = '';
      fetchAndRenderCatalog();
    });
    return;
  }
  grid.innerHTML = productos
    .map((p) => {
      const qty = cart[p.id] || 0;
      const hasDiscount = p.precio_lista && p.precio < p.precio_lista;
      const pct = hasDiscount ? Math.round((1 - p.precio / p.precio_lista) * 100) : 0;
      return `<div class="card" data-id="${p.id}">
        <div style="position:relative;">
          ${
            p.imagen
              ? `<img src="${escapeHtml(p.imagen)}" alt="${escapeHtml(p.nombre)}" data-view-photo tabindex="0" role="button" style="height:80px;width:100%;object-fit:cover;cursor:zoom-in;"/>`
              : `<div style="height:80px;background:var(--color-neutral-200);display:flex;align-items:center;justify-content:center;color:var(--color-neutral-600);font-family:var(--font-heading);font-size:28px;font-weight:800;">${escapeHtml(p.nombre[0])}</div>`
          }
          ${hasDiscount ? `<div class="discount-badge"><span>-${pct}%</span></div>` : ''}
        </div>
        <span class="tag tag-accent">${escapeHtml(p.categoria)}</span>
        <div class="card-title">${escapeHtml(p.nombre)}</div>
        ${
          hasDiscount
            ? `<div style="display:flex;align-items:baseline;gap:8px;">
                 <span style="text-decoration:line-through;color:#e2231a;font-size:13px;">${money(p.precio_lista)}</span>
                 <span style="font-family:var(--font-heading);font-weight:800;font-size:17px;">${money(p.precio)}</span>
               </div>`
            : `<div style="font-family:var(--font-heading);font-weight:800;font-size:17px;">${money(p.precio)}</div>`
        }
        ${
          qty > 0
            ? `<div class="qty-row"><button type="button" class="qty-btn" data-dec aria-label="Quitar uno">–</button><div style="font-weight:800;">${qty}</div><button type="button" class="qty-btn" data-inc aria-label="Agregar uno">+</button></div>`
            : `<button type="button" class="btn btn-primary btn-block" style="justify-content:center;margin-top:0;" data-add>Agregar</button>`
        }
      </div>`;
    })
    .join('');
  grid.querySelectorAll('[data-view-photo]').forEach((img) =>
    img.addEventListener('click', (e) => {
      e.stopPropagation();
      const id = img.closest('.card').dataset.id;
      const p = productos.find((p) => String(p.id) === String(id));
      openProductDetail(p);
    })
  );
  grid.querySelectorAll('[data-add], [data-inc]').forEach((btn) =>
    btn.addEventListener('click', () => {
      const id = btn.closest('.card').dataset.id;
      cart[id] = (cart[id] || 0) + 1;
      renderProductGrid();
      renderCartBar();
      scheduleCartSave();
    })
  );
  grid.querySelectorAll('[data-dec]').forEach((btn) =>
    btn.addEventListener('click', () => {
      const id = btn.closest('.card').dataset.id;
      cart[id] = (cart[id] || 0) - 1;
      if (cart[id] <= 0) delete cart[id];
      renderProductGrid();
      renderCartBar();
      scheduleCartSave();
    })
  );
  pagerEl.innerHTML = pagerHtml(catalogState.page, catalogState.totalPages);
  bindPager(pagerEl, catalogState, fetchAndRenderCatalog);
}

let cartSaveTimer = null;
function scheduleCartSave() {
  clearTimeout(cartSaveTimer);
  cartSaveTimer = setTimeout(() => {
    const items = cartEntries().map((e) => ({ producto_id: e.producto.id, cantidad: e.qty }));
    api('/carrito', { method: 'PUT', body: JSON.stringify({ items }) }).catch(() => {});
  }, 1000);
}

function cartEntries() {
  return Object.entries(cart).map(([id, qty]) => {
    const p = productCache[id];
    return { producto: p, qty };
  });
}

function cartTotal() {
  return cartEntries().reduce((sum, e) => sum + e.producto.precio * e.qty, 0);
}

function renderCartBar() {
  const entries = cartEntries();
  const count = entries.reduce((a, e) => a + e.qty, 0);
  document.getElementById('cart-count-badge').textContent = count > 0 ? count : '';
  const bar = document.getElementById('cart-bar');
  bar.classList.toggle('hidden', count === 0);
  document.getElementById('cart-summary').textContent = `${count} producto(s) · ${money(cartTotal())}`;
}

document.getElementById('go-to-cart').addEventListener('click', renderCartView);
document.getElementById('cart-link').addEventListener('click', () => {
  if (cartEntries().length > 0) renderCartView();
});
document.getElementById('cart-back').addEventListener('click', () => {
  show('catalog-view');
});
document.getElementById('cart-icon').innerHTML = icon('cart', 16);

function renderCartView() {
  show('cart-view');
  const entries = cartEntries();
  const content = document.getElementById('cart-content');
  if (entries.length === 0) {
    content.innerHTML = `<div class="card" style="padding:40px;text-align:center;">
      <div style="font-weight:700;margin-bottom:6px;">Tu carrito está vacío</div>
      <button class="btn btn-primary" id="back-to-catalog">Ir al catálogo</button>
    </div>`;
    document.getElementById('back-to-catalog').addEventListener('click', () => show('catalog-view'));
    return;
  }
  content.innerHTML = `
    <div style="display:flex;flex-direction:column;margin-bottom:20px;">
      ${entries
        .map(
          (e) => `<div style="background:var(--color-surface);padding:12px 14px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--color-divider);" data-id="${e.producto.id}">
          <div style="flex:1;min-width:0;">
            <div style="font-weight:700;font-size:14px;">${escapeHtml(e.producto.nombre)}</div>
            <div class="text-muted" style="font-size:12px;">${money(e.producto.precio)} c/u</div>
          </div>
          <div class="qty-row" style="border:none;padding:0;gap:8px;">
            <button type="button" class="qty-btn" data-dec aria-label="Quitar uno">–</button><div style="font-weight:800;width:16px;text-align:center;">${e.qty}</div><button type="button" class="qty-btn" data-inc aria-label="Agregar uno">+</button>
          </div>
          <div style="font-weight:800;width:70px;text-align:right;">${money(e.producto.precio * e.qty)}</div>
        </div>`
        )
        .join('')}
    </div>
    <div class="card">
      <div class="card-title">Datos del pedido</div>
      <div class="field"><input class="input" id="cliente-nombre" placeholder="Nombre del cliente" value="${escapeHtml(socioNombre)}"/></div>
      <div class="field"><input class="input" id="cliente-telefono" placeholder="Teléfono"/></div>
      <div class="field"><input class="input" id="cliente-direccion" placeholder="Dirección de entrega"/></div>
      <div class="hr" style="margin:8px 0;"></div>
      <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:800;"><span>Total</span><span>${money(cartTotal())}</span></div>
      <button type="button" class="btn btn-primary btn-block" style="justify-content:center;" id="confirm-order">Enviar pedido</button>
      <div id="order-error" class="error-text"></div>
    </div>
  `;
  content.querySelectorAll('[data-inc]').forEach((btn) =>
    btn.addEventListener('click', () => {
      const id = btn.closest('[data-id]').dataset.id;
      cart[id] = (cart[id] || 0) + 1;
      renderCartBar();
      renderCartView();
      scheduleCartSave();
    })
  );
  content.querySelectorAll('[data-dec]').forEach((btn) =>
    btn.addEventListener('click', () => {
      const id = btn.closest('[data-id]').dataset.id;
      cart[id] = (cart[id] || 0) - 1;
      if (cart[id] <= 0) delete cart[id];
      renderCartBar();
      renderCartView();
      scheduleCartSave();
    })
  );
  document.getElementById('confirm-order').addEventListener('click', async () => {
    const btn = document.getElementById('confirm-order');
    if (btn.disabled) return;
    const items = cartEntries().map((e) => ({ producto_id: e.producto.id, cantidad: e.qty }));
    btn.disabled = true;
    const originalText = btn.textContent;
    btn.textContent = 'Enviando…';
    try {
      const result = await api('/pedido', {
        method: 'POST',
        body: JSON.stringify({
          cliente_nombre: document.getElementById('cliente-nombre').value,
          telefono: document.getElementById('cliente-telefono').value,
          direccion: document.getElementById('cliente-direccion').value,
          items,
        }),
      });
      cart = {};
      show('confirm-view');
      document.getElementById('confirm-title').textContent = `Pedido ${result.orderId} confirmado`;
    } catch (err) {
      document.getElementById('order-error').textContent = err.message;
      btn.disabled = false;
      btn.textContent = originalText;
    }
  });
}

function getVideoEmbedUrl(url) {
  if (!url) return null;
  let u;
  try {
    u = new URL(url);
  } catch {
    return null;
  }
  const host = u.hostname.replace(/^www\./, '');
  if (host === 'youtu.be') {
    const id = u.pathname.slice(1);
    return id ? `https://www.youtube.com/embed/${encodeURIComponent(id)}` : null;
  }
  if (host === 'youtube.com' || host === 'm.youtube.com') {
    if (u.pathname === '/watch') {
      const id = u.searchParams.get('v');
      return id ? `https://www.youtube.com/embed/${encodeURIComponent(id)}` : null;
    }
    const shortsMatch = u.pathname.match(/^\/shorts\/([^/]+)/);
    if (shortsMatch) return `https://www.youtube.com/embed/${encodeURIComponent(shortsMatch[1])}`;
    if (u.pathname.startsWith('/embed/')) return url;
  }
  if (host === 'drive.google.com') {
    const fileMatch = u.pathname.match(/^\/file\/d\/([^/]+)/);
    if (fileMatch) return `https://drive.google.com/file/d/${encodeURIComponent(fileMatch[1])}/preview`;
  }
  return null;
}

function openProductDetail(p) {
  const root = document.getElementById('dialog-root');
  root.innerHTML = `
    <div class="dialog-backdrop" id="dialog-backdrop">
      <div class="dialog" style="width:min(420px,100%);max-height:88vh;overflow-y:auto;">
        <div style="display:flex;align-items:center;justify-content:space-between;">
          <div class="dialog-title">${escapeHtml(p.nombre)}</div>
          <button type="button" class="btn-icon" id="dialog-close">${icon('x',16)}</button>
        </div>
        ${
          p.imagen
            ? `<img src="${escapeHtml(p.imagen)}" alt="${escapeHtml(p.nombre)}" style="width:100%;max-height:60vh;object-fit:contain;background:var(--color-neutral-200);"/>`
            : ''
        }
        <span class="tag tag-accent" style="align-self:flex-start;">${escapeHtml(p.categoria)}</span>
        <div style="font-family:var(--font-heading);font-weight:800;font-size:19px;">${money(p.precio)}</div>
        ${
          p.descripcion
            ? `<div class="dialog-body" style="white-space:pre-line;">${escapeHtml(p.descripcion)}</div>`
            : ''
        }
        ${
          p.video_url
            ? (() => {
                const embed = getVideoEmbedUrl(p.video_url);
                return embed
                  ? `<iframe src="${escapeHtml(embed)}" style="width:100%;aspect-ratio:16/9;border:0;margin-top:8px;" allow="autoplay; encrypted-media" allowfullscreen referrerpolicy="no-referrer"></iframe>`
                  : `<a href="${escapeHtml(p.video_url)}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary" style="margin-top:8px;align-self:flex-start;">${icon('play', 15)} Ver video</a>`;
              })()
            : ''
        }
      </div>
    </div>`;
  const close = () => (root.innerHTML = '');
  document.getElementById('dialog-close').addEventListener('click', close);
  document.getElementById('dialog-backdrop').addEventListener('click', (e) => {
    if (e.target.id === 'dialog-backdrop') close();
  });
}

document.getElementById('confirm-continue').addEventListener('click', loadCatalog);

document.addEventListener('keydown', (e) => {
  if ((e.key === 'Enter' || e.key === ' ') && e.target.getAttribute?.('role') === 'button') {
    e.preventDefault();
    e.target.click();
  }
});

boot();
