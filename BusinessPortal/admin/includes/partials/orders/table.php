<?php
/**
 * Orders — card grid (admin).
 * Expects: $orders (grouped from fetchOrdersGrouped()).
 */
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-grid-alt text-primary'></i> All Orders</h6>
        <div class="d-flex gap-2 align-items-center">
            <input type="text" id="tableSearch" class="form-control form-control-sm"
                placeholder="Search orders…" style="width:240px;">
        </div>
    </div>

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
        <div class="ord-grid" id="ordersTable">
            <?php foreach ($orders as $order):
                $jobs     = $order['jobs'];
                $jobCount = count($jobs);
                $orderId  = str_pad($order['id'], 4, '0', STR_PAD_LEFT);
                $hasTear  = false;
                foreach ($jobs as $j) { if (($j['tear_out'] ?? '') === 'Yes') { $hasTear = true; break; } }
                $searchHay = strtolower(
                    "#{$order['id']} {$order['company_name']} "
                    . implode(' ', array_map(fn($j) => "{$j['job_type']} {$j['material_type']} {$j['material_color']} {$j['edge_profile']}", $jobs))
                );
            ?>
                <article class="ord-card" data-search="<?= htmlspecialchars($searchHay) ?>">
                    <header class="ord-card-head">
                        <div class="ord-card-id">
                            <span class="ord-num">#<?= $orderId ?></span>
                            <?php if ($jobCount > 0): ?>
                                <span class="ord-pill"><?= $jobCount ?> job<?= $jobCount > 1 ? 's' : '' ?></span>
                            <?php endif; ?>
                            <?php if ($hasTear): ?>
                                <span class="ord-pill ord-pill-warn"><i class='bx bx-trash-alt'></i> Tear-out</span>
                            <?php endif; ?>
                        </div>
                        <div class="ord-card-actions">
                            <a href="order-view?id=<?= $order['id'] ?>" class="ord-iconbtn" title="View" aria-label="View">
                                <i class='bx bx-show'></i>
                            </a>
                            <a href="order-edit?id=<?= $order['id'] ?>" class="ord-iconbtn" title="Edit" aria-label="Edit">
                                <i class='bx bx-edit'></i>
                            </a>
                            <button type="button"
                                class="ord-iconbtn ord-iconbtn-danger delete-btn"
                                data-id="<?= $order['id'] ?>"
                                data-label="Order #<?= $orderId ?>"
                                title="Delete" aria-label="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </header>

                    <div class="ord-card-meta">
                        <span class="ord-meta-item"><i class='bx bx-buildings'></i> <?= htmlspecialchars($order['company_name']) ?></span>
                        <span class="ord-meta-sep">·</span>
                        <span class="ord-meta-item"><i class='bx bx-calendar'></i> <?= date('M j, Y', strtotime($order['created_at'])) ?></span>
                    </div>

                    <ul class="ord-jobs">
                        <?php foreach ($jobs as $idx => $j):
                            $jobType = htmlspecialchars($j['job_type']);
                            if (!empty($j['job_type_other'])) $jobType .= ' / ' . htmlspecialchars($j['job_type_other']);
                            $sinkLine = trim(($j['sink_provider'] ?? '') . ' / ' . ($j['sink_type'] ?? ''), ' /');
                            $isTear = ($j['tear_out'] ?? '') === 'Yes';
                        ?>
                            <li class="ord-job">
                                <?php if ($jobCount > 1): ?>
                                    <span class="ord-job-num"><?= $idx + 1 ?></span>
                                <?php endif; ?>
                                <div class="ord-job-body">
                                    <div class="ord-job-title">
                                        <strong><?= $jobType ?></strong>
                                        <?php if ($isTear): ?>
                                            <span class="ord-tag ord-tag-warn">Tear-out</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ord-job-specs">
                                        <span><i class='bx bx-cube'></i> <?= htmlspecialchars($j['material_type']) ?></span>
                                        <?php if (!empty($j['material_color'])): ?>
                                            <span><i class='bx bx-palette'></i> <?= htmlspecialchars($j['material_color']) ?></span>
                                        <?php endif; ?>
                                        <span><i class='bx bx-ruler'></i> <?= htmlspecialchars(formatThickness($j['thickness'], $j['thickness_custom'] ?? null)) ?></span>
                                        <?php if (!empty($j['edge_profile'])): ?>
                                            <span><i class='bx bx-stop'></i> <?= htmlspecialchars($j['edge_profile']) ?></span>
                                        <?php endif; ?>
                                        <?php if ($sinkLine): ?>
                                            <span><i class='bx bx-water'></i> <?= htmlspecialchars($sinkLine) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            <?php endforeach; ?>
        </div>

        <p class="ord-empty-search" id="ordersEmptySearch" style="display:none;">
            <i class='bx bx-search-alt'></i> No orders match your search.
        </p>
    <?php endif; ?>
</div>

<script>
(function () {
    var input = document.getElementById('tableSearch');
    var grid  = document.getElementById('ordersTable');
    var note  = document.getElementById('ordersEmptySearch');
    if (!input || !grid) return;

    input.addEventListener('input', function () {
        var q = this.value.trim().toLowerCase();
        var visible = 0;
        grid.querySelectorAll('.ord-card').forEach(function (card) {
            var match = !q || (card.dataset.search || '').indexOf(q) !== -1;
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        if (note) note.style.display = visible === 0 ? 'block' : 'none';
    });
})();
</script>
