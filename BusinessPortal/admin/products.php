<?php

/**
 * admin/products.php — Products Index
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Products';
$breadcrumb  = [['label' => 'Products']];

/* ── Fetch products ──────────────────────────────────── */
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
    $dbError  = htmlspecialchars($e->getMessage());
}

/* ── Availability counts ─────────────────────────────── */
$countAvail    = 0;
$countReserved = 0;
$countSold = 0;
foreach ($products as $p) {
    if ($p['availability'] === 'Available in store') $countAvail++;
    elseif ($p['availability'] === 'Reserved')       $countReserved++;
    elseif ($p['availability'] === 'Sold Out')       $countSold++;
}

$availBadge = function (string $v): string {
    return match ($v) {
        'Available in store' => '<span class="badge badge-success"><span class="dot"></span>Available</span>',
        'Reserved'           => '<span class="badge badge-warning"><span class="dot"></span>Reserved</span>',
        'Sold Out'           => '<span class="badge badge-critical"><span class="dot"></span>Sold Out</span>',
        default              => '<span class="badge badge-neutral">' . htmlspecialchars($v) . '</span>',
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
                    <h1 class="page-title">Remnants</h1>
                    <p class="page-subtitle"><?= count($products) ?> total product<?= count($products) != 1 ? 's' : '' ?></p>
                </div>
                <div class="page-actions">
                    <a href="products-new.php" class="btn btn-primary">
                        <i class='bx bx-plus'></i> Add Remnants
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
                            <div class="kpi-label">Total Remnants</div>
                            <div class="kpi-value"><?= count($products) ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card">
                        <div class="kpi-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label">Available</div>
                                <div class="kpi-value" style="color:var(--color-success);"><?= $countAvail ?></div>
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
                                <div class="kpi-label">Reserved</div>
                                <div class="kpi-value" style="color:var(--color-warning);"><?= $countReserved ?></div>
                            </div>
                            <div class="kpi-icon" style="background:var(--color-warning-l);">
                                <i class='bx bx-time' style="color:var(--color-warning);"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card">
                        <div class="kpi-card d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label">Sold Out</div>
                                <div class="kpi-value" style="color:var(--color-critical);"><?= $countSold ?></div>
                            </div>
                            <div class="kpi-icon" style="background:var(--color-critical-l);">
                                <i class='bx bx-x-circle' style="color:var(--color-critical);"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products table card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title"><i class='bx bx-cube-alt text-primary'></i> All Remnants</h6>
                    <div class="d-flex gap-2 align-items-center">
                        <div class="input-group" style="width:220px;">
                            <span class="input-group-text" style="border-right:none;">
                                <i class='bx bx-search' style="font-size:15px;"></i>
                            </span>
                            <input type="text" id="productSearch" class="form-control"
                                placeholder="Search products…" style="border-left:none;">
                        </div>
                        <select id="availFilter" class="form-select" style="width:160px;">
                            <option value="">All Statuses</option>
                            <option>Available in store</option>
                            <option>Reserved</option>
                            <option>Sold Out</option>
                        </select>
                    </div>
                </div>

                <?php if (empty($products)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class='bx bx-cube-alt'></i></div>
                        <p class="empty-state-title">No products yet</p>
                        <p class="empty-state-desc">Add your first granite slab or product to get started.</p>
                        <a href="products-new.php" class="btn btn-primary btn-sm">
                            <i class='bx bx-plus'></i> Add Product
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="productsTable">
                            <thead>
                                <tr>
                                    <th style="width:52px;"></th>
                                    <th>Title</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Qty</th>
                               
                                    <th>Status</th>
                                    <th>Added</th>
                                    <th class="text-end" style="width:80px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productsTbody">
                                <?php foreach ($products as $p):
                                    $imgPath  = '../assets/products/' . $p['image'];
                                    $hasImage = !empty($p['image']) && file_exists(__DIR__ . '/../assets/products/' . $p['image']);
                                    $price    = $p['price'] !== null ? '$' . number_format($p['price'], 2) : '—';
                                ?>
                                    <tr data-avail="<?= htmlspecialchars($p['availability']) ?>">
                                        <td>
                                            <?php if ($hasImage): ?>
                                                <img src="<?= htmlspecialchars($imgPath) ?>"
                                                    class="product-thumb" alt="<?= htmlspecialchars($p['title']) ?>">
                                            <?php else: ?>
                                                <div class="product-thumb-placeholder">
                                                    <i class='bx bx-image'></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="products-edit.php?id=<?= $p['id'] ?>"
                                                style="font-weight:600;color:var(--color-text);">
                                                <?= htmlspecialchars($p['title']) ?>
                                            </a>
                                            <div style="font-size:11px;color:var(--color-text-sub);">ID #<?= $p['id'] ?></div>
                                        </td>
                                        <td><?= htmlspecialchars($p['color_name']) ?></td>
                                        <td><?= htmlspecialchars($p['size']) ?></td>
                                        <td><?= intval($p['quantity']) ?></td>
                                        <td><?= $availBadge($p['availability']) ?></td>
                                        <td style="color:var(--color-text-sub);white-space:nowrap;">
                                            <?= date('M j, Y', strtotime($p['created_at'])) ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                <a href="products-edit.php?id=<?= $p['id'] ?>"
                                                    class="btn btn-icon btn-sm btn-outline" title="Edit">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <button type="button"
                                                    class="btn btn-icon btn-sm btn-outline text-danger delete-product-btn"
                                                    data-id="<?= $p['id'] ?>"
                                                    data-title="<?= htmlspecialchars($p['title']) ?>"
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

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Delete Product</h6>
                        <button class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex gap-3 align-items-start">
                            <div style="width:40px;height:40px;background:var(--color-critical-l);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class='bx bx-trash' style="font-size:20px;color:var(--color-critical);"></i>
                            </div>
                            <div>
                                <p style="font-weight:600;margin:0 0 4px;" id="deleteTitle"></p>
                                <p style="font-size:13px;color:var(--color-text-sub);margin:0;">
                                    This product will be permanently deleted. This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-critical btn-sm" id="confirmDeleteBtn">
                            <span id="deleteSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                            Delete product
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script>
            (function() {
                /* ── Filters ── */
                const search = document.getElementById('productSearch');
                const filterSel = document.getElementById('availFilter');
                const rows = () => document.querySelectorAll('#productsTbody tr');

                function applyFilter() {
                    const q = search?.value.toLowerCase() || '';
                    const a = filterSel?.value || '';
                    rows().forEach(tr => {
                        const matchQ = !q || tr.textContent.toLowerCase().includes(q);
                        const matchA = !a || tr.dataset.avail === a;
                        tr.style.display = (matchQ && matchA) ? '' : 'none';
                    });
                }
                search?.addEventListener('input', applyFilter);
                filterSel?.addEventListener('change', applyFilter);

                /* ── Delete flow ── */
                let deleteId = null;
                const modal = new bootstrap.Modal(document.getElementById('deleteModal'));

                document.querySelectorAll('.delete-product-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        deleteId = btn.dataset.id;
                        document.getElementById('deleteTitle').textContent = 'Delete "' + btn.dataset.title + '"?';
                        modal.show();
                    });
                });

                document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
                    if (!deleteId) return;
                    const btn = document.getElementById('confirmDeleteBtn');
                    const spinner = document.getElementById('deleteSpinner');
                    btn.disabled = true;
                    spinner.classList.remove('d-none');

                    const fd = new FormData();
                    fd.append('id', deleteId);

                    try {
                        const res = await fetch('../auth/delete-product.php', {
                            method: 'POST',
                            body: fd
                        });
                        const data = await res.json();
                        modal.hide();
                        if (data.success) {
                            document.querySelector(`.delete-product-btn[data-id="${deleteId}"]`)
                                ?.closest('tr')?.remove();
                            showToast(data.message || 'Product deleted.', 'success');
                        } else {
                            showToast(data.message || 'Could not delete product.', 'error');
                        }
                    } catch {
                        modal.hide();
                        showToast('Network error.', 'error');
                    }

                    btn.disabled = false;
                    spinner.classList.add('d-none');
                    deleteId = null;
                });
            })();
        </script>