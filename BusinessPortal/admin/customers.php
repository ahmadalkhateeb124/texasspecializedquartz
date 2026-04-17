<?php

/**
 * admin/customers.php — Customers Index
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Customers';
$breadcrumb  = [['label' => 'Customers']];

/* ── Fetch customers ─────────────────────────────────── */
try {
    $stmt = $pdo->query("
        SELECT cc.*,
               a.status AS account_status,
               a.created_at AS account_created
        FROM customers_companies cc
        LEFT JOIN accounts a ON cc.id = a.company_id
        ORDER BY cc.id DESC
    ");
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $customers = [];
    $dbError   = htmlspecialchars($e->getMessage());
}

/* ── Status counts ───────────────────────────────────── */
$countActive     = 0;
$countInactive   = 0;
$countBlacklisted = 0;
foreach ($customers as $c) {
    $s = $c['account_status'] ?? 'Inactive';
    if ($s === 'Active')      $countActive++;
    elseif ($s === 'Inactive') $countInactive++;
    elseif ($s === 'Blacklisted') $countBlacklisted++;
}

$statusBadge = function (string $s): string {
    return match ($s) {
        'Active'      => '<span class="badge badge-success"><span class="dot"></span>Active</span>',
        'Blacklisted' => '<span class="badge badge-critical"><span class="dot"></span>Blacklisted</span>',
        default       => '<span class="badge badge-neutral"><span class="dot"></span>Inactive</span>',
    };
};

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
                    <h1 class="page-title">Customers</h1>
                    <p class="page-subtitle"><?= count($customers) ?> total customer<?= count($customers) != 1 ? 's' : '' ?></p>
                </div>
                <div class="page-actions">
                    <a href="customers-new.php" class="btn btn-primary">
                        <i class='bx bx-user-plus'></i> Add Customer
                    </a>
                </div>
            </div>

            <?php if (isset($dbError)): ?>
                <div class="alert alert-danger mb-3"><i class='bx bx-error me-2'></i><?= $dbError ?></div>
            <?php endif; ?>

            <!-- KPI strip -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-xl-3">
                    <div class="card">
                        <div class="kpi-card">
                            <div class="kpi-label">Total Customers</div>
                            <div class="kpi-value"><?= count($customers) ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card">
                        <div class="kpi-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label">Active</div>
                                <div class="kpi-value" style="color:var(--color-success);"><?= $countActive ?></div>
                            </div>
                            <div class="kpi-icon" style="background:var(--color-success-l);">
                                <i class='bx bx-check-circle' style="color:var(--color-success);"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card">
                        <div class="kpi-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label">Inactive</div>
                                <div class="kpi-value" style="color:var(--color-text-sub);"><?= $countInactive ?></div>
                            </div>
                            <div class="kpi-icon" style="background:var(--color-bg-subdued);
                        <i class='bx bx-user-x' style=" color:var(--color-text-sub);"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card">
                        <div class="kpi-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label">Blacklisted</div>
                                <div class="kpi-value" style="color:var(--color-critical);"><?= $countBlacklisted ?></div>
                            </div>
                            <div class="kpi-icon" style="background:var(--color-critical-l);">
                                <i class='bx bx-block' style="color:var(--color-critical);"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customers table -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title"><i class='bx bx-group text-primary'></i> All Customers</h6>
                    <div class="d-flex gap-2 align-items-center">
                        <div class="input-group" style="width:220px;">
                            <span class="input-group-text" style="border-right:none;">
                                <i class='bx bx-search' style="font-size:15px;"></i>
                            </span>
                            <input type="text" id="customerSearch" class="form-control"
                                placeholder="Search customers…" style="border-left:none;">
                        </div>
                        <select id="statusFilter" class="form-select" style="width:150px;">
                            <option value="">All Statuses</option>
                            <option>Active</option>
                            <option>Inactive</option>
                            <option>Blacklisted</option>
                        </select>
                    </div>
                </div>

                <?php if (empty($customers)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class='bx bx-group'></i></div>
                        <p class="empty-state-title">No customers yet</p>
                        <p class="empty-state-desc">Add your first customer to get started.</p>
                        <a href="customers-new.php" class="btn btn-primary btn-sm">
                            <i class='bx bx-plus'></i> Add Customer
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="customersTable">
                            <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>Contact</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Added</th>
                                    <th class="text-end" style="width:90px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="customersTbody">
                                <?php foreach ($customers as $c):
                                    $status   = $c['account_status'] ?? 'Inactive';
                                    $initials = strtoupper(substr($c['company_name'] ?? 'C', 0, 2));
                                    $location = implode(', ', array_filter([$c['city'] ?? '', $c['state'] ?? '']));
                                ?>
                                    <tr data-status="<?= htmlspecialchars($status) ?>">
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="width:34px;height:34px;border-radius:50%;background:var(--color-primary);
                                            display:flex;align-items:center;justify-content:center;
                                            color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">
                                                    <?= htmlspecialchars($initials) ?>
                                                </div>
                                                <div>
                                                    <a href="customers-view.php?id=<?= $c['id'] ?>"
                                                        style="font-weight:600;color:var(--color-text);">
                                                        <?= htmlspecialchars($c['company_name']) ?>
                                                    </a>
                                                    <div style="font-size:11px;color:var(--color-text-sub);">ID #<?= $c['id'] ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($c['contact_name'] ?? '—') ?></div>
                                            <?php if (!empty($c['contact_position'])): ?>
                                                <div style="font-size:11px;color:var(--color-text-sub);"><?= htmlspecialchars($c['contact_position']) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($c['phone'] ?? '—') ?></td>
                                        <td style="color:var(--color-text-sub);"><?= htmlspecialchars($c['email'] ?? '—') ?></td>
                                        <td style="color:var(--color-text-sub);"><?= htmlspecialchars($location ?: '—') ?></td>
                                        <td><?= $statusBadge($status) ?></td>
                                        <td style="color:var(--color-text-sub);white-space:nowrap;">
                                            <?= date('M j, Y', strtotime($c['created_at'])) ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                <a href="customers-view.php?id=<?= $c['id'] ?>"
                                                    class="btn btn-icon btn-sm btn-outline" title="View">
                                                    <i class='bx bx-show'></i>
                                                </a>
                                                <a href="customers-edit.php?id=<?= $c['id'] ?>"
                                                    class="btn btn-icon btn-sm btn-outline" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <button type="button" class="btn btn-icon btn-sm btn-outline text-danger"
                                                    onclick="deleteCustomer(<?= $c['id'] ?>, '<?= htmlspecialchars($c['company_name']) ?>')"
                                                    title="Delete">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script>
            (function() {
                const search = document.getElementById('customerSearch');
                const filter = document.getElementById('statusFilter');
                const rows = () => document.querySelectorAll('#customersTbody tr');

                function applyFilter() {
                    const q = search?.value.toLowerCase() || '';
                    const s = filter?.value || '';
                    rows().forEach(tr => {
                        const matchQ = !q || tr.textContent.toLowerCase().includes(q);
                        const matchS = !s || tr.dataset.status === s;
                        tr.style.display = (matchQ && matchS) ? '' : 'none';
                    });
                }
                search?.addEventListener('input', applyFilter);
                filter?.addEventListener('change', applyFilter);
            })();

            function deleteCustomer(id, name) {
                if (!confirm(`Are you sure you want to delete customer "${name}"? This action cannot be undone.`)) {
                    return;
                }

                fetch('../auth/delete-customer.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            id: id,
                            csrf_token: '<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>'
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast(data.message || 'Failed to delete customer', 'error');
                        }
                    })
                    .catch(() => showToast('Network error', 'error'));
            }
        </script>