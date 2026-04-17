<?php

/**
 * customer/index.php — Customer Dashboard
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

requireCustomer('../auth-login-minimal.php');

$currentUser = currentUser();
$accountId   = $currentUser['id'];
$pageTitle   = 'My Dashboard';
$breadcrumb  = [['label' => 'Dashboard']];

/* ── Load company info ──────────────────────────────────────── */
$customerMeta = [];
try {
    $stmt = $pdo->prepare("
        SELECT a.name, a.email,
               c.company_name, c.phone, c.address, a.created_at
        FROM accounts a
        JOIN customers_companies c ON c.id = a.company_id
        WHERE a.id = ?
    ");
    $stmt->execute([$accountId]);
    $customerMeta = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
}

/* ── Order stats ────────────────────────────────────────────── */
function custCount(PDO $pdo, string $sql, array $p = []): int
{
    try {
        $s = $pdo->prepare($sql);
        $s->execute($p);
        return (int)($s->fetchColumn() ?: 0);
    } catch (PDOException $e) {
        return 0;
    }
}

$totalOrders   = custCount($pdo, "SELECT COUNT(*) FROM fabrication_orders WHERE account_id=?", [$accountId]);
$monthlyOrders = custCount($pdo, "
    SELECT COUNT(*) FROM fabrication_orders
    WHERE account_id=? AND MONTH(created_at)=MONTH(CURDATE()) AND YEAR(created_at)=YEAR(CURDATE())
", [$accountId]);
$weeklyOrders  = custCount($pdo, "
    SELECT COUNT(*) FROM fabrication_orders
    WHERE account_id=? AND created_at >= DATE_SUB(CURDATE(),INTERVAL 7 DAY)
", [$accountId]);

/* ── Chart data: last 30 days ───────────────────────────────── */
try {
    $stmt = $pdo->prepare("
        SELECT DATE(created_at) AS d, COUNT(*) AS n
        FROM fabrication_orders
        WHERE account_id=? AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY DATE(created_at)
        ORDER BY d ASC
    ");
    $stmt->execute([$accountId]);
    $chartData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $chartData = [];
}

/* ── Recent orders ──────────────────────────────────────────── */
try {
    $stmt = $pdo->prepare("
        SELECT f.id, f.created_at, f.updated_at,
               COUNT(j.id) AS jobs
        FROM fabrication_orders f
        LEFT JOIN job_sections j ON j.order_id = f.id
        WHERE f.account_id = ?
        GROUP BY f.id
        ORDER BY f.created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$accountId]);
    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recentOrders = [];
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
                    <h1 class="page-title">
                        Welcome, <?= htmlspecialchars($_company) ?> 👋
                    </h1>
                    <p class="page-desc">
                        <?= !empty($customerMeta['company_name'])
                            ? htmlspecialchars($customerMeta['company_name'])
                            : 'Your order portal' ?>
                        &nbsp;· Customer Portal
                    </p>
                </div>
                <a href="order-new.php" class="btn btn-primary d-none d-sm-inline-flex">
                    <i class='bx bx-plus'></i> New Order
                </a>
            </div>

            <!-- ── Stat cards ──────────────────────────────────────────── -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-xl-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="stat-label">Total Orders</div>
                                    <div class="stat-value"><?= number_format($totalOrders) ?></div>
                                </div>
                                <div class="stat-icon" style="background:var(--primary-light);">
                                    <i class='bx bx-file' style="color:var(--primary);"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                All orders submitted
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="stat-label">This Month</div>
                                    <div class="stat-value"><?= number_format($monthlyOrders) ?></div>
                                </div>
                                <div class="stat-icon" style="background:var(--success-light);">
                                    <i class='bx bx-calendar-check' style="color:var(--success);"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <?= date('F Y') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="stat-label">Last 7 Days</div>
                                    <div class="stat-value"><?= number_format($weeklyOrders) ?></div>
                                </div>
                                <div class="stat-icon" style="background:var(--warning-light);">
                                    <i class='bx bx-trending-up' style="color:var(--warning);"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                Recent activity
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="stat-label">Account Since</div>
                                    <div class="stat-value" style="font-size:17px;">
                                        <?= !empty($customerMeta['created_at'])
                                            ? date('M Y', strtotime($customerMeta['created_at']))
                                            : '—' ?>
                                    </div>
                                </div>
                                <div class="stat-icon" style="background:var(--info-light);">
                                    <i class='bx bx-buildings' style="color:var(--info);"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <?= !empty($customerMeta['company_name'])
                                    ? htmlspecialchars($customerMeta['company_name'])
                                    : 'Customer account' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Main content grid ────────────────────────────────────── -->
            <div class="row g-3">

                <!-- Activity chart -->
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-line-chart text-primary'></i> Order Activity (30 Days)</h6>
                            <a href="orders.php" class="btn btn-sm btn-light">View All</a>
                        </div>
                        <div class="card-body">
                            <?php if ($totalOrders === 0): ?>
                                <div class="empty-state">
                                    <i class='bx bx-bar-chart-alt-2 empty-state-icon'></i>
                                    <p class="empty-state-title">No orders yet</p>
                                    <p class="empty-state-desc">Submit your first fabrication order to get started.</p>
                                    <a href="order-new.php" class="btn btn-sm btn-primary">
                                        <i class='bx bx-plus'></i> Create Order
                                    </a>
                                </div>
                            <?php else: ?>
                                <div id="activityChart" style="min-height:220px;"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Contact / quick actions -->
                <div class="col-xl-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-lightning text-primary'></i> Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="order-new.php" class="btn btn-primary">
                                    <i class='bx bx-plus'></i> Submit New Order
                                </a>
                                <a href="orders.php" class="btn btn-light">
                                    <i class='bx bx-list-ul'></i> View My Orders
                                </a>
                                <a href="profile.php" class="btn btn-light">
                                    <i class='bx bx-user'></i> My Profile
                                </a>
                                <a href="mailto:Cs@TexasSpecializedQuartz.com" class="btn btn-light">
                                    <i class='bx bx-envelope'></i> Contact Support
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Company info card -->
                    <?php if (!empty($customerMeta)): ?>
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title"><i class='bx bx-buildings text-primary'></i> Company Info</h6>
                                <a href="profile.php" style="font-size:12px;">Edit</a>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column gap-2" style="font-size:13px;">
                                    <div>
                                        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Company</div>
                                        <div><?= htmlspecialchars($customerMeta['company_name'] ?? '—') ?></div>
                                    </div>
                                    <div>
                                        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Email</div>
                                        <div><?= htmlspecialchars($customerMeta['email'] ?? '—') ?></div>
                                    </div>
                                    <?php if (!empty($customerMeta['phone'])): ?>
                                        <div>
                                            <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Phone</div>
                                            <div><?= htmlspecialchars($customerMeta['phone']) ?></div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($customerMeta['address'])): ?>
                                        <div>
                                            <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Address</div>
                                            <div><?= htmlspecialchars($customerMeta['address']) ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Recent orders table -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-file text-primary'></i> Recent Orders</h6>
                            <a href="orders.php" class="btn btn-sm btn-light">All Orders</a>
                        </div>
                        <div class="table-responsive">
                            <?php if (empty($recentOrders)): ?>
                                <div class="empty-state py-4">
                                    <i class='bx bx-file-blank empty-state-icon' style="font-size:36px;"></i>
                                    <p class="empty-state-desc">No orders submitted yet.</p>
                                    <a href="order-new.php" class="btn btn-sm btn-primary"><i class='bx bx-plus'></i> New Order</a>
                                </div>
                            <?php else: ?>
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Job Sections</th>
                                            <th>Date Submitted</th>
                                            <th>Last Updated</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentOrders as $o): ?>
                                            <tr>
                                                <td>
                                                    <span style="font-weight:700;">
                                                        #<?= str_pad($o['id'], 4, '0', STR_PAD_LEFT) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary">
                                                        <?= $o['jobs'] ?> section<?= $o['jobs'] != 1 ? 's' : '' ?>
                                                    </span>
                                                </td>
                                                <td style="color:var(--muted);">
                                                    <?= date('M j, Y', strtotime($o['created_at'])) ?>
                                                </td>
                                                <td style="color:var(--muted);">
                                                    <?= date('M j, Y', strtotime($o['updated_at'])) ?>
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
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div><!-- /row -->
        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <?php if ($totalOrders > 0): ?>
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script>
                (function() {
                    const raw = <?= json_encode($chartData) ?>;

                    // Build last 30 days array
                    const days = [],
                        counts = [];
                    for (let i = 29; i >= 0; i--) {
                        const d = new Date();
                        d.setDate(d.getDate() - i);
                        const key = d.toISOString().split('T')[0];
                        days.push(d.toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric'
                        }));
                        const f = raw.find(r => r.d === key);
                        counts.push(f ? parseInt(f.n) : 0);
                    }

                    new ApexCharts(document.querySelector('#activityChart'), {
                        series: [{
                            name: 'Orders',
                            data: counts
                        }],
                        chart: {
                            type: 'area',
                            height: 220,
                            toolbar: {
                                show: false
                            },
                            fontFamily: 'Inter,sans-serif',
                            sparkline: {
                                enabled: false
                            }
                        },
                        colors: ['#0f766e'],
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: .3,
                                opacityTo: .02,
                                stops: [0, 100]
                            }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2.5
                        },
                        dataLabels: {
                            enabled: false
                        },
                        xaxis: {
                            categories: days,
                            tickAmount: 6,
                            labels: {
                                style: {
                                    colors: '#64748b',
                                    fontSize: '11px'
                                }
                            },
                            axisBorder: {
                                show: false
                            },
                            axisTicks: {
                                show: false
                            }
                        },
                        yaxis: {
                            min: 0,
                            tickAmount: 3,
                            labels: {
                                style: {
                                    colors: '#64748b',
                                    fontSize: '11px'
                                }
                            }
                        },
                        grid: {
                            borderColor: '#e2e8f0',
                            strokeDashArray: 4,
                            padding: {
                                left: 0,
                                right: 0
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: v => v + ' order' + (v !== 1 ? 's' : '')
                            }
                        }
                    }).render();
                })();
            </script>
        <?php endif; ?>