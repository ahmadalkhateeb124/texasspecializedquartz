<?php
/**
 * auth/reactivate-order.php — Admin unlocks a closed (completed) order so the
 * customer and employee can interact with it again. The sign-off record and
 * PDF are kept as a permanent historical record; only the lock is lifted.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}
if (!isset($_POST['csrf_token']) || !hash_equals(csrfToken(), $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$orderId = (int)($_POST['order_id'] ?? 0);
if ($orderId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE fabrication_orders SET admin_status = 'in_progress' WHERE id = ?");
    $stmt->execute([$orderId]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    Logger::exception($e, 'app');
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
