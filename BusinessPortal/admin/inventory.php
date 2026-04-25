<?php

/**
 * admin/inventory.php — Inventory Slabs (natural stone catalogue).
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Inventory';
$breadcrumb  = [['label' => 'Inventory']];

$dbError = null;
try {
    $slabs = (new InventorySlabRepository($pdo))->all();
} catch (PDOException $e) {
    $slabs   = [];
    $dbError = htmlspecialchars($e->getMessage());
}

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="page-title">Inventory Slabs</h1>
                    <p class="page-desc">
                        <?= count($slabs) ?> slab<?= count($slabs) !== 1 ? 's' : '' ?> in catalogue
                    </p>
                </div>
                <a href="inventory-new" class="btn btn-primary">
                    <i class='bx bx-plus'></i> Add Slab
                </a>
            </div>

            <?php if ($dbError): ?>
                <div class="alert alert-danger mb-3">
                    <i class='bx bx-error me-2'></i><?= $dbError ?>
                </div>
            <?php endif; ?>

            <?php include __DIR__ . '/includes/partials/inventory/table.php'; ?>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/inventory.js"></script>
