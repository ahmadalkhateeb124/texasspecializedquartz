<?php
/**
 * Mail credentials — PRODUCTION ONLY.
 *
 * 1. Copy to `mail.credentials.php` (same dir).
 * 2. Set your real SMTP credentials.
 * 3. Never commit `mail.credentials.php` (it's gitignored).
 */

return [
    'smtp_host'   => 'smtp.hostinger.com',
    'smtp_port'   => 465,
    'smtp_secure' => 'ssl',          // 'ssl' or 'tls'
    'username'    => 'inquiry@texasspecializedquartz.com',
    'password'    => 'YOUR_REAL_SMTP_PASSWORD',
    'from_email'  => 'inquiry@texasspecializedquartz.com',
    'from_name'   => 'Texas Specialized Quartz',
    'admin_email' => 'Cs@TexasSpecializedQuartz.com',
];
