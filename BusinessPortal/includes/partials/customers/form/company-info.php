<?php
/** Expects optional $company for prefill. */
$c = $company ?? [];
$v = fn(string $k): string => htmlspecialchars($c[$k] ?? '');
?>
<div class="card mb-4">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-buildings text-primary'></i> Company Information</h6>
    </div>
    <div class="card-section">
        <div class="mb-3">
            <label class="form-label">Company Name <span class="text-danger">*</span></label>
            <input type="text" name="company_name" class="form-control"
                placeholder="Enter company legal name" required value="<?= $v('company_name') ?>">
        </div>
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                <input type="text" name="contact_name" class="form-control"
                    placeholder="Full name" required value="<?= $v('contact_name') ?>">
            </div>
            <div class="col-sm-6">
                <label class="form-label">Contact Position</label>
                <input type="text" name="contact_position" class="form-control"
                    placeholder="Manager, Owner, Director…" value="<?= $v('contact_position') ?>">
            </div>
        </div>
    </div>
</div>
