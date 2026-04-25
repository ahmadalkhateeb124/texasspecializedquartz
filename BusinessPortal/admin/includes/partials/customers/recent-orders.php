<?php /** Expects: $recentOrders */ ?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-file text-primary'></i> Recent Orders</h6>
        <span class="badge badge-neutral"><?= count($recentOrders) ?></span>
    </div>

    <?php if (empty($recentOrders)): ?>
        <div class="empty-state" style="padding:2rem;">
            <div class="empty-state-icon"><i class='bx bx-file'></i></div>
            <p class="empty-state-title">No orders yet</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer Name</th>
                        <th>City</th>
                        <th>Sales Rep</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $ord): ?>
                        <tr>
                            <td style="font-weight:600;">#<?= $ord['id'] ?></td>
                            <td><?= htmlspecialchars($ord['customer_name']) ?></td>
                            <td style="color:var(--color-text-sub);"><?= htmlspecialchars($ord['city'] ?? '—') ?></td>
                            <td style="color:var(--color-text-sub);"><?= htmlspecialchars($ord['sales_rep'] ?? '—') ?></td>
                            <td style="color:var(--color-text-sub);white-space:nowrap;">
                                <?= date('M j, Y', strtotime($ord['created_at'])) ?>
                            </td>
                            <td class="text-end">
                                <a href="order-view?id=<?= $ord['id'] ?>"
                                    class="btn btn-icon btn-sm btn-outline" title="View">
                                    <i class='bx bx-show'></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
