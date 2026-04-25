<?php
/**
 * Orders summary stat bar.
 * Expects: $stats (array from summarizeOrders()).
 */
?>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center" style="padding:14px 10px;">
            <div style="font-size:22px;font-weight:800;color:var(--text);"><?= $stats['total'] ?></div>
            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Total Orders</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center" style="padding:14px 10px;">
            <div style="font-size:22px;font-weight:800;color:var(--primary);"><?= $stats['this_month'] ?></div>
            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">This Month</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center" style="padding:14px 10px;">
            <div style="font-size:22px;font-weight:800;color:var(--color-primary);"><?= $stats['total_jobs'] ?></div>
            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Job Sections</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center" style="padding:14px 10px;">
            <div style="font-size:22px;font-weight:800;color:var(--warning);"><?= $stats['this_week'] ?></div>
            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Last 7 Days</div>
        </div>
    </div>
</div>
