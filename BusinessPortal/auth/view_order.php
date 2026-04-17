<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check customer account status
$account_id = isCustomer() ? ($_SESSION['account_id'] ?? null) : null;
if ($account_id) {
    $stmt = $pdo->prepare("SELECT status FROM accounts WHERE id = ?");
    $stmt->execute([$account_id]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$account || $account['status'] !== 'Active') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Account access denied.']);
        exit;
    }
}

$orderId = $_GET['id'] ?? 0;
if (!$orderId) {
    echo json_encode(['success' => false, 'message' => 'Order ID required']);
    exit;
}

try {
    // Get order details
    $stmt = $pdo->prepare("SELECT * FROM fabrication_orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    // Get job sections
    $stmt = $pdo->prepare("SELECT * FROM job_sections WHERE order_id = ? ORDER BY id");
    $stmt->execute([$orderId]);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $order['jobs'] = $jobs;

    echo json_encode(['success' => true, 'order' => $order]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
