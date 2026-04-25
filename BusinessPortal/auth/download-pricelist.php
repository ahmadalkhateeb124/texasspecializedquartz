<?php
/**
 * Secure price list download endpoint.
 *
 * URL: /BusinessPortal/auth/download-pricelist.php?id=<priceListId>[&preview=1]
 *
 * Authorisation rules:
 *   • Admin     → can download any list
 *   • Customer  → only if the list is in `price_list_accounts` for their account
 *   • Anonymous → 401
 *
 * Streams the file with `Content-Disposition: attachment` so browsers always
 * download instead of rendering inline (use ?preview=1 for inline view).
 */

require_once __DIR__ . '/../src/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/pricelist-db.php';

requireLogin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    exit('Invalid request.');
}

$pm = new PriceListManager($pdo);
$pl = $pm->getPriceListById($id);
if (!$pl) {
    http_response_code(404);
    exit('Price list not found.');
}

/* ── Authorisation ───────────────────────────────────────────── */
if (!isAdmin()) {
    $user = currentUser();
    $accountId = (int)($user['id'] ?? 0);

    $stmt = $pdo->prepare("
        SELECT 1
          FROM price_list_accounts
         WHERE price_list_id = :pl
           AND account_id    = :a
         LIMIT 1
    ");
    $stmt->execute([':pl' => $id, ':a' => $accountId]);
    if (!$stmt->fetchColumn()) {
        http_response_code(403);
        exit('You do not have access to this file.');
    }
}

/* ── Resolve file on disk ────────────────────────────────────── */
$filePath = $pm->getFilePath($pl['file_path']);
if (!is_file($filePath)) {
    http_response_code(404);
    exit('File missing on server.');
}

/* ── Build a safe display filename ───────────────────────────── */
$ext = strtolower(pathinfo($pl['file_path'], PATHINFO_EXTENSION));

$mimeMap = [
    'pdf'  => 'application/pdf',
    'xls'  => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'doc'  => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
];
$mime = $mimeMap[$ext] ?? 'application/octet-stream';

$displayBase = preg_replace('/[^\w\-. ]/u', '_', (string)$pl['file_name']);
$displayBase = trim($displayBase) ?: 'pricelist';
$displayName = $displayBase . '.' . $ext;

/* ── Inline preview only for safe types (?preview=1) ─────────── */
$wantPreview = !empty($_GET['preview']);
$inline      = $wantPreview && in_array($ext, ['pdf', 'jpg', 'jpeg', 'png', 'gif'], true);
$disposition = $inline ? 'inline' : 'attachment';

/* ── Stream ──────────────────────────────────────────────────── */
while (ob_get_level() > 0) ob_end_clean();

header('Content-Type: ' . $mime);
header('Content-Disposition: ' . $disposition . '; filename="' . $displayName . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: private');
header('X-Content-Type-Options: nosniff');

readfile($filePath);
exit;
