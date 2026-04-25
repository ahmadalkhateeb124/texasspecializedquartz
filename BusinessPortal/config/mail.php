<?php
/**
 * Mail (SMTP) configuration — environment-aware.
 *
 * Local dev: returns the inline defaults below (with an empty password,
 *            so emails won't actually send — that's fine for testing).
 * Production: loads credentials from `mail.credentials.php`
 *             (gitignored, NOT committed). Falls back to env variables.
 */

$_mHost  = $_SERVER['HTTP_HOST'] ?? '';
$_mLocal = in_array($_mHost, ['localhost', '127.0.0.1'], true)
        || str_starts_with($_mHost, '192.168.')
        || str_starts_with($_mHost, '10.')
        || PHP_SAPI === 'cli';

$defaults = [
    'enabled'        => true,
    'smtp_host'      => 'smtp.hostinger.com',
    'smtp_port'      => 465,
    'smtp_secure'    => 'ssl',
    'username'       => 'inquiry@texasspecializedquartz.com',
    'password'       => '',
    'from_email'     => 'inquiry@texasspecializedquartz.com',
    'from_name'      => 'Texas Specialized Quartz',
    'admin_email'    => 'Cs@TexasSpecializedQuartz.com',
    'phpmailer_path' => __DIR__ . '/../../PHPMail/src',
];

if ($_mLocal) {
    return $defaults;
}

/* ── Production: load from gitignored credentials file ── */
$credFile = __DIR__ . '/mail.credentials.php';
if (is_file($credFile)) {
    return array_merge($defaults, require $credFile);
}

/* ── Fallback to environment variables ── */
$envOverride = array_filter([
    'smtp_host'   => getenv('MAIL_HOST')   ?: null,
    'smtp_port'   => getenv('MAIL_PORT')   ? (int)getenv('MAIL_PORT') : null,
    'smtp_secure' => getenv('MAIL_SECURE') ?: null,
    'username'    => getenv('MAIL_USER')   ?: null,
    'password'    => getenv('MAIL_PASS')   ?: null,
    'from_email'  => getenv('MAIL_FROM')   ?: null,
    'admin_email' => getenv('MAIL_TO')     ?: null,
], fn($v) => $v !== null);

return array_merge($defaults, $envOverride);
