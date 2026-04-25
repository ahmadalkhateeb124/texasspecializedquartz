<?php
$c = $company ?? [];
$v = fn(string $k): string => htmlspecialchars($c[$k] ?? '');
?>
<div class="card mb-4">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-map text-primary'></i> Address</h6>
    </div>
    <div class="card-section">
        <div class="mb-3">
            <label class="form-label">Street Address</label>
            <input type="text" name="address" class="form-control"
                placeholder="123 Main Street" value="<?= $v('address') ?>">
        </div>
        <div class="row g-3">
            <div class="col-sm-4">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" placeholder="City" value="<?= $v('city') ?>">
            </div>
            <div class="col-sm-4">
                <label class="form-label">State</label>
                <input type="text" name="state" class="form-control" placeholder="TX" value="<?= $v('state') ?>">
            </div>
            <div class="col-sm-4">
                <label class="form-label">ZIP Code</label>
                <input type="text" name="zip_code" class="form-control" placeholder="78201" value="<?= $v('zip_code') ?>">
            </div>
        </div>
    </div>
</div>
