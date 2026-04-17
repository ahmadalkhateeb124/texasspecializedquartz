<?php

/**
 * customer/orders.php — Customer: My Orders
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

requireCustomer('../auth-login-minimal.php');

$currentUser = currentUser();
$accountId   = $currentUser['id'];
$pageTitle   = 'My Orders';
$breadcrumb  = [['label' => 'My Orders']];

/* ── Load company meta for sidebar ─────────────────────────── */
$customerMeta = [];
try {
    $s = $pdo->prepare("
        SELECT c.company_name
        FROM accounts a
        JOIN customers_companies c ON c.id = a.company_id
        WHERE a.id = ?
    ");
    $s->execute([$accountId]);
    $customerMeta = $s->fetch(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
}

/* ── Fetch this customer's orders ───────────────────────────── */
try {
    $stmt = $pdo->prepare("
        SELECT
            f.id          AS order_id,
            f.created_at,
            f.updated_at,
            j.id          AS job_id,
            j.job_type,
            j.job_type_other,
            j.material_type,
            j.material_color,
            j.thickness,
            j.edge_profile,
            j.sink_provider,
            j.sink_type,
            j.tear_out
        FROM fabrication_orders f
        LEFT JOIN job_sections j ON j.order_id = f.id
        WHERE f.account_id = ?
        ORDER BY f.created_at DESC, j.id ASC
    ");
    $stmt->execute([$accountId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $rows    = [];
    $dbError = htmlspecialchars($e->getMessage());
}

/* Group by order */
$orders = [];
foreach ($rows as $r) {
    $oid = $r['order_id'];
    if (!isset($orders[$oid])) {
        $orders[$oid] = [
            'id'         => $oid,
            'created_at' => $r['created_at'],
            'updated_at' => $r['updated_at'],
            'jobs'       => [],
        ];
    }
    if ($r['job_id']) $orders[$oid]['jobs'][] = $r;
}

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <!-- Page header -->
            <div class="page-header d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="page-title">My Fabrication Orders</h1>
                    <p class="page-desc">View all orders you have submitted.</p>
                </div>
                <a href="order-new.php" class="btn btn-primary">
                    <i class='bx bx-plus'></i> New Order
                </a>
            </div>

            <?php if (isset($dbError)): ?>
                <div class="alert alert-danger mb-3"><i class='bx bx-error me-2'></i><?= $dbError ?></div>
            <?php endif; ?>

            <?php if (empty($orders)): ?>
                <!-- ── Empty state ──────────────────────────────────────── -->
                <div class="card">
                    <div class="card-body">
                        <div class="empty-state" style="padding:80px 20px;">
                            <i class='bx bx-file-blank empty-state-icon'></i>
                            <p class="empty-state-title">No orders yet</p>
                            <p class="empty-state-desc">
                                You haven't submitted any fabrication orders.<br>
                                Get started by creating your first order.
                            </p>
                            <a href="order-new.php" class="btn btn-primary">
                                <i class='bx bx-plus'></i> Submit First Order
                            </a>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- ── Summary strip ────────────────────────────────────── -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="card text-center" style="padding:12px 10px;">
                            <div style="font-size:20px;font-weight:800;color:var(--text);"><?= count($orders) ?></div>
                            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Total Orders</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card text-center" style="padding:12px 10px;">
                            <?php $mOrds = array_filter($orders, fn($o) => date('Y-m', strtotime($o['created_at'])) === date('Y-m')); ?>
                            <div style="font-size:20px;font-weight:800;color:var(--primary);"><?= count($mOrds) ?></div>
                            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">This Month</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card text-center" style="padding:12px 10px;">
                            <?php $totalSections = array_sum(array_map(fn($o) => count($o['jobs']), $orders)); ?>
                            <div style="font-size:20px;font-weight:800;color:var(--success);"><?= $totalSections ?></div>
                            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Job Sections</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card text-center" style="padding:12px 10px;">
                            <?php $wk = array_filter($orders, fn($o) => strtotime($o['created_at']) >= strtotime('-7 days')); ?>
                            <div style="font-size:20px;font-weight:800;color:var(--warning);"><?= count($wk) ?></div>
                            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Last 7 Days</div>
                        </div>
                    </div>
                </div>

                <!-- ── Orders table ─────────────────────────────────────── -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title"><i class='bx bx-file text-primary'></i> Order History</h6>
                        <input type="text" id="searchInput" class="form-control form-control-sm"
                            placeholder="Search…" style="width:200px;">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="myOrdersTable">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Job Type</th>
                                    <th>Material</th>
                                    <th>Color</th>
                                    <th>Thickness</th>
                                    <th>Edge Profile</th>
                                    <th>Sink</th>
                                    <th>Tear Out</th>
                                    <th>Date</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $o):
                                    $jobs = $o['jobs'];
                                ?>
                                    <tr>
                                        <td>
                                            <span style="font-weight:700;">#<?= str_pad($o['id'], 4, '0', STR_PAD_LEFT) ?></span>
                                            <?php if (count($jobs) > 0): ?>
                                                <span class="badge badge-primary ms-1"><?= count($jobs) ?> section<?= count($jobs) != 1 ? 's' : '' ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php foreach ($jobs as $j) {
                                                $t = htmlspecialchars($j['job_type']);
                                                if (!empty($j['job_type_other'])) $t .= ' / ' . htmlspecialchars($j['job_type_other']);
                                                echo $t . '<br>';
                                            } ?></td>
                                        <td><?php foreach ($jobs as $j) echo htmlspecialchars($j['material_type']) . '<br>'; ?></td>
                                        <td><?php foreach ($jobs as $j) echo htmlspecialchars($j['material_color']) . '<br>'; ?></td>
                                        <td><?php foreach ($jobs as $j) {
                                                $thickness_display = $j['thickness'];
                                                if ($thickness_display === '2cm') {
                                                    $thickness_display = '2 Cm';
                                                } elseif ($thickness_display === '3cm') {
                                                    $thickness_display = '3 Cm';
                                                } elseif ($thickness_display === 'custom' && !empty($j['thickness_custom'])) {
                                                    $thickness_display = $j['thickness_custom'] . ' Cm';
                                                }
                                                echo htmlspecialchars($thickness_display) . '<br>';
                                            } ?></td>
                                        <td><?php foreach ($jobs as $j) echo htmlspecialchars($j['edge_profile']) . '<br>'; ?></td>
                                        <td><?php foreach ($jobs as $j) {
                                                $s = trim($j['sink_provider'] . ' / ' . $j['sink_type'], ' /');
                                                echo htmlspecialchars($s ?: '-') . '<br>';
                                            } ?></td>
                                        <td><?php foreach ($jobs as $j): ?>
                                                <span class="badge <?= $j['tear_out'] === 'Yes' ? 'badge-warning' : 'badge-muted' ?>">
                                                    <?= htmlspecialchars($j['tear_out'] ?: '—') ?>
                                                </span><br>
                                            <?php endforeach; ?>
                                        </td>
                                        <td style="white-space:nowrap;color:var(--muted);">
                                            <?= date('M j, Y', strtotime($o['created_at'])) ?>
                                        </td>
                                        <td class="text-end">
                                            <a href="order-view.php?id=<?= $o['id'] ?>"
                                                class="btn btn-xs btn-light">
                                                <i class='bx bx-show'></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script>
            document.getElementById('searchInput')?.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('#myOrdersTable tbody tr').forEach(tr => {
                    tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });
        </script>