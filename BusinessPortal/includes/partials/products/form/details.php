<?php
/** Expects optional $product for prefill. */
$p = $product ?? [];
$v = fn(string $k): string => htmlspecialchars((string)($p[$k] ?? ''));
$avail = $p['availability'] ?? 'Available in store';
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-detail text-primary'></i> Remnants Details</h6>
    </div>
    <div class="card-section">
        <div class="mb-3">
            <label class="form-label">Product Title <span class="text-danger">*</span></label>
            <input type="text" name="product_title" class="form-control"
                placeholder="e.g. Calacatta White 3cm Slab" required value="<?= $v('title') ?>">
        </div>

        <div class="row g-3">
            <div class="col-sm-12">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" name="Quantity" class="form-control"
                    placeholder="0" min="0" required value="<?= $v('quantity') ?>">
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-sm-6">
                <label class="form-label">Color Name <span class="text-danger">*</span></label>
                <input type="text" name="ColorName" class="form-control"
                    placeholder="e.g. Calacatta White" required value="<?= $v('color_name') ?>">
            </div>
            <div class="col-sm-6">
                <label class="form-label">Size <span class="text-danger">*</span></label>
                <input type="text" name="Size" class="form-control"
                    placeholder="e.g. 126 x 63" required value="<?= $v('size') ?>">
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label">Availability</label>
            <select name="availability" class="form-select">
                <?php foreach (['Available in store', 'Reserved', 'Sold Out'] as $opt): ?>
                    <option value="<?= $opt ?>" <?= $avail === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>
