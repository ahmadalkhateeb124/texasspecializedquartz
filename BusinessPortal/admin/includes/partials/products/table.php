<?php /** Expects: $products */ ?>
<style>
    .product-thumb {
        width: 52px; height: 40px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid var(--border);
        display: block;
    }
    .product-thumb-placeholder {
        width: 52px; height: 40px;
        border-radius: 6px;
        background: var(--bg);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-dis);
        font-size: 18px;
    }
</style>
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
            <a href="products-new" class="btn btn-primary btn-sm">
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
                        $hasImage = !empty($p['image']) && file_exists(__DIR__ . '/../../../../assets/products/' . $p['image']);
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
                                <a href="products-edit?id=<?= $p['id'] ?>"
                                    style="font-weight:600;color:var(--color-text);">
                                    <?= htmlspecialchars($p['title']) ?>
                                </a>
                                <div style="font-size:11px;color:var(--color-text-sub);">ID #<?= $p['id'] ?></div>
                            </td>
                            <td><?= htmlspecialchars($p['color_name']) ?></td>
                            <td><?= htmlspecialchars($p['size']) ?></td>
                            <td><?= (int)$p['quantity'] ?></td>
                            <td><?= ProductRepository::availabilityBadge($p['availability']) ?></td>
                            <td style="color:var(--color-text-sub);white-space:nowrap;">
                                <?= date('M j, Y', strtotime($p['created_at'])) ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="products-edit?id=<?= $p['id'] ?>"
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
