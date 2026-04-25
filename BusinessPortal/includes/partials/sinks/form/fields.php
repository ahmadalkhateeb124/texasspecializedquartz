<?php
/**
 * Shared sink form used by sinks-new and sinks-edit.
 * Prefills from $sink if set.
 */
$s = $sink ?? [];
$v = fn(string $k): string => htmlspecialchars($s[$k] ?? '');
$siteRoot  = realpath(__DIR__ . '/../../../../..');
$hasImage  = !empty($s['image']) && $siteRoot && file_exists($siteRoot . '/' . ltrim($s['image'], '/'));
$imgWebSrc = $hasImage ? '/texasspecializedquartz/' . ltrim($s['image'], '/') : '';
?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title"><i class='bx bx-grid-alt text-primary'></i> Sink Details</h6>
            </div>
            <div class="card-section">
                <div class="row g-3">
                    <div class="col-sm-8">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                            placeholder="e.g. LS-78 Single Bowl Kitchen Sink" required value="<?= $v('name') ?>">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Model / Code</label>
                        <input type="text" name="model" class="form-control"
                            placeholder="e.g. LS-78" value="<?= $v('model') ?>">
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <?php foreach (['kitchen','bathroom','bar','laundry'] as $c): ?>
                                <option value="<?= $c ?>" <?= ($s['category'] ?? 'kitchen') === $c ? 'selected' : '' ?>>
                                    <?= ucfirst($c) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Subcategory <span class="text-danger">*</span></label>
                        <input type="text" name="subcategory" class="form-control"
                            placeholder="e.g. Undermount Standard Sinks" required value="<?= $v('subcategory') ?>">
                    </div>

                    <div class="col-sm-6">
                        <label class="form-label">Sort order</label>
                        <input type="number" name="sort_order" class="form-control"
                            value="<?= (int)($s['sort_order'] ?? 0) ?>">
                    </div>
                    <div class="col-sm-6">
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
                <img id="sinkPreview" src="<?= htmlspecialchars($imgWebSrc) ?>"
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
                    <?= !empty($s) ? 'Save Changes' : 'Add Sink' ?>
                </button>
                <a href="sinks" class="btn btn-default w-100 mt-2">Cancel</a>
            </div>
        </div>
    </div>
</div>
