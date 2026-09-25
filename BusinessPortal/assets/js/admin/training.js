/**
 * admin/training.js — upload / edit / delete videos (grid view).
 */
(function () {
    /* ═══ Search ═══ */
    document.getElementById('videoSearch')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#videosGrid .video-grid-item').forEach(el => {
            el.style.display = !q || el.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    /* ═══ File upload zones ═══ */
    function wireFileZone(inputId, selectedBoxId) {
        const inp = document.getElementById(inputId);
        const box = document.getElementById(selectedBoxId);
        if (!inp || !box) return;
        const zone  = inp.closest('.pl-file-zone');
        const ph    = zone.querySelector('.pl-file-placeholder');
        const nameE = box.querySelector('.pl-file-name');
        const clr   = box.querySelector('.pl-file-clear');

        inp.addEventListener('change', () => {
            const f = inp.files[0];
            if (!f) return reset();
            nameE.textContent = f.name;
            ph.style.display  = 'none';
            box.style.display = 'flex';
        });
        clr?.addEventListener('click', (e) => { e.stopPropagation(); e.preventDefault(); inp.value = ''; reset(); });
        function reset() { nameE.textContent = ''; box.style.display = 'none'; ph.style.display = 'block'; }
    }
    wireFileZone('videoFile',     'uploadVideoSelected');
    wireFileZone('editVideoFile', 'editVideoSelected');

    document.getElementById('uploadVideoModal')?.addEventListener('hidden.bs.modal', () => {
        document.getElementById('uploadVideoForm').reset();
        document.getElementById('uploadVideoSelected').style.display = 'none';
        document.getElementById('uploadVideoZone').querySelector('.pl-file-placeholder').style.display = 'block';
    });

    /* ═══ Upload ═══ */
    document.getElementById('uploadVideoForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn = document.getElementById('uploadBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="pl-spinner" style="width:14px;height:14px;border-color:rgba(255,255,255,.35);border-top-color:#fff;margin-right:8px;"></span>Uploading…';
        try {
            const res  = await fetch('../auth/upload-video.php', { method: 'POST', body: new FormData(this) });
            const data = await res.json();
            if (data.success) {
                showToast('Video uploaded.', 'success');
                bootstrap.Modal.getInstance(document.getElementById('uploadVideoModal'))?.hide();
                setTimeout(() => location.reload(), 900);
            } else {
                showToast(data.message || 'Failed to upload video.', 'error');
            }
        } catch {
            showToast('Network error.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-upload me-1"></i> Upload Video';
        }
    });

    /* ═══ Edit ═══ */
    document.querySelectorAll('.edit-video-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('editVideoId').value   = btn.dataset.id;
            document.getElementById('editVideoName').value = btn.dataset.name;
        });
    });

    document.getElementById('editVideoForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn = document.getElementById('editBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="pl-spinner" style="width:14px;height:14px;border-color:rgba(255,255,255,.35);border-top-color:#fff;margin-right:8px;"></span>Saving…';
        try {
            const res  = await fetch('../auth/update-video.php', { method: 'POST', body: new FormData(this) });
            const data = await res.json();
            if (data.success) {
                showToast('Video updated.', 'success');
                bootstrap.Modal.getInstance(document.getElementById('editVideoModal'))?.hide();
                setTimeout(() => location.reload(), 900);
            } else {
                showToast(data.message || 'Failed to update video.', 'error');
            }
        } catch {
            showToast('Network error.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-check me-1"></i> Save Changes';
        }
    });

    /* ═══ Delete ═══ */
    document.querySelectorAll('.delete-video-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id   = btn.dataset.id;
            const name = btn.dataset.name;
            const ok = await confirmDialog({
                title: 'Delete video?',
                message: `"${name}" will be permanently removed.`,
                confirmText: 'Delete video',
                icon: 'bx-trash',
            });
            if (!ok) return;

            const fd = new FormData();
            fd.append('video_id', id);
            try {
                const res  = await fetch('../auth/delete-video.php', { method: 'POST', body: fd });
                const data = await res.json();
                if (data.success) {
                    showToast('Video deleted.', 'success');
                    setTimeout(() => location.reload(), 600);
                } else {
                    showToast(data.message || 'Failed to delete video.', 'error');
                }
            } catch {
                showToast('Network error.', 'error');
            }
        });
    });
})();
