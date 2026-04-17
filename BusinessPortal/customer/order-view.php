<?php

/**
 * customer/order-view.php — Customer: View My Order
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireCustomer('../auth-login-minimal.php');
$currentUser = currentUser();
$accountId   = $currentUser['id'];
$companyId   = $currentUser['company_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: orders.php');
    exit;
}
try {
    // Check if this order belongs to the customer
    $stmt = $pdo->prepare("SELECT f.* FROM fabrication_orders f WHERE f.id = ? AND f.account_id = ?");
    $stmt->execute([$id, $accountId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$order) {
        header('Location: orders.php');
        exit;
    }
    $stmt = $pdo->prepare("SELECT * FROM job_sections WHERE order_id = ? ORDER BY id ASC");
    $stmt->execute([$id]);
    $job_sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}
$pageTitle   = 'Order #' . $order['id'];
$breadcrumb  = [
    ['label' => 'My Orders', 'url' => 'orders.php'],
    ['label' => 'Order #' . $order['id']]
];
/* helpers */
function thicknessLabel(string $val, ?string $custom): string
{
    if ($val === 'custom' && $custom) return htmlspecialchars($custom) . ' cm (custom)';
    if ($val === '2cm') return '2 cm';
    if ($val === '3cm') return '3 cm';
    return htmlspecialchars($val ?: 'N/A');
}
function fieldOrNA(?string $v): string
{
    return $v ? htmlspecialchars($v) : '<span style="color:var(--color-text-sub)">—</span>';
}
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
                    <h1 class="page-title">Order #<?= $order['id'] ?></h1>
                    <p class="page-subtitle">
                        Created <?= date('M j, Y', strtotime($order['created_at'])) ?>
                        &middot; Last updated <?= date('M j, Y g:i A', strtotime($order['updated_at'])) ?>
                    </p>
                </div>
                <div class="page-actions">
                    <a href="orders.php" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Orders
                    </a>
                </div>
            </div>
            <div class="row g-4">
                <!-- Customer Info -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-user text-primary'></i> Customer Information</h6>
                        </div>
                        <div class="card-section">
                            <dl style="margin:0;display:grid;gap:10px;">
                                <div>
                                    <dt style="font-size:12px;color:var(--color-text-sub);">Name</dt>
                                    <dd style="margin:0;font-weight:500;"><?= fieldOrNA($order['customer_name']) ?></dd>
                                </div>
                                <div>
                                    <dt style="font-size:12px;color:var(--color-text-sub);">Phone</dt>
                                    <dd style="margin:0;"><?= fieldOrNA($order['phone'] ?? null) ?></dd>
                                </div>
                                <div>
                                    <dt style="font-size:12px;color:var(--color-text-sub);">Address</dt>
                                    <dd style="margin:0;"><?= fieldOrNA($order['address']) ?></dd>
                                </div>
                                <div>
                                    <dt style="font-size:12px;color:var(--color-text-sub);">City</dt>
                                    <dd style="margin:0;"><?= fieldOrNA($order['city']) ?></dd>
                                </div>
                                <div>
                                    <dt style="font-size:12px;color:var(--color-text-sub);">ZIP Code</dt>
                                    <dd style="margin:0;"><?= fieldOrNA($order['zip_code']) ?></dd>
                                </div>
                                <div>
                                    <dt style="font-size:12px;color:var(--color-text-sub);">PO Number</dt>
                                    <dd style="margin:0;"><?= fieldOrNA($order['po_number'] ?? null) ?></dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    <!-- Sales Rep -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-briefcase text-primary'></i> Sales Representative</h6>
                        </div>
                        <div class="card-section">
                            <dl style="margin:0;display:grid;gap:10px;">
                                <div>
                                    <dt style="font-size:12px;color:var(--color-text-sub);">Name</dt>
                                    <dd style="margin:0;font-weight:500;"><?= fieldOrNA($order['sales_rep']) ?></dd>
                                </div>
                                <div>
                                    <dt style="font-size:12px;color:var(--color-text-sub);">Phone</dt>
                                    <dd style="margin:0;"><?= fieldOrNA($order['sales_rep_phone']) ?></dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    <!-- Notes -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-note text-primary'></i> Notes & Instructions</h6>
                        </div>
                        <div class="card-section">
                            <p style="white-space:pre-wrap;margin:0;font-size:13px;color:var(--color-text);">
                                <?= !empty($order['notes']) ? htmlspecialchars($order['notes']) : '<span style="color:var(--color-text-sub)">No special instructions provided.</span>' ?>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Job Sections -->
                <div class="col-lg-8">
                    <?php if (empty($job_sections)): ?>
                        <div class="card">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class='bx bx-layer'></i></div>
                                <p class="empty-state-title">No job sections</p>
                                <p class="empty-state-desc">This order has no job sections attached.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($job_sections as $idx => $job): ?>
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="card-title">
                                        <i class='bx bx-layer text-primary'></i>
                                        Job Section #<?= $idx + 1 ?>
                                    </h6>
                                </div>
                                <div class="card-section">
                                    <div class="row g-3">
                                        <div class="col-sm-4">
                                            <div style="font-size:12px;color:var(--color-text-sub);">Job Type</div>
                                            <div style="font-weight:500;">
                                                <?php
                                                $jt = $job['job_type'];
                                                echo ($jt === 'other' && !empty($job['job_type_other']))
                                                    ? htmlspecialchars($jt . ' – ' . $job['job_type_other'])
                                                    : htmlspecialchars(ucfirst($jt ?? '—'));
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div style="font-size:12px;color:var(--color-text-sub);">Material Type</div>
                                            <div style="font-weight:500;">
                                                <?php
                                                $mt = $job['material_type'];
                                                echo ($mt === 'other' && !empty($job['material_other']))
                                                    ? htmlspecialchars($mt . ' – ' . $job['material_other'])
                                                    : htmlspecialchars(ucfirst($mt ?? '—'));
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div style="font-size:12px;color:var(--color-text-sub);">Material Color</div>
                                            <div><?= fieldOrNA($job['material_color'] ?? null) ?></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div style="font-size:12px;color:var(--color-text-sub);">Thickness</div>
                                            <div><?= thicknessLabel($job['thickness'] ?? '', $job['thickness_custom'] ?? null) ?></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div style="font-size:12px;color:var(--color-text-sub);">Edge Profile</div>
                                            <div>
                                                <?php
                                                $ep = $job['edge_profile'] ?? '';
                                                echo ($ep === 'custom' && !empty($job['edge_profile_custom']))
                                                    ? 'Custom – ' . htmlspecialchars($job['edge_profile_custom'])
                                                    : fieldOrNA($ep ?: null);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div style="font-size:12px;color:var(--color-text-sub);">Tear Out</div>
                                            <div>
                                                <?php if (isset($job['tear_out'])): ?>
                                                    <?php if ($job['tear_out'] === 'yes'): ?>
                                                        <span class="badge badge-warning">Yes</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-neutral">No</span>
                                                    <?php endif; ?>
                                                <?php else: echo '—';
                                                endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div style="font-size:12px;color:var(--color-text-sub);">Sink Provider</div>
                                            <div><?= fieldOrNA($job['sink_provider'] ?? null) ?></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div style="font-size:12px;color:var(--color-text-sub);">Sink Type</div>
                                            <div><?= fieldOrNA($job['sink_type'] ?? null) ?></div>
                                        </div>
                                        <?php if (($job['sink_provider'] ?? '') === 'aa_granite'): ?>
                                            <div class="col-sm-4">
                                                <div style="font-size:12px;color:var(--color-text-sub);">Sink Style</div>
                                                <div>
                                                    <?php
                                                    $ss = $job['sink_style'] ?? '';
                                                    echo ($ss === 'other' && !empty($job['sink_style_other']))
                                                        ? 'Other – ' . htmlspecialchars($job['sink_style_other'])
                                                        : fieldOrNA($ss ?: null);
                                                    ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col-sm-6">
                                            <div style="font-size:12px;color:var(--color-text-sub);">Created</div>
                                            <div style="color:var(--color-text-sub);"><?= date('M j, Y H:i', strtotime($job['created_at'])) ?></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div style="font-size:12px;color:var(--color-text-sub);">Updated</div>
                                            <div style="color:var(--color-text-sub);"><?= date('M j, Y H:i', strtotime($job['updated_at'])) ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <!-- Attached image -->
                    <?php if (!empty($order['image'])): ?>
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title"><i class='bx bx-image text-primary'></i> Attached File</h6>
                            </div>
                            <div class="card-section">
                                <?php
                                $ext = strtolower(pathinfo($order['image'], PATHINFO_EXTENSION));
                                $imgUrl = '../assets/products/' . $order['image'];
                                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])):
                                ?>
                                    <img src="<?= htmlspecialchars($imgUrl) ?>"
                                        style="max-height:300px;max-width:100%;border-radius:var(--radius-sm);"
                                        alt="Order attachment">
                                    <div class="mt-3">
                                        <a href="<?= htmlspecialchars($imgUrl) ?>" download class="btn btn-primary btn-sm">
                                            <i class='bx bx-download'></i> Download Image
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div style="display:flex;align-items:center;gap:10px;padding:12px;
                                    background:var(--color-bg);border-radius:var(--radius-sm);">
                                        <i class='bx bx-file' style="font-size:24px;color:var(--color-primary);"></i>
                                        <div>
                                            <div style="font-weight:500;"><?= htmlspecialchars($order['image']) ?></div>
                                            <div style="font-size:12px;color:var(--color-text-sub);"><?= strtoupper($ext) ?> file</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="download.php?file=<?= urlencode($order['image']) ?>" class="btn btn-primary btn-sm">
                                            <i class='bx bx-download'></i> Download File
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
        <?php include __DIR__ . '/includes/footer.php'; ?>
        <style>
            /* تحسينات إضافية للتصميم */
            .card {
                border: 1px solid var(--color-border);
                border-radius: 8px;
                overflow: hidden;
            }

            .card-header {
                background: var(--color-bg);
                border-bottom: 1px solid var(--color-border);
                padding: 16px 20px;
            }

            .card-title {
                margin: 0;
                font-size: 15px;
                font-weight: 600;
                color: var(--color-text);
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .card-section {
                padding: 20px;
            }

            .badge {
                display: inline-block;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
            }

            .badge-warning {
                background: rgba(107, 205, 42, 0.1);
                color: #35c03e;
                border: 1px solid rgba(107, 205, 42, 0.1);
            }

            .badge-neutral {
                background: rgba(222, 90, 67, 0.1);
                color: #c73734;
                border: 1px solid rgba(226, 51, 51, 0.1);
            }

            .badge-primary {
                background: rgba(13, 110, 253, 0.1);
                color: #0d6efd;
                border: 1px solid rgba(13, 110, 253, 0.2);
            }

            .empty-state {
                text-align: center;
                padding: 40px 20px;
            }

            .empty-state-icon {
                font-size: 48px;
                color: var(--color-text-sub);
                margin-bottom: 16px;
            }

            .empty-state-title {
                font-size: 16px;
                font-weight: 600;
                color: var(--color-text);
                margin-bottom: 8px;
            }

            .empty-state-desc {
                font-size: 14px;
                color: var(--color-text-sub);
                margin-bottom: 20px;
            }

            /* تحسينات للشبكة */
            .row.g-4>[class*="col-"] {
                padding-left: 16px;
                padding-right: 16px;
            }

            @media (max-width: 768px) {
                .page-header {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 16px;
                }

                .page-actions {
                    width: 100%;
                }

                .page-actions .btn {
                    width: 100%;
                    margin-bottom: 8px;
                }
            }
        </style>
</body>

</html>