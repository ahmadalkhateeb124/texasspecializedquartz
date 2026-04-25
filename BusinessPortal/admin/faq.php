<?php

/**
 * admin/faq.php — Manage public FAQ items.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'FAQ Manager';
$breadcrumb  = [['label' => 'FAQ']];

$repo  = new FaqRepository($pdo);
$items = $repo->all();

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">FAQ Manager</h1>
                    <p class="page-subtitle"><?= count($items) ?> question<?= count($items) !== 1 ? 's' : '' ?> · managed in database, indexed for SEO.</p>
                </div>
                <div class="page-actions">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#faqModal" data-mode="create">
                        <i class='bx bx-plus'></i> Add Question
                    </button>
                </div>
            </div>

            <div class="card">
                <?php if (empty($items)): ?>
                    <div class="empty-state" style="padding:50px 20px;">
                        <div class="empty-state-icon"><i class='bx bx-help-circle'></i></div>
                        <p class="empty-state-title">No FAQ items yet</p>
                        <p class="empty-state-desc">Add your first question to get started.</p>
                    </div>
                <?php else: ?>
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width:60px;">Order</th>
                                <th>Question</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th class="text-end" style="width:110px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $f): ?>
                                <tr>
                                    <td><span class="badge badge-neutral"><?= (int)$f['sort_order'] ?></span></td>
                                    <td>
                                        <div style="font-weight:600;"><?= htmlspecialchars($f['question']) ?></div>
                                        <div style="font-size:11px;color:var(--text-sub);margin-top:2px;">ID #<?= (int)$f['id'] ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($f['category'] ?? '—') ?></td>
                                    <td>
                                        <?php if ($f['status'] === 'published'): ?>
                                            <span class="badge badge-success"><span class="dot"></span>Published</span>
                                        <?php else: ?>
                                            <span class="badge badge-neutral"><span class="dot"></span>Draft</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <button class="btn btn-icon btn-sm btn-outline edit-faq-btn"
                                                data-bs-toggle="modal" data-bs-target="#faqModal"
                                                data-mode="edit"
                                                data-id="<?= (int)$f['id'] ?>"
                                                data-question="<?= htmlspecialchars($f['question'], ENT_QUOTES) ?>"
                                                data-answer="<?= htmlspecialchars($f['answer'], ENT_QUOTES) ?>"
                                                data-category="<?= htmlspecialchars($f['category'] ?? '', ENT_QUOTES) ?>"
                                                data-sort="<?= (int)$f['sort_order'] ?>"
                                                data-status="<?= htmlspecialchars($f['status']) ?>" title="Edit">
                                                <i class='bx bx-edit'></i>
                                            </button>
                                            <button class="btn btn-icon btn-sm btn-outline text-danger delete-faq-btn"
                                                data-id="<?= (int)$f['id'] ?>"
                                                data-question="<?= htmlspecialchars($f['question'], ENT_QUOTES) ?>" title="Delete">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

        </main>

        <!-- FAQ modal -->
        <div class="modal fade" id="faqModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content pl-modal">
                    <form id="faqForm">
                        <div class="pl-modal-header">
                            <div class="pl-header-icon"><i class='bx bx-help-circle'></i></div>
                            <div style="flex:1;">
                                <h5 class="pl-modal-title" id="faqModalTitle">Add Question</h5>
                                <p class="pl-modal-sub">Visible on the public FAQ page and indexed for Google rich snippets.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="pl-modal-body">
                            <input type="hidden" name="action" id="faqAction" value="create">
                            <input type="hidden" name="id" id="faqId" value="">

                            <div class="pl-field">
                                <label class="pl-label">Question <span class="pl-required">*</span></label>
                                <input type="text" name="question" id="faqQuestion" class="pl-input" required
                                    placeholder="e.g. How long does granite installation take?">
                            </div>
                            <div class="pl-field">
                                <label class="pl-label">Answer <span class="pl-required">*</span></label>
                                <textarea name="answer" id="faqAnswer" class="pl-input" rows="8" required
                                    placeholder="Write a clear, helpful answer. HTML is allowed (paragraphs, lists, links)."></textarea>
                                <div class="pl-hint">Tip: concise answers rank better in Google's FAQ rich snippet.</div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <div class="pl-field mb-0">
                                        <label class="pl-label">Category</label>
                                        <input type="text" name="category" id="faqCategory" class="pl-input"
                                            placeholder="e.g. Granite, Installation">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="pl-field mb-0">
                                        <label class="pl-label">Sort Order</label>
                                        <input type="number" name="sort_order" id="faqSort" class="pl-input" value="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="pl-field mb-0">
                                        <label class="pl-label">Status</label>
                                        <select name="status" id="faqStatus" class="pl-input">
                                            <option value="published">Published</option>
                                            <option value="draft">Draft</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer pl-modal-footer">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary"><i class='bx bx-save'></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script>
        (function () {
            const modal = document.getElementById('faqModal');
            const form  = document.getElementById('faqForm');

            modal.addEventListener('show.bs.modal', (ev) => {
                const btn  = ev.relatedTarget;
                const mode = btn?.dataset.mode || 'create';
                document.getElementById('faqAction').value = mode === 'edit' ? 'update' : 'create';
                document.getElementById('faqModalTitle').textContent = mode === 'edit' ? 'Edit Question' : 'Add Question';

                if (mode === 'edit') {
                    document.getElementById('faqId').value       = btn.dataset.id;
                    document.getElementById('faqQuestion').value = btn.dataset.question;
                    document.getElementById('faqAnswer').value   = btn.dataset.answer;
                    document.getElementById('faqCategory').value = btn.dataset.category || '';
                    document.getElementById('faqSort').value     = btn.dataset.sort || 0;
                    document.getElementById('faqStatus').value   = btn.dataset.status || 'published';
                } else {
                    form.reset();
                    document.getElementById('faqId').value = '';
                    document.getElementById('faqAction').value = 'create';
                }
            });

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const fd = new FormData(form);
                try {
                    const res = await fetch('../auth/faq-action.php', { method:'POST', body:fd });
                    const data = await res.json();
                    if (data.success) { showToast('Saved.', 'success'); setTimeout(()=>location.reload(), 500); }
                    else showToast(data.message || 'Failed.', 'error');
                } catch { showToast('Network error.', 'error'); }
            });

            document.querySelectorAll('.delete-faq-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const ok = await confirmDialog({
                        title: 'Delete FAQ item?',
                        message: `"${btn.dataset.question}" will be permanently deleted.`,
                        confirmText: 'Delete', icon: 'bx-trash',
                    });
                    if (!ok) return;
                    const fd = new FormData();
                    fd.append('action', 'delete');
                    fd.append('id', btn.dataset.id);
                    try {
                        const res = await fetch('../auth/faq-action.php', { method:'POST', body:fd });
                        const data = await res.json();
                        if (data.success) { showToast('Deleted.', 'success'); btn.closest('tr')?.remove(); }
                        else showToast(data.message || 'Failed.', 'error');
                    } catch { showToast('Network error.', 'error'); }
                });
            });
        })();
        </script>
    </div>
</body>
</html>
