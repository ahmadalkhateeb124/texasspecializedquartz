<?php

ob_start();

/* ── Environment-aware error reporting ───────────────────────
   Local: show every error to help debugging.
   Production: log silently, never leak details to visitors. */
$_host    = $_SERVER['HTTP_HOST'] ?? '';
$_isLocal = in_array($_host, ['localhost', '127.0.0.1'], true)
         || str_starts_with($_host, '192.168.')
         || str_starts_with($_host, '10.');

if ($_isLocal) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', __DIR__ . '/logs/php-errors.log');
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
}
unset($_host, $_isLocal);

$requestUrl = isset($_GET['url']) ? $_GET['url'] : '/';
$SP = isset($_GET['puid']) ? $_GET['puid'] : '';

// مسار الصفحة
$path = 'pages/' . $requestUrl . '.php';

// طباعة لمساعدة في التصحيح

switch ($requestUrl) {
    case '/':
    case 'Home':
        include_once 'inc/conn.php';
        include_once 'parts/header.php';
        // CheckUserLogIn($base_url);
        include_once 'pages/Home.php';
        include_once 'parts/footer.php';
        break;

    case 'B':
    case 'M':
        include_once 'inc/conn.php';
        include_once $path;
        break;

    default:
        if (file_exists($path)) {
            include_once 'inc/conn.php';
            include_once 'parts/header.php';
            // CheckUserLogIn($base_url);
            include_once $path;
            include_once 'parts/footer.php';
        } else {
            include_once 'inc/conn.php';
            include_once 'parts/header.php';
            include_once 'pages/404.php';
            include_once 'parts/footer.php';
        }
        break;
}
