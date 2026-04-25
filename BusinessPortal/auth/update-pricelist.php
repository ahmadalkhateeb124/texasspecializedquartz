<?php

/**
 * Update Price List Handler
 */

require_once __DIR__ . '/../src/session.php';
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
    if (!isset($_POST['price_list_id']) || empty($_POST['price_list_id'])) {
        throw new Exception('Price list ID is required.');
    }

    if (!isset($_POST['file_name']) || empty(trim($_POST['file_name']))) {
        throw new Exception('File name is required.');
    }

    $file = isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE ? $_FILES['file'] : null;
    $assignedAccounts = isset($_POST['assigned_accounts']) ? array_filter((array)$_POST['assigned_accounts']) : [];

    $priceListManager = new PriceListManager($pdo);
    $priceListManager->updatePriceList($_POST['price_list_id'], $file, trim($_POST['file_name']), $assignedAccounts);

    echo json_encode([
        'success' => true,
        'message' => 'Price list updated successfully'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
