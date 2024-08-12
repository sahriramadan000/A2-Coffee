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
    database: 'a2',
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
    syncAllOrdersAndRelatedTables()
}, 1000);

const promisifiedLocalQuery = util.promisify(localPool.query).bind(localPool);
const promisifiedCloudQuery = util.promisify(cloudPool.query).bind(cloudPool);

async function syncAllOrdersAndRelatedTables() {
    const today = moment().format('YYYY-MM-DD');

    try {
        // Sinkronkan tabel orders tanpa menghapus data di lokal
        const cloudOrders = await promisifiedCloudQuery(`SELECT * FROM orders WHERE created_at::date = $1`, [today]);

        for (const order of cloudOrders.rows) {
            const id = order.id; // Asumsikan 'id' adalah primary key di tabel orders
            const localOrderQuery = `SELECT 1 FROM orders WHERE id = $1 LIMIT 1`;
            const localOrder = await promisifiedLocalQuery(localOrderQuery, [id]);

            if (localOrder.rows.length === 0) {
                const keys = Object.keys(order).map(key => key === 'table' ? '"table"' : key);
                const values = Object.values(order);
                const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
                const insertQuery = `INSERT INTO orders (${keys.join(', ')}) VALUES (${placeholders})`;
                await promisifiedLocalQuery(insertQuery, values);
                console.log(`Inserted order ID ${id} into local orders table.`);
            }
        }

        console.log('Table orders synchronized successfully from cloud to local.');

        // Sinkronkan tabel order_products
        const cloudOrderProducts = await promisifiedCloudQuery(`SELECT * FROM order_products WHERE created_at::date = $1`, [today]);

        for (const orderProduct of cloudOrderProducts.rows) {
            const id = orderProduct.id; // Asumsikan 'id' adalah primary key di tabel order_products
            const localOrderProductQuery = `SELECT 1 FROM order_products WHERE id = $1 LIMIT 1`;
            const localOrderProduct = await promisifiedLocalQuery(localOrderProductQuery, [id]);

            if (localOrderProduct.rows.length === 0) {
                const keys = Object.keys(orderProduct).map(key => key === 'table' ? '"table"' : key);
                const values = Object.values(orderProduct);
                const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
                const insertQuery = `INSERT INTO order_products (${keys.join(', ')}) VALUES (${placeholders})`;
                await promisifiedLocalQuery(insertQuery, values);
                console.log(`Inserted order_product ID ${id} into local order_products table.`);
            }
        }

        console.log('Table order_products synchronized successfully from cloud to local.');

        // Sinkronkan tabel order_product_addons
        const cloudOrderProductAddons = await promisifiedCloudQuery(`SELECT * FROM order_product_addons WHERE created_at::date = $1`, [today]);

        for (const orderProductAddon of cloudOrderProductAddons.rows) {
            const id = orderProductAddon.id; // Asumsikan 'id' adalah primary key di tabel order_product_addons
            const localOrderProductAddonQuery = `SELECT 1 FROM order_product_addons WHERE id = $1 LIMIT 1`;
            const localOrderProductAddon = await promisifiedLocalQuery(localOrderProductAddonQuery, [id]);

            if (localOrderProductAddon.rows.length === 0) {
                const keys = Object.keys(orderProductAddon).map(key => key === 'table' ? '"table"' : key);
                const values = Object.values(orderProductAddon);
                const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
                const insertQuery = `INSERT INTO order_product_addons (${keys.join(', ')}) VALUES (${placeholders})`;
                await promisifiedLocalQuery(insertQuery, values);
                console.log(`Inserted order_product_addon ID ${id} into local order_product_addons table.`);
            }
        }

        console.log('Table order_product_addons synchronized successfully from cloud to local.');

        // Sinkronkan tabel order_coupons
        const cloudOrderCoupons = await promisifiedCloudQuery(`SELECT * FROM order_coupons WHERE created_at::date = $1`, [today]);

        for (const orderCoupon of cloudOrderCoupons.rows) {
            const id = orderCoupon.id; // Asumsikan 'id' adalah primary key di tabel order_coupons
            const localOrderCouponQuery = `SELECT 1 FROM order_coupons WHERE id = $1 LIMIT 1`;
            const localOrderCoupon = await promisifiedLocalQuery(localOrderCouponQuery, [id]);

            if (localOrderCoupon.rows.length === 0) {
                const keys = Object.keys(orderCoupon).map(key => key === 'table' ? '"table"' : key);
                const values = Object.values(orderCoupon);
                const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
                const insertQuery = `INSERT INTO order_coupons (${keys.join(', ')}) VALUES (${placeholders})`;
                await promisifiedLocalQuery(insertQuery, values);
                console.log(`Inserted order_coupon ID ${id} into local order_coupons table.`);
            }
        }

        console.log('Table order_coupons synchronized successfully from cloud to local.');

    } catch (error) {
        console.error('Error syncing orders and related tables:', error);
    }
}

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
    const today = moment().format('YYYY-MM-DD');

    try {
        // 1. Sinkronkan tabel permissions (dependency for role_has_permissions and model_has_permissions)
        const cloudPermissions = await promisifiedCloudQuery(`SELECT * FROM permissions`);
        await promisifiedLocalQuery(`DELETE FROM permissions`);
        for (const permission of cloudPermissions.rows) {
            const keys = Object.keys(permission).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(permission);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO permissions (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table permissions synchronized successfully from cloud to local.');

        // 2. Sinkronkan tabel roles (dependency for role_has_permissions and model_has_roles)
        const cloudRoles = await promisifiedCloudQuery(`SELECT * FROM roles`);
        await promisifiedLocalQuery(`DELETE FROM roles`);
        for (const role of cloudRoles.rows) {
            const keys = Object.keys(role).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(role);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO roles (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table roles synchronized successfully from cloud to local.');

        // 3. Sinkronkan tabel users (dependency for orders and other user-related tables)
        const cloudUsers = await promisifiedCloudQuery(`SELECT * FROM users`);
        await promisifiedLocalQuery(`DELETE FROM users`);
        for (const user of cloudUsers.rows) {
            const keys = Object.keys(user).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(user);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO users (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table users synchronized successfully from cloud to local.');

        // 4. Sinkronkan tabel suppliers (dependency for materials)
        const cloudSuppliers = await promisifiedCloudQuery(`SELECT * FROM suppliers`);
        await promisifiedLocalQuery(`DELETE FROM suppliers`);
        for (const supplier of cloudSuppliers.rows) {
            const keys = Object.keys(supplier).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(supplier);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO suppliers (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table suppliers synchronized successfully from cloud to local.');

        // 5. Sinkronkan tabel products (dependency for order_products and product-related tables)
        const cloudProducts = await promisifiedCloudQuery(`SELECT * FROM products`);
        await promisifiedLocalQuery(`DELETE FROM products`);
        for (const product of cloudProducts.rows) {
            const keys = Object.keys(product).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(product);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO products (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table products synchronized successfully from cloud to local.');

        // 6. Sinkronkan tabel materials (dependency for orders or related processes)
        const cloudMaterials = await promisifiedCloudQuery(`SELECT * FROM materials`);
        await promisifiedLocalQuery(`DELETE FROM materials`);
        for (const material of cloudMaterials.rows) {
            const keys = Object.keys(material).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(material);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO materials (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table materials synchronized successfully from cloud to local.');

        // 7. Sinkronkan tabel orders (dependency for order_products, order_coupons, etc.)
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

        // 8. Sinkronkan tabel order_products
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

        // 9. Sinkronkan tabel order_product_addons
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

        // 10. Sinkronkan tabel order_coupons
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

        // 11. Sinkronkan tabel addons
        const cloudAddons = await promisifiedCloudQuery(`SELECT * FROM addons`);
        await promisifiedLocalQuery(`DELETE FROM addons`);
        for (const addon of cloudAddons.rows) {
            const keys = Object.keys(addon).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(addon);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO addons (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table addons synchronized successfully from cloud to local.');

        // 12. Sinkronkan tabel product_addons
        const cloudProductAddons = await promisifiedCloudQuery(`SELECT * FROM product_addons`);
        await promisifiedLocalQuery(`DELETE FROM product_addons`);
        for (const productAddon of cloudProductAddons.rows) {
            const keys = Object.keys(productAddon).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(productAddon);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO product_addons (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table product_addons synchronized successfully from cloud to local.');

        // 13. Sinkronkan tabel tags
        const cloudTags = await promisifiedCloudQuery(`SELECT * FROM tags`);
        await promisifiedLocalQuery(`DELETE FROM tags`);
        for (const tag of cloudTags.rows) {
            const keys = Object.keys(tag).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(tag);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO tags (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table tags synchronized successfully from cloud to local.');

        // 14. Sinkronkan tabel product_tags
        const cloudProductTags = await promisifiedCloudQuery(`SELECT * FROM product_tags`);
        await promisifiedLocalQuery(`DELETE FROM product_tags`);
        for (const productTag of cloudProductTags.rows) {
            const keys = Object.keys(productTag).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(productTag);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO product_tags (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table product_tags synchronized successfully from cloud to local.');

        // 15. Sinkronkan tabel customers
        const cloudCustomers = await promisifiedCloudQuery(`SELECT * FROM customers`);
        await promisifiedLocalQuery(`DELETE FROM customers`);
        for (const customer of cloudCustomers.rows) {
            const keys = Object.keys(customer).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(customer);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO customers (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table customers synchronized successfully from cloud to local.');

        // 16. Sinkronkan tabel coupons
        const cloudCoupons = await promisifiedCloudQuery(`SELECT * FROM coupons`);
        await promisifiedLocalQuery(`DELETE FROM coupons`);
        for (const coupon of cloudCoupons.rows) {
            const keys = Object.keys(coupon).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(coupon);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO coupons (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table coupons synchronized successfully from cloud to local.');

        // 17. Sinkronkan tabel cache_onhold_controls
        const cloudCacheOnholdControls = await promisifiedCloudQuery(`SELECT * FROM cache_onhold_controls`);
        await promisifiedLocalQuery(`DELETE FROM cache_onhold_controls`);
        for (const cacheControl of cloudCacheOnholdControls.rows) {
            const keys = Object.keys(cacheControl).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(cacheControl);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO cache_onhold_controls (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table cache_onhold_controls synchronized successfully from cloud to local.');

        // 18. Sinkronkan tabel stores
        const cloudStores = await promisifiedCloudQuery(`SELECT * FROM stores`);
        await promisifiedLocalQuery(`DELETE FROM stores`);
        for (const store of cloudStores.rows) {
            const keys = Object.keys(store).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(store);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO stores (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table stores synchronized successfully from cloud to local.');

        // 19. Sinkronkan tabel tables
        const cloudTables = await promisifiedCloudQuery(`SELECT * FROM tables`);
        await promisifiedLocalQuery(`DELETE FROM tables`);
        for (const table of cloudTables.rows) {
            const keys = Object.keys(table).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(table);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO tables (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table tables synchronized successfully from cloud to local.');

        // 20. Sinkronkan tabel other_settings
        const cloudOtherSettings = await promisifiedCloudQuery(`SELECT * FROM other_settings`);
        await promisifiedLocalQuery(`DELETE FROM other_settings`);
        for (const setting of cloudOtherSettings.rows) {
            const keys = Object.keys(setting).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(setting);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO other_settings (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table other_settings synchronized successfully from cloud to local.');

        // 21. Sinkronkan tabel role_has_permissions
        const cloudRoleHasPermissions = await promisifiedCloudQuery(`SELECT * FROM role_has_permissions`);
        await promisifiedLocalQuery(`DELETE FROM role_has_permissions`);
        for (const rolePermission of cloudRoleHasPermissions.rows) {
            const keys = Object.keys(rolePermission).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(rolePermission);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO role_has_permissions (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table role_has_permissions synchronized successfully from cloud to local.');

        // 22. Sinkronkan tabel model_has_permissions
        const cloudModelHasPermissions = await promisifiedCloudQuery(`SELECT * FROM model_has_permissions`);
        await promisifiedLocalQuery(`DELETE FROM model_has_permissions`);
        for (const modelPermission of cloudModelHasPermissions.rows) {
            const keys = Object.keys(modelPermission).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(modelPermission);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO model_has_permissions (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table model_has_permissions synchronized successfully from cloud to local.');

        // 23. Sinkronkan tabel model_has_roles
        const cloudModelHasRoles = await promisifiedCloudQuery(`SELECT * FROM model_has_roles`);
        await promisifiedLocalQuery(`DELETE FROM model_has_roles`);
        for (const modelRole of cloudModelHasRoles.rows) {
            const keys = Object.keys(modelRole).map(key => key === 'table' ? '"table"' : key);
            const values = Object.values(modelRole);
            const placeholders = values.map((_, index) => `$${index + 1}`).join(', ');
            const insertQuery = `INSERT INTO model_has_roles (${keys.join(', ')}) VALUES (${placeholders})`;
            await promisifiedLocalQuery(insertQuery, values);
        }
        console.log('Table model_has_roles synchronized successfully from cloud to local.');

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
