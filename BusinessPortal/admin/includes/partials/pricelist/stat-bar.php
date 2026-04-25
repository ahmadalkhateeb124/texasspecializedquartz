<?php /** Expects: $fileCount, $accounts */ ?>
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="kpi-card d-flex justify-content-between align-items-center">
                <div>
                    <div class="kpi-label">Total Price Lists</div>
                    <div class="kpi-value"><?= $fileCount ?></div>
                </div>
                <div class="kpi-icon" style="background:var(--color-primary-l);">
                    <i class='bx bx-file' style="color:var(--color-primary);"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="kpi-card d-flex justify-content-between align-items-center">
                <div>
                    <div class="kpi-label">Active Customers</div>
                    <div class="kpi-value" style="color:var(--color-success);"><?= count($accounts) ?></div>
                </div>
                <div class="kpi-icon" style="background:var(--color-success-l);">
                    <i class='bx bx-user-check' style="color:var(--color-success);"></i>
                </div>
            </div>
        </div>
    </div>
</div>
