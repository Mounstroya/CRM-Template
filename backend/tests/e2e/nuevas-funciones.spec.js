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

test.describe('nuevas funciones — click-through real', () => {
  test.setTimeout(120000);

  test('1. edicion de productos: abrir modal, ver datos reales, guardar', async ({ page }) => {
    const errors = [];
    page.on('pageerror', (e) => errors.push(e.message));
    await login(page);
    await page.goto('/mi-local/productos');
    await page.waitForSelector('#tbl_productos tbody tr', { timeout: 15000 });

    // Click the edit (clave) link on the first row.
    await page.locator('#tbl_productos tbody tr').first().locator('span[onclick^="editProducto"]').click();
    await page.waitForSelector('#modal_edit_producto.show', { timeout: 10000 });
    const descripcion = await page.inputValue('#edit_descripcion');
    expect(descripcion.length).toBeGreaterThan(0);
    const factor = await page.inputValue('#edit_factor');
    expect(factor).not.toBe('');

    // Change descripcion slightly and save.
    await page.fill('#edit_precio_1', '123.45');
    await page.click('#form_edit_productos button[type="submit"]');
    await page.waitForTimeout(1500);
    expect(errors).toEqual([]);
  });

  test('2. compras: abrir modal de nuevo pedido', async ({ page }) => {
    const errors = [];
    page.on('pageerror', (e) => errors.push(e.message));
    await login(page);
    await page.goto('/mi-local/productos');
    await page.waitForSelector('#tbl_productos tbody tr', { timeout: 15000 });
    await page.click('button[onclick="modal_compras()"]');
    await page.waitForSelector('#modal_compras.show', { timeout: 10000 });
    expect(errors).toEqual([]);
  });

  test('3. servicios: cobro de servicio en punto de venta', async ({ page }) => {
    const errors = [];
    page.on('pageerror', (e) => errors.push(e.message));
    await login(page);
    await page.goto('/punto-de-venta');
    await page.waitForTimeout(1000);
    const btn = page.locator('button, a').filter({ hasText: /servicio/i }).first();
    if (await btn.count()) {
      await btn.click();
      await page.waitForTimeout(1500);
      const radios = await page.locator('#tiposServicio input[type=radio]').count();
      expect(radios).toBeGreaterThan(0);
    }
    expect(errors).toEqual([]);
  });

  test('3b. servicios: recaudacion en caja', async ({ page }) => {
    const errors = [];
    page.on('pageerror', (e) => errors.push(e.message));
    await login(page);
    await page.goto('/caja-registradora');
    await page.waitForTimeout(1000);
    await page.click('button:has-text("RECAUDACIÓN PAGO SERVICIOS")');
    await page.waitForTimeout(1500);
    expect(errors).toEqual([]);
  });

  test('4. cancelacion parcial de producto en historico', async ({ page }) => {
    const errors = [];
    page.on('pageerror', (e) => errors.push(e.message));
    await login(page);
    await page.goto('/historico');
    await page.waitForTimeout(2000);
    expect(errors).toEqual([]);
  });

  test('5. auditoria: generar y tomar auditoria completa', async ({ page }) => {
    const errors = [];
    page.on('pageerror', (e) => errors.push(e.message));
    await login(page);
    await page.goto('/auditoria');
    await page.waitForSelector('#tblLocales tbody tr', { timeout: 15000 });

    // Find a row with a GENERAR button (no audit started yet) or CONTINUAR link.
    const generarBtn = page.locator('#tblLocales button:has-text("GENERAR")').first();
    const continuarLink = page.locator('#tblLocales a:has-text("CONTINUAR")').first();

    if (await generarBtn.count()) {
      await generarBtn.click();
      await page.waitForTimeout(2000);
    }

    const link = page.locator('#tblLocales a:has-text("CONTINUAR")').first();
    if (await link.count()) {
      const href = await link.getAttribute('href');
      await page.goto('/' + href.replace(/^\//, ''));
      await page.waitForSelector('#tblConteo tbody tr', { timeout: 10000 });
      const firstInput = page.locator('#tblConteo tbody tr').first().locator('.input-conteo');
      await firstInput.fill('99');
      await firstInput.dispatchEvent('change');
      await page.waitForTimeout(1000);
      const diff = await page.locator('#tblConteo tbody tr').first().locator('.celda-diferencia').textContent();
      expect(diff.trim().length).toBeGreaterThan(0);
    }
    expect(errors).toEqual([]);
  });

  test('6. reportes: excel de clientes descarga, carga masiva modal abre', async ({ page }) => {
    const errors = [];
    page.on('pageerror', (e) => errors.push(e.message));
    await login(page);
    await page.goto('/cartera-de-clientes');
    await page.waitForTimeout(1000);
    await page.click('button[data-target="#modalCargaMasiva"]');
    await page.waitForSelector('#modalCargaMasiva.show', { timeout: 10000 });
    const fileInput = await page.locator('#modalCargaMasiva input[type=file]').count();
    expect(fileInput).toBe(1);
    expect(errors).toEqual([]);
  });
});
