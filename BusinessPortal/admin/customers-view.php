<?php

/**
 * admin/customers-view.php — View Customer
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: customers.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM customers_companies WHERE id = ?");
    $stmt->execute([$id]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$company) {
        header('Location: customers.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE company_id = ?");
    $stmt->execute([$id]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    /* Recent orders */
    $stmt = $pdo->prepare("
        SELECT fo.id, fo.customer_name, fo.created_at, fo.city, fo.sales_rep
        FROM fabrication_orders fo
        LEFT JOIN accounts a ON fo.account_id = a.id
        WHERE a.company_id = ? OR fo.customer_name = ?
        ORDER BY fo.created_at DESC LIMIT 10
    ");
    $stmt->execute([$id, $company['company_name']]);
    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}

$currentUser = currentUser();
$pageTitle   = htmlspecialchars($company['company_name']);
$breadcrumb  = [
    ['label' => 'Customers', 'url' => 'customers.php'],
    ['label' => htmlspecialchars($company['company_name'])]
];

$status   = $account['status'] ?? 'Inactive';
$initialss = strtoupper(substr($company['company_name'] ?? 'C', 0, 2));

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <!-- Page header -->
            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title"><?= htmlspecialchars($company['company_name']) ?></h1>
                    <p class="page-subtitle">Customer profile &middot; ID #<?= $company['id'] ?></p>
                </div>
                <div class="page-actions">
                    <a href="customers.php" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back
                    </a>
                    <a href="customers-edit.php?id=<?= $id ?>" class="btn btn-primary">
                        <i class='bx bx-edit'></i> Edit Customer
                    </a>
                </div>
            </div>

            <div class="row g-4">

                <!-- Left: profile card -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-section text-center" style="padding-top:2rem;">
                            <div style="width:72px;height:72px;border-radius:50%;background:var(--color-primary);
                                display:flex;align-items:center;justify-content:center;
                                color:#fff;font-size:28px;font-weight:700;margin:0 auto 12px;">
                                <?= htmlspecialchars($initialss) ?>
                            </div>
                            <h5 style="margin:0 0 4px;font-weight:700;"><?= htmlspecialchars($company['company_name']) ?></h5>
                            <p style="color:var(--color-text-sub);font-size:13px;margin:0 0 12px;">
                                <?= htmlspecialchars($company['contact_position'] ?? 'Company') ?>
                            </p>
                            <?php
                            $badgeHtml = match ($status) {
                                'Active'      => '<span class="badge badge-success"><span class="dot"></span>Active</span>',
                                'Blacklisted' => '<span class="badge badge-critical"><span class="dot"></span>Blacklisted</span>',
                                default       => '<span class="badge badge-neutral"><span class="dot"></span>Inactive</span>',
                            };
                            echo $badgeHtml;
                            ?>
                        </div>
                        <div class="card-section">
                            <div class="resource-item" style="padding:10px 0;border-bottom:1px solid var(--color-border);">
                                <div style="font-size:12px;color:var(--color-text-sub);">Contact Person</div>
                                <div style="font-weight:500;"><?= htmlspecialchars($company['contact_name'] ?? '—') ?></div>
                            </div>
                            <div class="resource-item" style="padding:10px 0;border-bottom:1px solid var(--color-border);">
                                <div style="font-size:12px;color:var(--color-text-sub);">Phone</div>
                                <div><?= htmlspecialchars($company['phone'] ?? '—') ?></div>
                            </div>
                            <div class="resource-item" style="padding:10px 0;border-bottom:1px solid var(--color-border);">
                                <div style="font-size:12px;color:var(--color-text-sub);">Email</div>
                                <div><?= htmlspecialchars($company['email'] ?? '—') ?></div>
                            </div>
                            <div class="resource-item" style="padding:10px 0;">
                                <div style="font-size:12px;color:var(--color-text-sub);">Member Since</div>
                                <div><?= date('M j, Y', strtotime($company['created_at'])) ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Address card -->
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

                <!-- Right: orders -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-file text-primary'></i> Recent Orders</h6>
                            <span class="badge badge-neutral"><?= count($recentOrders) ?></span>
                        </div>

                        <?php if (empty($recentOrders)): ?>
                            <div class="empty-state" style="padding:2rem;">
                                <div class="empty-state-icon"><i class='bx bx-file'></i></div>
                                <p class="empty-state-title">No orders yet</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer Name</th>
                                            <th>City</th>
                                            <th>Sales Rep</th>
                                            <th>Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentOrders as $ord): ?>
                                            <tr>
                                                <td style="font-weight:600;">#<?= $ord['id'] ?></td>
                                                <td><?= htmlspecialchars($ord['customer_name']) ?></td>
                                                <td style="color:var(--color-text-sub);"><?= htmlspecialchars($ord['city'] ?? '—') ?></td>
                                                <td style="color:var(--color-text-sub);"><?= htmlspecialchars($ord['sales_rep'] ?? '—') ?></td>
                                                <td style="color:var(--color-text-sub);white-space:nowrap;">
                                                    <?= date('M j, Y', strtotime($ord['created_at'])) ?>
                                                </td>
                                                <td class="text-end">
                                                    <a href="order-view.php?id=<?= $ord['id'] ?>"
                                                        class="btn btn-icon btn-sm btn-outline" title="View">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>