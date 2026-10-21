# Busitema University Internationalization & Partnerships

Production-ready PHP 8/MySQL website with a public site and content-management admin area.

## Requirements

- PHP 8.1 or newer with PDO MySQL, mbstring and fileinfo
- MySQL 5.7+ or MariaDB 10.4+
- Apache with `mod_rewrite`, `mod_headers`, `mod_expires` and `mod_deflate` (standard on Hostinger)
- HTTPS certificate

No Composer runtime dependencies are required. Node.js is needed only when rebuilding Tailwind CSS locally.

## Hostinger deployment

1. In hPanel, create a MySQL database and database user. Save the exact database host, name, username and password shown by Hostinger.
2. Open phpMyAdmin, select that database, and import `database/database.sql`, followed by `database/seeds.sql` if sample content is required.
3. Upload the project contents—including `.htaccess`—directly into the domain's `public_html` folder. Do not upload `node_modules`.
4. Copy `.env.example` to `.env` on the server and set the production values. Do not make `.env` publicly downloadable or commit it to Git.
5. Set `APP_URL` to the final HTTPS origin with no trailing slash, for example `https://international.busitema.ac.ug`.
6. In hPanel's PHP Configuration, select PHP 8.2 or newer and enable HTTPS/SSL.
7. Ensure `public/assets/images/hero`, `news`, `partners`, `team`, and `seo` are writable by PHP (normally directories `755`; use `775` only if Hostinger requires it).
8. Create the first admin user using a password hash generated locally:

   ```bash
   php -r "echo password_hash('REPLACE-WITH-A-LONG-PASSWORD', PASSWORD_DEFAULT), PHP_EOL;"
   ```

   Then insert the resulting hash in phpMyAdmin:

   ```sql
   INSERT INTO admin_users (name, username, password_hash, role)
   VALUES ('Site Administrator', 'admin', 'PASTE_HASH_HERE', 'admin');
   ```

9. Sign in at `/admin/login`, replace sample content, and verify that `/robots.txt` and `/sitemap.xml` load.
10. Keep `APP_DEBUG=false` in production. Turn it on only temporarily in a private local environment.

When upgrading a database created by an older version of the project, run the SQL files in `database/migrations/` once, in filename order. Fresh installations do not need the migrations.

## Production environment

```dotenv
DB_HOST=localhost
DB_PORT=3306
DB_NAME=hostinger_database_name
DB_USER=hostinger_database_user
DB_PASS="a-strong-database-password"
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Africa/Kampala
APP_URL=https://your-final-domain.example
```

Quoted `.env` values are supported, which is useful when a password contains spaces or `#` characters.

## Local development

Create and select a local database, import both database scripts into it, copy `.env.example` to `.env`, set `APP_DEBUG=true`, and serve the project through Apache/XAMPP. To rebuild Tailwind CSS:

```bash
npm ci
npm run build:css
```

Before each release, run:

```bash
npm run build:css
php -l public/index.php
```

The root and public `.htaccess` files provide clean URLs, private-source protection, compression, security headers, and long-lived versioned asset caching.
