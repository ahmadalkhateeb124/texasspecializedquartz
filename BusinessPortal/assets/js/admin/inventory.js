/**
 * admin/inventory.js — list page: search + material filter + delete.
 */
(function () {
    const search = document.getElementById('slabSearch');
    const filter = document.getElementById('materialFilter');
    const rows   = () => document.querySelectorAll('#slabsTbody tr');

    function apply() {
        const q = search?.value.toLowerCase() || '';
        const m = filter?.value || '';
        rows().forEach(tr => {
            const mQ = !q || tr.textContent.toLowerCase().includes(q);
            const mM = !m || tr.dataset.material === m;
            tr.style.display = (mQ && mM) ? '' : 'none';
        });
    }
    search?.addEventListener('input',  apply);
    filter?.addEventListener('change', apply);

    document.querySelectorAll('.delete-slab-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id   = btn.dataset.id;
            const name = btn.dataset.name;
            const ok = await confirmDialog({
                title: 'Delete slab?',
                message: `"${name}" will be removed from the inventory.`,
                confirmText: 'Delete slab',
                icon: 'bx-trash',
            });
            if (!ok) return;

            const fd = new FormData();
            fd.append('id', id);
            try {
                const res  = await fetch('../auth/delete_inventory_slab.php', { method: 'POST', body: fd });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, 'success');
                    btn.closest('tr')?.remove();
                } else {
                    showToast(data.message || 'Failed to delete.', 'error');
                }
            } catch {
                showToast('Network error.', 'error');
            }
        });
    });
})();
