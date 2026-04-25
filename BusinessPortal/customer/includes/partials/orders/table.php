<?php
/**
 * Customer order history table.
 * Expects: $orders.
 */

$formatThickness = function (array $j): string {
    if ($j['thickness'] === '2cm') return '2 Cm';
    if ($j['thickness'] === '3cm') return '3 Cm';
    if ($j['thickness'] === 'custom' && !empty($j['thickness_custom'])) return $j['thickness_custom'] . ' Cm';
    return (string)$j['thickness'];
};
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-file text-primary'></i> Order History</h6>
        <input type="text" id="searchInput" class="form-control form-control-sm"
            placeholder="Search…" style="width:200px;">
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" id="myOrdersTable">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Job Type</th>
                    <th>Material</th>
                    <th>Color</th>
                    <th>Thickness</th>
                    <th>Edge Profile</th>
                    <th>Sink</th>
                    <th>Tear Out</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o):
                    $jobs = $o['jobs'];
                    $n    = count($jobs);
                ?>
                    <tr>
                        <td>
                            <span style="font-weight:700;">#<?= str_pad($o['id'], 4, '0', STR_PAD_LEFT) ?></span>
                            <?php if ($n > 0): ?>
                                <span class="badge badge-primary ms-1"><?= $n ?> section<?= $n !== 1 ? 's' : '' ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php foreach ($jobs as $j) {
                                $t = htmlspecialchars($j['job_type']);
                                if (!empty($j['job_type_other'])) $t .= ' / ' . htmlspecialchars($j['job_type_other']);
                                echo $t . '<br>';
                            } ?></td>
                        <td><?php foreach ($jobs as $j) echo htmlspecialchars($j['material_type']) . '<br>'; ?></td>
                        <td><?php foreach ($jobs as $j) echo htmlspecialchars($j['material_color']) . '<br>'; ?></td>
                        <td><?php foreach ($jobs as $j) echo htmlspecialchars($formatThickness($j)) . '<br>'; ?></td>
                        <td><?php foreach ($jobs as $j) echo htmlspecialchars($j['edge_profile']) . '<br>'; ?></td>
                        <td><?php foreach ($jobs as $j) {
                                $s = trim($j['sink_provider'] . ' / ' . $j['sink_type'], ' /');
                                echo htmlspecialchars($s ?: '-') . '<br>';
                            } ?></td>
                        <td>
                            <?php foreach ($jobs as $j): ?>
                                <span class="badge <?= $j['tear_out'] === 'Yes' ? 'badge-warning' : 'badge-muted' ?>">
                                    <?= htmlspecialchars($j['tear_out'] ?: '—') ?>
                                </span><br>
                            <?php endforeach; ?>
                        </td>
                        <td style="white-space:nowrap;color:var(--muted);">
                            <?= date('M j, Y', strtotime($o['created_at'])) ?>
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
    </div>
</div>
