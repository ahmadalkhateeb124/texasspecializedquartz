<?php

/**
 * employee/calendar.php — Personal calendar of scheduled events for this employee's job sections.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireEmployee('../auth-login-minimal.php');

$currentUser = currentUser();
$employeeId  = $currentUser['id'];
$pageTitle   = 'My Calendar';
$breadcrumb  = [['label' => 'Calendar']];

$events = (new JobScheduleRepository($pdo))->forEmployee($employeeId);

$fcEvents = [];
foreach ($events as $e) {
    $title = JobScheduleRepository::EVENT_TYPES[$e['event_type']] ?? $e['event_type'];
    if (!empty($e['company_name'])) {
        $title .= ' — ' . $e['company_name'];
    }
    $fcEvents[] = [
        'id'              => (int)$e['id'],
        'title'           => $title,
        'start'           => str_replace(' ', 'T', $e['scheduled_date']),
        'backgroundColor' => JobScheduleRepository::eventColor($e['event_type']),
        'borderColor'     => JobScheduleRepository::eventColor($e['event_type']),
        'extendedProps'   => [
            'order_id'       => (int)$e['order_id'],
            'job_section_id' => (int)$e['job_section_id'],
            'event_type'     => $e['event_type'],
            'status'         => $e['status'],
            'notes'          => $e['notes'],
            'job_type'       => $e['job_type'],
            'customer_name'  => $e['customer_name'] ?? '',
            'order_phone'    => $e['order_phone'] ?? '',
            'address'        => $e['address'] ?? '',
            'city'           => $e['city'] ?? '',
        ],
    ];
}

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">My Calendar</h1>
                    <p class="page-desc"><?= count($events) ?> scheduled event<?= count($events) !== 1 ? 's' : '' ?></p>
                </div>
            </div>

            <div class="card calendar-card" style="padding:16px;">
                <div class="calendar-legend" style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:14px;font-size:12px;">
                    <?php foreach (JobScheduleRepository::EVENT_TYPES as $k => $label):
                        $color = JobScheduleRepository::eventColor($k);
                    ?>
                        <span style="display:inline-flex;align-items:center;gap:6px;">
                            <span style="width:12px;height:12px;border-radius:3px;background:<?= $color ?>;"></span>
                            <?= $label ?>
                        </span>
                    <?php endforeach; ?>
                </div>
                <div id="calendar"></div>
            </div>

        </main>

        <!-- Event detail modal -->
        <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content pl-modal">
                    <div class="ev-modal-banner" id="evBanner">
                        <div class="ev-modal-banner-icon"><i class='bx bx-calendar-event' id="evBannerIcon"></i></div>
                        <div class="ev-modal-banner-text">
                            <div class="ev-modal-banner-sub">Scheduled Event</div>
                            <div class="ev-modal-banner-title" id="evModalTitle">Event</div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="pl-modal-body">
                        <div class="ev-meta-row">
                            <span class="ev-meta-label"><i class='bx bx-calendar'></i> Date &amp; Time</span>
                            <span class="ev-meta-val" id="evDate">—</span>
                        </div>
                        <div class="ev-meta-row">
                            <span class="ev-meta-label"><i class='bx bx-hash'></i> Order</span>
                            <span class="ev-meta-val">#<span id="evOrderId">—</span></span>
                        </div>
                        <div class="ev-meta-row">
                            <span class="ev-meta-label"><i class='bx bx-layer'></i> Job Section</span>
                            <span class="ev-meta-val" id="evJobType">—</span>
                        </div>
                        <div class="ev-meta-row">
                            <span class="ev-meta-label"><i class='bx bx-user'></i> Customer</span>
                            <span class="ev-meta-val" id="evCustomer">—</span>
                        </div>
                        <div class="ev-meta-row">
                            <span class="ev-meta-label"><i class='bx bx-phone'></i> Phone</span>
                            <span class="ev-meta-val" id="evPhone">—</span>
                        </div>
                        <div class="ev-meta-row">
                            <span class="ev-meta-label"><i class='bx bx-map'></i> Address</span>
                            <span class="ev-meta-val" id="evAddress">—</span>
                        </div>
                        <div class="ev-meta-row">
                            <span class="ev-meta-label"><i class='bx bx-flag'></i> Status</span>
                            <span class="ev-status-badge" id="evStatus">—</span>
                        </div>
                        <div id="evNotesWrap" class="ev-notes-wrap" style="display:none;">
                            <div class="ev-meta-label"><i class='bx bx-note'></i> Notes</div>
                            <div class="ev-notes-body" id="evNotes"></div>
                        </div>
                    </div>
                    <div class="modal-footer pl-modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .ev-modal-banner {
                position: relative;
                padding: 22px 24px;
                display: flex;
                align-items: center;
                gap: 14px;
                background: linear-gradient(135deg, var(--ev-clr, #3b82f6), color-mix(in srgb, var(--ev-clr, #3b82f6) 75%, #000 8%));
                color: #fff;
            }
            .ev-modal-banner .btn-close { position: absolute; top: 14px; right: 14px; filter: brightness(0) invert(1); opacity: .85; }
            .ev-modal-banner-icon { width: 48px; height: 48px; border-radius: 12px; background: rgba(255,255,255,.18); display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
            .ev-modal-banner-sub { font-size: 11px; text-transform: uppercase; letter-spacing: .08em; opacity: .85; margin-bottom: 2px; }
            .ev-modal-banner-title { font-family: 'Fraunces', Georgia, 'Times New Roman', serif; font-size: 22px; line-height: 1.15; }
            .ev-meta-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px dashed var(--border); gap: 10px; }
            .ev-meta-row:last-of-type { border-bottom: 0; }
            .ev-meta-label { font-size: 12px; color: var(--text-sub); font-weight: 500; display: flex; align-items: center; gap: 6px; }
            .ev-meta-label i { font-size: 14px; color: var(--text-dis); }
            .ev-meta-val { font-size: 13px; font-weight: 600; color: var(--text); text-align: right; }
            .ev-status-badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 999px; text-transform: uppercase; letter-spacing: .04em; }
            .ev-notes-wrap { margin-top: 10px; padding: 10px 12px; background: var(--bg, #f9f9f8); border-radius: 8px; border-left: 3px solid var(--border); }
            .ev-notes-wrap .ev-meta-label { margin-bottom: 6px; }
            .ev-notes-body { font-size: 13px; color: var(--text); line-height: 1.5; }

            /* ── Calendar mobile fixes ─────────────────────────── */
            @media (max-width: 640px) {
                .calendar-card { padding: 10px !important; }
                .calendar-legend { font-size: 10.5px !important; gap: 8px !important; margin-bottom: 10px !important; }
                .fc .fc-toolbar {
                    flex-direction: column;
                    gap: 8px;
                }
                .fc .fc-toolbar-chunk { display: flex; justify-content: center; }
                .fc .fc-toolbar-title { font-size: 15px; }
                .fc .fc-button {
                    padding: 4px 8px;
                    font-size: 11.5px;
                }
                .fc .fc-daygrid-day-number { font-size: 10.5px; padding: 2px 4px; }
                .fc .fc-daygrid-day-top { flex-direction: row; }
                .fc .fc-event-title, .fc .fc-event-time { font-size: 9.5px; }
                .fc .fc-daygrid-event { margin-top: 1px; }
                .fc-daygrid-dot-event .fc-event-title { font-weight: 500; }
                .fc .fc-col-header-cell-cushion { font-size: 10.5px; }
                .fc .fc-list-event-title, .fc .fc-list-event-time { font-size: 12px; }
                .fc .fc-list-day-text, .fc .fc-list-day-side-text { font-size: 12px; }
            }
        </style>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet'>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
        <script>
        (function () {
            const events = <?= json_encode($fcEvents, JSON_UNESCAPED_SLASHES) ?>;
            const el = document.getElementById('calendar');
            const cal = new FullCalendar.Calendar(el, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left:  'prev,next today',
                    center:'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events,
                eventClick: (info) => {
                    const p = info.event.extendedProps;
                    const color = info.event.backgroundColor || '#3b82f6';
                    const icons = {
                        inspection: 'bx-search-alt',
                        template:   'bx-ruler',
                        start:      'bx-play-circle',
                        install:    'bx-wrench',
                        complete:   'bx-check-circle',
                        follow_up:  'bx-phone-call',
                    };
                    const statusStyle = {
                        pending:     ['#fef3c7', '#92400e', 'Pending'],
                        in_progress: ['#dbeafe', '#1e40af', 'In Progress'],
                        completed:   ['#d1fae5', '#065f46', 'Completed'],
                        cancelled:   ['#fee2e2', '#991b1b', 'Cancelled'],
                    };

                    const banner = document.getElementById('evBanner');
                    banner.style.setProperty('--ev-clr', color);
                    document.getElementById('evBannerIcon').className =
                        'bx ' + (icons[p.event_type] || 'bx-calendar-event');

                    document.getElementById('evModalTitle').textContent = info.event.title;
                    document.getElementById('evOrderId').textContent    = p.order_id;
                    document.getElementById('evJobType').textContent    = p.job_type
                        ? (p.job_type.charAt(0).toUpperCase() + p.job_type.slice(1))
                        : '—';
                    document.getElementById('evCustomer').textContent = p.customer_name || '—';
                    document.getElementById('evPhone').textContent    = p.order_phone || '—';
                    document.getElementById('evAddress').textContent  = [p.address, p.city].filter(Boolean).join(', ') || '—';
                    document.getElementById('evDate').textContent       =
                        info.event.start.toLocaleDateString(undefined, { weekday:'long', year:'numeric', month:'short', day:'numeric' })
                        + ' · ' + info.event.start.toLocaleTimeString([], { hour:'2-digit', minute:'2-digit' });

                    const statusEl = document.getElementById('evStatus');
                    const [bg, fg, lbl] = statusStyle[p.status] || ['#f3f4f6', '#374151', p.status];
                    statusEl.textContent = lbl;
                    statusEl.style.background = bg;
                    statusEl.style.color = fg;

                    const notesWrap = document.getElementById('evNotesWrap');
                    const notes = p.notes || '';
                    if (notes) {
                        document.getElementById('evNotes').textContent = notes;
                        notesWrap.style.display = '';
                    } else {
                        notesWrap.style.display = 'none';
                    }

                    new bootstrap.Modal(document.getElementById('eventModal')).show();
                }
            });
            cal.render();
        })();
        </script>
    </div>
</body>
</html>
