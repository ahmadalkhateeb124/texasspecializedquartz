<?php /** Expects $job_sections */ ?>
<?php if (empty($job_sections)): ?>
    <div class="card">
        <div class="empty-state">
            <div class="empty-state-icon"><i class='bx bx-layer'></i></div>
            <p class="empty-state-title">No job sections</p>
            <p class="empty-state-desc">This order has no job sections attached.</p>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($job_sections as $idx => $job): ?>
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title">
                    <i class='bx bx-layer text-primary'></i>
                    Job Section #<?= $idx + 1 ?>
                </h6>
            </div>
            <div class="card-section">
                <div class="row g-3">
                    <div class="col-sm-4">
                        <div style="font-size:12px;color:var(--color-text-sub);">Job Type</div>
                        <div style="font-weight:500;">
                            <?php
                            $jt = $job['job_type'];
                            echo ($jt === 'other' && !empty($job['job_type_other']))
                                ? htmlspecialchars($jt . ' – ' . $job['job_type_other'])
                                : htmlspecialchars(ucfirst($jt ?? '—'));
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div style="font-size:12px;color:var(--color-text-sub);">Material Type</div>
                        <div style="font-weight:500;">
                            <?php
                            $mt = $job['material_type'];
                            echo ($mt === 'other' && !empty($job['material_other']))
                                ? htmlspecialchars($mt . ' – ' . $job['material_other'])
                                : htmlspecialchars(ucfirst($mt ?? '—'));
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div style="font-size:12px;color:var(--color-text-sub);">Material Color</div>
                        <div><?= orderFieldOrNA($job['material_color'] ?? null) ?></div>
                    </div>
                    <div class="col-sm-4">
                        <div style="font-size:12px;color:var(--color-text-sub);">Thickness</div>
                        <div><?= orderThicknessLabel($job['thickness'] ?? '', $job['thickness_custom'] ?? null) ?></div>
                    </div>
                    <div class="col-sm-4">
                        <div style="font-size:12px;color:var(--color-text-sub);">Edge Profile</div>
                        <div>
                            <?php
                            $ep = $job['edge_profile'] ?? '';
                            echo ($ep === 'custom' && !empty($job['edge_profile_custom']))
                                ? 'Custom – ' . htmlspecialchars($job['edge_profile_custom'])
                                : orderFieldOrNA($ep ?: null);
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div style="font-size:12px;color:var(--color-text-sub);">Tear Out</div>
                        <div>
                            <?php if (isset($job['tear_out'])): ?>
                                <?php if ($job['tear_out'] === 'yes'): ?>
                                    <span class="badge badge-warning">Yes</span>
                                <?php else: ?>
                                    <span class="badge badge-neutral">No</span>
                                <?php endif; ?>
                            <?php else: echo '—'; endif; ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div style="font-size:12px;color:var(--color-text-sub);">Sink Provider</div>
                        <div><?= orderFieldOrNA($job['sink_provider'] ?? null) ?></div>
                    </div>
                    <div class="col-sm-4">
                        <div style="font-size:12px;color:var(--color-text-sub);">Sink Type</div>
                        <div><?= orderFieldOrNA($job['sink_type'] ?? null) ?></div>
                    </div>
                    <?php if (($job['sink_provider'] ?? '') === 'ts_granite'): ?>
                        <div class="col-sm-4">
                            <div style="font-size:12px;color:var(--color-text-sub);">Sink Style</div>
                            <div>
                                <?php
                                $ss = $job['sink_style'] ?? '';
                                echo ($ss === 'other' && !empty($job['sink_style_other']))
                                    ? 'Other – ' . htmlspecialchars($job['sink_style_other'])
                                    : orderFieldOrNA($ss ?: null);
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-sm-6">
                        <div style="font-size:12px;color:var(--color-text-sub);">Created</div>
                        <div style="color:var(--color-text-sub);">
                            <?= date('M j, Y H:i', strtotime($job['created_at'])) ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:12px;color:var(--color-text-sub);">Updated</div>
                        <div style="color:var(--color-text-sub);">
                            <?= date('M j, Y H:i', strtotime($job['updated_at'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
