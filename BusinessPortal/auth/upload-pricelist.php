<?php

/**
 * Upload Price List Handler
 */

session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/pricelist-db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Check authentication (admin only)
if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized - Admin access required']);
    exit;
}

try {
    if (!isset($_FILES['file'])) {
        throw new Exception('No file received in upload request.');
    }

    if (!isset($_POST['file_name']) || empty(trim($_POST['file_name']))) {
        throw new Exception('File name is required.');
    }

    $assignedAccounts = isset($_POST['assigned_accounts']) ? (array)$_POST['assigned_accounts'] : [];

    $priceListManager = new PriceListManager($pdo);
    $priceListId = $priceListManager->uploadPriceList($_FILES['file'], trim($_POST['file_name']), $assignedAccounts);

    echo json_encode([
        'success' => true,
        'message' => 'Price list uploaded successfully',
        'price_list_id' => $priceListId
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
