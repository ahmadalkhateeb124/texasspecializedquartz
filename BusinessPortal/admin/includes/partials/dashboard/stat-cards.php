<?php /** Expects: $totalOrders, $totalProducts, $totalCustomers, $monthlyOrders, $avail */
$pct = $totalOrders > 0 ? round(($monthlyOrders / $totalOrders) * 100, 1) : 0;
?>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-value"><?= number_format($totalOrders) ?></div>
                    </div>
                    <div class="stat-icon" style="background:var(--color-success-l);">
                        <i class='bx bx-file' style="color:var(--primary);"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="stat-up"><i class='bx bx-trending-up'></i> <?= number_format($monthlyOrders) ?></span>
                    <span class="ms-1">orders this month</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Total Products</div>
                        <div class="stat-value"><?= number_format($totalProducts) ?></div>
                    </div>
                    <div class="stat-icon" style="background:var(--color-primary-l);">
                        <i class='bx bx-cube-alt' style="color:var(--color-primary-h);"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span style="color:var(--success);font-weight:600;"><?= $avail['available'] ?></span> available ·
                    <span style="color:var(--warning);font-weight:600;"><?= $avail['reserved'] ?></span> reserved ·
                    <span style="color:var(--danger);font-weight:600;"><?= $avail['sold_out'] ?></span> sold
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Active Customers</div>
                        <div class="stat-value"><?= number_format($totalCustomers) ?></div>
                    </div>
                    <div class="stat-icon" style="background:var(--color-success-l);">
                        <i class='bx bx-group' style="color:var(--info);"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <a href="customers" style="font-size:12px;">View all customers →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">This Month</div>
                        <div class="stat-value"><?= number_format($monthlyOrders) ?></div>
                    </div>
                    <div class="stat-icon" style="background:var(--color-warning-l);">
                        <i class='bx bx-calendar' style="color:var(--warning);"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span style="font-weight:600;"><?= $pct ?>%</span>
                    <span class="ms-1">of all-time orders</span>
                </div>
            </div>
        </div>
    </div>
</div>
