<?php /** Expects: $sinks */
$siteRoot = realpath(__DIR__ . '/../../../../..');
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-grid-alt text-primary'></i> All Sinks</h6>
        <div class="d-flex gap-2 align-items-center">
            <input type="text" id="sinkSearch" class="form-control form-control-sm"
                placeholder="Search…" style="width:220px;">
            <select id="categoryFilter" class="form-select form-select-sm" style="width:150px;">
                <option value="">All categories</option>
                <option value="kitchen">Kitchen</option>
                <option value="bathroom">Bathroom</option>
                <option value="bar">Bar</option>
                <option value="laundry">Laundry</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <?php if (empty($sinks)): ?>
            <div class="empty-state">
                <i class='bx bx-grid-alt empty-state-icon'></i>
                <p class="empty-state-title">No sinks yet</p>
                <p class="empty-state-desc">Add your first sink to the catalog.</p>
                <a href="sinks-new" class="btn btn-primary btn-sm">
                    <i class='bx bx-plus'></i> Add Sink
                </a>
            </div>
        <?php else: ?>
            <table class="table table-hover mb-0" id="sinksTable">
                <thead>
                    <tr>
                        <th style="width:70px;">Image</th>
                        <th>Name</th>
                        <th>Model</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Status</th>
                        <th class="text-end" style="width:110px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="sinksTbody">
                    <?php foreach ($sinks as $s):
                        $imgWebPath = site_asset(ltrim($s['image'] ?? '', '/'));
                        $hasImg = !empty($s['image']) && file_exists(site_path(ltrim($s['image'], '/')));
                    ?>
                        <tr data-category="<?= htmlspecialchars($s['category']) ?>">
                            <td>
                                <?php if ($hasImg): ?>
                                    <img src="<?= htmlspecialchars($imgWebPath) ?>"
                                        alt="" style="width:50px;height:40px;object-fit:cover;border-radius:4px;">
                                <?php else: ?>
                                    <div style="width:50px;height:40px;background:var(--color-bg-subdued);
                                                border-radius:4px;display:flex;align-items:center;
                                                justify-content:center;color:var(--color-text-sub);">
                                        <i class='bx bx-image'></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="sinks-edit?id=<?= $s['id'] ?>"
                                    style="font-weight:600;color:var(--color-text);">
                                    <?= htmlspecialchars($s['name']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($s['model']) ?></td>
                            <td><span class="badge badge-neutral"><?= htmlspecialchars(ucfirst($s['category'])) ?></span></td>
                            <td style="color:var(--color-text-sub);"><?= htmlspecialchars($s['subcategory']) ?></td>
                            <td>
                                <?php if ($s['status'] === 'active'): ?>
                                    <span class="badge badge-success"><span class="dot"></span>Active</span>
                                <?php else: ?>
                                    <span class="badge badge-neutral"><span class="dot"></span>Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="sinks-edit?id=<?= $s['id'] ?>"
                                        class="btn btn-icon btn-sm btn-outline" title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn-icon btn-sm btn-outline text-danger delete-sink-btn"
                                        data-id="<?= $s['id'] ?>"
                                        data-name="<?= htmlspecialchars($s['name']) ?>"
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
