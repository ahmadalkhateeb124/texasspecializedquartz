<?php /** Expects: $employees, $counts */ ?>
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-4">
        <div class="card">
            <div class="kpi-card">
                <div class="kpi-label">Total Employees</div>
                <div class="kpi-value"><?= count($employees) ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-4">
        <div class="card">
            <div class="kpi-card d-flex justify-content-between align-items-center">
                <div>
                    <div class="kpi-label">Active</div>
                    <div class="kpi-value" style="color:var(--color-success);"><?= $counts['active'] ?></div>
                </div>
                <div class="kpi-icon" style="background:var(--color-success-l);">
                    <i class='bx bx-check-circle' style="color:var(--color-success);"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-4">
        <div class="card">
            <div class="kpi-card d-flex justify-content-between align-items-center">
                <div>
                    <div class="kpi-label">Inactive</div>
                    <div class="kpi-value" style="color:var(--color-text-sub);"><?= $counts['inactive'] ?></div>
                </div>
                <div class="kpi-icon" style="background:var(--color-bg-subdued);">
                    <i class='bx bx-user-x' style="color:var(--color-text-sub);"></i>
                </div>
            </div>
        </div>
    </div>
</div>
