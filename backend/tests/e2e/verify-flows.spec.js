// @ts-check
const { test, expect } = require('@playwright/test');

const ADMIN_EMAIL = 'administrador@fusiond3.mx';
const ADMIN_PASSWORD = '12345';

async function login(page) {
  await page.goto('/');
  await page.fill('input[name="email"]', ADMIN_EMAIL);
  await page.fill('input[name="password"]', ADMIN_PASSWORD);
  await page.click('button[type="submit"]');
  await page.waitForURL('**/mi-local');
}

async function api(page, method, url, body) {
  return page.evaluate(async ({ method, url, body }) => {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const form = new URLSearchParams(body || {});
    const resp = await fetch(url, {
      method,
      headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/x-www-form-urlencoded' },
      body: method === 'GET' ? undefined : form.toString(),
      credentials: 'same-origin',
    });
    const status = resp.status;
    let json = null;
    try { json = await resp.json(); } catch (e) { /* not json */ }
    return { status, json };
  }, { method, url, body });
}

test.describe('verificacion real de flujos backend (via fetch en sesion del navegador)', () => {
  test.setTimeout(120000);

  test('item2: flujo de compra en 2 fases mueve el stock', async ({ page }) => {
    await login(page);
    await page.goto('/mi-local/productos');

    const prov = await api(page, 'POST', '/addProveedor', { nombre: 'Proveedor E2E ' + Date.now() });
    expect(prov.status).toBe(200);
    const proveedorId = prov.json.proveedor.id;

    const before = await api(page, 'POST', '/editProducto', { id: 113 });
    const stockBefore = parseFloat(before.json.product.stock);

    const pedido = await api(page, 'POST', '/productos.comprar', {
      proveedores_id: String(proveedorId),
      productos: JSON.stringify([{ id: 113, cantidad: 10, costo: 40 }]),
      efectivo: '400', transferencia: '0', tarjeta: '0',
    });
    expect(pedido.status).toBe(200);
    const pedidoId = pedido.json.pedido.id;

    const activas = await api(page, 'POST', '/getRequisicionesActivas', {});
    const found = activas.json.requisiciones.find((r) => r.id === pedidoId);
    expect(found).toBeTruthy();
    expect(found.proveedor).toContain('Proveedor E2E');

    const ver = await api(page, 'POST', '/verRequisicion', { id: pedidoId });
    expect(ver.status).toBe(200);
    const detalleId = ver.json.requisicion[0].id;
    expect(parseFloat(ver.json.requisicion[0].cantidad_solicitada)).toBe(10);

    const finalizar = await api(page, 'POST', '/finalizarCompra', {
      productos: JSON.stringify([{ id: detalleId, cantidad_comprada: 10 }]),
    });
    expect(finalizar.status).toBe(200);

    const after = await api(page, 'POST', '/editProducto', { id: 113 });
    const stockAfter = parseFloat(after.json.product.stock);
    expect(stockAfter).toBe(stockBefore + 10);
  });

  test('item3: cobro de servicio y recaudacion en caja', async ({ page }) => {
    await login(page);
    await page.goto('/punto-de-venta');

    const servicios = await api(page, 'POST', '/servicios/getServicios', {});
    expect(servicios.status).toBe(200);
    expect(servicios.json.servicios.length).toBeGreaterThan(0);
    const servicioId = servicios.json.servicios[0].id;

    // /getCajas is global (not scoped to the current user), but cobroServicio/
    // recaudacion ARE scoped to Auth::id()'s own open caja — always attempt to open
    // one for this session; a 422 here just means it was already open, ignore it.
    await api(page, 'POST', '/caja_status', { efectivo: '1000' });

    const cobro = await api(page, 'POST', '/servicios/cobroServicio', {
      servicio_id: String(servicioId), efectivo: '50', transferencia: '0', referencia: 'REF-E2E-' + Date.now(),
    });
    expect(cobro.status).toBe(200);
    expect(cobro.json.ok).toBe(true);

    const recaudacion = await api(page, 'POST', '/servicios/recaudacion', {});
    expect(recaudacion.status).toBe(200);
    expect(recaudacion.json.cobros.length).toBeGreaterThan(0);
    const items = recaudacion.json.cobros.map((c) => ({ id: c.id, efectivo: c.efectivo, transferencia: c.transferencia, retiro: c.efectivo, diferencia: 0 }));

    const finalizar = await api(page, 'POST', '/servicios/finalizarRecaudacion', { items: JSON.stringify(items) });
    expect(finalizar.status).toBe(200);
    expect(finalizar.json.ok).toBe(true);

    const recaudacion2 = await api(page, 'POST', '/servicios/recaudacion', {});
    expect(recaudacion2.json.cobros.length).toBe(0);
  });

  test('item4: cancelacion parcial de un producto de una venta', async ({ page }) => {
    await login(page);
    await page.goto('/punto-de-venta');

    const registro = await api(page, 'POST', '/registroVenta', {
      productos: JSON.stringify([{ id: 113, cantidad: 4, precio_venta: 100 }]),
      cliente_id: '0', ventaTipo: '0',
    });
    expect(registro.status).toBe(200);
    const ventaId = registro.json.venta.id;
    const totalBefore = parseFloat(registro.json.venta.total);

    const stockBefore = (await api(page, 'POST', '/editProducto', { id: 113 })).json.product.stock;

    const cancel = await api(page, 'POST', '/ventas.cancelarProducto', {
      venta_id: String(ventaId), producto_id: '113', cantidad: '2',
    });
    expect(cancel.status).toBe(200);
    expect(cancel.json.status).toBe(true);

    const stockAfter = (await api(page, 'POST', '/editProducto', { id: 113 })).json.product.stock;
    expect(parseFloat(stockAfter)).toBe(parseFloat(stockBefore) + 2);

    const ventas = await api(page, 'POST', '/getVentas', {});
    const venta = ventas.json.ventas.find((v) => v.id === ventaId);
    expect(parseFloat(venta.total)).toBeLessThan(totalBefore);
  });

  test('item5: auditoria completa - generar, contar, finalizar', async ({ page }) => {
    await login(page);
    await page.goto('/auditoria');

    const locales = await api(page, 'POST', '/autidoria/get-locales-auditoria', {});
    const sucursal = locales.json.sucursales.find((s) => s.ultima_auditoria === null);
    expect(sucursal).toBeTruthy();

    const nueva = await api(page, 'POST', '/autidoria/nueva-auditoria', { id: sucursal.id });
    expect(nueva.status).toBe(200);
    const auditoriaId = nueva.json.sucursal.ultima_auditoria_id;

    await page.goto('/auditoria/' + auditoriaId + '/show');
    await page.waitForSelector('#tblConteo tbody tr');
    const rowCount = await page.locator('#tblConteo tbody tr').count();
    expect(rowCount).toBeGreaterThan(0);

    const conteo = await api(page, 'POST', `/auditoria/${auditoriaId}/conteo`, { producto_id: '113', stock_contado: '999' });
    expect(conteo.status).toBe(200);
    expect(conteo.json.conteo.diferencia).not.toBe(0);

    const finalizar = await api(page, 'POST', `/auditoria/${auditoriaId}/finalizar`, {});
    expect(finalizar.status).toBe(200);
    expect(finalizar.json.diferencias).toBeGreaterThan(0);

    const localesAfter = await api(page, 'POST', '/autidoria/get-locales-auditoria', {});
    const sucursalAfter = localesAfter.json.sucursales.find((s) => s.id === sucursal.id);
    expect(sucursalAfter.ultima_auditoria.fecha_fin).not.toBeNull();
  });
});
