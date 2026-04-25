<?php

/**
 * Delete Price List Handler
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

    $priceListManager = new PriceListManager($pdo);
    $priceListManager->deletePriceList($_POST['price_list_id']);

    echo json_encode([
        'success' => true,
        'message' => 'Price list deleted successfully'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
