<?php

/**
 * customer/order-view.php — Customer: View a single order.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../includes/partials/orders/view/_helpers.php';

requireCustomer('../auth-login-minimal.php');

$currentUser = currentUser();
$accountId   = $currentUser['id'];
$id          = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: orders');
    exit;
}

try {
    $order = (new OrderRepository($pdo))->findWithJobs($id);
    if (!$order || (int)$order['account_id'] !== (int)$accountId) {
        header('Location: orders');
        exit;
    }
    $job_sections = $order['jobs'];
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}

$pageTitle  = 'Order #' . $order['id'];
$breadcrumb = [
    ['label' => 'My Orders', 'url' => 'orders.php'],
    ['label' => 'Order #' . $order['id']],
];
$extraCss   = '<link rel="stylesheet" href="../assets/css/shared/order-view.css">';

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Order #<?= $order['id'] ?></h1>
                    <p class="page-subtitle">
                        Created <?= date('M j, Y', strtotime($order['created_at'])) ?>
                        &middot; Last updated <?= date('M j, Y g:i A', strtotime($order['updated_at'])) ?>
                    </p>
                </div>
                <div class="page-actions">
                    <a href="orders" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Orders
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <?php include __DIR__ . '/../includes/partials/orders/view/customer-info.php'; ?>
                    <?php include __DIR__ . '/../includes/partials/orders/view/sales-rep.php'; ?>
                    <?php include __DIR__ . '/../includes/partials/orders/view/notes.php'; ?>
                </div>

                <div class="col-lg-8">
                    <?php include __DIR__ . '/includes/partials/orders/schedule-timeline.php'; ?>
                    <?php include __DIR__ . '/../includes/partials/orders/view/job-sections.php'; ?>
                    <?php include __DIR__ . '/../includes/partials/orders/view/attachment.php'; ?>
                </div>
            </div>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>
