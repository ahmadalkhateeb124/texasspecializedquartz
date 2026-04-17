<?php
// delete-order.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_POST['csrf_token']) || !hash_equals(csrfToken(), $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$order_id = intval($_POST['id'] ?? 0);
if ($order_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Order ID is required']);
    exit;
}

try {
    $pdo->beginTransaction();

    $check_stmt = $pdo->prepare("SELECT id FROM fabrication_orders WHERE id = ?");
    $check_stmt->execute([$order_id]);

    if ($check_stmt->rowCount() === 0) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    $pdo->prepare("DELETE FROM job_sections WHERE order_id = ?")->execute([$order_id]);
    $pdo->prepare("DELETE FROM fabrication_orders WHERE id = ?")->execute([$order_id]);

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Order deleted successfully']);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log("Order deletion error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while deleting the order.']);
}
