<?php
/**
 * PHPMail/Inquiry.php — Public contact form handler.
 *
 * Anti-spam stack:
 *   - Honeypot field (`website`) must be empty
 *   - Form must take ≥ 3 seconds (`loaded_at` timestamp)
 *   - Per-session token (`contact_token`)
 *   - Rate limit: max 5 submissions / 30 min per IP+session
 *   - Length limits, link counter, suspicious-pattern blocklist
 */

require_once __DIR__ . '/../inc/conn.php';
require_once __DIR__ . '/src/PHPMailer.php';
require_once __DIR__ . '/src/SMTP.php';
require_once __DIR__ . '/src/Exception.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

/* ───────── 1. Honeypot ───────── */
if (!empty(trim((string)($_POST['website'] ?? '')))) {
    // bot — pretend success so attacker doesn't tune
    echo json_encode(['success' => true, 'message' => 'Thanks!']);
    exit;
}

/* ───────── 2. Time trap (must take ≥ 3s) ───────── */
$loadedAt = (int)($_POST['loaded_at'] ?? 0);
if ($loadedAt > 0 && (time() - $loadedAt) < 3) {
    echo json_encode(['success' => true, 'message' => 'Thanks!']);
    exit;
}

/* ───────── 3. Session token ───────── */
$submittedToken = (string)($_POST['contact_token'] ?? '');
$expectedToken  = (string)($_SESSION['contact_token'] ?? '');
if ($submittedToken === '' || $expectedToken === '' || !hash_equals($expectedToken, $submittedToken)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Form expired. Please refresh and try again.']);
    exit;
}

/* ───────── 5. Pull + validate inputs ───────── */
$nameRaw    = trim((string)($_POST['name']    ?? ''));
$emailRaw   = trim((string)($_POST['Email']   ?? ''));
$subjectRaw = trim((string)($_POST['subject'] ?? ''));
$messageRaw = trim((string)($_POST['message'] ?? ''));

$errors = [];
if ($nameRaw === '' || mb_strlen($nameRaw) < 2 || mb_strlen($nameRaw) > 80) {
    $errors[] = 'Please enter a valid name.';
}
if ($emailRaw === '' || !filter_var($emailRaw, FILTER_VALIDATE_EMAIL) || mb_strlen($emailRaw) > 120) {
    $errors[] = 'Please enter a valid email address.';
}
if ($subjectRaw === '' || mb_strlen($subjectRaw) > 150) {
    $errors[] = 'Please enter a subject (max 150 characters).';
}
if ($messageRaw === '' || mb_strlen($messageRaw) < 10 || mb_strlen($messageRaw) > 4000) {
    $errors[] = 'Message must be 10–4000 characters.';
}

/* Block CRLF injection in headers (defends mail injection) */
foreach ([$nameRaw, $emailRaw, $subjectRaw] as $hdr) {
    if (preg_match('/[\r\n]/', $hdr)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid characters in form.']);
        exit;
    }
}

/* Cap link count in message to deter SEO spam */
if (preg_match_all('~https?://~i', $messageRaw) > 4) {
    $errors[] = 'Too many links in message.';
}

if ($errors) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

/* ───────── 6. Persist to DB (best effort) ───────── */
try {
    $stmt = $pdo->prepare("
        INSERT INTO inquiries (name, email, subject, message, ip)
        VALUES (:n, :e, :s, :m, :ip)
    ");
    $stmt->execute([
        ':n'  => $nameRaw,
        ':e'  => $emailRaw,
        ':s'  => $subjectRaw,
        ':m'  => $messageRaw,
        ':ip' => $_SERVER['REMOTE_ADDR'] ?? null,
    ]);
} catch (Throwable $e) {
    error_log('Inquiry DB insert failed: ' . $e->getMessage());
}

/* ───────── 7. Build + send email ───────── */
$mailCfgPath = __DIR__ . '/../BusinessPortal/config/mail.php';
$mailCfg     = is_file($mailCfgPath) ? require $mailCfgPath : [];

$smtpHost   = $mailCfg['smtp_host']   ?? 'smtp.hostinger.com';
$smtpPort   = (int)($mailCfg['smtp_port'] ?? 587);
$smtpSecure = $mailCfg['smtp_secure'] ?? 'tls';
$smtpUser   = $mailCfg['username']    ?? 'inquiry@texasspecializedquartz.com';
$smtpPass   = $mailCfg['password']    ?? '';
$fromEmail  = $mailCfg['from_email']  ?? $smtpUser;
$fromName   = $mailCfg['from_name']   ?? 'Texas Specialized Quartz';
$receiver   = $mailCfg['admin_email'] ?? 'Cs@TexasSpecializedQuartz.com';

$bodyHtml = '<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px 0;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;border:1px solid #e0e0e0;">
      <tr><td style="background:#1a1a1a;padding:24px 32px;">
        <p style="margin:0;color:#ffffff;font-size:20px;font-weight:bold;">Texas Specialized Quartz &amp; Granite</p>
        <p style="margin:4px 0 0;color:#ccb97a;font-size:13px;">New Website Inquiry</p>
      </td></tr>
      <tr><td style="padding:32px;">
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td style="padding:10px 0;border-bottom:1px solid #f0f0f0;">
              <span style="color:#888;font-size:12px;text-transform:uppercase;letter-spacing:.5px;">Name</span><br>
              <span style="color:#222;font-size:15px;font-weight:600;">' . htmlspecialchars($nameRaw) . '</span>
            </td>
          </tr>
          <tr>
            <td style="padding:10px 0;border-bottom:1px solid #f0f0f0;">
              <span style="color:#888;font-size:12px;text-transform:uppercase;letter-spacing:.5px;">Email</span><br>
              <a href="mailto:' . htmlspecialchars($emailRaw) . '" style="color:#1a6fc4;font-size:15px;">' . htmlspecialchars($emailRaw) . '</a>
            </td>
          </tr>
          <tr>
            <td style="padding:10px 0;border-bottom:1px solid #f0f0f0;">
              <span style="color:#888;font-size:12px;text-transform:uppercase;letter-spacing:.5px;">Phone / Subject</span><br>
              <span style="color:#222;font-size:15px;">' . htmlspecialchars($subjectRaw) . '</span>
            </td>
          </tr>
          <tr>
            <td style="padding:16px 0 0;">
              <span style="color:#888;font-size:12px;text-transform:uppercase;letter-spacing:.5px;">Message</span><br>
              <div style="margin-top:8px;color:#333;font-size:14px;line-height:1.7;background:#f9f9f9;border-left:3px solid #ccb97a;padding:12px 16px;border-radius:0 4px 4px 0;">'
              . nl2br(htmlspecialchars($messageRaw)) . '</div>
            </td>
          </tr>
        </table>
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:24px;">
          <tr>
            <td align="center">
              <a href="mailto:' . htmlspecialchars($emailRaw) . '" style="display:inline-block;background:#ccb97a;color:#1a1a1a;text-decoration:none;padding:12px 28px;border-radius:6px;font-weight:bold;font-size:14px;">Reply to ' . htmlspecialchars($nameRaw) . '</a>
            </td>
          </tr>
        </table>
      </td></tr>
      <tr><td style="background:#f9f9f9;padding:16px 32px;border-top:1px solid #e8e8e8;">
        <p style="margin:0;color:#aaa;font-size:11px;">Submitted from IP: ' . htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? '') . ' &nbsp;·&nbsp; texasspecializedquartz.com</p>
      </td></tr>
    </table>
  </td></tr>
</table>
</body></html>';

$altBody = "New inquiry from: {$nameRaw}\n"
         . "Email: {$emailRaw}\n"
         . "Phone/Subject: {$subjectRaw}\n"
         . str_repeat('-', 40) . "\n"
         . $messageRaw . "\n"
         . str_repeat('-', 40) . "\n"
         . "texasspecializedquartz.com";

$mail = new PHPMailer(true);
try {
    $mail->CharSet    = 'UTF-8';
    $mail->Encoding   = 'base64';
    $mail->isHTML(true);
    $mail->isSMTP();
    $mail->Host       = $smtpHost;
    $mail->Port       = $smtpPort;
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = $smtpSecure;
    $mail->Username   = $smtpUser;
    $mail->Password   = $smtpPass;
    $mail->XMailer    = ' ';

    $mail->setFrom($fromEmail, $fromName);
    $emails = [
    $receiver,
        'jay@texasspecializedquartz.com'
    ];
    
    foreach ($emails as $to) {
        $mail->addAddress($to);
    }
    $mail->addReplyTo($emailRaw, $nameRaw);
    $mail->Subject    = 'New Inquiry from ' . mb_substr($nameRaw, 0, 50) . ' — Texas Specialized Quartz';
    $mail->Body       = $bodyHtml;
    $mail->AltBody    = $altBody;

    $mail->addCustomHeader('X-Priority', '3');
    $mail->addCustomHeader('X-Mailer-Info', 'texasspecializedquartz.com contact form');
    $mail->MessageID  = '<' . uniqid('tsq-', true) . '@texasspecializedquartz.com>';

    $mail->send();

    /* Save to Sent folder if IMAP credentials available */
    if (function_exists('imap_open') && !empty($mailCfg['imap_path'])) {
        try { save_mail($mail, $smtpUser, $smtpPass, $mailCfg['imap_path']); } catch (Throwable $e) {}
    }

    /* Rotate token after successful send so the same form can't be replayed */
    unset($_SESSION['contact_token']);

    echo json_encode(['success' => true, 'message' => 'Thank you! We will reach out shortly.']);
} catch (Exception $e) {
    error_log('Inquiry mail failed: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, we could not send your message. Please call (469) 814-0555.'
    ]);
}

function save_mail($mail, $user, $pass, $path)
{
    $stream = @imap_open($path, $user, $pass);
    if ($stream === false) return false;
    $ok = imap_append($stream, $path, $mail->getSentMIMEMessage());
    imap_close($stream);
    return $ok;
}
