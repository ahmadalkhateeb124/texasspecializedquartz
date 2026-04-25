<?php
/**
 * Database credentials — PRODUCTION ONLY.
 *
 * 1. Copy this file to `database.credentials.php` (same directory).
 * 2. Fill in the real database credentials from your hosting panel.
 * 3. NEVER commit `database.credentials.php` (it's gitignored).
 *
 * The main `database.php` config will require this file automatically
 * on production hosts.
 */

return [
    'host'     => 'localhost',
    'port'     => 3306,
    'dbname'   => 'YOUR_PROD_DB_NAME',
    'username' => 'YOUR_PROD_DB_USER',
    'password' => 'YOUR_PROD_DB_PASSWORD',
    'charset'  => 'utf8mb4',
];
