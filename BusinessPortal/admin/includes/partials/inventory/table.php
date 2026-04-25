<?php /** Expects: $slabs */
$siteRoot = realpath(__DIR__ . '/../../../../..');
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-cube text-primary'></i> All Slabs</h6>
        <div class="d-flex gap-2 align-items-center">
            <input type="text" id="slabSearch" class="form-control form-control-sm"
                placeholder="Search…" style="width:220px;">
            <select id="materialFilter" class="form-select form-select-sm" style="width:150px;">
                <option value="">All materials</option>
                <option value="granite">Granite</option>
                <option value="marble">Marble</option>
                <option value="quartzite">Quartzite</option>
                <option value="quartz">Quartz</option>
                <option value="other">Other</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <?php if (empty($slabs)): ?>
            <div class="empty-state">
                <i class='bx bx-cube empty-state-icon'></i>
                <p class="empty-state-title">No slabs yet</p>
                <p class="empty-state-desc">Add your first inventory slab.</p>
                <a href="inventory-new" class="btn btn-primary btn-sm">
                    <i class='bx bx-plus'></i> Add Slab
                </a>
            </div>
        <?php else: ?>
            <table class="table table-hover mb-0" id="slabsTable">
                <thead>
                    <tr>
                        <th style="width:70px;">Image</th>
                        <th>Name</th>
                        <th>Material</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th class="text-end" style="width:110px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="slabsTbody">
                    <?php foreach ($slabs as $s):
                        $imgWebPath = '/texasspecializedquartz/' . ltrim($s['image'], '/');
                        $hasImg = !empty($s['image']) && $siteRoot && file_exists($siteRoot . '/' . ltrim($s['image'], '/'));
                    ?>
                        <tr data-material="<?= htmlspecialchars($s['material_type']) ?>">
                            <td>
                                <?php if ($hasImg): ?>
                                    <img src="<?= htmlspecialchars($imgWebPath) ?>"
                                        style="width:50px;height:40px;object-fit:cover;border-radius:4px;">
                                <?php else: ?>
                                    <div style="width:50px;height:40px;background:var(--color-bg-subdued);
                                                border-radius:4px;display:flex;align-items:center;
                                                justify-content:center;color:var(--color-text-sub);">
                                        <i class='bx bx-image'></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="inventory-edit?id=<?= $s['id'] ?>"
                                    style="font-weight:600;color:var(--color-text);">
                                    <?= htmlspecialchars($s['name']) ?>
                                </a>
                            </td>
                            <td><span class="badge badge-neutral"><?= htmlspecialchars(ucfirst($s['material_type'])) ?></span></td>
                            <td><?= htmlspecialchars($s['size']) ?></td>
                            <td>
                                <span style="font-weight:600;"><?= (int)$s['quantity'] ?></span>
                                <span style="color:var(--color-text-sub);font-size:11px;">slab<?= $s['quantity'] != 1 ? 's' : '' ?></span>
                            </td>
                            <td>
                                <?php if ($s['status'] === 'active'): ?>
                                    <span class="badge badge-success"><span class="dot"></span>Active</span>
                                <?php else: ?>
                                    <span class="badge badge-neutral"><span class="dot"></span>Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="inventory-edit?id=<?= $s['id'] ?>"
                                        class="btn btn-icon btn-sm btn-outline" title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn-icon btn-sm btn-outline text-danger delete-slab-btn"
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
