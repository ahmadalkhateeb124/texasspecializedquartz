<?php /** Expects: $totalProducts */ ?>
<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title"><i class='bx bx-bar-chart-alt-2 text-primary'></i> Orders — Last 7 Days</h6>
            </div>
            <div class="card-body">
                <div id="chartOrders" style="min-height:260px;"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title"><i class='bx bx-pie-chart-alt text-primary'></i> Product Status</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <?php if ($totalProducts > 0): ?>
                    <div id="chartAvail" style="min-height:260px; width:100%;"></div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class='bx bx-cube-alt empty-state-icon'></i>
                        <p class="empty-state-desc">No products yet</p>
                        <a href="products-new" class="btn btn-sm btn-primary">Add Product</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
