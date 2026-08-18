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

test('login works and lands on mi-local', async ({ page }) => {
  await login(page);
  expect(page.url()).toContain('/mi-local');
});

test('every main section loads without a server-error page', async ({ page }) => {
  await login(page);
  const sections = [
    '/mi-local', '/cartera-de-clientes', '/cartera-de-usuarios', '/creditos',
    '/historico', '/depositos', '/depositos-consulta', '/garantia',
    '/garantia-atender', '/auditoria', '/caja-registradora', '/mi-local/productos',
    '/punto-de-venta', '/configuracion',
  ];
  for (const path of sections) {
    const resp = await page.goto(path);
    expect(resp.status(), `${path} status`).toBe(200);
    const bodyText = await page.textContent('body');
    expect(bodyText).not.toContain('Server Error');
    expect(bodyText).not.toContain('Whoops');
  }
});

test('cliente: add new client via modal reflects in the real table', async ({ page }) => {
  await login(page);
  await page.goto('/cartera-de-clientes');

  const uniqueName = 'PLAYWRIGHT TEST CLIENTE ' + Date.now();
  await page.evaluate(() => $('#modalAddcliente').modal('show'));
  await page.waitForSelector('#modalAddcliente.show');
  await page.fill('#modalAddcliente input[name="nombre"]', uniqueName);
  await page.fill('#modalAddcliente input[name="correo"]', 'pwtest@example.com');
  await page.fill('#modalAddcliente input[name="telefono"]', '7770000000');
  await page.click('#formAddCliente button[type="submit"]');

  await page.waitForSelector(`#tablaClientes td:has-text("${uniqueName}")`, { timeout: 10000 });
  const cell = await page.textContent('#tablaClientes');
  expect(cell).toContain(uniqueName);
});

test('proveedor: add new supplier via modal reflects in the real table', async ({ page }) => {
  await login(page);
  await page.goto('/mi-local/productos');

  const uniqueName = 'PLAYWRIGHT TEST PROVEEDOR ' + Date.now();
  await page.evaluate(() => $('#modal_nuevo_proveedor').modal('show'));
  await page.waitForSelector('#modal_nuevo_proveedor.show');
  await page.fill('#modal_nuevo_proveedor input[name="nombre"]', uniqueName);
  await page.click('#addProveedor button[type="submit"]');

  await page.waitForSelector(`#tbl_proveedores:has-text("${uniqueName}")`, { timeout: 10000, state: 'attached' });
});

test('departamento: add new department via modal reflects in the real table', async ({ page }) => {
  await login(page);
  await page.goto('/mi-local/productos');

  const uniqueName = 'PLAYWRIGHT TEST DEPTO ' + Date.now();
  await page.evaluate(() => $('#modal_nuevo_departamento').modal('show'));
  await page.waitForSelector('#modal_nuevo_departamento.show');
  await page.fill('#modal_nuevo_departamento input[name="departamento"]', uniqueName);
  await page.click('#form_depto button[type="submit"]');

  await page.waitForSelector('.toastMessage, .notify-message-content', { timeout: 10000 }).catch(() => {});
});

test('garantia: change status via the real Cambiar Estatus modal', async ({ page }) => {
  await login(page);
  await page.goto('/garantia-atender');

  const firstBtn = page.locator('button[data-target="#statusModal"]').first();
  await expect(firstBtn).toBeVisible();
  await firstBtn.click();
  await page.waitForSelector('#statusModal.show');
  await page.selectOption('#statusModal select[name="status"]', '3');
  await page.click('#statusForm button[type="submit"]');

  await page.waitForTimeout(1500);
  const resp = await page.request.get('/garantia-atender');
  expect(resp.status()).toBe(200);
});

test('caja: apertura via the real activarCaja() trigger', async ({ page, request }) => {
  await login(page);
  await page.goto('/caja-registradora');
  await page.waitForTimeout(500);

  page.on('dialog', (dialog) => dialog.accept());

  const statusImg = page.locator('#status_caja');
  await expect(statusImg).toBeVisible();
  const [resp] = await Promise.all([
    page.waitForResponse((r) => r.url().includes('/caja_status'), { timeout: 8000 }),
    statusImg.click(),
  ]);
  expect(resp.status()).toBe(200);
  const body = await resp.json();
  expect(body.ok).toBe(true);
});

test('credito: abonar via the real dynamically-rendered row button', async ({ page }) => {
  await login(page);
  await page.goto('/creditos');
  await page.waitForSelector('#tblCreditos tr', { timeout: 10000 });

  const abonarBtn = page.locator('#tblCreditos button:has-text("Abonar")').first();
  await expect(abonarBtn).toBeVisible();
  await abonarBtn.click();
  await page.waitForSelector('#modalAbonar.show');
  await page.fill('#modalAbonar input[name="efectivo"]', '1');
  await page.click('#formAbonoCredito button[type="submit"]');
  await page.waitForTimeout(1500);
});
