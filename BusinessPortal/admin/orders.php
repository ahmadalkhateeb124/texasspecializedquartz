<?php

/**
 * admin/orders.php — All Fabrication Orders (Admin View)
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

requireAdmin('../auth-login-minimal.php');

$currentUser = currentUser();
$pageTitle   = 'Fabrication Orders';
$breadcrumb  = [['label' => 'Orders']];

/* ── Fetch all orders with job sections ─────────────────────── */
try {
    $stmt = $pdo->query("
        SELECT
            f.id          AS order_id,
            f.created_at,
            f.updated_at,
            c.company_name,
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
        LEFT JOIN accounts a ON a.id = f.account_id
        LEFT JOIN customers_companies c ON c.id = a.company_id
        LEFT JOIN job_sections j ON j.order_id = f.id
        ORDER BY f.created_at DESC, j.id ASC
    ");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $rows = [];
    $dbError = htmlspecialchars($e->getMessage());
}

/* Group rows by order_id */
$orders = [];
foreach ($rows as $r) {
    $oid = $r['order_id'];
    if (!isset($orders[$oid])) {
        $orders[$oid] = [
            'id'           => $oid,
            'created_at'   => $r['created_at'],
            'updated_at'   => $r['updated_at'],
            'company_name' => $r['company_name'] ?? '—',
            'jobs'         => [],
        ];
    }
    if ($r['job_id']) {
        $orders[$oid]['jobs'][] = $r;
    }
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
                    <h1 class="page-title">Fabrication Orders</h1>
                    <p class="page-desc">Manage and track all fabrication job orders.</p>
                </div>
                <a href="order-new.php" class="btn btn-primary">
                    <i class='bx bx-plus'></i> New Order
                </a>
            </div>

            <?php if (isset($dbError)): ?>
                <div class="alert alert-danger mb-3">
                    <i class='bx bx-error me-2'></i> Database error: <?= $dbError ?>
                </div>
            <?php endif; ?>

            <!-- ── Summary Stat Bar ────────────────────────────────────── -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card text-center" style="padding:14px 10px;">
                        <div style="font-size:22px;font-weight:800;color:var(--text);"><?= count($orders) ?></div>
                        <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Total Orders</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center" style="padding:14px 10px;">
                        <?php
                        $thisMonth = array_filter(
                            $orders,
                            fn($o) =>
                            date('Y-m', strtotime($o['created_at'])) === date('Y-m')
                        );
                        ?>
                        <div style="font-size:22px;font-weight:800;color:var(--primary);"><?= count($thisMonth) ?></div>
                        <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">This Month</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center" style="padding:14px 10px;">
                        <?php $totalJobs = array_sum(array_map(fn($o) => count($o['jobs']), $orders)); ?>
                        <div style="font-size:22px;font-weight:800;color:var(--color-primary);"><?= $totalJobs ?></div>
                        <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Job Sections</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center" style="padding:14px 10px;">
                        <?php $thisWeek = array_filter(
                            $orders,
                            fn($o) =>
                            strtotime($o['created_at']) >= strtotime('-7 days')
                        ); ?>
                        <div style="font-size:22px;font-weight:800;color:var(--warning);"><?= count($thisWeek) ?></div>
                        <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Last 7 Days</div>
                    </div>
                </div>
            </div>

            <!-- ── Orders Table ────────────────────────────────────────── -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title"><i class='bx bx-file text-primary'></i> All Orders</h6>
                    <div class="d-flex gap-2">
                        <input type="text" id="tableSearch" class="form-control form-control-sm"
                            placeholder="Search orders…" style="width:220px;">
                    </div>
                </div>
                <div class="table-responsive">
                    <?php if (empty($orders)): ?>
                        <div class="empty-state">
                            <i class='bx bx-file-blank empty-state-icon'></i>
                            <p class="empty-state-title">No orders yet</p>
                            <p class="empty-state-desc">Start by creating your first fabrication order.</p>
                            <a href="order-new.php" class="btn btn-primary btn-sm">
                                <i class='bx bx-plus'></i> Create Order
                            </a>
                        </div>
                    <?php else: ?>
                        <table class="table table-hover mb-0" id="ordersTable">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Company</th>
                                    <th>Job Type</th>
                                    <th>Material</th>
                                    <th>Color</th>
                                    <th>Thickness</th>
                                    <th>Edge Profile</th>
                                    <th>Sink</th>
                                    <th>Tear Out</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <?php $jobs = $order['jobs'];
                                    $jobCount = count($jobs); ?>
                                    <tr>
                                        <td>
                                            <span style="font-weight:700;font-size:13px;">
                                                #<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?>
                                            </span>
                                            <?php if ($jobCount > 0): ?>
                                                <span class="badge badge-primary ms-1"><?= $jobCount ?> job<?= $jobCount > 1 ? 's' : '' ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($order['company_name']) ?></td>

                                        <!-- Job Type -->
                                        <td>
                                            <?php foreach ($jobs as $j):
                                                $jt = htmlspecialchars($j['job_type']);
                                                if (!empty($j['job_type_other'])) $jt .= ' / ' . htmlspecialchars($j['job_type_other']);
                                                echo $jt . '<br>';
                                            endforeach; ?>
                                        </td>

                                        <!-- Material -->
                                        <td>
                                            <?php foreach ($jobs as $j):
                                                echo htmlspecialchars($j['material_type']) . '<br>';
                                            endforeach; ?>
                                        </td>

                                        <!-- Color -->
                                        <td>
                                            <?php foreach ($jobs as $j):
                                                echo htmlspecialchars($j['material_color']) . '<br>';
                                            endforeach; ?>
                                        </td>

                                        <!-- Thickness -->
                                        <td>
                                            <?php foreach ($jobs as $j):
                                                $thickness_display = $j['thickness'];
                                                if ($thickness_display === '2cm') {
                                                    $thickness_display = '2 Cm';
                                                } elseif ($thickness_display === '3cm') {
                                                    $thickness_display = '3 Cm';
                                                } elseif ($thickness_display === 'custom' && !empty($j['thickness_custom'])) {
                                                    $thickness_display = $j['thickness_custom'] . ' Cm';
                                                }
                                                echo htmlspecialchars($thickness_display) . '<br>';
                                            endforeach; ?>
                                        </td>

                                        <!-- Edge Profile -->
                                        <td>
                                            <?php foreach ($jobs as $j):
                                                echo htmlspecialchars($j['edge_profile']) . '<br>';
                                            endforeach; ?>
                                        </td>

                                        <!-- Sink -->
                                        <td>
                                            <?php foreach ($jobs as $j):
                                                $sink = trim($j['sink_provider'] . ' / ' . $j['sink_type'], ' /');
                                                echo htmlspecialchars($sink ?: '—') . '<br>';
                                            endforeach; ?>
                                        </td>

                                        <!-- Tear Out -->
                                        <td>
                                            <?php foreach ($jobs as $j): ?>
                                                <span class="badge <?= $j['tear_out'] === 'Yes' ? 'badge-warning' : 'badge-muted' ?>">
                                                    <?= htmlspecialchars($j['tear_out'] ?: '—') ?>
                                                </span><br>
                                            <?php endforeach; ?>
                                        </td>

                                        <td style="white-space:nowrap;color:var(--muted);">
                                            <?= date('M j, Y', strtotime($order['created_at'])) ?>
                                        </td>

                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">

                                                <a href="order-view.php?id=<?= $order['id'] ?>"
                                                    class="btn btn-xs btn-light" title="view">
                                                    <i class='bx bx-show'></i>
                                                </a>
                                                <a href="order-edit.php?id=<?= $order['id'] ?>"
                                                    class="btn btn-xs btn-light" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <button type="button"
                                                    class="btn btn-xs btn-light text-danger delete-btn"
                                                    data-id="<?= $order['id'] ?>"
                                                    data-label="Order #<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?>"
                                                    title="Delete">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

        </main>

        <!-- ── Delete Confirm Modal ──────────────────────────────────── -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title text-danger">
                            <i class='bx bx-trash me-2'></i>Delete Order
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class='bx bx-error-circle' style="font-size:48px;color:var(--danger);"></i>
                        <p class="mt-3 mb-1 fw-600" id="deleteLabel" style="font-weight:600;"></p>
                        <p class="text-muted mb-0" style="font-size:13px;">
                            This action will permanently delete the order and all associated job sections.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                            <span id="deleteSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                            Delete Order
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── View Order Modal ──────────────────────────────────────── -->
        <div class="modal fade" id="viewOrderModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">
                            <i class='bx bx-show me-2'></i>Order Details
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="orderDetails">
                        <div class="text-center py-5">
                            <div class="spinner-border" role="status"></div>
                            <p class="mt-2">Loading order details...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include __DIR__ . '/includes/footer.php'; ?>
        <script>
            (function() {

                /* ── Live search filter ── */
                document.getElementById('tableSearch')?.addEventListener('input', function() {
                    const q = this.value.toLowerCase();
                    document.querySelectorAll('#ordersTable tbody tr').forEach(tr => {
                        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
                    });
                });

                /* ── Delete flow ── */
                let deleteId = null;
                const modalEl = document.getElementById('deleteModal');
                const modal = modalEl ? new bootstrap.Modal(modalEl) : null;

                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        deleteId = btn.dataset.id;
                        document.getElementById('deleteLabel').textContent = 'Delete ' + btn.dataset.label + '?';
                        modal?.show();
                    });
                });

                document.getElementById('confirmDeleteBtn')?.addEventListener('click', async () => {
                    if (!deleteId) return;

                    const btn = document.getElementById('confirmDeleteBtn');
                    const spinner = document.getElementById('deleteSpinner');

                    btn.disabled = true;
                    spinner.classList.remove('d-none');

                    const fd = new FormData();
                    fd.append('id', deleteId);
                    fd.append('csrf_token', '<?= csrfToken() ?>');

                    try {
                        const res = await fetch('../auth/delete-order.php', {
                            method: 'POST',
                            body: fd
                        });

                        const data = await res.json();

                        modal?.hide();

                        if (data.success) {
                            showToast(data.message || 'Order deleted.', 'success');

                            document.querySelector(`.delete-btn[data-id="${deleteId}"]`)
                                ?.closest('tr')?.remove();
                        } else {
                            showToast(data.message || 'Could not delete order.', 'error');
                        }

                    } catch {
                        modal?.hide();
                        showToast('Network error. Please try again.', 'error');
                    }

                    btn.disabled = false;
                    spinner.classList.add('d-none');
                    deleteId = null;
                });

                /* ── View order modal ── */
                const viewModalEl = document.getElementById('viewOrderModal');
                const viewModal = viewModalEl ? new bootstrap.Modal(viewModalEl) : null;

                document.querySelectorAll('.view-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const orderId = btn.dataset.id;
                        loadOrderDetails(orderId);
                        viewModal?.show();
                    });
                });

                async function loadOrderDetails(orderId) {
                    const detailsEl = document.getElementById('orderDetails');

                    detailsEl.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border" role="status"></div>
                <p class="mt-2">Loading order details...</p>
            </div>
        `;

                    try {
                        const res = await fetch(`../auth/view_order.php?id=${orderId}`);
                        const data = await res.json();

                        if (data.success) {
                            detailsEl.innerHTML = generateOrderHTML(data.order);
                        } else {
                            detailsEl.innerHTML = `
                    <div class="text-center py-5 text-danger">
                        <i class='bx bx-error' style="font-size:48px;"></i>
                        <p class="mt-2">${data.message || 'Failed to load order details'}</p>
                    </div>
                `;
                        }

                    } catch {
                        detailsEl.innerHTML = `
                <div class="text-center py-5 text-danger">
                    <i class='bx bx-error' style="font-size:48px;"></i>
                    <p class="mt-2">Network error</p>
                </div>
            `;
                    }
                }

                /* ── Helpers ── */
                function formatThickness(thickness, custom) {
                    if (thickness === '2cm') return '2 Cm';
                    if (thickness === '3cm') return '3 Cm';
                    if (thickness === 'custom' && custom) return custom + ' Cm';
                    return thickness;
                }

                function generateOrderHTML(order) {
                    let html = `
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3">Customer Information</h6>
                    <p><strong>Name:</strong> ${order.customer_name}</p>
                    <p><strong>Phone:</strong> ${order.phone}</p>
                    <p><strong>Address:</strong> ${order.address}, ${order.city} ${order.zip_code}</p>
                    <p><strong>Sales Rep:</strong> ${order.sales_rep} (${order.sales_rep_phone})</p>
                    <p><strong>PO Number:</strong> ${order.po_number || 'N/A'}</p>
                    <p><strong>Notes:</strong> ${order.notes || 'N/A'}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3">Order Details</h6>
                    <p><strong>Order ID:</strong> #${String(order.id).padStart(4, '0')}</p>
                    <p><strong>Created:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                    <p><strong>Updated:</strong> ${new Date(order.updated_at).toLocaleString()}</p>
                    ${order.image ? `<p><strong>Attachment:</strong> <a href="/assets/products/${order.image}" download>Download File</a></p>` : ''}
                </div>
            </div>
            <hr>
            <h6 class="fw-bold mb-3">Job Sections</h6>
        `;

                    if (order.jobs && order.jobs.length > 0) {
                        order.jobs.forEach((job, index) => {
                            html += `
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Job ${index + 1}: ${job.job_type} ${job.job_type_other ? `(${job.job_type_other})` : ''}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Material:</strong> ${job.material_type} ${job.material_other ? `(${job.material_other})` : ''}</p>
                                    <p><strong>Thickness:</strong> ${formatThickness(job.thickness, job.thickness_custom)}</p>
                                    <p><strong>Color:</strong> ${job.material_color}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Edge Profile:</strong> ${job.edge_profile} ${job.edge_profile_custom ? `(${job.edge_profile_custom})` : ''}</p>
                                    <p><strong>Sink:</strong> ${job.sink_provider} ${job.sink_type} ${job.sink_style} ${job.sink_style_other ? `(${job.sink_style_other})` : ''}</p>
                                    <p><strong>Tear Out:</strong> ${job.tear_out}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                        });
                    } else {
                        html += '<p>No job sections found.</p>';
                    }

                    return html;
                }

            })();
        </script>