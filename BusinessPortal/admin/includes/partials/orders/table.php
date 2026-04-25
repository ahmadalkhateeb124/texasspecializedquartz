<?php
/**
 * Orders table.
 * Expects: $orders (grouped orders from fetchOrdersGrouped()).
 */
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-file text-primary'></i> All Orders</h6>
        <div class="d-flex gap-2">
            <input type="text" id="tableSearch" class="form-control form-control-sm"
                placeholder="Search orders…" style="width:220px;">
        </div>
    </div>
    <div class="table-responsive">
        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <i class='bx bx-file-blank empty-state-icon'></i>
                <p class="empty-state-title">No orders yet</p>
                <p class="empty-state-desc">Start by creating your first fabrication order.</p>
                <a href="order-new" class="btn btn-primary btn-sm">
                    <i class='bx bx-plus'></i> Create Order
                </a>
            </div>
        <?php else: ?>
            <table class="table table-hover mb-0" id="ordersTable">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Company</th>
                        <th>Job Type</th>
                        <th>Material</th>
                        <th>Color</th>
                        <th>Thickness</th>
                        <th>Edge Profile</th>
                        <th>Sink</th>
                        <th>Tear Out</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <?php
                        $jobs     = $order['jobs'];
                        $jobCount = count($jobs);
                        ?>
                        <tr>
                            <td>
                                <span style="font-weight:700;font-size:13px;">
                                    #<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?>
                                </span>
                                <?php if ($jobCount > 0): ?>
                                    <span class="badge badge-primary ms-1"><?= $jobCount ?> job<?= $jobCount > 1 ? 's' : '' ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($order['company_name']) ?></td>

                            <td>
                                <?php foreach ($jobs as $j):
                                    $jt = htmlspecialchars($j['job_type']);
                                    if (!empty($j['job_type_other'])) $jt .= ' / ' . htmlspecialchars($j['job_type_other']);
                                    echo $jt . '<br>';
                                endforeach; ?>
                            </td>

                            <td>
                                <?php foreach ($jobs as $j): ?>
                                    <?= htmlspecialchars($j['material_type']) ?><br>
                                <?php endforeach; ?>
                            </td>

                            <td>
                                <?php foreach ($jobs as $j): ?>
                                    <?= htmlspecialchars($j['material_color']) ?><br>
                                <?php endforeach; ?>
                            </td>

                            <td>
                                <?php foreach ($jobs as $j): ?>
                                    <?= htmlspecialchars(formatThickness($j['thickness'], $j['thickness_custom'] ?? null)) ?><br>
                                <?php endforeach; ?>
                            </td>

                            <td>
                                <?php foreach ($jobs as $j): ?>
                                    <?= htmlspecialchars($j['edge_profile']) ?><br>
                                <?php endforeach; ?>
                            </td>

                            <td>
                                <?php foreach ($jobs as $j):
                                    $sink = trim($j['sink_provider'] . ' / ' . $j['sink_type'], ' /');
                                ?>
                                    <?= htmlspecialchars($sink ?: '—') ?><br>
                                <?php endforeach; ?>
                            </td>

                            <td>
                                <?php foreach ($jobs as $j): ?>
                                    <span class="badge <?= $j['tear_out'] === 'Yes' ? 'badge-warning' : 'badge-muted' ?>">
                                        <?= htmlspecialchars($j['tear_out'] ?: '—') ?>
                                    </span><br>
                                <?php endforeach; ?>
                            </td>

                            <td style="white-space:nowrap;color:var(--muted);">
                                <?= date('M j, Y', strtotime($order['created_at'])) ?>
                            </td>

                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="order-view?id=<?= $order['id'] ?>"
                                        class="btn btn-xs btn-light" title="view">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    <a href="order-edit?id=<?= $order['id'] ?>"
                                        class="btn btn-xs btn-light" title="Edit">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn-xs btn-light text-danger delete-btn"
                                        data-id="<?= $order['id'] ?>"
                                        data-label="Order #<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?>"
                                        title="Delete">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
