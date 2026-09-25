<?php

/**
 * employee/order-view.php — Full order detail for the employee assigned to it
 * (read-only order/customer info; the employee may only add/edit schedule events).
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../includes/partials/orders/view/_helpers.php';

requireEmployee('../auth-login-minimal.php');

$currentUser = currentUser();
$employeeId  = $currentUser['id'];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    $order = (new OrderRepository($pdo))->findWithJobs($id);
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}
if (!$order) {
    header('Location: index.php');
    exit;
}

/* Only employees assigned to this order may view it — but they see the full order once assigned. */
$empRepo          = new EmployeeRepository($pdo);
$assignedEmployee = $empRepo->employeeForOrder($id);
if (!$assignedEmployee || (int)$assignedEmployee['id'] !== $employeeId) {
    header('Location: index.php');
    exit;
}

$job_sections = $order['jobs'];
$signoff      = (new SignoffRepository($pdo))->findByOrder($id);

$scheduleRepo = new JobScheduleRepository($pdo);
foreach ($job_sections as &$job) {
    $job['schedule_events'] = $scheduleRepo->forSection((int)$job['id']);
}
unset($job);

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = csrfToken();
}

$pageTitle  = 'Order #' . $order['id'];
$breadcrumb = [
    ['label' => 'Dashboard', 'url' => 'index.php'],
    ['label' => 'Order #' . $order['id']],
];

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Order #<?= $order['id'] ?></h1>
                    <p class="page-subtitle">
                        Created <?= date('M j, Y', strtotime($order['created_at'])) ?>
                        &middot; Last updated <?= date('M j, Y g:i A', strtotime($order['updated_at'])) ?>
                    </p>
                </div>
                <div class="page-actions">
                    <?php if ($signoff): ?>
                        <a href="../signoff-download.php?order_id=<?= $id ?>" target="_blank" class="btn btn-outline">
                            <i class='bx bx-file-blank'></i> View Sign-Off PDF
                        </a>
                    <?php else: ?>
                        <a href="order-signoff?id=<?= $id ?>" class="btn btn-primary">
                            <i class='bx bx-check-shield'></i> Close Order &amp; Sign Off
                        </a>
                    <?php endif; ?>
                    <a href="index" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Dashboard
                    </a>
                </div>
            </div>

            <?php if (isset($_GET['signoff']) && $_GET['signoff'] === 'success'): ?>
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                    <i class='bx bx-check-circle'></i> Sign-off completed — the order has been marked as completed.
                </div>
            <?php elseif (isset($_GET['signoff']) && $_GET['signoff'] === 'exists'): ?>
                <div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
                    <i class='bx bx-info-circle'></i> This order already has a sign-off on file.
                </div>
            <?php endif; ?>

            <?php if (($order['admin_status'] ?? '') === 'completed'): ?>
                <div class="alert alert-secondary d-flex align-items-center gap-2 mb-4">
                    <i class='bx bx-lock-alt'></i> This order is closed and read-only. Contact your administrator if it needs to be reopened.
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-lg-4">
                    <?php include __DIR__ . '/../includes/partials/orders/view/customer-info.php'; ?>
                    <?php include __DIR__ . '/../includes/partials/orders/view/sales-rep.php'; ?>
                    <?php include __DIR__ . '/../includes/partials/orders/view/notes.php'; ?>
                </div>

                <div class="col-lg-8">
                    <?php include __DIR__ . '/../includes/partials/orders/view/job-sections.php'; ?>
                </div>
            </div>

        </main>

        <!-- Add/Edit Event modal -->
        <div class="modal fade" id="employeeScheduleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content pl-modal">
                    <form id="employeeScheduleForm">
                        <div class="pl-modal-header">
                            <div class="pl-header-icon"><i class='bx bx-calendar-plus'></i></div>
                            <div style="flex:1;">
                                <h5 class="pl-modal-title" id="empSchModalTitle">Schedule Event</h5>
                                <p class="pl-modal-sub" id="empSchModalSub">Add a new event for this job section.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="pl-modal-body">
                            <input type="hidden" name="action" id="empSchAction" value="create">
                            <input type="hidden" name="id" id="empSchId" value="">
                            <input type="hidden" name="job_section_id" id="empSchJobSection" value="">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

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
                                        <input type="datetime-local" name="scheduled_date" id="empSchDate" class="pl-input" required>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="pl-field mb-0">
                                        <label class="pl-label">Status</label>
                                        <select name="status" id="empSchStatus" class="pl-input">
                                            <?php foreach (JobScheduleRepository::STATUSES as $k => $label): ?>
                                                <option value="<?= $k ?>"><?= $label ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="pl-field mt-3">
                                <label class="pl-label">Notes <span class="pl-optional">(optional)</span></label>
                                <textarea name="notes" id="empSchNotes" class="pl-input" rows="3"
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
            .sch-type-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
            .sch-type-opt { cursor: pointer; margin: 0; }
            .sch-type-opt input { position: absolute; opacity: 0; pointer-events: none; }
            .sch-type-card {
                display: flex; flex-direction: column; align-items: center; gap: 6px;
                padding: 12px 8px; border: 1.5px solid var(--border); border-radius: 10px;
                font-size: 12px; font-weight: 500; color: var(--text-sub); background: var(--surface);
                transition: all .15s ease;
            }
            .sch-type-card i { font-size: 22px; color: var(--clr); }
            .sch-type-opt:hover .sch-type-card { border-color: var(--clr); background: color-mix(in srgb, var(--clr) 6%, transparent); }
            .sch-type-opt input:checked + .sch-type-card {
                border-color: var(--clr); background: color-mix(in srgb, var(--clr) 10%, transparent);
                color: var(--text); box-shadow: 0 0 0 3px color-mix(in srgb, var(--clr) 18%, transparent);
            }
            @media (max-width: 540px) { .sch-type-grid { grid-template-columns: repeat(2, 1fr); } }
        </style>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script>
        (function () {
            const modal = document.getElementById('employeeScheduleModal');
            const form  = document.getElementById('employeeScheduleForm');

            modal.addEventListener('show.bs.modal', (ev) => {
                const btn  = ev.relatedTarget;
                const mode = btn?.dataset.mode || 'create';
                document.getElementById('empSchAction').value = (mode === 'edit') ? 'update' : 'create';
                const titleEl = document.getElementById('empSchModalTitle');
                const subEl   = document.getElementById('empSchModalSub');
                if (mode === 'edit') {
                    titleEl.textContent = 'Edit Scheduled Event';
                    subEl.textContent   = 'Update event details, date, or status.';
                    document.getElementById('empSchId').value         = btn.dataset.id;
                    document.getElementById('empSchJobSection').value = btn.dataset.jobSectionId;
                    const radio = form.querySelector(`input[name="event_type"][value="${btn.dataset.eventType}"]`);
                    if (radio) radio.checked = true;
                    document.getElementById('empSchDate').value   = btn.dataset.scheduledDate;
                    document.getElementById('empSchStatus').value = btn.dataset.status;
                    document.getElementById('empSchNotes').value  = btn.dataset.notes || '';
                } else {
                    form.reset();
                    titleEl.textContent = 'Schedule Event';
                    subEl.textContent   = 'Add a new event for this job section.';
                    document.getElementById('empSchAction').value     = 'create';
                    document.getElementById('empSchId').value         = '';
                    document.getElementById('empSchJobSection').value = btn?.dataset.jobSectionId || '';
                    const first = form.querySelector('input[name="event_type"]');
                    if (first) first.checked = true;
                }
            });

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const fd = new FormData(form);
                try {
                    const res  = await fetch('../auth/schedule.php', { method: 'POST', body: fd });
                    const data = await res.json();
                    if (data.success) {
                        showToast('Event saved.', 'success');
                        setTimeout(() => location.reload(), 600);
                    } else {
                        showToast(data.message || 'Failed to save.', 'error');
                    }
                } catch { showToast('Network error.', 'error'); }
            });

            document.querySelectorAll('.delete-event-btn').forEach(btn => {
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
                        const res  = await fetch('../auth/schedule.php', { method: 'POST', body: fd });
                        const data = await res.json();
                        if (data.success) {
                            showToast('Event deleted.', 'success');
                            setTimeout(() => location.reload(), 600);
                        } else {
                            showToast(data.message || 'Failed.', 'error');
                        }
                    } catch { showToast('Network error.', 'error'); }
                });
            });
        })();
        </script>
    </div>
</body>
</html>
