<?php

/**
 * src/bootstrap.php — autoloader for the src/ library.
 * Usage: require_once __DIR__ . '/../src/bootstrap.php';
 */

/* Hardened session bootstrap (sets secure cookie flags before session_start) */
require_once __DIR__ . '/session.php';

spl_autoload_register(function (string $class): void {
    // Only handle unprefixed app classes (skip PHPMailer's namespaced classes).
    if (str_contains($class, '\\')) {
        return;
    }

    $roots = [
        __DIR__ . '/',                  // top-level helpers like Logger.php
        __DIR__ . '/Repositories/',
        __DIR__ . '/Services/',
        __DIR__ . '/Emails/',
    ];
    foreach ($roots as $root) {
        $file = $root . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

/* Global exception + fatal error logging.
   On production, also keeps the visitor from seeing a stack trace. */
set_exception_handler(function (Throwable $e): void {
    Logger::exception($e, 'uncaught');

    if (PHP_SAPI !== 'cli' && !headers_sent()) {
        http_response_code(500);
    }
});

register_shutdown_function(function (): void {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        Logger::error('php.fatal', [
            'msg'  => $err['message'],
            'file' => ($err['file'] ?? '?') . ':' . ($err['line'] ?? '?'),
        ]);
    }
});
