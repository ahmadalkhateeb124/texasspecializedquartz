<?php

/**
 * customer/index.php — Customer Dashboard
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireCustomer('../auth-login-minimal.php');

$currentUser = currentUser();
$accountId   = $currentUser['id'];
$pageTitle   = 'My Dashboard';
$breadcrumb  = [['label' => 'Dashboard']];

$repo          = new CustomerDashboardRepository($pdo);
$customerMeta  = $repo->accountMeta($accountId);
$totalOrders   = $repo->totalOrders($accountId);
$monthlyOrders = $repo->monthlyOrders($accountId);
$weeklyOrders  = $repo->weeklyOrders($accountId);
$chartData     = $repo->dailyCountsLast30($accountId);
$recentOrders  = $repo->recentOrders($accountId);

$companyName   = $customerMeta['company_name'] ?? 'Customer';

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="page-title">
                        Welcome, <?= htmlspecialchars($companyName) ?> 👋
                    </h1>
                    <p class="page-desc">
                        <?= htmlspecialchars($customerMeta['company_name'] ?? 'Your order portal') ?>
                        &nbsp;· Customer Portal
                    </p>
                </div>
                <a href="order-new" class="btn btn-primary d-none d-sm-inline-flex">
                    <i class='bx bx-plus'></i> New Order
                </a>
            </div>

            <?php include __DIR__ . '/includes/partials/dashboard/stat-cards.php'; ?>

            <div class="row g-3">
                <div class="col-xl-8">
                    <?php include __DIR__ . '/includes/partials/dashboard/activity-chart.php'; ?>
                </div>
                <div class="col-xl-4">
                    <?php include __DIR__ . '/includes/partials/dashboard/quick-actions.php'; ?>
                    <?php include __DIR__ . '/includes/partials/dashboard/company-info.php'; ?>
                </div>
                <div class="col-12">
                    <?php include __DIR__ . '/includes/partials/dashboard/recent-orders.php'; ?>
                </div>
            </div>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <?php if ($totalOrders > 0): ?>
            <div id="chartData" data-raw='<?= htmlspecialchars(json_encode($chartData), ENT_QUOTES) ?>' style="display:none;"></div>
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script src="../assets/js/customer/dashboard.js"></script>
        <?php endif; ?>
