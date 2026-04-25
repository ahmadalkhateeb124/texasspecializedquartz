<?php

/**
 * admin/index.php — Admin Dashboard
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin('../auth-login-minimal.php');

$currentUser = currentUser();
$pageTitle   = 'Dashboard';
$breadcrumb  = [['label' => 'Dashboard']];

$repo           = new AdminDashboardRepository($pdo);
$totalOrders    = $repo->totalOrders();
$totalProducts  = $repo->totalProducts();
$totalCustomers = $repo->activeCustomers();
$monthlyOrders  = $repo->monthlyOrders();
$recentRows     = $repo->ordersLast7Days();
$avail          = $repo->productAvailabilityBreakdown();
$recentOrders   = $repo->recentOrders();

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
                        Welcome back, <?= htmlspecialchars($currentUser['fullname'] ?? $currentUser['name'] ?? 'Admin') ?> 👋
                    </h1>
                    <p class="page-desc">Here's what's happening with your business today.</p>
                </div>
                <a href="orders" class="btn btn-primary d-none d-sm-inline-flex">
                    <i class='bx bx-plus'></i> New Order
                </a>
            </div>

            <?php include __DIR__ . '/includes/partials/dashboard/stat-cards.php'; ?>
            <?php include __DIR__ . '/includes/partials/dashboard/charts.php'; ?>
            <?php include __DIR__ . '/includes/partials/dashboard/recent-orders.php'; ?>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <div id="ordersChartData" data-raw='<?= htmlspecialchars(json_encode($recentRows), ENT_QUOTES) ?>' style="display:none;"></div>
        <div id="availChartData"  data-raw='<?= htmlspecialchars(json_encode($avail['rows']), ENT_QUOTES) ?>' style="display:none;"></div>

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="../assets/js/admin/dashboard.js"></script>
