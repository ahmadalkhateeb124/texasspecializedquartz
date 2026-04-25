<?php /** Expects: $recentOrders */ ?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-file text-primary'></i> Recent Orders</h6>
        <a href="orders" class="btn btn-sm btn-light">All Orders</a>
    </div>
    <div class="table-responsive">
        <?php if (empty($recentOrders)): ?>
            <div class="empty-state py-4">
                <i class='bx bx-file-blank empty-state-icon' style="font-size:36px;"></i>
                <p class="empty-state-desc">No orders submitted yet.</p>
                <a href="order-new" class="btn btn-sm btn-primary">
                    <i class='bx bx-plus'></i> New Order
                </a>
            </div>
        <?php else: ?>
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Job Sections</th>
                        <th>Date Submitted</th>
                        <th>Last Updated</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $o): ?>
                        <tr>
                            <td>
                                <span style="font-weight:700;">
                                    #<?= str_pad($o['id'], 4, '0', STR_PAD_LEFT) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-primary">
                                    <?= $o['jobs'] ?> section<?= $o['jobs'] != 1 ? 's' : '' ?>
                                </span>
                            </td>
                            <td style="color:var(--muted);">
                                <?= date('M j, Y', strtotime($o['created_at'])) ?>
                            </td>
                            <td style="color:var(--muted);">
                                <?= date('M j, Y', strtotime($o['updated_at'])) ?>
                            </td>
                            <td class="text-end">
                                <a href="order-view?id=<?= $o['id'] ?>" class="btn btn-xs btn-light">
                                    <i class='bx bx-show'></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
