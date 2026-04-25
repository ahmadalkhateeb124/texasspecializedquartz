<?php /** Expects: $recentOrders */ ?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-list-ul text-primary'></i> Recent Orders</h6>
        <a href="orders" class="btn btn-sm btn-light">View all</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Company</th>
                    <th>Job Sections</th>
                    <th>Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentOrders)): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state py-4">
                                <i class='bx bx-file-blank empty-state-icon' style="font-size:36px;"></i>
                                <p class="empty-state-desc">No orders found. Create your first order!</p>
                                <a href="orders" class="btn btn-sm btn-primary">
                                    <i class='bx bx-plus'></i> New Order
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentOrders as $o): ?>
                        <tr>
                            <td>
                                <span class="fw-600" style="font-size:13px;font-weight:600;">
                                    #<?= str_pad($o['id'], 4, '0', STR_PAD_LEFT) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($o['company_name'] ?? '—') ?></td>
                            <td>
                                <span class="badge badge-primary">
                                    <?= $o['jobs'] ?> section<?= $o['jobs'] != 1 ? 's' : '' ?>
                                </span>
                            </td>
                            <td style="color:var(--muted);">
                                <?= date('M j, Y', strtotime($o['created_at'])) ?>
                            </td>
                            <td class="text-end">
                                <a href="order-view?id=<?= $o['id'] ?>"
                                    class="btn btn-xs btn-light" title="View">
                                    <i class='bx bx-show'></i>
                                </a>
                                <a href="order-edit?id=<?= $o['id'] ?>"
                                    class="btn btn-xs btn-light ms-1" title="Edit">
                                    <i class='bx bx-edit'></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
