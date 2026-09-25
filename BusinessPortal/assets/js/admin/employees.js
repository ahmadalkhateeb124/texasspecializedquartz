/**
 * admin/employees.js — search + status filter + delete flow for the employees list.
 * CSRF token read from <meta name="csrf-token">.
 */
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    const search = document.getElementById('employeeSearch');
    const filter = document.getElementById('statusFilter');
    const rows = () => document.querySelectorAll('#employeesTbody tr');

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

    document.querySelectorAll('.delete-employee-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id   = btn.dataset.id;
            const name = btn.dataset.name;
            const ok = await confirmDialog({
                title: 'Delete employee?',
                message: `"${name}" will be permanently removed and unassigned from all job sections.`,
                confirmText: 'Delete employee',
                icon: 'bx-trash',
            });
            if (!ok) return;

            fetch('../auth/delete-employee.php', {
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
                        showToast(data.message || 'Failed to delete employee', 'error');
                    }
                })
                .catch(() => showToast('Network error', 'error'));
        });
    });
})();
