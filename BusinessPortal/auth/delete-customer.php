<?php
// auth/delete-customer.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
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

// Get customer ID
$customerId = $_POST['id'] ?? 0;
if ($customerId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid customer ID'
    ]);
    exit;
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // 1. First, get customer info for logging
    $stmt = $pdo->prepare("SELECT company_name FROM customers_companies WHERE id = ?");
    $stmt->execute([$customerId]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Customer not found'
        ]);
        exit;
    }

    $customerName = $customer['company_name'];

    // 2. Delete associated account first (due to foreign key constraint)
    $stmt = $pdo->prepare("DELETE FROM accounts WHERE company_id = ?");
    $stmt->execute([$customerId]);

    // 3. Delete the customer
    $stmt = $pdo->prepare("DELETE FROM customers_companies WHERE id = ?");
    $stmt->execute([$customerId]);

    // Check if deletion was successful
    if ($stmt->rowCount() > 0) {
        $pdo->commit();
        // Log the deletion
        error_log("Customer deleted: {$customerName} (ID: {$customerId}) by user: " . ($_SESSION['user_id'] ?? 'unknown'));
        echo json_encode([
            'success' => true,
            'message' => "Customer '{$customerName}' deleted successfully"
        ]);
    } else {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete customer'
        ]);
    }
} catch (PDOException $e) {
    // Rollback on error
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Delete customer error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred'
    ]);
}
