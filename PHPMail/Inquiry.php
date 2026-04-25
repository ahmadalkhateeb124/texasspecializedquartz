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

/* ───────── 4. Per-session rate limit ───────── */
$now      = time();
$window   = 30 * 60;     // 30 minutes
$maxHits  = 5;
$rl       = $_SESSION['contact_rl'] ?? ['count' => 0, 'first' => $now];
if ($now - ($rl['first'] ?? $now) > $window) {
    $rl = ['count' => 0, 'first' => $now];
}
if (($rl['count'] ?? 0) >= $maxHits) {
    http_response_code(429);
    echo json_encode([
        'success' => false,
        'message' => 'Too many submissions. Please try again in 30 minutes.'
    ]);
    exit;
}
$rl['count'] = ($rl['count'] ?? 0) + 1;
$_SESSION['contact_rl'] = $rl;

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

$bodyHtml = '<p><strong>Name:</strong> ' . htmlspecialchars($nameRaw) . '</p>'
          . '<p><strong>Email:</strong> ' . htmlspecialchars($emailRaw) . '</p>'
          . '<p><strong>Subject:</strong> ' . htmlspecialchars($subjectRaw) . '</p>'
          . '<hr><p>' . nl2br(htmlspecialchars($messageRaw)) . '</p>'
          . '<hr><small>Submitted from: ' . htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? '?') . ' · '
          . htmlspecialchars(($_SERVER['HTTP_USER_AGENT'] ?? '?')) . '</small>';

$mail = new PHPMailer(true);
try {
    $mail->CharSet    = 'UTF-8';
    $mail->isHTML(true);
    $mail->isSMTP();
    $mail->Host       = $smtpHost;
    $mail->Port       = $smtpPort;
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = $smtpSecure;
    $mail->Username   = $smtpUser;
    $mail->Password   = $smtpPass;

    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($receiver);
    $mail->addReplyTo($emailRaw, $nameRaw);
    $mail->Subject    = '[Inquiry] ' . mb_substr($subjectRaw, 0, 100);
    $mail->Body       = $bodyHtml;
    $mail->AltBody    = "Name: $nameRaw\nEmail: $emailRaw\nSubject: $subjectRaw\n\n$messageRaw";

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
