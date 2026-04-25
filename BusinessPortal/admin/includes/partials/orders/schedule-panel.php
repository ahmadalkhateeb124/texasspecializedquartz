<?php
/**
 * Admin schedule panel for an order.
 * Expects: $order (with id), $job_sections, $pdo
 */
$scheduleRepo = new JobScheduleRepository($pdo);
$scheduleEvents = $scheduleRepo->forOrder((int)$order['id']);
$adminStatus    = $scheduleRepo->orderStatus((int)$order['id']);
$csrfToken      = csrfToken();
?>
<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="card-title mb-0">
            <i class='bx bx-calendar text-primary'></i> Schedule &amp; Status
        </h6>
        <button type="button" class="btn btn-primary btn-sm"
            data-bs-toggle="modal" data-bs-target="#scheduleModal"
            data-mode="create" data-order-id="<?= (int)$order['id'] ?>">
            <i class='bx bx-plus'></i> Add Event
        </button>
    </div>
    <div class="card-section">

        <div class="overall-status-wrap mb-4" data-order-id="<?= (int)$order['id'] ?>">
            <div class="overall-status-label">
                <i class='bx bx-flag'></i> Overall Order Status
            </div>
            <div class="overall-status-pills">
                <?php
                $stInfo = [
                    'new'         => ['bx-file',         '#6b7280'],
                    'scheduled'   => ['bx-calendar',     '#3b82f6'],
                    'in_progress' => ['bx-loader-alt',   '#f59e0b'],
                    'completed'   => ['bx-check-circle', '#10b981'],
                    'cancelled'   => ['bx-x-circle',     '#dc2626'],
                ];
                $stLabels = ['new'=>'New','scheduled'=>'Scheduled','in_progress'=>'In Progress','completed'=>'Completed','cancelled'=>'Cancelled'];
                foreach ($stLabels as $k => $lbl):
                    [$icon, $clr] = $stInfo[$k];
                    $active = $adminStatus === $k;
                ?>
                    <button type="button"
                        class="status-pill <?= $active ? 'is-active' : '' ?>"
                        data-status="<?= $k ?>"
                        style="--clr:<?= $clr ?>;">
                        <i class='bx <?= $icon ?>'></i>
                        <span><?= $lbl ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <style>
            .overall-status-wrap {
                padding: 14px 16px;
                background: var(--surface-muted, #fafafa);
                border: 1px solid var(--border);
                border-radius: 12px;
            }
            .overall-status-label {
                font-size: 12px;
                font-weight: 600;
                color: var(--text);
                letter-spacing: .02em;
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            .overall-status-label i { font-size: 15px; color: var(--brand); }
            .overall-status-pills {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
            }
            .status-pill {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 14px;
                border-radius: 999px;
                border: 1.5px solid var(--border);
                background: var(--surface);
                font-size: 12px;
                font-weight: 500;
                color: var(--text-sub);
                cursor: pointer;
                transition: all .15s ease;
            }
            .status-pill i { font-size: 15px; color: var(--clr); }
            .status-pill:hover {
                border-color: var(--clr);
                color: var(--text);
            }
            .status-pill.is-active {
                background: var(--clr);
                border-color: var(--clr);
                color: #fff;
                box-shadow: 0 2px 8px color-mix(in srgb, var(--clr) 35%, transparent);
            }
            .status-pill.is-active i { color: #fff; }
        </style>

        <?php if (empty($scheduleEvents)): ?>
            <div class="empty-state" style="padding:30px 10px;">
                <i class='bx bx-calendar empty-state-icon' style="font-size:28px;"></i>
                <p class="empty-state-title" style="font-size:14px;">No events scheduled</p>
                <p class="empty-state-desc">Add inspection / template / install dates for this order.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Job</th>
                            <th>Date &amp; Time</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($scheduleEvents as $e):
                        $etype = $e['event_type'];
                        $color = JobScheduleRepository::eventColor($etype);
                        $jobLbl= $e['job_type'] === 'other' && !empty($e['job_type_other'])
                                    ? $e['job_type_other']
                                    : ucfirst((string)$e['job_type']);
                    ?>
                        <tr data-schedule-id="<?= (int)$e['id'] ?>">
                            <td>
                                <span class="badge" style="background:<?= $color ?>;color:#fff;">
                                    <?= JobScheduleRepository::EVENT_TYPES[$etype] ?? $etype ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($jobLbl) ?></td>
                            <td><?= date('M j, Y g:i A', strtotime($e['scheduled_date'])) ?></td>
                            <td><?= JobScheduleRepository::statusBadge($e['status']) ?></td>
                            <td style="max-width:200px;"><?= htmlspecialchars($e['notes'] ?? '') ?></td>
                            <td class="text-end">
                                <button class="btn btn-icon btn-sm btn-outline edit-sched-btn"
                                    data-bs-toggle="modal" data-bs-target="#scheduleModal"
                                    data-mode="edit"
                                    data-id="<?= (int)$e['id'] ?>"
                                    data-job-section-id="<?= (int)$e['job_section_id'] ?>"
                                    data-event-type="<?= htmlspecialchars($etype) ?>"
                                    data-scheduled-date="<?= htmlspecialchars(str_replace(' ', 'T', substr($e['scheduled_date'], 0, 16))) ?>"
                                    data-status="<?= htmlspecialchars($e['status']) ?>"
                                    data-notes="<?= htmlspecialchars($e['notes'] ?? '', ENT_QUOTES) ?>"
                                    title="Edit">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="btn btn-icon btn-sm btn-outline text-danger delete-sched-btn"
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
</div>

<!-- Schedule event modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content pl-modal">
            <form id="scheduleForm">
                <div class="pl-modal-header">
                    <div class="pl-header-icon"><i class='bx bx-calendar-plus'></i></div>
                    <div style="flex:1;">
                        <h5 class="pl-modal-title" id="schModalTitle">Schedule Event</h5>
                        <p class="pl-modal-sub" id="schModalSub">Add a new event for this order.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="pl-modal-body">
                    <input type="hidden" name="action" id="schAction" value="create">
                    <input type="hidden" name="id" id="schId" value="">
                    <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                    <div class="pl-field">
                        <label class="pl-label">Job Section <span class="pl-required">*</span></label>
                        <select name="job_section_id" id="schJobSection" class="pl-input" required>
                            <?php foreach ($job_sections as $j):
                                $lbl = $j['job_type'] === 'other' && !empty($j['job_type_other'])
                                        ? $j['job_type_other']
                                        : ucfirst((string)$j['job_type']);
                            ?>
                                <option value="<?= (int)$j['id'] ?>">#<?= (int)$j['id'] ?> — <?= htmlspecialchars($lbl) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="pl-field">
                        <label class="pl-label">Event Type <span class="pl-required">*</span></label>
                        <div class="sch-type-grid">
                            <?php foreach (JobScheduleRepository::EVENT_TYPES as $k => $label):
                                $color = JobScheduleRepository::eventColor($k);
                                $icons = [
                                    'inspection' => 'bx-search-alt',
                                    'template'   => 'bx-ruler',
                                    'start'      => 'bx-play-circle',
                                    'install'    => 'bx-wrench',
                                    'complete'   => 'bx-check-circle',
                                    'follow_up'  => 'bx-phone-call',
                                ];
                            ?>
                                <label class="sch-type-opt">
                                    <input type="radio" name="event_type" value="<?= $k ?>" <?= $k === 'inspection' ? 'checked' : '' ?>>
                                    <span class="sch-type-card" style="--clr:<?= $color ?>;">
                                        <i class='bx <?= $icons[$k] ?? 'bx-calendar' ?>'></i>
                                        <span><?= $label ?></span>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-7">
                            <div class="pl-field mb-0">
                                <label class="pl-label">Date &amp; Time <span class="pl-required">*</span></label>
                                <input type="datetime-local" name="scheduled_date" id="schDate" class="pl-input" required>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="pl-field mb-0">
                                <label class="pl-label">Status</label>
                                <select name="status" id="schStatus" class="pl-input">
                                    <?php foreach (JobScheduleRepository::STATUSES as $k => $label): ?>
                                        <option value="<?= $k ?>"><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="pl-field mt-3">
                        <label class="pl-label">Notes <span class="pl-optional">(optional)</span></label>
                        <textarea name="notes" id="schNotes" class="pl-input" rows="3"
                            placeholder="Any specific instructions or reminders…"></textarea>
                    </div>
                </div>

                <div class="modal-footer pl-modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Save Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .sch-type-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }
    .sch-type-opt { cursor: pointer; margin: 0; }
    .sch-type-opt input { position: absolute; opacity: 0; pointer-events: none; }
    .sch-type-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 12px 8px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 12px;
        font-weight: 500;
        color: var(--text-sub);
        background: var(--surface);
        transition: all .15s ease;
    }
    .sch-type-card i { font-size: 22px; color: var(--clr); }
    .sch-type-opt:hover .sch-type-card {
        border-color: var(--clr);
        background: color-mix(in srgb, var(--clr) 6%, transparent);
    }
    .sch-type-opt input:checked + .sch-type-card {
        border-color: var(--clr);
        background: color-mix(in srgb, var(--clr) 10%, transparent);
        color: var(--text);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--clr) 18%, transparent);
    }
    @media (max-width: 540px) {
        .sch-type-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<script>
(function () {
    const modal = document.getElementById('scheduleModal');
    const form  = document.getElementById('scheduleForm');

    modal.addEventListener('show.bs.modal', (ev) => {
        const btn  = ev.relatedTarget;
        const mode = btn?.dataset.mode || 'create';
        document.getElementById('schAction').value = (mode === 'edit') ? 'update' : 'create';
        const titleEl = document.getElementById('schModalTitle');
        const subEl   = document.getElementById('schModalSub');
        if (mode === 'edit') {
            titleEl.textContent = 'Edit Scheduled Event';
            subEl.textContent   = 'Update event details, date, or status.';
            document.getElementById('schId').value        = btn.dataset.id;
            document.getElementById('schJobSection').value= btn.dataset.jobSectionId;
            const radio = form.querySelector(`input[name="event_type"][value="${btn.dataset.eventType}"]`);
            if (radio) radio.checked = true;
            document.getElementById('schDate').value      = btn.dataset.scheduledDate;
            document.getElementById('schStatus').value    = btn.dataset.status;
            document.getElementById('schNotes').value     = btn.dataset.notes || '';
        } else {
            form.reset();
            titleEl.textContent = 'Schedule Event';
            subEl.textContent   = 'Add a new event for this order.';
            document.getElementById('schAction').value = 'create';
            document.getElementById('schId').value     = '';
            const first = form.querySelector('input[name="event_type"]');
            if (first) first.checked = true;
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(form);
        try {
            const res = await fetch('../auth/schedule.php', { method: 'POST', body: fd });
            const data = await res.json();
            if (data.success) {
                showToast('Event saved.', 'success');
                setTimeout(() => location.reload(), 600);
            } else {
                showToast(data.message || 'Failed to save.', 'error');
            }
        } catch { showToast('Network error.', 'error'); }
    });

    document.querySelectorAll('.delete-sched-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const ok = await confirmDialog({
                title: 'Delete event?',
                message: 'This scheduled event will be removed.',
                confirmText: 'Delete event',
                icon: 'bx-trash',
            });
            if (!ok) return;
            const fd = new FormData();
            fd.append('action', 'delete');
            fd.append('id', btn.dataset.id);
            try {
                const res = await fetch('../auth/schedule.php', { method: 'POST', body: fd });
                const data = await res.json();
                if (data.success) {
                    showToast('Event deleted.', 'success');
                    btn.closest('tr')?.remove();
                } else {
                    showToast(data.message || 'Failed.', 'error');
                }
            } catch { showToast('Network error.', 'error'); }
        });
    });

    const statusWrap = document.querySelector('.overall-status-wrap');
    statusWrap?.querySelectorAll('.status-pill').forEach(pill => {
        pill.addEventListener('click', async () => {
            const status = pill.dataset.status;
            const orderId = statusWrap.dataset.orderId;
            statusWrap.querySelectorAll('.status-pill').forEach(p => p.classList.remove('is-active'));
            pill.classList.add('is-active');
            const fd = new FormData();
            fd.append('action', 'set_order_status');
            fd.append('order_id', orderId);
            fd.append('status', status);
            try {
                const res = await fetch('../auth/schedule.php', { method: 'POST', body: fd });
                const data = await res.json();
                showToast(data.success ? 'Status updated.' : (data.message || 'Failed.'),
                          data.success ? 'success' : 'error');
            } catch { showToast('Network error.', 'error'); }
        });
    });
})();
</script>
