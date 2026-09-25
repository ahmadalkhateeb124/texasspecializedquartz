<?php /** Expects $job_sections, $order */ ?>
<?php if (empty($job_sections)): ?>
    <div class="card" style="border:1px solid #ffd29a;background:#fff7eb;">
        <div class="empty-state" style="padding:32px 20px;">
            <div class="empty-state-icon" style="color:#b56a00;"><i class='bx bx-error-circle'></i></div>
            <p class="empty-state-title" style="color:#b56a00;">Order missing job details</p>
            <p class="empty-state-desc">
                This order was submitted <strong>without selecting any job area</strong>
                (Kitchen, Bathroom, Master Bath, Other), so no material/edge/sink
                information is attached. Edit the order to add the missing details
                before fabrication.
            </p>
            <?php if (function_exists('isAdmin') && isAdmin() && !empty($order['id'])): ?>
                <a href="order-edit?id=<?= (int)$order['id'] ?>" class="btn btn-primary btn-sm mt-2">
                    <i class='bx bx-edit'></i> Edit order to add details
                </a>
            <?php endif; ?>
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

                <?php if (function_exists('isAdmin') && isAdmin()): ?>
                    <div class="mt-3 pt-3" style="border-top:1px dashed var(--color-border, #e5e0d8);">
                        <div style="font-size:12px;font-weight:600;color:var(--color-text-sub);margin-bottom:6px;">
                            <i class='bx bx-calendar'></i> Schedule
                        </div>
                        <?php if (empty($job['schedule_events'])): ?>
                            <p style="font-size:13px;color:var(--color-text-sub);margin:0;">No events scheduled yet.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Event</th>
                                            <th>Date &amp; Time</th>
                                            <th>Status</th>
                                            <th>Notes</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($job['schedule_events'] as $e):
                                            $color = JobScheduleRepository::eventColor($e['event_type']);
                                        ?>
                                            <tr>
                                                <td>
                                                    <span class="badge" style="background:<?= $color ?>;color:#fff;">
                                                        <?= JobScheduleRepository::EVENT_TYPES[$e['event_type']] ?? $e['event_type'] ?>
                                                    </span>
                                                </td>
                                                <td><?= date('M j, Y g:i A', strtotime($e['scheduled_date'])) ?></td>
                                                <td><?= JobScheduleRepository::statusBadge($e['status']) ?></td>
                                                <td style="max-width:200px;"><?= htmlspecialchars($e['notes'] ?? '') ?></td>
                                                <td class="text-end">
                                                    <button class="btn btn-icon btn-sm btn-outline edit-event-btn"
                                                        data-bs-toggle="modal" data-bs-target="#adminScheduleModal"
                                                        data-id="<?= (int)$e['id'] ?>"
                                                        data-event-type="<?= htmlspecialchars($e['event_type']) ?>"
                                                        data-scheduled-date="<?= htmlspecialchars(str_replace(' ', 'T', substr($e['scheduled_date'], 0, 16))) ?>"
                                                        data-status="<?= htmlspecialchars($e['status']) ?>"
                                                        data-notes="<?= htmlspecialchars($e['notes'] ?? '', ENT_QUOTES) ?>"
                                                        title="Edit">
                                                        <i class='bx bx-edit'></i>
                                                    </button>
                                                    <button class="btn btn-icon btn-sm btn-outline text-danger delete-event-btn"
                                                        data-id="<?= (int)$e['id'] ?>" title="Delete">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php elseif (function_exists('isEmployee') && isEmployee()): ?>
                    <?php $orderLocked = ($order['admin_status'] ?? '') === 'completed'; ?>
                    <div class="mt-3 pt-3" style="border-top:1px dashed var(--color-border, #e5e0d8);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div style="font-size:12px;font-weight:600;color:var(--color-text-sub);">
                                <i class='bx bx-calendar'></i> Schedule
                            </div>
                            <?php if (!$orderLocked): ?>
                                <button type="button" class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal" data-bs-target="#employeeScheduleModal"
                                    data-mode="create"
                                    data-job-section-id="<?= (int)$job['id'] ?>">
                                    <i class='bx bx-plus'></i> Add Event
                                </button>
                            <?php endif; ?>
                        </div>

                        <?php if (empty($job['schedule_events'])): ?>
                            <p style="font-size:13px;color:var(--color-text-sub);margin:0;">No events scheduled yet.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Event</th>
                                            <th>Date &amp; Time</th>
                                            <th>Status</th>
                                            <th>Notes</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($job['schedule_events'] as $e):
                                            $color = JobScheduleRepository::eventColor($e['event_type']);
                                        ?>
                                            <tr>
                                                <td>
                                                    <span class="badge" style="background:<?= $color ?>;color:#fff;">
                                                        <?= JobScheduleRepository::EVENT_TYPES[$e['event_type']] ?? $e['event_type'] ?>
                                                    </span>
                                                </td>
                                                <td><?= date('M j, Y g:i A', strtotime($e['scheduled_date'])) ?></td>
                                                <td><?= JobScheduleRepository::statusBadge($e['status']) ?></td>
                                                <td style="max-width:200px;"><?= htmlspecialchars($e['notes'] ?? '') ?></td>
                                                <td class="text-end">
                                                    <?php if (!$orderLocked): ?>
                                                        <button class="btn btn-icon btn-sm btn-outline edit-event-btn"
                                                            data-bs-toggle="modal" data-bs-target="#employeeScheduleModal"
                                                            data-mode="edit"
                                                            data-id="<?= (int)$e['id'] ?>"
                                                            data-job-section-id="<?= (int)$job['id'] ?>"
                                                            data-event-type="<?= htmlspecialchars($e['event_type']) ?>"
                                                            data-scheduled-date="<?= htmlspecialchars(str_replace(' ', 'T', substr($e['scheduled_date'], 0, 16))) ?>"
                                                            data-status="<?= htmlspecialchars($e['status']) ?>"
                                                            data-notes="<?= htmlspecialchars($e['notes'] ?? '', ENT_QUOTES) ?>"
                                                            title="Edit">
                                                            <i class='bx bx-edit'></i>
                                                        </button>
                                                        <button class="btn btn-icon btn-sm btn-outline text-danger delete-event-btn"
                                                            data-id="<?= (int)$e['id'] ?>" title="Delete">
                                                            <i class='bx bx-trash'></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <span style="color:var(--color-text-sub);font-size:12px;">—</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php elseif (function_exists('isCustomer') && isCustomer()): ?>
                    <div class="mt-3 pt-3" style="border-top:1px dashed var(--color-border, #e5e0d8);">
                        <div style="font-size:12px;font-weight:600;color:var(--color-text-sub);margin-bottom:6px;">
                            <i class='bx bx-calendar'></i> Schedule
                        </div>
                        <?php if (empty($job['schedule_events'])): ?>
                            <p style="font-size:13px;color:var(--color-text-sub);margin:0;">No events scheduled yet.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Event</th>
                                            <th>Date &amp; Time</th>
                                            <th>Status</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($job['schedule_events'] as $e):
                                            $color = JobScheduleRepository::eventColor($e['event_type']);
                                        ?>
                                            <tr>
                                                <td>
                                                    <span class="badge" style="background:<?= $color ?>;color:#fff;">
                                                        <?= JobScheduleRepository::EVENT_TYPES[$e['event_type']] ?? $e['event_type'] ?>
                                                    </span>
                                                </td>
                                                <td><?= date('M j, Y g:i A', strtotime($e['scheduled_date'])) ?></td>
                                                <td><?= JobScheduleRepository::statusBadge($e['status']) ?></td>
                                                <td style="max-width:220px;"><?= htmlspecialchars($e['notes'] ?? '') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
