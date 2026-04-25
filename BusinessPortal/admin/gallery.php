<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Photo Gallery';
$breadcrumb  = [['label' => 'Photo Gallery']];

$repo  = new GalleryRepository($pdo);
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
                    <h1 class="page-title">Photo Gallery</h1>
                    <p class="page-subtitle"><?= count($items) ?> photo<?= count($items) !== 1 ? 's' : '' ?> · published on <code>/photogallery</code></p>
                </div>
                <div class="page-actions">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#galleryModal" data-mode="create">
                        <i class='bx bx-plus'></i> Add Photo
                    </button>
                </div>
            </div>

            <div class="card">
                <?php if (empty($items)): ?>
                    <div class="empty-state" style="padding:50px 20px;">
                        <div class="empty-state-icon"><i class='bx bx-image'></i></div>
                        <p class="empty-state-title">No photos yet</p>
                        <p class="empty-state-desc">Upload the first project photo to get started.</p>
                    </div>
                <?php else: ?>
                    <div class="gx-admin-grid">
                        <?php foreach ($items as $g):
                            $isAdminUploaded = str_starts_with((string)$g['image'], 'gx-');
                            $src = $isAdminUploaded
                                ? '../assets/gallery/' . htmlspecialchars($g['image'])
                                : '../../images/photo/' . htmlspecialchars($g['image']);
                        ?>
                            <article class="gx-admin-card">
                                <div class="gx-admin-img"><img src="<?= $src ?>" alt="<?= htmlspecialchars($g['caption']) ?>"></div>
                                <div class="gx-admin-body">
                                    <div class="gx-admin-tags">
                                        <span class="badge badge-neutral"><?= ucfirst($g['category']) ?></span>
                                        <span class="badge badge-neutral"><?= ucfirst($g['material']) ?></span>
                                        <?php if ($g['status'] === 'inactive'): ?>
                                            <span class="badge badge-neutral">Hidden</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="gx-admin-cap"><?= htmlspecialchars($g['caption']) ?></p>
                                    <div class="gx-admin-actions">
                                        <button class="btn btn-icon btn-sm btn-outline edit-gx-btn"
                                            data-bs-toggle="modal" data-bs-target="#galleryModal"
                                            data-mode="edit"
                                            data-id="<?= (int)$g['id'] ?>"
                                            data-caption="<?= htmlspecialchars($g['caption'], ENT_QUOTES) ?>"
                                            data-category="<?= htmlspecialchars($g['category']) ?>"
                                            data-material="<?= htmlspecialchars($g['material']) ?>"
                                            data-sort="<?= (int)$g['sort_order'] ?>"
                                            data-status="<?= htmlspecialchars($g['status']) ?>"
                                            title="Edit"><i class='bx bx-edit'></i></button>
                                        <button class="btn btn-icon btn-sm btn-outline text-danger delete-gx-btn"
                                            data-id="<?= (int)$g['id'] ?>"
                                            title="Delete"><i class='bx bx-trash'></i></button>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Modal -->
        <div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content pl-modal">
                    <form id="galleryForm" enctype="multipart/form-data">
                        <div class="pl-modal-header">
                            <div class="pl-header-icon"><i class='bx bx-image-add'></i></div>
                            <div style="flex:1;">
                                <h5 class="pl-modal-title" id="gxModalTitle">Add Photo</h5>
                                <p class="pl-modal-sub">Photo will appear on the public <code>/photogallery</code> page.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="pl-modal-body">
                            <input type="hidden" name="action" id="gxAction" value="create">
                            <input type="hidden" name="id" id="gxId" value="">

                            <div class="pl-field">
                                <label class="pl-label">Image <span class="pl-required" id="gxImgReq">*</span></label>
                                <input type="file" name="image" id="gxImage" class="pl-input" accept="image/*">
                                <div class="pl-hint">JPG, PNG, WEBP, AVIF or GIF — up to 10 MB. Leave empty when editing to keep current image.</div>
                            </div>

                            <div class="pl-field">
                                <label class="pl-label">Caption <span class="pl-required">*</span></label>
                                <input type="text" name="caption" id="gxCaption" class="pl-input" required
                                    placeholder="e.g. Granite kitchen island in Plano, TX">
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="pl-field mb-0">
                                        <label class="pl-label">Category</label>
                                        <select name="category" id="gxCategory" class="pl-input">
                                            <?php foreach (GalleryRepository::CATEGORIES as $k => $v): ?>
                                                <option value="<?= $k ?>"><?= $v ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="pl-field mb-0">
                                        <label class="pl-label">Material</label>
                                        <select name="material" id="gxMaterial" class="pl-input">
                                            <?php foreach (GalleryRepository::MATERIALS as $k => $v): ?>
                                                <option value="<?= $k ?>"><?= $v ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <div class="pl-field mb-0">
                                        <label class="pl-label">Sort Order</label>
                                        <input type="number" name="sort_order" id="gxSort" class="pl-input" value="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="pl-field mb-0">
                                        <label class="pl-label">Status</label>
                                        <select name="status" id="gxStatus" class="pl-input">
                                            <option value="active">Active</option>
                                            <option value="inactive">Hidden</option>
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

        <style>
            .gx-admin-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px; padding: 18px; }
            .gx-admin-card { background:#fff; border:1px solid var(--border); border-radius:12px; overflow:hidden; display:flex; flex-direction:column; }
            .gx-admin-img { aspect-ratio: 4/3; background:#f5f0e8; overflow:hidden; }
            .gx-admin-img img { width:100%; height:100%; object-fit:cover; display:block; }
            .gx-admin-body { padding:14px 14px 16px; display:flex; flex-direction:column; gap:8px; flex:1; }
            .gx-admin-tags { display:flex; gap:6px; flex-wrap:wrap; }
            .gx-admin-cap { font-size:12px; color:var(--text-sub); margin:0; line-height:1.5; flex:1; }
            .gx-admin-actions { display:flex; gap:6px; justify-content:flex-end; margin-top:auto; }
        </style>

        <script>
        (function(){
            const modal = document.getElementById('galleryModal');
            const form  = document.getElementById('galleryForm');
            modal.addEventListener('show.bs.modal', (ev) => {
                const btn  = ev.relatedTarget;
                const mode = btn?.dataset.mode || 'create';
                document.getElementById('gxAction').value = mode === 'edit' ? 'update' : 'create';
                document.getElementById('gxModalTitle').textContent = mode === 'edit' ? 'Edit Photo' : 'Add Photo';
                document.getElementById('gxImage').required = (mode === 'create');
                document.getElementById('gxImgReq').style.display = (mode === 'create') ? '' : 'none';
                if (mode === 'edit') {
                    document.getElementById('gxId').value       = btn.dataset.id;
                    document.getElementById('gxCaption').value  = btn.dataset.caption;
                    document.getElementById('gxCategory').value = btn.dataset.category;
                    document.getElementById('gxMaterial').value = btn.dataset.material;
                    document.getElementById('gxSort').value     = btn.dataset.sort;
                    document.getElementById('gxStatus').value   = btn.dataset.status;
                } else {
                    form.reset();
                    document.getElementById('gxId').value = '';
                }
            });
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const fd = new FormData(form);
                try {
                    const res = await fetch('../auth/gallery-action.php', { method:'POST', body:fd });
                    const data = await res.json();
                    if (data.success) { showToast('Saved.', 'success'); setTimeout(()=>location.reload(), 500); }
                    else showToast(data.message || 'Failed.', 'error');
                } catch { showToast('Network error.', 'error'); }
            });
            document.querySelectorAll('.delete-gx-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const ok = await confirmDialog({
                        title: 'Delete photo?',
                        message: 'This photo will be permanently deleted from the gallery.',
                        confirmText: 'Delete', icon: 'bx-trash',
                    });
                    if (!ok) return;
                    const fd = new FormData();
                    fd.append('action', 'delete');
                    fd.append('id', btn.dataset.id);
                    try {
                        const res = await fetch('../auth/gallery-action.php', { method:'POST', body:fd });
                        const data = await res.json();
                        if (data.success) { showToast('Deleted.', 'success'); btn.closest('.gx-admin-card')?.remove(); }
                        else showToast(data.message || 'Failed.', 'error');
                    } catch { showToast('Network error.', 'error'); }
                });
            });
        })();
        </script>
    </div>
</body>
</html>
