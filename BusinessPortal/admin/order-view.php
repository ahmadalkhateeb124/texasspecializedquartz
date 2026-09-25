<?php

/**
 * admin/order-view.php — Fabrication Order Detail (Admin)
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../includes/partials/orders/view/_helpers.php';

requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: orders');
    exit;
}

try {
    $order = (new OrderRepository($pdo))->findWithJobs($id);
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}
if (!$order) {
    header('Location: orders');
    exit;
}
$job_sections = $order['jobs'];

$employeeRepo = new EmployeeRepository($pdo);
$scheduleRepo = new JobScheduleRepository($pdo);
foreach ($job_sections as &$job) {
    $job['schedule_events'] = $scheduleRepo->forSection((int)$job['id']);
}
unset($job);
$assignedEmployee = $employeeRepo->employeeForOrder($id);
$allEmployees     = $employeeRepo->allActive();
$signoff          = (new SignoffRepository($pdo))->findByOrder($id);

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = csrfToken();
}

$currentUser = currentUser();
$pageTitle   = 'Order #' . $order['id'];
$breadcrumb  = [
    ['label' => 'Orders', 'url' => 'orders.php'],
    ['label' => 'Order #' . $order['id']],
];
$extraCss    = '<link rel="stylesheet" href="../assets/css/shared/order-view.css">';

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
                    <?php endif; ?>
                    <?php if (($order['admin_status'] ?? '') === 'completed'): ?>
                        <button type="button" class="btn btn-outline" id="reactivateOrderBtn" data-order-id="<?= (int)$order['id'] ?>">
                            <i class='bx bx-lock-open-alt'></i> Reactivate Order
                        </button>
                    <?php endif; ?>
                    <a href="orders" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Orders
                    </a>
                    <a href="order-edit?id=<?= $order['id'] ?>" class="btn btn-primary">
                        <i class='bx bx-edit'></i> Edit Order
                    </a>
                </div>
            </div>

            <?php if (($order['admin_status'] ?? '') === 'completed'): ?>
                <div class="alert alert-secondary d-flex align-items-center gap-2 mb-4">
                    <i class='bx bx-lock-alt'></i> This order is closed. The customer and employee see a read-only view until you reactivate it.
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-id-card text-primary'></i> Assigned Employee</h6>
                        </div>
                        <div class="card-section">
                            <div class="mb-2">
                                <?php if ($assignedEmployee): ?>
                                    <div class="d-flex align-items-center gap-2" style="padding:6px 10px;background:var(--color-bg-subdued, #f7f4ef);border-radius:8px;">
                                        <div style="width:28px;height:28px;border-radius:50%;background:#000000;color:#fff;
                                                    display:flex;align-items:center;justify-content:center;
                                                    font-size:11px;font-weight:700;flex-shrink:0;">
                                            <?= htmlspecialchars(strtoupper(substr($assignedEmployee['fullname'], 0, 2))) ?>
                                        </div>
                                        <div style="min-width:0;">
                                            <div style="font-weight:600;font-size:13px;">
                                                <?= htmlspecialchars($assignedEmployee['fullname']) ?>
                                                <?php if (!empty($assignedEmployee['designation'])): ?>
                                                    <span style="font-weight:400;color:var(--color-text-sub);">— <?= htmlspecialchars($assignedEmployee['designation']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div style="font-size:11.5px;color:var(--color-text-sub);display:flex;gap:12px;flex-wrap:wrap;">
                                                <?php if (!empty($assignedEmployee['email'])): ?>
                                                    <span><i class='bx bx-envelope'></i> <?= htmlspecialchars($assignedEmployee['email']) ?></span>
                                                <?php endif; ?>
                                                <?php if (!empty($assignedEmployee['phone'])): ?>
                                                    <span><i class='bx bx-phone'></i> <?= htmlspecialchars($assignedEmployee['phone']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span style="color:var(--color-text-sub);font-size:13px;">Unassigned</span>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline assign-employees-btn"
                                data-order-id="<?= (int)$order['id'] ?>"
                                data-current-id="<?= $assignedEmployee['id'] ?? '' ?>">
                                <i class='bx bx-user-plus'></i> <?= $assignedEmployee ? 'Change Employee' : 'Assign Employee' ?>
                            </button>
                        </div>
                    </div>
                    <?php include __DIR__ . '/../includes/partials/orders/view/customer-info.php'; ?>
                    <?php include __DIR__ . '/../includes/partials/orders/view/sales-rep.php'; ?>
                    <?php include __DIR__ . '/../includes/partials/orders/view/notes.php'; ?>
                </div>

                <div class="col-lg-8">
                    <?php include __DIR__ . '/../includes/partials/orders/view/job-sections.php'; ?>
                    <?php include __DIR__ . '/../includes/partials/orders/view/attachment.php'; ?>
                </div>
            </div>

        </main>

        <?php if (isAdmin()): ?>
        <!-- Assign Employee modal -->
        <div class="modal fade" id="assignEmployeesModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content pl-modal">
                    <div class="pl-modal-header">
                        <div class="pl-header-icon"><i class='bx bx-user-plus'></i></div>
                        <div>
                            <h5 class="pl-modal-title">Assign Employee</h5>
                            <p class="pl-modal-sub">Choose the single staff member responsible for this order</p>
                        </div>
                    </div>
                    <div class="pl-modal-body">
                        <input type="hidden" id="assignOrderId" value="">
                        <div class="pl-picker-list" style="max-height:280px;">
                            <label class="pl-picker-item">
                                <input type="radio" name="assign_emp" class="assign-emp-radio" value="">
                                <div class="pl-picker-avatar"><i class='bx bx-user-x'></i></div>
                                <div class="pl-picker-info">
                                    <div class="pl-picker-name">Unassigned</div>
                                </div>
                            </label>
                            <?php foreach ($allEmployees as $emp): ?>
                                <label class="pl-picker-item">
                                    <input type="radio" name="assign_emp" class="assign-emp-radio" value="<?= (int)$emp['id'] ?>">
                                    <div class="pl-picker-avatar"><?= htmlspecialchars(strtoupper(substr($emp['fullname'], 0, 2))) ?></div>
                                    <div class="pl-picker-info">
                                        <div class="pl-picker-name"><?= htmlspecialchars($emp['fullname']) ?></div>
                                        <?php if (!empty($emp['designation'])): ?>
                                            <div class="pl-picker-email"><?= htmlspecialchars($emp['designation']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                            <?php if (empty($allEmployees)): ?>
                                <p style="color:var(--color-text-sub);font-size:13px;padding:10px 16px;">
                                    No active employees yet. <a href="employees-new">Add one first</a>.
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="modal-footer pl-modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveAssignEmployeesBtn">
                            <i class='bx bx-check'></i> Save Assignment
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

        <!-- Edit Event modal -->
        <div class="modal fade" id="adminScheduleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content pl-modal">
                    <form id="adminScheduleForm">
                        <div class="pl-modal-header">
                            <div class="pl-header-icon"><i class='bx bx-calendar-edit'></i></div>
                            <div style="flex:1;">
                                <h5 class="pl-modal-title">Edit Scheduled Event</h5>
                                <p class="pl-modal-sub">Update event details, date, or status.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="pl-modal-body">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" id="admSchId" value="">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

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
                                        <input type="datetime-local" name="scheduled_date" id="admSchDate" class="pl-input" required>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="pl-field mb-0">
                                        <label class="pl-label">Status</label>
                                        <select name="status" id="admSchStatus" class="pl-input">
                                            <?php foreach (JobScheduleRepository::STATUSES as $k => $label): ?>
                                                <option value="<?= $k ?>"><?= $label ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="pl-field mt-3">
                                <label class="pl-label">Notes <span class="pl-optional">(optional)</span></label>
                                <textarea name="notes" id="admSchNotes" class="pl-input" rows="3"></textarea>
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
                padding: 12px 8px; border: 1.5px solid var(--color-border); border-radius: 10px;
                font-size: 12px; font-weight: 500; color: var(--color-text-sub); background: var(--color-surface, #fff);
                transition: all .15s ease;
            }
            .sch-type-card i { font-size: 22px; color: var(--clr); }
            .sch-type-opt:hover .sch-type-card { border-color: var(--clr); background: color-mix(in srgb, var(--clr) 6%, transparent); }
            .sch-type-opt input:checked + .sch-type-card {
                border-color: var(--clr); background: color-mix(in srgb, var(--clr) 10%, transparent);
                color: var(--color-text); box-shadow: 0 0 0 3px color-mix(in srgb, var(--clr) 18%, transparent);
            }
            @media (max-width: 540px) { .sch-type-grid { grid-template-columns: repeat(2, 1fr); } }
        </style>
        <?php endif; ?>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <?php if (isAdmin()): ?>
        <!-- Loaded after footer.php so Bootstrap's JS bundle is available before this runs -->
        <script src="../assets/js/admin/assign-employees.js"></script>
        <script>
        (function () {
            const modal = document.getElementById('adminScheduleModal');
            const form  = document.getElementById('adminScheduleForm');
            if (!modal || !form) return;

            modal.addEventListener('show.bs.modal', (ev) => {
                const btn = ev.relatedTarget;
                if (!btn) return;
                document.getElementById('admSchId').value = btn.dataset.id;
                const radio = form.querySelector(`input[name="event_type"][value="${btn.dataset.eventType}"]`);
                if (radio) radio.checked = true;
                document.getElementById('admSchDate').value   = btn.dataset.scheduledDate;
                document.getElementById('admSchStatus').value = btn.dataset.status;
                document.getElementById('admSchNotes').value  = btn.dataset.notes || '';
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

            const reactivateBtn = document.getElementById('reactivateOrderBtn');
            reactivateBtn?.addEventListener('click', async () => {
                const ok = await confirmDialog({
                    title: 'Reactivate this order?',
                    message: 'The customer and employee will be able to interact with it again.',
                    confirmText: 'Reactivate',
                    icon: 'bx-lock-open-alt',
                    type: 'primary',
                });
                if (!ok) return;
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const body = new URLSearchParams({ order_id: reactivateBtn.dataset.orderId, csrf_token: csrf });
                try {
                    const res  = await fetch('../auth/reactivate-order.php', { method: 'POST', body });
                    const data = await res.json();
                    if (data.success) {
                        showToast('Order reactivated.', 'success');
                        setTimeout(() => location.reload(), 600);
                    } else {
                        showToast(data.message || 'Failed.', 'error');
                    }
                } catch { showToast('Network error.', 'error'); }
            });
        })();
        </script>
        <?php endif; ?>
