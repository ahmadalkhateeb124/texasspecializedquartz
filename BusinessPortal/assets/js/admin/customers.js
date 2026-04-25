/**
 * admin/customers.js — search + status filter + delete flow for the customers list.
 * CSRF token read from <meta name="csrf-token">.
 */
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    const search = document.getElementById('customerSearch');
    const filter = document.getElementById('statusFilter');
    const rows = () => document.querySelectorAll('#customersTbody tr');

    function applyFilter() {
        const q = search?.value.toLowerCase() || '';
        const s = filter?.value || '';
        rows().forEach(tr => {
            const matchQ = !q || tr.textContent.toLowerCase().includes(q);
            const matchS = !s || tr.dataset.status === s;
            tr.style.display = (matchQ && matchS) ? '' : 'none';
        });
    }

    search?.addEventListener('input',  applyFilter);
    filter?.addEventListener('change', applyFilter);

    document.querySelectorAll('.delete-customer-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id   = btn.dataset.id;
            const name = btn.dataset.name;
            const ok = await confirmDialog({
                title: 'Delete customer?',
                message: `"${name}" and all related data will be permanently deleted.`,
                confirmText: 'Delete customer',
                icon: 'bx-trash',
            });
            if (!ok) return;

            fetch('../auth/delete-customer.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    new URLSearchParams({ id, csrf_token: csrf }),
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message || 'Failed to delete customer', 'error');
                    }
                })
                .catch(() => showToast('Network error', 'error'));
        });
    });
})();
