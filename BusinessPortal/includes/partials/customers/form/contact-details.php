<?php
$c = $company ?? [];
$v = fn(string $k): string => htmlspecialchars($c[$k] ?? '');
?>
<div class="card mb-4">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-phone text-primary'></i> Contact Details</h6>
    </div>
    <div class="card-section">
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                <input type="tel" name="phone" class="form-control"
                    placeholder="+1 (555) 000-0000" required value="<?= $v('phone') ?>">
            </div>
            <div class="col-sm-6">
                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control"
                    placeholder="company@example.com" required value="<?= $v('email') ?>">
            </div>
        </div>
    </div>
</div>
