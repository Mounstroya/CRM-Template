const mysql = require('mysql2/promise');

const pool = mysql.createPool({
  host: process.env.DB_HOST || 'db',
  port: Number(process.env.DB_PORT || 3306),
  user: process.env.DB_USERNAME || 'fusiond3',
  password: process.env.DB_PASSWORD || 'fusiond3pass',
  database: process.env.DB_DATABASE || 'fusiond3',
  waitForConnections: true,
  connectionLimit: 5,
});

module.exports = { pool };
