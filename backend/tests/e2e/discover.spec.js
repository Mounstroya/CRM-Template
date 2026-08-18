// @ts-check
const { test } = require('@playwright/test');

const ADMIN_EMAIL = 'administrador@fusiond3.mx';
const ADMIN_PASSWORD = '12345';

const PAGES = [
  '/mi-local', '/cartera-de-clientes', '/cartera-de-usuarios', '/creditos',
  '/historico', '/depositos', '/depositos-consulta', '/garantia',
  '/garantia-atender', '/auditoria', '/caja-registradora', '/mi-local/productos',
  '/punto-de-venta', '/configuracion', '/whatsapp',
];

test('discovery: console errors per page', async ({ page }) => {
  test.setTimeout(180000);
  await page.goto('/');
  await page.fill('input[name="email"]', ADMIN_EMAIL);
  await page.fill('input[name="password"]', ADMIN_PASSWORD);
  await page.click('button[type="submit"]');
  await page.waitForURL('**/mi-local');

  const report = {};

  for (const path of PAGES) {
    const errors = [];
    const consoleHandler = (msg) => {
      if (msg.type() === 'error') errors.push(msg.text());
    };
    const pageErrorHandler = (err) => errors.push('PAGEERROR: ' + err.message);
    page.on('console', consoleHandler);
    page.on('pageerror', pageErrorHandler);

    const resp = await page.goto(path, { waitUntil: 'networkidle', timeout: 15000 }).catch((e) => null);
    await page.waitForTimeout(1500);

    report[path] = {
      status: resp ? resp.status() : 'NAV_ERROR',
      errors: [...new Set(errors)].slice(0, 15),
    };

    page.off('console', consoleHandler);
    page.off('pageerror', pageErrorHandler);
  }

  console.log('DISCOVERY_REPORT_START');
  console.log(JSON.stringify(report, null, 2));
  console.log('DISCOVERY_REPORT_END');
});
