<?php
// check_availability.php
header('Content-Type: application/json');

if (!isset($_GET['type']) || !isset($_GET['value'])) {
    echo json_encode(['exists' => false]);
    exit;
}

$type = $_GET['type'];
$value = trim($_GET['value']);

// Include database connection
require_once __DIR__ . '/../partials/conn.php';
require_once __DIR__ . '/../auth/auth-check.php';

try {
    $exists = false;

    if ($type === 'ein') {
        // Validate EIN format
        if (!preg_match('/^\d{2}-\d{7}$/', $value)) {
            echo json_encode(['exists' => false]);
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM customers_companies WHERE ein = ?");
        $stmt->execute([$value]);
        $exists = $stmt->rowCount() > 0;

    } elseif ($type === 'email') {
        // Validate email format
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['exists' => false]);
            exit;
        }

        // Check in both tables (customers_companies and accounts)
        $stmt1 = $pdo->prepare("SELECT id FROM customers_companies WHERE email = ?");
        $stmt1->execute([$value]);

        $stmt2 = $pdo->prepare("SELECT id FROM accounts WHERE email = ?");
        $stmt2->execute([$value]);

        $exists = ($stmt1->rowCount() > 0) || ($stmt2->rowCount() > 0);
    }

    echo json_encode(['exists' => $exists]);

} catch (Exception $e) {
    // Log error but don't expose to user
    error_log("Availability check error: " . $e->getMessage());
    echo json_encode(['exists' => false]);
}