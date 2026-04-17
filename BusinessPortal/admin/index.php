<?php

/**
 * admin/index.php — Admin Dashboard
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

requireAdmin('../auth-login-minimal.php');

$currentUser = currentUser();
$pageTitle   = 'Dashboard';
$breadcrumb  = [['label' => 'Dashboard']];

/* ── Stats ─────────────────────────────────────────────────────── */
function dbCount(PDO $pdo, string $sql, array $params = []): int
{
    try {
        $s = $pdo->prepare($sql);
        $s->execute($params);
        return (int) ($s->fetchColumn() ?: 0);
    } catch (PDOException $e) {
        return 0;
    }
}

$totalOrders    = dbCount($pdo, "SELECT COUNT(*) FROM fabrication_orders");
$totalProducts  = dbCount($pdo, "SELECT COUNT(*) FROM products");
$totalCustomers = dbCount($pdo, "SELECT COUNT(*) FROM accounts WHERE status = 'Active'");
$monthlyOrders  = dbCount($pdo, "
    SELECT COUNT(*) FROM fabrication_orders
    WHERE MONTH(created_at)=MONTH(CURRENT_DATE())
      AND YEAR(created_at)=YEAR(CURRENT_DATE())
");

/* ── Chart data ─────────────────────────────────────────────────── */
try {
    $stmt = $pdo->query("
        SELECT DATE(created_at) AS d, COUNT(*) AS n
        FROM fabrication_orders
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at)
        ORDER BY d ASC
    ");
    $recentRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recentRows = [];
}

try {
    $stmt = $pdo->query("
        SELECT availability, COUNT(*) AS n
        FROM products
        GROUP BY availability
    ");
    $availRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $availRows = [];
}

/* ── Recent orders table ────────────────────────────────────────── */
try {
    $stmt = $pdo->query("
        SELECT f.id, f.created_at,
               c.company_name,
               COUNT(j.id) AS jobs
        FROM fabrication_orders f
        LEFT JOIN accounts a ON a.id = f.account_id
        LEFT JOIN customers_companies c ON c.id = a.company_id
        LEFT JOIN job_sections j ON j.order_id = f.id
        GROUP BY f.id
        ORDER BY f.created_at DESC
        LIMIT 8
    ");
    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recentOrders = [];
}

/* ── Availability breakdown ─────────────────────────────────────── */
$available = $reserved = $soldOut = 0;
foreach ($availRows as $r) {
    if ($r['availability'] === 'Available in store') $available = (int)$r['n'];
    elseif ($r['availability'] === 'Reserved')       $reserved  = (int)$r['n'];
    elseif ($r['availability'] === 'Sold Out')       $soldOut   = (int)$r['n'];
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
                    <h1 class="page-title">Welcome back, <?= htmlspecialchars($_cu['fullname'] ?? 'Admin') ?> 👋</h1>
                    <p class="page-desc">Here's what's happening with your business today.</p>
                </div>
                <a href="orders.php" class="btn btn-primary d-none d-sm-inline-flex">
                    <i class='bx bx-plus'></i> New Order
                </a>
            </div>

            <!-- ── Stat cards ──────────────────────────────────────────── -->
            <div class="row g-3 mb-4">

                <!-- Total Orders -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="stat-label">Total Orders</div>
                                    <div class="stat-value"><?= number_format($totalOrders) ?></div>
                                </div>
                                <div class="stat-icon" style="background:var(--color-success-l);">
                                    <i class='bx bx-file' style="color:var(--primary);"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <span class="stat-up"><i class='bx bx-trending-up'></i> <?= number_format($monthlyOrders) ?></span>
                                <span class="ms-1">orders this month</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="stat-label">Total Products</div>
                                    <div class="stat-value"><?= number_format($totalProducts) ?></div>
                                </div>
                                <div class="stat-icon" style="background:var(--color-primary-l);">
                                    <i class='bx bx-cube-alt' style="color:var(--color-primary-h);"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <span style="color:var(--success);font-weight:600;"><?= $available ?></span> available ·
                                <span style="color:var(--warning);font-weight:600;"><?= $reserved ?></span> reserved ·
                                <span style="color:var(--danger);font-weight:600;"><?= $soldOut ?></span> sold
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Customers -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="stat-label">Active Customers</div>
                                    <div class="stat-value"><?= number_format($totalCustomers) ?></div>
                                </div>
                                <div class="stat-icon" style="background:var(--color-success-l);">
                                    <i class='bx bx-group' style="color:var(--info);"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <a href="customers.php" style="font-size:12px;">View all customers →</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Orders -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="stat-label">This Month</div>
                                    <div class="stat-value"><?= number_format($monthlyOrders) ?></div>
                                </div>
                                <div class="stat-icon" style="background:var(--color-warning-l);">
                                    <i class='bx bx-calendar' style="color:var(--warning);"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <?php
                                $pct = $totalOrders > 0 ? round(($monthlyOrders / $totalOrders) * 100, 1) : 0;
                                ?>
                                <span style="font-weight:600;"><?= $pct ?>%</span>
                                <span class="ms-1">of all-time orders</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Charts ──────────────────────────────────────────────── -->
            <div class="row g-3 mb-4">

                <!-- Weekly orders chart -->
                <div class="col-xl-8">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-bar-chart-alt-2 text-primary'></i> Orders — Last 7 Days</h6>
                        </div>
                        <div class="card-body">
                            <div id="chartOrders" style="min-height:260px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Product availability donut -->
                <div class="col-xl-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-pie-chart-alt text-primary'></i> Product Status</h6>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <?php if ($totalProducts > 0): ?>
                                <div id="chartAvail" style="min-height:260px; width:100%;"></div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class='bx bx-cube-alt empty-state-icon'></i>
                                    <p class="empty-state-desc">No products yet</p>
                                    <a href="../NewProducts.php" class="btn btn-sm btn-primary">Add Product</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Recent Orders table ─────────────────────────────────── -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title"><i class='bx bx-list-ul text-primary'></i> Recent Orders</h6>
                    <a href="orders.php" class="btn btn-sm btn-light">View all</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Company</th>
                                <th>Job Sections</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentOrders)): ?>
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state py-4">
                                            <i class='bx bx-file-blank empty-state-icon' style="font-size:36px;"></i>
                                            <p class="empty-state-desc">No orders found. Create your first order!</p>
                                            <a href="orders.php" class="btn btn-sm btn-primary">
                                                <i class='bx bx-plus'></i> New Order
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentOrders as $o): ?>
                                    <tr>
                                        <td>
                                            <span class="fw-600" style="font-size:13px;font-weight:600;">
                                                #<?= str_pad($o['id'], 4, '0', STR_PAD_LEFT) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($o['company_name'] ?? '—') ?></td>
                                        <td>
                                            <span class="badge badge-primary"><?= $o['jobs'] ?> section<?= $o['jobs'] != 1 ? 's' : '' ?></span>
                                        </td>
                                        <td style="color:var(--muted);">
                                            <?= date('M j, Y', strtotime($o['created_at'])) ?>
                                        </td>
                                        <td class="text-end">
                                            <a href="order-view.php?id=<?= $o['id'] ?>"
                                                class="btn btn-xs btn-light" title="View">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <a href="order-edit.php?id=<?= $o['id'] ?>"
                                                class="btn btn-xs btn-light ms-1" title="Edit">
                                                <i class='bx bx-edit'></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <!-- ApexCharts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            (function() {
                /* ── Weekly orders bar chart ── */
                const raw = <?= json_encode($recentRows) ?>;
                const last7 = [];
                for (let i = 6; i >= 0; i--) {
                    const d = new Date();
                    d.setDate(d.getDate() - i);
                    last7.push(d.toISOString().split('T')[0]);
                }
                const labels = last7.map(d => new Date(d).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric'
                }));
                const counts = last7.map(d => {
                    const f = raw.find(r => r.d === d);
                    return f ? parseInt(f.n) : 0;
                });

                new ApexCharts(document.querySelector('#chartOrders'), {
                    series: [{
                        name: 'Orders',
                        data: counts
                    }],
                    chart: {
                        type: 'bar',
                        height: 260,
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'Inter,sans-serif'
                    },
                    colors: ['#2563eb'],
                    plotOptions: {
                        bar: {
                            borderRadius: 6,
                            columnWidth: '50%'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        categories: labels,
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: '#64748b',
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: '#64748b',
                                fontSize: '12px'
                            }
                        },
                        tickAmount: 4,
                        min: 0
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
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'light',
                            type: 'vertical',
                            gradientToColors: ['#60a5fa'],
                            stops: [0, 100]
                        }
                    }
                }).render();

                /* ── Product availability donut ── */
                const availData = <?= json_encode($availRows) ?>;
                if (availData.length > 0) {
                    const labelMap = {
                        'Available in store': 'Available',
                        'Reserved': 'Reserved',
                        'Sold Out': 'Sold Out'
                    };
                    new ApexCharts(document.querySelector('#chartAvail'), {
                        series: availData.map(r => parseInt(r.n)),
                        labels: availData.map(r => labelMap[r.availability] || r.availability),
                        chart: {
                            type: 'donut',
                            height: 260,
                            fontFamily: 'Inter,sans-serif'
                        },
                        colors: ['#10b981', '#f59e0b', '#ef4444'],
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '62%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Products',
                                            fontSize: '12px',
                                            color: '#64748b'
                                        }
                                    }
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: (_, opts) => opts.w.config.series[opts.seriesIndex]
                        },
                        legend: {
                            position: 'bottom',
                            fontSize: '12px'
                        },
                        stroke: {
                            width: 2
                        }
                    }).render();
                }
            })();
        </script>