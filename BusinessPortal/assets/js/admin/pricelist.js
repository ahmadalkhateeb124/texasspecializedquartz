/**
 * admin/pricelist.js — upload / edit / delete / view assigned (native picker, no Select2).
 * Reads available-count from <meta name="pricelist-available-count">.
 */
(function () {
    const availableCount = parseInt(
        document.querySelector('meta[name="pricelist-available-count"]')?.content || '0',
        10
    );

    /* ═══ Search on list page ═══ */
    document.getElementById('priceListSearch')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#priceListTbody tr').forEach(tr => {
            tr.style.display = !q || tr.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    /* ═══ Customer picker ═══ */
    function initPicker(pickerEl) {
        if (!pickerEl || pickerEl.dataset.init === '1') return;
        pickerEl.dataset.init = '1';

        const hiddenSel = document.getElementById(pickerEl.dataset.select);
        const searchInp = pickerEl.querySelector('[data-picker-search]');
        const list      = pickerEl.querySelector('[data-picker-list]');
        const badge     = pickerEl.querySelector('[data-count]');
        const counter   = pickerEl.querySelector('[data-selected-count]');

        function sync() {
            const checked = [...pickerEl.querySelectorAll('[data-picker-check]:checked')].map(c => c.value);
            if (hiddenSel) [...hiddenSel.options].forEach(o => { o.selected = checked.includes(o.value); });
            if (badge)   { badge.textContent = `${checked.length} / ${availableCount}`; badge.classList.toggle('has-selection', checked.length > 0); }
            if (counter) counter.textContent = checked.length;
            pickerEl.querySelectorAll('[data-picker-check]').forEach(c =>
                c.closest('.pl-picker-item')?.classList.toggle('selected', c.checked)
            );
        }

        pickerEl.querySelectorAll('[data-picker-check]').forEach(c => c.addEventListener('change', sync));

        searchInp?.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            list.querySelectorAll('.pl-picker-item').forEach(el => {
                el.classList.toggle('hidden', q && !el.dataset.name.includes(q));
            });
        });

        pickerEl.querySelector('[data-picker-all]')?.addEventListener('click', () => {
            pickerEl.querySelectorAll('[data-picker-check]').forEach(c => {
                if (!c.closest('.pl-picker-item')?.classList.contains('hidden')) c.checked = true;
            });
            sync();
        });
        pickerEl.querySelector('[data-picker-clear]')?.addEventListener('click', () => {
            pickerEl.querySelectorAll('[data-picker-check]').forEach(c => c.checked = false);
            sync();
        });

        sync();
    }
    document.querySelectorAll('.pl-picker').forEach(initPicker);

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
    wireFileZone('priceListFile',     'uploadFileSelected');
    wireFileZone('editPriceListFile', 'editFileSelected');

    /* ═══ Reset upload form on close ═══ */
    document.getElementById('uploadPriceListModal')?.addEventListener('hidden.bs.modal', () => {
        const form = document.getElementById('uploadPriceListForm');
        form.reset();
        document.querySelectorAll('#uploadPriceListModal .pl-picker').forEach(p => {
            p.querySelectorAll('[data-picker-check]').forEach(c => c.checked = false);
            p.querySelectorAll('[data-picker-check]').forEach(c =>
                c.closest('.pl-picker-item')?.classList.remove('selected')
            );
            const badge = p.querySelector('[data-count]');
            const counter = p.querySelector('[data-selected-count]');
            if (badge)   { badge.textContent = `0 / ${availableCount}`; badge.classList.remove('has-selection'); }
            if (counter) counter.textContent = 0;
            const search = p.querySelector('[data-picker-search]');
            if (search) { search.value = ''; p.querySelectorAll('.pl-picker-item.hidden').forEach(el => el.classList.remove('hidden')); }
        });
        document.getElementById('uploadFileSelected').style.display = 'none';
        document.getElementById('uploadFileZone').querySelector('.pl-file-placeholder').style.display = 'block';
    });

    /* ═══ Upload submit ═══ */
    document.getElementById('uploadPriceListForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn = document.getElementById('uploadBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="pl-spinner" style="width:14px;height:14px;border-color:rgba(255,255,255,.35);border-top-color:#fff;margin-right:8px;"></span>Uploading…';
        try {
            const res  = await fetch('../auth/upload-pricelist.php', { method: 'POST', body: new FormData(this) });
            const data = await res.json();
            if (data.success) {
                showToast('Price list uploaded.', 'success');
                setTimeout(() => location.reload(), 900);
            } else {
                showToast(data.message || 'Failed to upload price list.', 'error');
            }
        } catch {
            showToast('Network error.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-upload me-1"></i> Upload';
        }
    });

    /* ═══ Edit triggers ═══ */
    document.querySelectorAll('.edit-pricelist-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id   = btn.dataset.id;
            document.getElementById('editPriceListId').value = id;
            document.getElementById('editFileName').value    = btn.dataset.name;

            const picker  = document.querySelector('#editPriceListModal .pl-picker');
            const loading = document.getElementById('editAssignLoading');
            loading && (loading.style.display = '');

            picker?.querySelectorAll('[data-picker-check]').forEach(c => { c.checked = false; });

            fetch(`../auth/get-assigned-accounts.php?price_list_id=${id}`)
                .then(r => r.json())
                .then(data => {
                    const ids = (data?.accounts || []).map(a => String(a.id));
                    picker?.querySelectorAll('[data-picker-check]').forEach(c => {
                        c.checked = ids.includes(c.value);
                        c.closest('.pl-picker-item')?.classList.toggle('selected', c.checked);
                    });
                    const hidden = document.getElementById('editAssignAccounts');
                    if (hidden) [...hidden.options].forEach(o => { o.selected = ids.includes(o.value); });
                    const badge = picker?.querySelector('[data-count]');
                    const counter = picker?.querySelector('[data-selected-count]');
                    if (badge)   { badge.textContent = `${ids.length} / ${availableCount}`; badge.classList.toggle('has-selection', ids.length > 0); }
                    if (counter) counter.textContent = ids.length;
                })
                .finally(() => loading && (loading.style.display = 'none'));
        });
    });

    /* ═══ Edit submit ═══ */
    document.getElementById('editPriceListForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn = document.getElementById('editBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="pl-spinner" style="width:14px;height:14px;border-color:rgba(255,255,255,.35);border-top-color:#fff;margin-right:8px;"></span>Saving…';
        try {
            const res  = await fetch('../auth/update-pricelist.php', { method: 'POST', body: new FormData(this) });
            const data = await res.json();
            if (data.success) {
                showToast('Price list updated.', 'success');
                bootstrap.Modal.getInstance(document.getElementById('editPriceListModal'))?.hide();
                setTimeout(() => location.reload(), 900);
            } else {
                showToast(data.message || 'Failed to update price list.', 'error');
            }
        } catch {
            showToast('Network error.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-check me-1"></i> Save Changes';
        }
    });

    /* ═══ Delete ═══ */
    document.querySelectorAll('.delete-pricelist-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id   = btn.dataset.id;
            const name = btn.dataset.name;
            const ok = await confirmDialog({
                title: 'Delete price list?',
                message: `"${name}" will be permanently deleted.`,
                confirmText: 'Delete price list',
                icon: 'bx-trash',
            });
            if (!ok) return;

            const fd = new FormData();
            fd.append('price_list_id', id);
            try {
                const res  = await fetch('../auth/delete-pricelist.php', { method: 'POST', body: fd });
                const data = await res.json();
                if (data.success) {
                    showToast('Price list deleted.', 'success');
                    setTimeout(() => location.reload(), 600);
                } else {
                    showToast(data.message || 'Failed to delete price list.', 'error');
                }
            } catch {
                showToast('Network error.', 'error');
            }
        });
    });

    /* ═══ View assigned ═══ */
    document.querySelectorAll('.view-assigned-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const listEl = document.getElementById('assignedAccountsList');
            listEl.innerHTML = `
                <div class="pl-picker-empty">
                    <div class="pl-spinner"></div>
                    <div style="font-size:13px;color:var(--text-sub);margin-top:8px;">Loading…</div>
                </div>`;

            fetch(`../auth/get-assigned-accounts.php?price_list_id=${id}`)
                .then(r => r.json())
                .then(data => {
                    const accounts = data?.accounts || [];
                    if (accounts.length === 0) {
                        listEl.innerHTML = `
                            <div class="pl-picker-empty">
                                <i class='bx bx-user-x'></i>
                                No customers assigned to this price list
                            </div>`;
                        return;
                    }
                    const rows = accounts.map(a => {
                        const initials = (a.name || 'C').substring(0, 2).toUpperCase();
                        return `
                            <div class="pl-assigned-item">
                                <div class="pl-picker-avatar">${initials}</div>
                                <div class="pl-picker-info">
                                    <div class="pl-picker-name">${a.name}</div>
                                    <div class="pl-picker-email">${a.email}</div>
                                </div>
                            </div>`;
                    }).join('');
                    listEl.innerHTML = `
                        <div style="margin-bottom:14px;font-size:12px;color:var(--text-sub);font-weight:500;">
                            ${accounts.length} customer${accounts.length !== 1 ? 's' : ''} assigned
                        </div>
                        <div class="pl-assigned-list">${rows}</div>`;
                })
                .catch(() => {
                    listEl.innerHTML = `
                        <div class="pl-picker-empty">
                            <i class='bx bx-error-circle'></i>
                            Failed to load assignments
                        </div>`;
                });
        });
    });
})();
