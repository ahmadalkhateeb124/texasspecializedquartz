<?php

/**
 * customer/priceList.php — Customer Price Lists View
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../auth/pricelist-db.php';
require_once __DIR__ . '/includes/partials/pricelist/_helpers.php';

requireCustomer('../auth-login-minimal.php');

$currentUser = currentUser();
$accountId   = $currentUser['id'];
$pageTitle   = 'Price Lists';
$breadcrumb  = [['label' => 'Price Lists']];

$dbError = null;
try {
    $priceListManager = new PriceListManager($pdo);
    $priceLists       = $priceListManager->getPriceListsForAccount($accountId);
} catch (PDOException $e) {
    $priceLists = [];
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

            <div class="page-header d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="page-title"><i class='bx bx-file me-2'></i>Price Lists</h1>
                    <p class="page-desc">Access and download your assigned pricing documents</p>
                </div>
            </div>

            <?php if ($dbError): ?>
                <div class="alert alert-danger mb-3">
                    <i class='bx bx-error-circle me-2'></i><?= $dbError ?>
                </div>
            <?php endif; ?>

            <?php include __DIR__ . '/includes/partials/pricelist/stat-bar.php'; ?>
            <?php include __DIR__ . '/includes/partials/pricelist/table.php'; ?>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/customer/pricelist.js"></script>
