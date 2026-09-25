<?php

/**
 * employee/index.php — Employee Dashboard: list of assigned job sections.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireEmployee('../auth-login-minimal.php');

$currentUser = currentUser();
$employeeId  = $currentUser['id'];
$pageTitle   = 'My Dashboard';
$breadcrumb  = [['label' => 'Dashboard']];

$sections = (new EmployeeRepository($pdo))->sectionsForEmployee($employeeId);

/* Group flat job-section rows by their parent order — one card per order. */
$orders = [];
foreach ($sections as $s) {
    $oid = (int)$s['order_id'];
    if (!isset($orders[$oid])) {
        $orders[$oid] = [
            'order_id'      => $oid,
            'admin_status'  => $s['admin_status'] ?? 'new',
            'customer_name' => $s['customer_name'] ?? '',
            'city'          => $s['city'] ?? '',
            'address'       => $s['address'] ?? '',
            'jobs'          => [],
        ];
    }
    $orders[$oid]['jobs'][] = $s;
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
                    <h1 class="page-title">Welcome, <?= htmlspecialchars($currentUser['name']) ?></h1>
                    <p class="page-desc">
                        <?= count($orders) ?> assigned order<?= count($orders) !== 1 ? 's' : '' ?>
                        (<?= count($sections) ?> job section<?= count($sections) !== 1 ? 's' : '' ?>)
                        &nbsp;· Employee Portal
                    </p>
                </div>
                <a href="calendar" class="btn btn-primary">
                    <i class='bx bx-calendar'></i> My Calendar
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title"><i class='bx bx-file text-primary'></i> My Orders</h6>
                </div>

                <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class='bx bx-layer'></i></div>
                        <p class="empty-state-title">No orders assigned yet</p>
                        <p class="empty-state-desc">Your admin will assign you to an order as work comes in.</p>
                    </div>
                <?php else: ?>
                    <div class="ord-grid">
                        <?php foreach ($orders as $o): ?>
                            <a href="order-view?id=<?= $o['order_id'] ?>" class="ord-card">
                                <div class="ord-card-head">
                                    <div class="ord-card-id">
                                        <span class="ord-num">Order #<?= $o['order_id'] ?></span>
                                        <span class="ord-pill"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $o['admin_status']))) ?></span>
                                    </div>
                                    <div class="ord-card-actions">
                                        <span class="ord-iconbtn"><i class='bx bx-chevron-right'></i></span>
                                    </div>
                                </div>
                                <div class="ord-card-meta">
                                    <span class="ord-meta-item"><i class='bx bx-user'></i> <?= htmlspecialchars($o['customer_name'] ?: '—') ?></span>
                                    <span class="ord-meta-sep">·</span>
                                    <span class="ord-meta-item"><i class='bx bx-map'></i> <?= htmlspecialchars($o['city'] ?: $o['address'] ?: '—') ?></span>
                                </div>
                                <ul class="ord-jobs">
                                    <?php foreach ($o['jobs'] as $s):
                                        $jt  = $s['job_type'];
                                        $jobLabel = ($jt === 'other' && !empty($s['job_type_other']))
                                            ? $jt . ' – ' . $s['job_type_other']
                                            : ucfirst($jt ?? '—');
                                    ?>
                                        <li class="ord-job">
                                            <div class="ord-job-num"><i class='bx bx-layer'></i></div>
                                            <div class="ord-job-body">
                                                <div class="ord-job-title">
                                                    <strong><?= htmlspecialchars($jobLabel) ?></strong>
                                                </div>
                                                <div class="ord-job-specs">
                                                    <?php if (!empty($s['material_type'])): ?>
                                                        <span><i class='bx bx-cube'></i> <?= htmlspecialchars(ucfirst($s['material_type'])) ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($s['material_color'])): ?>
                                                        <span><i class='bx bx-palette'></i> <?= htmlspecialchars($s['material_color']) ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($s['thickness'])): ?>
                                                        <span><i class='bx bx-ruler'></i> <?= htmlspecialchars($s['thickness']) ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($s['edge_profile'])): ?>
                                                        <span><i class='bx bx-shape-polygon'></i> <?= htmlspecialchars(ucfirst($s['edge_profile'])) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>
