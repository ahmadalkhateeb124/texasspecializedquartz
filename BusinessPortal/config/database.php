<?php

/**
 * Database configuration — environment-aware.
 *
 * Local dev:   uses inline defaults (no setup needed).
 * Production:  loads credentials from `database.credentials.php`
 *              (gitignored, NOT committed). Falls back to env
 *              variables if the file is missing.
 *
 * To deploy, create `database.credentials.php` next to this file
 * (use `database.credentials.example.php` as a template).
 */

$pdoOptions = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$_dbHost  = $_SERVER['HTTP_HOST'] ?? '';
$_isLocal = getenv('APP_ENV') !== 'production'   // `php run.php --live` on the server
         && (in_array($_dbHost, ['localhost', '127.0.0.1'], true)
             || str_starts_with($_dbHost, '192.168.')
             || str_starts_with($_dbHost, '10.')
             || PHP_SAPI === 'cli');

if ($_isLocal) {
    return [
        'host'     => 'localhost',
        'port'     => 3306,
        'dbname'   => 'u557236614_gr',
        'username' => 'root',
        'password' => '',
        'charset'  => 'utf8mb4',
        'options'  => $pdoOptions,
    ];
}

/* ── Production: load credentials from a gitignored file ── */
$credFile = __DIR__ . '/database.credentials.php';
if (is_file($credFile)) {
    $creds = require $credFile;
    return array_merge(['options' => $pdoOptions], $creds);
}

/* ── Last resort: read from server environment vars ── */
return [
    'host'     => getenv('DB_HOST')     ?: 'localhost',
    'port'     => (int)(getenv('DB_PORT') ?: 3306),
    'dbname'   => getenv('DB_NAME')     ?: '',
    'username' => getenv('DB_USER')     ?: '',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset'  => getenv('DB_CHARSET')  ?: 'utf8mb4',
    'options'  => $pdoOptions,
];
