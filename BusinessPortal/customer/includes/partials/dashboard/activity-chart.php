<?php /** Expects: $totalOrders */ ?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-line-chart text-primary'></i> Order Activity (30 Days)</h6>
        <a href="orders" class="btn btn-sm btn-light">View All</a>
    </div>
    <div class="card-body">
        <?php if ($totalOrders === 0): ?>
            <div class="empty-state">
                <i class='bx bx-bar-chart-alt-2 empty-state-icon'></i>
                <p class="empty-state-title">No orders yet</p>
                <p class="empty-state-desc">Submit your first fabrication order to get started.</p>
                <a href="order-new" class="btn btn-sm btn-primary">
                    <i class='bx bx-plus'></i> Create Order
                </a>
            </div>
        <?php else: ?>
            <div id="activityChart" style="min-height:220px;"></div>
        <?php endif; ?>
    </div>
</div>
