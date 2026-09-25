<?php
/**
 * admin/test-mail.php — One-shot SMTP diagnostic.
 *
 * Open ONCE on production:
 *   https://texasspecializedquartz.com/BusinessPortal/admin/test-mail.php?key=tx-mailtest-2026
 *
 * Sends a small test email to admin_email using current mail.php config.
 * After confirming it works on prod, DELETE this file (don't leave it
 * accessible — it's a public endpoint).
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../src/bootstrap.php';

// Lightweight protection — random key + admin login required
if (($_GET['key'] ?? '') !== 'tx-mailtest-2026') {
    http_response_code(403);
    exit('Forbidden — missing key.');
}
if (!isAdmin()) {
    http_response_code(401);
    exit('Forbidden — admin login required.');
}

header('Content-Type: text/plain; charset=utf-8');

$cfg = require __DIR__ . '/../config/mail.php';

echo "═══════════════════════════════════════════════════════\n";
echo "  SMTP Diagnostic — Texas Specialized Quartz\n";
echo "═══════════════════════════════════════════════════════\n\n";

echo "Active configuration:\n";
echo "  enabled        : " . var_export($cfg['enabled'], true) . "\n";
echo "  smtp_host      : {$cfg['smtp_host']}\n";
echo "  smtp_port      : {$cfg['smtp_port']}\n";
echo "  smtp_secure    : {$cfg['smtp_secure']}\n";
echo "  username       : {$cfg['username']}\n";
echo "  password       : " . (empty($cfg['password']) ? '(EMPTY ❌)' : '••••••••') . "\n";
echo "  from_email     : {$cfg['from_email']}\n";
echo "  admin_email    : {$cfg['admin_email']}\n";
echo "  phpmailer_path : {$cfg['phpmailer_path']}\n";
echo "  PHPMailer file : " . (file_exists($cfg['phpmailer_path'] . '/PHPMailer.php') ? 'found ✓' : 'MISSING ❌') . "\n\n";

if (empty($cfg['password'])) {
    echo "❌ Password is empty — SMTP auth will fail.\n";
    echo "   Create BusinessPortal/config/mail.credentials.php with the\n";
    echo "   real Hostinger SMTP password.\n";
    exit;
}

/* Optional: pass ?to=anything@example.com to send elsewhere
   (e.g. mail-tester.com address for deliverability scoring) */
$recipient = filter_var($_GET['to'] ?? '', FILTER_VALIDATE_EMAIL) ?: $cfg['admin_email'];

echo "Sending test email to: {$recipient} ...\n\n";

$mailer = new Mailer($cfg);
$result = $mailer->send(
    $recipient,
    'TSQG SMTP test — ' . date('Y-m-d H:i:s'),
    '<h2>SMTP test successful ✓</h2><p>If you received this, your order-notification emails will work.</p><p>Sent at ' . date('c') . ' from ' . ($_SERVER['HTTP_HOST'] ?? '?') . '</p>',
    [
        'reply_to_email' => $cfg['from_email'],
        'reply_to_name'  => 'TSQG Mail Test',
    ]
);

echo "Result:\n";
echo "  success : " . ($result['success'] ? 'YES ✓' : 'NO ❌') . "\n";
echo "  method  : " . ($result['debug']['method']           ?? '?') . "\n";
echo "  error   : " . ($result['debug']['phpmailer_error'] ?? '(none)') . "\n";

echo "\n═══════════════════════════════════════════════════════\n";
echo $result['success']
    ? "✓ Email sent to {$recipient} (check Inbox + Spam folder).\n\n"
      . "💡 To test deliverability score, get an address from\n"
      . "   https://mail-tester.com and send to it:\n"
      . "   ?key=tx-mailtest-2026&to=test-xyz@mail-tester.com\n"
    : "❌ Email FAILED. Read 'error' above for the SMTP error.\n";
echo "\n⚠️  Delete this file after testing:\n";
echo "    BusinessPortal/admin/test-mail.php\n";
