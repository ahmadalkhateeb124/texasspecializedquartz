<?php
/**
 * Customer orders summary stat bar.
 * Expects: $orders (grouped orders).
 */
$thisMonth    = array_filter($orders, fn($o) => date('Y-m', strtotime($o['created_at'])) === date('Y-m'));
$thisWeek     = array_filter($orders, fn($o) => strtotime($o['created_at']) >= strtotime('-7 days'));
$totalSections = array_sum(array_map(fn($o) => count($o['jobs']), $orders));
?>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center" style="padding:12px 10px;">
            <div style="font-size:20px;font-weight:800;color:var(--text);"><?= count($orders) ?></div>
            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Total Orders</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center" style="padding:12px 10px;">
            <div style="font-size:20px;font-weight:800;color:var(--primary);"><?= count($thisMonth) ?></div>
            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">This Month</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center" style="padding:12px 10px;">
            <div style="font-size:20px;font-weight:800;color:var(--success);"><?= $totalSections ?></div>
            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Job Sections</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center" style="padding:12px 10px;">
            <div style="font-size:20px;font-weight:800;color:var(--warning);"><?= count($thisWeek) ?></div>
            <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">Last 7 Days</div>
        </div>
    </div>
</div>
