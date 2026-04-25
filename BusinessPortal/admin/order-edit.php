<?php

/**
 * admin/order-edit.php — Edit Fabrication Order
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: orders');
    exit;
}

try {
    $order = (new OrderRepository($pdo))->findWithJobs($id);
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}

if (!$order) {
    header('Location: orders');
    exit;
}

$jobs             = $order['jobs'];
$existingJobTypes = [];
foreach ($jobs as $job) {
    $existingJobTypes[$job['job_type']] = ($existingJobTypes[$job['job_type']] ?? 0) + 1;
}

$currentUser = currentUser();
$pageTitle   = 'Edit Order';
$breadcrumb  = [
    ['label' => 'Orders', 'url' => 'orders.php'],
    ['label' => 'Edit Order #' . str_pad($id, 4, '0', STR_PAD_LEFT)],
];
$extraCss    = '<link rel="stylesheet" href="../assets/css/shared/order-form.css">';

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Edit Fabrication Order</h1>
                    <p class="page-subtitle">
                        Order #<?= str_pad($id, 4, '0', STR_PAD_LEFT) ?> &mdash;
                        <?= htmlspecialchars($order['customer_name']) ?>
                    </p>
                </div>
                <div class="page-actions">
                    <a href="orders" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Orders
                    </a>
                </div>
            </div>

            <form method="POST" id="orderForm" action="../auth/UpdateFabricationOrders.php"
                enctype="multipart/form-data" novalidate>
                <input type="hidden" name="order_id" value="<?= $id ?>">

                <div class="row g-4">
                    <div class="col-lg-8">
                        <?php include __DIR__ . '/../includes/partials/orders/form/customer.php'; ?>
                        <?php include __DIR__ . '/../includes/partials/orders/form/sales-rep.php'; ?>
                        <?php include __DIR__ . '/../includes/partials/orders/form/job-areas.php'; ?>

                        <div id="jobSectionsContainer">
                            <?php foreach ($jobs as $job): ?>
                                <?php include __DIR__ . '/../includes/partials/orders/form/job-section-prefilled.php'; ?>
                            <?php endforeach; ?>
                        </div>

                        <?php include __DIR__ . '/../includes/partials/orders/form/notes-attachment.php'; ?>
                    </div>

                    <div class="col-lg-4">
                        <div class="card" style="position:sticky;top:calc(var(--header-h) + 16px);">
                            <div class="card-header">
                                <h6 class="card-title">
                                    <i class='bx bx-check-shield text-primary'></i> Update Order
                                </h6>
                            </div>
                            <div class="card-section">
                                <p style="font-size:13px;color:var(--color-text-sub);margin-bottom:16px;">
                                    Review all sections before updating. Required fields are marked
                                    <span class="text-danger">*</span>.
                                </p>
                                <div id="summaryChips" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;"></div>
                                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                    <span id="submitSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                                    <i class='bx bx-save' id="submitIcon"></i>
                                    Update Order
                                </button>
                                <a href="orders" class="btn btn-default w-100 mt-2">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <?php /* Template used when a new job type is toggled on during edit. */ ?>
            <?php include __DIR__ . '/../includes/partials/orders/form/job-section-template.php'; ?>

        </main>

        <script src="../assets/js/shared/order-edit.js"></script>

        <?php include __DIR__ . '/includes/footer.php'; ?>
