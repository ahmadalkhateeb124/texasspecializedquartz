<?php
date_default_timezone_set('Asia/Amman');

/* ── Base URL / path (local vs live) ─────────────────────────── */
$host = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = in_array($host, ['localhost', '127.0.0.1'], true) || str_starts_with($host, '192.168.');

/* ── Hardened session cookies (must run BEFORE session_start) ── */
if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || ($_SERVER['SERVER_PORT'] ?? '') === '443';

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps && !$isLocal,   // HTTPS only in production
        'httponly' => true,                     // not readable from JS
        'samesite' => 'Lax',                    // mitigates CSRF
    ]);
    ini_set('session.use_strict_mode',     '1'); // reject uninitialized session IDs
    ini_set('session.use_only_cookies',    '1'); // never accept session in URL
    ini_set('session.cookie_httponly',     '1');
    ini_set('session.sid_length',         '48'); // longer, harder to guess
    ini_set('session.sid_bits_per_character', '6');

    session_start();
}

if ($isLocal) {
    $base_url  = 'http://' . $host . '/texasspecializedquartz/';
    $base_path = $_SERVER['DOCUMENT_ROOT'] . '/texasspecializedquartz/';
} else {
    $base_url  = 'https://texasspecializedquartz.com/';
    $base_path = $_SERVER['DOCUMENT_ROOT'];
}

/* ── PDO connection (shared with BusinessPortal) ─────────────── */
if (!isset($pdo)) {
    $dbConfig = require __DIR__ . '/../BusinessPortal/config/database.php';
    try {
        $pdo = new PDO(
            "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}",
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['options']
        );
    } catch (PDOException $e) {
        die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
    }
}

/* ── Site settings (DB-backed key/value) ─────────────────────── */
require_once __DIR__ . '/../BusinessPortal/src/Repositories/SiteSettingsRepository.php';

function getCurrentURL(): string
{
    $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $proto . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '');
}

$currentURL = getCurrentURL();
