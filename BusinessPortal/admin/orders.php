<?php

/**
 * admin/orders.php — All Fabrication Orders (Admin View)
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/repositories/orders_repo.php';

requireAdmin('../auth-login-minimal.php');

$currentUser = currentUser();
$pageTitle   = 'Fabrication Orders';
$breadcrumb  = [['label' => 'Orders']];

$result  = fetchOrdersGrouped($pdo);
$orders  = $result['orders'];
$dbError = $result['error'];
$stats   = summarizeOrders($orders);

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="page-title">Fabrication Orders</h1>
                    <p class="page-desc">Manage and track all fabrication job orders.</p>
                </div>
                <a href="order-new" class="btn btn-primary">
                    <i class='bx bx-plus'></i> New Order
                </a>
            </div>

            <?php if ($dbError): ?>
                <div class="alert alert-danger mb-3">
                    <i class='bx bx-error me-2'></i> Database error: <?= $dbError ?>
                </div>
            <?php endif; ?>

            <?php include __DIR__ . '/includes/partials/orders/stat-bar.php'; ?>
            <?php include __DIR__ . '/includes/partials/orders/table.php'; ?>

        </main>

        <?php include __DIR__ . '/includes/partials/orders/modals.php'; ?>

        <meta name="csrf-token" content="<?= csrfToken() ?>">

        <?php include __DIR__ . '/includes/footer.php'; ?>
        <script src="../assets/js/admin/orders.js"></script>
