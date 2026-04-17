<?php

/**
 * Get Assigned Accounts for Price List
 */

session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/pricelist-db.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['price_list_id'])) {
        throw new Exception('Missing price list ID');
    }

    $priceListManager = new PriceListManager($pdo);
    $accounts = $priceListManager->getAssignedAccounts($_GET['price_list_id']);

    echo json_encode([
        'success' => true,
        'accounts' => $accounts
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
