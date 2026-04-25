<?php

/**
 * admin/customers.php — Customers Index
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Customers';
$breadcrumb  = [['label' => 'Customers']];

$dbError = null;
try {
    $repo      = new CustomerRepository($pdo);
    $customers = $repo->allWithAccountStatus();
    $counts    = $repo->statusCounts($customers);
} catch (PDOException $e) {
    $customers = [];
    $counts    = ['active' => 0, 'inactive' => 0, 'blacklisted' => 0];
    $dbError   = htmlspecialchars($e->getMessage());
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
                    <h1 class="page-title">Customers</h1>
                    <p class="page-subtitle">
                        <?= count($customers) ?> total customer<?= count($customers) !== 1 ? 's' : '' ?>
                    </p>
                </div>
                <div class="page-actions">
                    <a href="customers-new" class="btn btn-primary">
                        <i class='bx bx-user-plus'></i> Add Customer
                    </a>
                </div>
            </div>

            <?php if ($dbError): ?>
                <div class="alert alert-danger mb-3">
                    <i class='bx bx-error me-2'></i><?= $dbError ?>
                </div>
            <?php endif; ?>

            <?php include __DIR__ . '/includes/partials/customers/stat-bar.php'; ?>
            <?php include __DIR__ . '/includes/partials/customers/table.php'; ?>

        </main>

        <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
        <script src="../assets/js/admin/customers.js"></script>

        <?php include __DIR__ . '/includes/footer.php'; ?>
