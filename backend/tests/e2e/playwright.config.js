// @ts-check
const { defineConfig } = require('@playwright/test');

module.exports = defineConfig({
  testDir: '.',
  timeout: 30000,
  retries: 0,
  reporter: [['list'], ['json', { outputFile: 'results.json' }]],
  use: {
    baseURL: process.env.BASE_URL || 'http://localhost:8080',
    headless: true,
    screenshot: 'only-on-failure',
  },
});
