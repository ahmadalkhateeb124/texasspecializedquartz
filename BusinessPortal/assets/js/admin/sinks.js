/**
 * admin/sinks.js — list page: search + category filter + delete flow.
 */
(function () {
    const search = document.getElementById('sinkSearch');
    const filter = document.getElementById('categoryFilter');
    const rows   = () => document.querySelectorAll('#sinksTbody tr');

    function apply() {
        const q = search?.value.toLowerCase() || '';
        const c = filter?.value || '';
        rows().forEach(tr => {
            const matchQ = !q || tr.textContent.toLowerCase().includes(q);
            const matchC = !c || tr.dataset.category === c;
            tr.style.display = (matchQ && matchC) ? '' : 'none';
        });
    }
    search?.addEventListener('input', apply);
    filter?.addEventListener('change', apply);

    document.querySelectorAll('.delete-sink-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id   = btn.dataset.id;
            const name = btn.dataset.name;
            const ok = await confirmDialog({
                title: 'Delete sink?',
                message: `"${name}" will be removed from the catalog.`,
                confirmText: 'Delete sink',
                icon: 'bx-trash',
            });
            if (!ok) return;

            const fd = new FormData();
            fd.append('id', id);
            try {
                const res  = await fetch('../auth/delete_sink.php', { method: 'POST', body: fd });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, 'success');
                    btn.closest('tr')?.remove();
                } else {
                    showToast(data.message || 'Failed to delete sink.', 'error');
                }
            } catch {
                showToast('Network error.', 'error');
            }
        });
    });
})();
