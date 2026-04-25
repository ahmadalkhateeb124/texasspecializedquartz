<?php

/**
 * admin/customers-view.php — View Customer
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: customers'); exit; }

try {
    $stmt = $pdo->prepare("SELECT * FROM customers_companies WHERE id = ?");
    $stmt->execute([$id]);
    $company = $stmt->fetch();
    if (!$company) { header('Location: customers'); exit; }

    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE company_id = ?");
    $stmt->execute([$id]);
    $account = $stmt->fetch() ?: [];

    $stmt = $pdo->prepare("
        SELECT fo.id, fo.customer_name, fo.created_at, fo.city, fo.sales_rep
        FROM fabrication_orders fo
        LEFT JOIN accounts a ON fo.account_id = a.id
        WHERE a.company_id = ? OR fo.customer_name = ?
        ORDER BY fo.created_at DESC LIMIT 10
    ");
    $stmt->execute([$id, $company['company_name']]);
    $recentOrders = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}

$currentUser = currentUser();
$pageTitle   = htmlspecialchars($company['company_name']);
$breadcrumb  = [
    ['label' => 'Customers', 'url' => 'customers.php'],
    ['label' => htmlspecialchars($company['company_name'])],
];
$status = $account['status'] ?? 'Inactive';

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title"><?= htmlspecialchars($company['company_name']) ?></h1>
                    <p class="page-subtitle">Customer profile &middot; ID #<?= $company['id'] ?></p>
                </div>
                <div class="page-actions">
                    <a href="customers" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back
                    </a>
                    <a href="customers-edit?id=<?= $id ?>" class="btn btn-primary">
                        <i class='bx bx-edit'></i> Edit Customer
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <?php include __DIR__ . '/includes/partials/customers/profile-card.php'; ?>

                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-map text-primary'></i> Address</h6>
                        </div>
                        <div class="card-section">
                            <?php
                            $addrParts = array_filter([
                                $company['address']  ?? '',
                                $company['city']     ?? '',
                                $company['state']    ?? '',
                                $company['zip_code'] ?? '',
                            ]);
                            echo htmlspecialchars(implode(', ', $addrParts) ?: 'No address on file');
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <?php include __DIR__ . '/includes/partials/customers/recent-orders.php'; ?>
                </div>
            </div>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>
