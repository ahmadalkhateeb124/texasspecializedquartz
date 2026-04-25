<?php /** Expects: $totalOrders, $monthlyOrders, $weeklyOrders, $customerMeta */ ?>
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-value"><?= number_format($totalOrders) ?></div>
                    </div>
                    <div class="stat-icon" style="background:var(--primary-light);">
                        <i class='bx bx-file' style="color:var(--primary);"></i>
                    </div>
                </div>
                <div class="stat-footer">All orders submitted</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">This Month</div>
                        <div class="stat-value"><?= number_format($monthlyOrders) ?></div>
                    </div>
                    <div class="stat-icon" style="background:var(--success-light);">
                        <i class='bx bx-calendar-check' style="color:var(--success);"></i>
                    </div>
                </div>
                <div class="stat-footer"><?= date('F Y') ?></div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Last 7 Days</div>
                        <div class="stat-value"><?= number_format($weeklyOrders) ?></div>
                    </div>
                    <div class="stat-icon" style="background:var(--warning-light);">
                        <i class='bx bx-trending-up' style="color:var(--warning);"></i>
                    </div>
                </div>
                <div class="stat-footer">Recent activity</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="stat-label">Account Since</div>
                        <div class="stat-value" style="font-size:17px;">
                            <?= !empty($customerMeta['created_at'])
                                ? date('M Y', strtotime($customerMeta['created_at']))
                                : '—' ?>
                        </div>
                    </div>
                    <div class="stat-icon" style="background:var(--info-light);">
                        <i class='bx bx-buildings' style="color:var(--info);"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <?= !empty($customerMeta['company_name'])
                        ? htmlspecialchars($customerMeta['company_name'])
                        : 'Customer account' ?>
                </div>
            </div>
        </div>
    </div>
</div>
