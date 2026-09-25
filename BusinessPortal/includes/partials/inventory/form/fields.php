<?php
/**
 * Shared inventory slab form.
 * Prefills from $slab if set.
 */
$s = $slab ?? [];
$v = fn(string $k): string => htmlspecialchars((string)($s[$k] ?? ''));
$hasImage = !empty($s['image']) && file_exists(site_path(ltrim($s['image'], '/')));
$imgSrc   = $hasImage ? site_asset(ltrim($s['image'], '/')) : '';
?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title"><i class='bx bx-cube text-primary'></i> Slab Details</h6>
            </div>
            <div class="card-section">
                <div class="row g-3">
                    <div class="col-sm-12">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                            placeholder="e.g. Negresco Granite" required value="<?= $v('name') ?>">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Material type <span class="text-danger">*</span></label>
                        <select name="material_type" class="form-select" required>
                            <?php foreach (['granite','marble','quartzite','quartz','other'] as $t): ?>
                                <option value="<?= $t ?>" <?= ($s['material_type'] ?? 'granite') === $t ? 'selected' : '' ?>>
                                    <?= ucfirst($t) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Size</label>
                        <input type="text" name="size" class="form-control"
                            placeholder="e.g. 3 CM" value="<?= $v('size') ?: '3 CM' ?>">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Quantity (slabs)</label>
                        <input type="number" name="quantity" class="form-control" min="0"
                            value="<?= (int)($s['quantity'] ?? 0) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Sort order</label>
                        <input type="number" name="sort_order" class="form-control"
                            value="<?= (int)($s['sort_order'] ?? 0) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active"   <?= ($s['status'] ?? 'active') === 'active'   ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($s['status'] ?? '')       === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title"><i class='bx bx-image text-primary'></i> Image</h6>
            </div>
            <div class="card-section">
                <img id="slabPreview" src="<?= htmlspecialchars($imgSrc) ?>"
                    style="<?= $hasImage ? '' : 'display:none;' ?>width:100%;max-height:200px;
                           object-fit:cover;border-radius:6px;margin-bottom:10px;">
                <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                <div style="font-size:11px;color:var(--color-text-sub);margin-top:4px;">
                    JPG, PNG, WebP — max 10MB
                </div>
                <?php if ($hasImage): ?>
                    <div class="form-check mt-2">
                        <input type="checkbox" name="delete_image" value="1" id="deleteImg" class="form-check-input">
                        <label for="deleteImg" class="form-check-label" style="font-size:12px;">
                            Remove current image
                        </label>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-section">
                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                    <span id="submitSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                    <i class='bx bx-save' id="submitIcon"></i>
                    <?= !empty($s) ? 'Save Changes' : 'Add Slab' ?>
                </button>
                <a href="inventory" class="btn btn-default w-100 mt-2">Cancel</a>
            </div>
        </div>
    </div>
</div>
