<?php
/**
 * Customer order history — card grid.
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
        <h6 class="card-title"><i class='bx bx-grid-alt text-primary'></i> Order History</h6>
        <input type="text" id="searchInput" class="form-control form-control-sm"
            placeholder="Search…" style="width:220px;">
    </div>

    <div class="ord-grid" id="myOrdersTable">
        <?php foreach ($orders as $o):
            $jobs    = $o['jobs'];
            $n       = count($jobs);
            $orderId = str_pad($o['id'], 4, '0', STR_PAD_LEFT);
            $hasTear = false;
            foreach ($jobs as $j) { if (($j['tear_out'] ?? '') === 'Yes') { $hasTear = true; break; } }
            $searchHay = strtolower(
                "#{$o['id']} "
                . implode(' ', array_map(fn($j) => "{$j['job_type']} {$j['material_type']} {$j['material_color']} {$j['edge_profile']}", $jobs))
            );
        ?>
            <article class="ord-card" data-search="<?= htmlspecialchars($searchHay) ?>">
                <header class="ord-card-head">
                    <div class="ord-card-id">
                        <span class="ord-num">#<?= $orderId ?></span>
                        <?php if ($n > 0): ?>
                            <span class="ord-pill"><?= $n ?> section<?= $n !== 1 ? 's' : '' ?></span>
                        <?php endif; ?>
                        <?php if ($hasTear): ?>
                            <span class="ord-pill ord-pill-warn"><i class='bx bx-trash-alt'></i> Tear-out</span>
                        <?php endif; ?>
                    </div>
                    <div class="ord-card-actions">
                        <a href="order-view?id=<?= $o['id'] ?>" class="ord-iconbtn ord-iconbtn-primary" title="View order">
                            <i class='bx bx-show'></i> <span>View</span>
                        </a>
                    </div>
                </header>

                <div class="ord-card-meta">
                    <span class="ord-meta-item"><i class='bx bx-calendar'></i> <?= date('M j, Y', strtotime($o['created_at'])) ?></span>
                </div>

                <ul class="ord-jobs">
                    <?php foreach ($jobs as $idx => $j):
                        $jobType = htmlspecialchars($j['job_type']);
                        if (!empty($j['job_type_other'])) $jobType .= ' / ' . htmlspecialchars($j['job_type_other']);
                        $sinkLine = trim(($j['sink_provider'] ?? '') . ' / ' . ($j['sink_type'] ?? ''), ' /');
                        $isTear = ($j['tear_out'] ?? '') === 'Yes';
                    ?>
                        <li class="ord-job">
                            <?php if ($n > 1): ?>
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
                                    <span><i class='bx bx-ruler'></i> <?= htmlspecialchars($formatThickness($j)) ?></span>
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

    <p class="ord-empty-search" id="myOrdersEmptySearch" style="display:none;">
        <i class='bx bx-search-alt'></i> No orders match your search.
    </p>
</div>

<script>
(function () {
    var input = document.getElementById('searchInput');
    var grid  = document.getElementById('myOrdersTable');
    var note  = document.getElementById('myOrdersEmptySearch');
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
