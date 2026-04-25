<?php
/**
 * Customer info form section. Used by New + Edit pages.
 * Prefills from $order if set.
 */
$o = $order ?? [];
$v = fn(string $k): string => htmlspecialchars($o[$k] ?? '');
?>
<div class="card mb-4">
    <div class="card-header">
        <h6 class="card-title">
            <i class='bx bx-user text-primary'></i> Customer Information
        </h6>
    </div>
    <div class="card-section">
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                <input type="text" name="customer_name" class="form-control"
                    placeholder="Full name" maxlength="100" required value="<?= $v('customer_name') ?>">
            </div>
            <div class="col-sm-6">
                <label class="form-label">Customer Phone</label>
                <input type="tel" name="phone" class="form-control"
                    placeholder="(123) 456-7890" value="<?= $v('phone') ?>">
            </div>
            <div class="col-sm-8">
                <label class="form-label">Street Address <span class="text-danger">*</span></label>
                <input type="text" name="address" class="form-control"
                    placeholder="Full street address" required value="<?= $v('address') ?>">
            </div>
            <div class="col-sm-4">
                <label class="form-label">City <span class="text-danger">*</span></label>
                <input type="text" name="city" class="form-control" placeholder="City" required
                    value="<?= $v('city') ?>">
            </div>
            <div class="col-sm-4">
                <label class="form-label">ZIP Code <span class="text-danger">*</span></label>
                <input type="text" name="ZipCode" class="form-control" placeholder="e.g. 78201" required
                    value="<?= $v('zip_code') ?>">
            </div>
            <div class="col-sm-4">
                <label class="form-label">PO Number</label>
                <input type="text" name="po_number" class="form-control" placeholder="Optional"
                    value="<?= $v('po_number') ?>">
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</div>
