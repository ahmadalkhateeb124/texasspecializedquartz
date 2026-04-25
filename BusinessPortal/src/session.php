<?php
/**
 * src/session.php — hardened session bootstrap.
 * Include BEFORE any session_start() call.
 *
 * Sets secure cookie flags, hardens session ID handling,
 * then starts the session if it isn't already active.
 */

if (session_status() === PHP_SESSION_NONE) {
    $_sHost   = $_SERVER['HTTP_HOST'] ?? '';
    $_sLocal  = in_array($_sHost, ['localhost', '127.0.0.1'], true)
             || str_starts_with($_sHost, '192.168.')
             || str_starts_with($_sHost, '10.')
             || PHP_SAPI === 'cli';
    $_sHttps  = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
             || ($_SERVER['SERVER_PORT'] ?? '') === '443';

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $_sHttps && !$_sLocal,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    ini_set('session.use_strict_mode',  '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly',  '1');

    session_start();

    unset($_sHost, $_sLocal, $_sHttps);
}
