<?php
$o = $order ?? [];
$v = fn(string $k): string => htmlspecialchars($o[$k] ?? '');
?>
<div class="card mb-4">
    <div class="card-header">
        <h6 class="card-title">
            <i class='bx bx-briefcase text-primary'></i> Sales Representative
        </h6>
    </div>
    <div class="card-section">
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label">Sales Rep Name <span class="text-danger">*</span></label>
                <input type="text" name="sales_rep" class="form-control"
                    placeholder="Representative name" required value="<?= $v('sales_rep') ?>">
            </div>
            <div class="col-sm-6">
                <label class="form-label">Sales Rep Phone <span class="text-danger">*</span></label>
                <input type="text" name="sales_rep_phone" class="form-control"
                    placeholder="Phone number" required value="<?= $v('sales_rep_phone') ?>">
            </div>
        </div>
    </div>
</div>
