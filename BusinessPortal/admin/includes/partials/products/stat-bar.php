<?php /** Expects: $products, $counts */ ?>
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="kpi-card">
                <div class="kpi-label">Total Remnants</div>
                <div class="kpi-value"><?= count($products) ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="kpi-card d-flex justify-content-between align-items-center">
                <div>
                    <div class="kpi-label">Available</div>
                    <div class="kpi-value" style="color:var(--color-success);"><?= $counts['available'] ?></div>
                </div>
                <div class="kpi-icon" style="background:var(--color-success-l);">
                    <i class='bx bx-check-circle' style="color:var(--color-success);"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="kpi-card d-flex justify-content-between align-items-center">
                <div>
                    <div class="kpi-label">Reserved</div>
                    <div class="kpi-value" style="color:var(--color-warning);"><?= $counts['reserved'] ?></div>
                </div>
                <div class="kpi-icon" style="background:var(--color-warning-l);">
                    <i class='bx bx-time' style="color:var(--color-warning);"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card">
            <div class="kpi-card d-flex justify-content-between align-items-center">
                <div>
                    <div class="kpi-label">Sold Out</div>
                    <div class="kpi-value" style="color:var(--color-critical);"><?= $counts['sold_out'] ?></div>
                </div>
                <div class="kpi-icon" style="background:var(--color-critical-l);">
                    <i class='bx bx-x-circle' style="color:var(--color-critical);"></i>
                </div>
            </div>
        </div>
    </div>
</div>
