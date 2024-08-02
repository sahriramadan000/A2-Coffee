const express = require('express');
const app = express();
const http = require('http');
const server = http.createServer(app);
const { Pool } = require('pg');
const util = require('util');
const moment = require('moment');

const bodyParser = require('body-parser');
const axios = require('axios');

// parse application/x-www-form-urlencoded
app.use(bodyParser.urlencoded({ extended: false }));

// parse application/json
app.use(bodyParser.json());

// Enable CORS
app.use((req, res, next) => {
  res.setHeader('Access-Control-Allow-Origin', 'http://a2-coffee.test');
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');
  next();
});

const options = {
  cors: {
    origin: "*",
    methods: ["GET", "POST"]
  }
}

// const pool = new Pool({
//   host: '85.31.224.243',
//   port: 5432,
//   database: 'a2coffee',
//   user: 'postgres',
//   password: 'SuksesJooal2024!',
// });

const localPool = new Pool({
    host: 'localhost',
    port: 5432,
    database: 'controlindo-pos',
    user: 'postgres',
    password: 'root',
  });

const cloudPool = new Pool({
    host: '85.31.224.243',
    port: 5432,
    database: 'a2coffee',
    user: 'postgres',
    password: 'SuksesJooal2024!',
});


setInterval(() => {
    RealtimeDashboardOrder()
}, 1000);


const promisifiedLocalQuery = util.promisify(localPool.query).bind(localPool);
const promisifiedCloudQuery = util.promisify(cloudPool.query).bind(cloudPool);

const syncLocalToCloud = async () => {
    try {
      const today = moment().format('YYYY-MM-DD');

      // Sinkronkan tabel orders terlebih dahulu
      const localOrders = await promisifiedLocalQuery(`SELECT * FROM orders WHERE created_at::date = $1`, [today]);

      const cloudOrders = await promisifiedCloudQuery(`SELECT 1 FROM orders WHERE created_at::date = $1 LIMIT 1`, [today]);
      if (cloudOrders.rows.length > 0) {
        await promisifiedCloudQuery(`DELETE FROM orders WHERE created_at::date = $1`, [today]);
      }

      for (const order of localOrders.rows) {
        const keys = Object.keys(order).map(key => key === 'table' ? '"table"' : key);
        const values = Object.values(order);
        const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
        const insertQuery = `INSERT INTO orders (${keys.join(', ')}) VALUES (${placeholders})`;
        await promisifiedCloudQuery(insertQuery, values);
      }
      console.log('Table orders synchronized successfully from local to cloud.');

      // Sinkronkan tabel order_products
      const localOrderProducts = await promisifiedLocalQuery(`SELECT * FROM order_products WHERE created_at::date = $1`, [today]);

      const cloudOrderProducts = await promisifiedCloudQuery(`SELECT 1 FROM order_products WHERE created_at::date = $1 LIMIT 1`, [today]);
      if (cloudOrderProducts.rows.length > 0) {
        await promisifiedCloudQuery(`DELETE FROM order_products WHERE created_at::date = $1`, [today]);
      }

      for (const orderProduct of localOrderProducts.rows) {
        const keys = Object.keys(orderProduct).map(key => key === 'table' ? '"table"' : key);
        const values = Object.values(orderProduct);
        const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
        const insertQuery = `INSERT INTO order_products (${keys.join(', ')}) VALUES (${placeholders})`;
        await promisifiedCloudQuery(insertQuery, values);
      }
      console.log('Table order_products synchronized successfully from local to cloud.');

      // Sinkronkan tabel order_product_addons
      const localOrderProductAddons = await promisifiedLocalQuery(`SELECT * FROM order_product_addons WHERE created_at::date = $1`, [today]);

      const cloudOrderProductAddons = await promisifiedCloudQuery(`SELECT 1 FROM order_product_addons WHERE created_at::date = $1 LIMIT 1`, [today]);
      if (cloudOrderProductAddons.rows.length > 0) {
        await promisifiedCloudQuery(`DELETE FROM order_product_addons WHERE created_at::date = $1`, [today]);
      }

      for (const orderProductAddon of localOrderProductAddons.rows) {
        const keys = Object.keys(orderProductAddon).map(key => key === 'table' ? '"table"' : key);
        const values = Object.values(orderProductAddon);
        const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
        const insertQuery = `INSERT INTO order_product_addons (${keys.join(', ')}) VALUES (${placeholders})`;
        await promisifiedCloudQuery(insertQuery, values);
      }
      console.log('Table order_product_addons synchronized successfully from local to cloud.');

      // Sinkronkan tabel order_coupons
      const localOrderCoupons = await promisifiedLocalQuery(`SELECT * FROM order_coupons WHERE created_at::date = $1`, [today]);

      const cloudOrderCoupons = await promisifiedCloudQuery(`SELECT 1 FROM order_coupons WHERE created_at::date = $1 LIMIT 1`, [today]);
      if (cloudOrderCoupons.rows.length > 0) {
        await promisifiedCloudQuery(`DELETE FROM order_coupons WHERE created_at::date = $1`, [today]);
      }

      for (const orderCoupon of localOrderCoupons.rows) {
        const keys = Object.keys(orderCoupon).map(key => key === 'table' ? '"table"' : key);
        const values = Object.values(orderCoupon);
        const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
        const insertQuery = `INSERT INTO order_coupons (${keys.join(', ')}) VALUES (${placeholders})`;
        await promisifiedCloudQuery(insertQuery, values);
      }
      console.log('Table order_coupons synchronized successfully from local to cloud.');

    } catch (err) {
      console.error('Error synchronizing tables from local to cloud:', err);
    }
};

const syncCloudToLocal = async () => {
    try {
      const today = moment().format('YYYY-MM-DD');

      // Sinkronkan tabel orders terlebih dahulu
      const cloudOrders = await promisifiedCloudQuery(`SELECT * FROM orders WHERE created_at::date = $1`, [today]);

      const localOrders = await promisifiedLocalQuery(`SELECT 1 FROM orders WHERE created_at::date = $1 LIMIT 1`, [today]);
      if (localOrders.rows.length > 0) {
        await promisifiedLocalQuery(`DELETE FROM orders WHERE created_at::date = $1`, [today]);
      }

      for (const order of cloudOrders.rows) {
        const keys = Object.keys(order).map(key => key === 'table' ? '"table"' : key);
        const values = Object.values(order);
        const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
        const insertQuery = `INSERT INTO orders (${keys.join(', ')}) VALUES (${placeholders})`;
        await promisifiedLocalQuery(insertQuery, values);
      }
      console.log('Table orders synchronized successfully from cloud to local.');

      // Sinkronkan tabel order_products
      const cloudOrderProducts = await promisifiedCloudQuery(`SELECT * FROM order_products WHERE created_at::date = $1`, [today]);

      const localOrderProducts = await promisifiedLocalQuery(`SELECT 1 FROM order_products WHERE created_at::date = $1 LIMIT 1`, [today]);
      if (localOrderProducts.rows.length > 0) {
        await promisifiedLocalQuery(`DELETE FROM order_products WHERE created_at::date = $1`, [today]);
      }

      for (const orderProduct of cloudOrderProducts.rows) {
        const keys = Object.keys(orderProduct).map(key => key === 'table' ? '"table"' : key);
        const values = Object.values(orderProduct);
        const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
        const insertQuery = `INSERT INTO order_products (${keys.join(', ')}) VALUES (${placeholders})`;
        await promisifiedLocalQuery(insertQuery, values);
      }
      console.log('Table order_products synchronized successfully from cloud to local.');

      // Sinkronkan tabel order_product_addons
      const cloudOrderProductAddons = await promisifiedCloudQuery(`SELECT * FROM order_product_addons WHERE created_at::date = $1`, [today]);

      const localOrderProductAddons = await promisifiedLocalQuery(`SELECT 1 FROM order_product_addons WHERE created_at::date = $1 LIMIT 1`, [today]);
      if (localOrderProductAddons.rows.length > 0) {
        await promisifiedLocalQuery(`DELETE FROM order_product_addons WHERE created_at::date = $1`, [today]);
      }

      for (const orderProductAddon of cloudOrderProductAddons.rows) {
        const keys = Object.keys(orderProductAddon).map(key => key === 'table' ? '"table"' : key);
        const values = Object.values(orderProductAddon);
        const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
        const insertQuery = `INSERT INTO order_product_addons (${keys.join(', ')}) VALUES (${placeholders})`;
        await promisifiedLocalQuery(insertQuery, values);
      }
      console.log('Table order_product_addons synchronized successfully from cloud to local.');

      // Sinkronkan tabel order_coupons
      const cloudOrderCoupons = await promisifiedCloudQuery(`SELECT * FROM order_coupons WHERE created_at::date = $1`, [today]);

      const localOrderCoupons = await promisifiedLocalQuery(`SELECT 1 FROM order_coupons WHERE created_at::date = $1 LIMIT 1`, [today]);
      if (localOrderCoupons.rows.length > 0) {
        await promisifiedLocalQuery(`DELETE FROM order_coupons WHERE created_at::date = $1`, [today]);
      }

      for (const orderCoupon of cloudOrderCoupons.rows) {
        const keys = Object.keys(orderCoupon).map(key => key === 'table' ? '"table"' : key);
        const values = Object.values(orderCoupon);
        const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
        const insertQuery = `INSERT INTO order_coupons (${keys.join(', ')}) VALUES (${placeholders})`;
        await promisifiedLocalQuery(insertQuery, values);
      }
      console.log('Table order_coupons synchronized successfully from cloud to local.');

    } catch (err) {
      console.error('Error synchronizing tables from cloud to local:', err);
    }
};

async function RealtimeDashboardOrder() {
  // Mendapatkan tanggal saat ini dalam format YYYY-MM-DD
  const currentDate = new Date();
  var formattedDate = moment().format('YYYY-MM-DD');
  var formattedTime = currentDate.toLocaleTimeString('en-US', { hour12: true });

  const query1 = `SELECT * FROM orders
  WHERE created_at::text LIKE '${formattedDate}%'
  AND payment_status = 'Paid'
  AND status_realtime = 'new'
  ORDER BY created_at ASC`;

  const [result1] = await Promise.all([promisifiedLocalQuery(query1)]);

  const orderFromSql1 = result1.rows;

  // For Resto
  for (const element of orderFromSql1) {
    try {
      axios.get('http://a2-coffee.test/api/print-customer/' + element.id, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
        .then(data => {
          console.log('success print!');

        })
        .catch(err => {
          console.log(err);
          return null;
        });

        promisifiedLocalQuery(`UPDATE orders SET status_realtime = 'Success Print' WHERE id = ${element.id}`);

    } catch (error) {
      console.log(error);
    }
  }
}

app.post('/sync-local-to-cloud', async (req, res) => {
    try {
      await syncLocalToCloud();
      res.status(200).send('All related tables for today synchronized successfully from local to cloud.');
    } catch (err) {
      res.status(500).send('Error synchronizing related tables from local to cloud.');
    }
});

app.post('/sync-cloud-to-local', async (req, res) => {
    try {
      await syncCloudToLocal();
      res.status(200).send('All related tables for today synchronized successfully from cloud to local.');
    } catch (err) {
      res.status(500).send('Error synchronizing related tables from cloud to local.');
    }
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
