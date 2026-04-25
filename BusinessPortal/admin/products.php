<?php

/**
 * admin/products.php — Products Index
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Products';
$breadcrumb  = [['label' => 'Products']];

$dbError = null;
try {
    $repo     = new ProductRepository($pdo);
    $products = $repo->all();
    $counts   = $repo->availabilityCounts($products);
} catch (PDOException $e) {
    $products = [];
    $counts   = ['available' => 0, 'reserved' => 0, 'sold_out' => 0];
    $dbError  = htmlspecialchars($e->getMessage());
}

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Remnants</h1>
                    <p class="page-subtitle">
                        <?= count($products) ?> total product<?= count($products) !== 1 ? 's' : '' ?>
                    </p>
                </div>
                <div class="page-actions">
                    <a href="products-new" class="btn btn-primary">
                        <i class='bx bx-plus'></i> Add Remnants
                    </a>
                </div>
            </div>

            <?php if ($dbError): ?>
                <div class="alert alert-danger mb-3">
                    <i class='bx bx-error me-2'></i><?= $dbError ?>
                </div>
            <?php endif; ?>

            <?php include __DIR__ . '/includes/partials/products/stat-bar.php'; ?>
            <?php include __DIR__ . '/includes/partials/products/table.php'; ?>

        </main>

        <?php include __DIR__ . '/includes/partials/products/delete-modal.php'; ?>
        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/products.js"></script>
