/**
 * admin/assign-employees.js — "Assign Employee" modal for an order's page.
 * One employee is responsible for the whole order (all its job sections).
 */
(function () {
    const csrf  = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const modalEl = document.getElementById('assignEmployeesModal');
    if (!modalEl) return;

    const modal    = new bootstrap.Modal(modalEl);
    const hiddenId = document.getElementById('assignOrderId');
    const radios   = () => modalEl.querySelectorAll('.assign-emp-radio');
    const saveBtn  = document.getElementById('saveAssignEmployeesBtn');

    document.querySelectorAll('.assign-employees-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const orderId   = btn.dataset.orderId;
            const currentId = btn.dataset.currentId || '';

            hiddenId.value = orderId;
            radios().forEach(r => { r.checked = (r.value === currentId); });

            modal.show();
        });
    });

    saveBtn?.addEventListener('click', async () => {
        const orderId = hiddenId.value;
        const selected = modalEl.querySelector('.assign-emp-radio:checked');
        const employeeId = selected ? selected.value : '';

        saveBtn.disabled = true;

        try {
            const body = new URLSearchParams({ order_id: orderId, employee_id: employeeId, csrf_token: csrf });

            const res  = await fetch('../auth/assign-order-employee.php', { method: 'POST', body });
            const data = await res.json();

            if (data.success) {
                showToast('Assignment saved.', 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(data.message || 'Could not save assignment.', 'error');
                saveBtn.disabled = false;
            }
        } catch {
            showToast('Network error.', 'error');
            saveBtn.disabled = false;
        }
    });
})();
