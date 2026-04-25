<?php

/**
 * admin/priceList.php — Price List Management
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../auth/pricelist-db.php';
require_once __DIR__ . '/../customer/includes/partials/pricelist/_helpers.php';

requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Price Lists';
$breadcrumb  = [['label' => 'Price Lists']];
$extraCss    = '';

$dbError = null;
try {
    $priceListManager = new PriceListManager($pdo);
    $priceLists       = $priceListManager->getAllPriceLists();
    $accounts         = $priceListManager->getActiveAccounts();
} catch (PDOException $e) {
    $priceLists = [];
    $accounts   = [];
    $dbError    = htmlspecialchars($e->getMessage());
}
$fileCount = count($priceLists);

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title"><i class='bx bx-file me-2'></i>Price Lists</h1>
                    <p class="page-subtitle">
                        <?= $fileCount ?> total file<?= $fileCount !== 1 ? 's' : '' ?>
                    </p>
                </div>
                <div class="page-actions">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadPriceListModal">
                        <i class='bx bx-plus'></i> Upload Price List
                    </button>
                </div>
            </div>

            <?php if ($dbError): ?>
                <div class="alert alert-danger mb-3"><i class='bx bx-error me-2'></i><?= $dbError ?></div>
            <?php endif; ?>

            <?php include __DIR__ . '/includes/partials/pricelist/stat-bar.php'; ?>
            <?php include __DIR__ . '/includes/partials/pricelist/table.php'; ?>

        </main>

        <?php include __DIR__ . '/includes/partials/pricelist/modals.php'; ?>
        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/pricelist.js"></script>
