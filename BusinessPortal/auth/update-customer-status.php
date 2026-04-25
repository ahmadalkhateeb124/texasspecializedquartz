<?php
// auth/update-customer-status.php
require_once __DIR__ . '/../src/session.php';
include __DIR__ . '/auth-check.php';
require_once __DIR__ . '/../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$status = $_GET['status'] ?? '';
$validStatuses = ['Active', 'Inactive', 'Blacklisted'];

if ($id <= 0) {
    die("Invalid account ID");
}

if (!in_array($status, $validStatuses, true)) {
    die("Invalid status");
}

try {
    $stmt = $pdo->prepare("UPDATE accounts SET status = ? WHERE company_id = ?");
    $stmt->execute([$status, $id]);

    // Log the status change
    error_log("Customer status updated: ID {$id} to {$status} by user: " . ($_SESSION['user_id'] ?? 'unknown'));

    header("Location: ../Customer.php?success=1");
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}