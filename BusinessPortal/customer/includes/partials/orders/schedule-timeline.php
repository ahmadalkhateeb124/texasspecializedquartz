<?php
/**
 * Customer schedule timeline (read-only).
 * Expects: $order, $pdo
 */
$scheduleRepo   = new JobScheduleRepository($pdo);
$scheduleEvents = $scheduleRepo->forOrder((int)$order['id']);
$adminStatus    = $scheduleRepo->orderStatus((int)$order['id']);

$statusInfo = [
    'new'         => ['New',         'bx-file',         '#6b7280'],
    'scheduled'   => ['Scheduled',   'bx-calendar',     '#3b82f6'],
    'in_progress' => ['In Progress', 'bx-loader-alt',   '#f59e0b'],
    'completed'   => ['Completed',   'bx-check-circle', '#10b981'],
    'cancelled'   => ['Cancelled',   'bx-x-circle',     '#dc2626'],
];
[$sLbl, $sIcon, $sClr] = $statusInfo[$adminStatus] ?? $statusInfo['new'];

$eventIcons = [
    'inspection' => 'bx-search-alt',
    'template'   => 'bx-ruler',
    'start'      => 'bx-play-circle',
    'install'    => 'bx-wrench',
    'complete'   => 'bx-check-circle',
    'follow_up'  => 'bx-phone-call',
];
$statusPill = [
    'pending'     => ['Pending',     '#fef3c7', '#92400e'],
    'in_progress' => ['In Progress', '#dbeafe', '#1e40af'],
    'completed'   => ['Completed',   '#d1fae5', '#065f46'],
    'cancelled'   => ['Cancelled',   '#fee2e2', '#991b1b'],
];

$now = time();
?>
<div class="card mb-3 sched-card">

    <!-- Hero status banner -->
    <div class="sched-hero" style="--clr:<?= $sClr ?>;">
        <div class="sched-hero-icon">
            <i class='bx <?= $sIcon ?>'></i>
        </div>
        <div class="sched-hero-text">
            <div class="sched-hero-label">Current Status</div>
            <div class="sched-hero-title"><?= $sLbl ?></div>
        </div>
        <div class="sched-hero-count">
            <div style="font-size:22px;font-weight:700;"><?= count($scheduleEvents) ?></div>
            <div style="font-size:10px;text-transform:uppercase;letter-spacing:.05em;opacity:.8;">
                Event<?= count($scheduleEvents) !== 1 ? 's' : '' ?>
            </div>
        </div>
    </div>

    <div class="card-section">
        <h6 class="sched-section-title">
            <i class='bx bx-time-five'></i> Scheduled Events
        </h6>

        <?php if (empty($scheduleEvents)): ?>
            <div class="sched-empty">
                <div class="sched-empty-icon"><i class='bx bx-calendar'></i></div>
                <p class="sched-empty-title">No events scheduled yet</p>
                <p class="sched-empty-desc">We'll notify you as soon as dates are confirmed.</p>
            </div>
        <?php else: ?>
            <div class="sched-timeline">
                <?php foreach ($scheduleEvents as $i => $e):
                    $etype  = $e['event_type'];
                    $color  = JobScheduleRepository::eventColor($etype);
                    $icon   = $eventIcons[$etype] ?? 'bx-calendar';
                    $jobLbl = $e['job_type'] === 'other' && !empty($e['job_type_other'])
                                ? $e['job_type_other']
                                : ucfirst((string)$e['job_type']);
                    $ts     = strtotime($e['scheduled_date']);
                    $isPast = $ts < $now;
                    $isDone = $e['status'] === 'completed';
                    [$pLbl, $pBg, $pFg] = $statusPill[$e['status']] ?? ['—','#f3f4f6','#374151'];
                ?>
                    <div class="sched-item <?= $isDone ? 'is-done' : '' ?>">
                        <div class="sched-marker" style="--clr:<?= $color ?>;">
                            <i class='bx <?= $icon ?>'></i>
                        </div>
                        <?php if ($i < count($scheduleEvents) - 1): ?>
                            <div class="sched-connector"></div>
                        <?php endif; ?>

                        <div class="sched-card-inner">
                            <div class="sched-card-head">
                                <div>
                                    <div class="sched-ev-type" style="color:<?= $color ?>;">
                                        <?= JobScheduleRepository::EVENT_TYPES[$etype] ?? $etype ?>
                                    </div>
                                    <div class="sched-ev-job"><?= htmlspecialchars($jobLbl) ?></div>
                                </div>
                                <span class="sched-ev-badge" style="background:<?= $pBg ?>;color:<?= $pFg ?>;">
                                    <?= $pLbl ?>
                                </span>
                            </div>

                            <div class="sched-ev-meta">
                                <span><i class='bx bx-calendar'></i> <?= date('l, M j, Y', $ts) ?></span>
                                <span><i class='bx bx-time'></i> <?= date('g:i A', $ts) ?></span>
                            </div>

                            <?php if (!empty($e['notes'])): ?>
                                <div class="sched-ev-notes">
                                    <i class='bx bx-note'></i>
                                    <span><?= htmlspecialchars($e['notes']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .sched-card { overflow: hidden; }

    .sched-hero {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 20px;
        background: linear-gradient(135deg, var(--clr), color-mix(in srgb, var(--clr) 75%, #000 8%));
        color: #fff;
    }
    .sched-hero-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        background: rgba(255,255,255,.18);
        backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .sched-hero-text { flex: 1; }
    .sched-hero-label {
        font-size: 11px; text-transform: uppercase; letter-spacing: .08em;
        opacity: .85; margin-bottom: 2px;
    }
    .sched-hero-title {
        font-family: 'Fraunces', Georgia, 'Times New Roman', serif;
        font-size: 24px; line-height: 1.1;
    }
    .sched-hero-count { text-align: right; }

    .sched-section-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        margin: 0 0 14px;
        display: flex; align-items: center; gap: 6px;
    }
    .sched-section-title i { color: var(--brand); font-size: 16px; }

    .sched-timeline { position: relative; }
    .sched-item {
        position: relative;
        padding-left: 56px;
        padding-bottom: 18px;
    }
    .sched-item:last-child { padding-bottom: 0; }

    .sched-marker {
        position: absolute;
        left: 0; top: 0;
        width: 40px; height: 40px;
        border-radius: 10px;
        background: color-mix(in srgb, var(--clr) 12%, transparent);
        color: var(--clr);
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        box-shadow: 0 0 0 3px var(--surface), 0 0 0 4px color-mix(in srgb, var(--clr) 25%, transparent);
        z-index: 2;
    }
    .sched-connector {
        position: absolute;
        left: 19px; top: 44px; bottom: -6px;
        width: 2px;
        background: linear-gradient(to bottom, var(--border), transparent);
    }
    .sched-item.is-done .sched-marker {
        background: var(--clr);
        color: #fff;
    }

    .sched-card-inner {
        padding: 12px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--surface);
        transition: border-color .15s, box-shadow .15s;
    }
    .sched-card-inner:hover {
        border-color: color-mix(in srgb, var(--text) 20%, var(--border));
        box-shadow: 0 4px 12px rgba(24,20,15,.05);
    }

    .sched-card-head {
        display: flex; justify-content: space-between; align-items: flex-start;
        gap: 10px; margin-bottom: 8px;
    }
    .sched-ev-type {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .02em;
    }
    .sched-ev-job {
        font-size: 11px;
        color: var(--text-sub);
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-top: 1px;
    }
    .sched-ev-badge {
        font-size: 10px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 999px;
        flex-shrink: 0;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .sched-ev-meta {
        display: flex; flex-wrap: wrap; gap: 14px;
        font-size: 12px; color: var(--text-sub);
    }
    .sched-ev-meta i { margin-right: 3px; vertical-align: middle; }

    .sched-ev-notes {
        margin-top: 8px;
        padding: 8px 10px;
        background: var(--bg, #f9f9f8);
        border-left: 3px solid var(--border);
        border-radius: 4px;
        font-size: 12px;
        color: var(--text-sub);
        line-height: 1.5;
        display: flex; gap: 6px; align-items: flex-start;
    }
    .sched-ev-notes i { color: var(--text-dis); margin-top: 2px; flex-shrink: 0; }

    .sched-empty {
        text-align: center;
        padding: 30px 20px;
    }
    .sched-empty-icon {
        width: 56px; height: 56px;
        margin: 0 auto 12px;
        border-radius: 50%;
        background: var(--bg, #f5f5f4);
        display: flex; align-items: center; justify-content: center;
        font-size: 28px;
        color: var(--text-dis);
    }
    .sched-empty-title { font-size: 14px; font-weight: 600; color: var(--text); margin: 0 0 4px; }
    .sched-empty-desc  { font-size: 12px; color: var(--text-sub); margin: 0; }
</style>
