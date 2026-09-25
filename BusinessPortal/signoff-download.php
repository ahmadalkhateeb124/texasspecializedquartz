<?php

/**
 * signoff-download.php — Serves an order's installation sign-off PDF,
 * gated to: the admin, the order's own customer, or the order's assigned employee.
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/src/bootstrap.php';

requireLogin();

$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($orderId <= 0) {
    http_response_code(400);
    die('Invalid order.');
}

$stmt = $pdo->prepare("SELECT account_id, assigned_employee_id FROM fabrication_orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    http_response_code(404);
    die('Order not found.');
}

$authorized = false;
if (isAdmin()) {
    $authorized = true;
} elseif (isCustomer()) {
    $authorized = (int)($order['account_id'] ?? 0) === (int)($_SESSION['account_id'] ?? 0);
} elseif (isEmployee()) {
    $authorized = (int)($order['assigned_employee_id'] ?? 0) === (int)($_SESSION['user_id'] ?? 0);
}

if (!$authorized) {
    http_response_code(403);
    die('You do not have access to this document.');
}

$signoff = (new SignoffRepository($pdo))->findByOrder($orderId);
if (!$signoff) {
    http_response_code(404);
    die('No sign-off exists for this order yet.');
}

$pdfConfig = require __DIR__ . '/config/pdf.php';
$path      = rtrim($pdfConfig['storage_dir'], '/') . '/' . basename($signoff['pdf_filename']);

if (!is_file($path)) {
    http_response_code(404);
    die('Sign-off file is missing.');
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="signoff-order-' . $orderId . '.pdf"');
header('Content-Length: ' . filesize($path));
readfile($path);
exit;
