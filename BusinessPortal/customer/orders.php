<?php

/**
 * customer/orders.php — Customer: My Orders
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireCustomer('../auth-login-minimal.php');

$currentUser = currentUser();
$accountId   = $currentUser['id'];
$pageTitle   = 'My Orders';
$breadcrumb  = [['label' => 'My Orders']];

$dbError = null;
try {
    $orders = (new OrderRepository($pdo))->allForAccount($accountId);
} catch (PDOException $e) {
    $orders  = [];
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
                    <h1 class="page-title">My Fabrication Orders</h1>
                    <p class="page-desc">View all orders you have submitted.</p>
                </div>
                <a href="order-new" class="btn btn-primary">
                    <i class='bx bx-plus'></i> New Order
                </a>
            </div>

            <?php if ($dbError): ?>
                <div class="alert alert-danger mb-3"><i class='bx bx-error me-2'></i><?= $dbError ?></div>
            <?php endif; ?>

            <?php if (empty($orders)): ?>
                <?php include __DIR__ . '/includes/partials/orders/empty-state.php'; ?>
            <?php else: ?>
                <?php include __DIR__ . '/includes/partials/orders/stat-bar.php'; ?>
                <?php include __DIR__ . '/includes/partials/orders/table.php'; ?>
            <?php endif; ?>

        </main>

        <script src="../assets/js/customer/orders.js"></script>

        <?php include __DIR__ . '/includes/footer.php'; ?>
