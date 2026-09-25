<?php

/**
 * admin/inquiries.php — Contact form submissions inbox.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Inbox';
$breadcrumb  = [['label' => 'Inbox']];

$repo        = new InquiryRepository($pdo);
$inquiries   = $repo->all();
$unreadCount = $repo->unreadCount();

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Inbox</h1>
                    <p class="page-subtitle">
                        <?= count($inquiries) ?> total message<?= count($inquiries) !== 1 ? 's' : '' ?>
                        <?php if ($unreadCount > 0): ?>
                            · <span style="color:var(--brand);font-weight:600;"><?= $unreadCount ?> unread</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="card">
                <?php if (empty($inquiries)): ?>
                    <div class="empty-state" style="padding:50px 20px;">
                        <div class="empty-state-icon"><i class='bx bx-envelope-open'></i></div>
                        <p class="empty-state-title">No messages yet</p>
                        <p class="empty-state-desc">Contact form submissions will appear here.</p>
                    </div>
                <?php else: ?>
                    <div class="inbox-list">
                        <?php foreach ($inquiries as $q): ?>
                            <div class="inbox-row <?= $q['is_read'] ? '' : 'is-unread' ?>" data-id="<?= $q['id'] ?>">
                                <button class="inbox-toggle" type="button">
                                    <div class="inbox-avatar"><?= strtoupper(substr($q['name'], 0, 2)) ?></div>
                                    <div class="inbox-main">
                                        <div class="inbox-top">
                                            <span class="inbox-from"><?= htmlspecialchars($q['name']) ?></span>
                                            <span class="inbox-date"><?= date('M j, Y · g:i A', strtotime($q['created_at'])) ?></span>
                                        </div>
                                        <div class="inbox-subject"><?= htmlspecialchars($q['subject'] ?: '(no subject)') ?></div>
                                        <div class="inbox-preview"><?= htmlspecialchars(mb_substr($q['message'], 0, 120)) ?><?= mb_strlen($q['message']) > 120 ? '…' : '' ?></div>
                                    </div>
                                    <?php if (!$q['is_read']): ?>
                                        <span class="inbox-dot" title="Unread"></span>
                                    <?php endif; ?>
                                </button>
                                <div class="inbox-body">
                                    <div class="inbox-meta">
                                        <div><i class='bx bx-envelope'></i> <a href="mailto:<?= htmlspecialchars($q['email']) ?>"><?= htmlspecialchars($q['email']) ?></a></div>
                                        <?php if ($q['ip']): ?><div><i class='bx bx-globe'></i> <?= htmlspecialchars($q['ip']) ?></div><?php endif; ?>
                                    </div>
                                    <div class="inbox-message">
                                        <?= nl2br(htmlspecialchars($q['message'])) ?>
                                    </div>
                                    <div class="inbox-actions">
                                        <a href="mailto:<?= htmlspecialchars($q['email']) ?>?subject=Re: <?= urlencode($q['subject'] ?: '') ?>" class="btn btn-primary btn-sm">
                                            <i class='bx bx-reply'></i> Reply
                                        </a>
                                        <button class="btn btn-outline btn-sm mark-toggle-btn" data-id="<?= $q['id'] ?>" data-read="<?= $q['is_read'] ?>">
                                            <i class='bx <?= $q['is_read'] ? 'bx-envelope' : 'bx-check' ?>'></i>
                                            Mark as <?= $q['is_read'] ? 'unread' : 'read' ?>
                                        </button>
                                        <button class="btn btn-outline btn-sm text-danger delete-inquiry-btn" data-id="<?= $q['id'] ?>">
                                            <i class='bx bx-trash'></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <style>
            .inbox-list { display:flex; flex-direction:column; }
            .inbox-row { border-bottom:1px solid var(--border); transition: background .15s; }
            .inbox-row:last-child { border-bottom:0; }
            .inbox-row:hover { background: var(--surface-muted); }
            .inbox-row.is-unread .inbox-from,
            .inbox-row.is-unread .inbox-subject { font-weight: 700; color: var(--text); }

            .inbox-toggle {
                display:flex; align-items:center; gap:14px;
                width:100%; padding:14px 18px;
                background:none; border:0; text-align:left;
                cursor:pointer; color:inherit;
            }
            .inbox-avatar {
                width:40px; height:40px; border-radius:50%;
                background:#000000; color:#fff;
                display:flex; align-items:center; justify-content:center;
                font-size:13px; font-weight:700; flex-shrink:0;
            }
            .inbox-main { flex:1; min-width:0; }
            .inbox-top { display:flex; justify-content:space-between; gap:8px; margin-bottom:2px; }
            .inbox-from { font-size:14px; color:var(--text); }
            .inbox-date { font-size:11px; color:var(--text-sub); white-space:nowrap; }
            .inbox-subject { font-size:13px; color:var(--text); margin-bottom:3px; }
            .inbox-preview { font-size:12px; color:var(--text-sub); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
            .inbox-dot { width:9px; height:9px; border-radius:50%; background:var(--brand); flex-shrink:0; }

            .inbox-body {
                display:none;
                padding:0 18px 18px 72px;
                background: var(--surface-muted);
            }
            .inbox-row.is-open .inbox-body { display:block; }
            .inbox-meta { display:flex; gap:20px; font-size:12px; color:var(--text-sub); padding:12px 0; border-bottom:1px dashed var(--border); }
            .inbox-meta a { color:var(--text-sub); }
            .inbox-message { font-size:13px; color:var(--text); line-height:1.6; padding:14px 0; white-space:pre-wrap; }
            .inbox-actions { display:flex; gap:8px; flex-wrap:wrap; padding-top:10px; border-top:1px dashed var(--border); }
        </style>

        <script>
        (function () {
            document.querySelectorAll('.inbox-toggle').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const row = btn.closest('.inbox-row');
                    const wasOpen = row.classList.contains('is-open');
                    document.querySelectorAll('.inbox-row.is-open').forEach(r => r.classList.remove('is-open'));
                    if (!wasOpen) {
                        row.classList.add('is-open');
                        if (row.classList.contains('is-unread')) {
                            const fd = new FormData();
                            fd.append('action', 'mark_read');
                            fd.append('id', row.dataset.id);
                            try {
                                await fetch('../auth/inquiry-action.php', { method:'POST', body:fd });
                                row.classList.remove('is-unread');
                                row.querySelector('.inbox-dot')?.remove();
                            } catch {}
                        }
                    }
                });
            });

            document.querySelectorAll('.mark-toggle-btn').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    const isRead = btn.dataset.read === '1';
                    const fd = new FormData();
                    fd.append('action', isRead ? 'mark_unread' : 'mark_read');
                    fd.append('id', btn.dataset.id);
                    try {
                        const res = await fetch('../auth/inquiry-action.php', { method:'POST', body:fd });
                        const data = await res.json();
                        if (data.success) { showToast('Updated.', 'success'); setTimeout(()=>location.reload(), 400); }
                        else showToast(data.message || 'Failed.', 'error');
                    } catch { showToast('Network error.', 'error'); }
                });
            });

            document.querySelectorAll('.delete-inquiry-btn').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    const ok = await confirmDialog({
                        title: 'Delete message?',
                        message: 'This message will be permanently deleted.',
                        confirmText: 'Delete',
                        icon: 'bx-trash',
                    });
                    if (!ok) return;
                    const fd = new FormData();
                    fd.append('action', 'delete');
                    fd.append('id', btn.dataset.id);
                    try {
                        const res = await fetch('../auth/inquiry-action.php', { method:'POST', body:fd });
                        const data = await res.json();
                        if (data.success) {
                            showToast('Deleted.', 'success');
                            btn.closest('.inbox-row')?.remove();
                        } else showToast(data.message || 'Failed.', 'error');
                    } catch { showToast('Network error.', 'error'); }
                });
            });
        })();
        </script>
    </div>
</body>
</html>
