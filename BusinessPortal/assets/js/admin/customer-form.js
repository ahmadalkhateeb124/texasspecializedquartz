/**
 * admin/customer-form.js — used by both customers-new and customers-edit.
 * Behaviour differs slightly by form id / success redirect.
 */
(function () {
    // Password toggles
    document.querySelectorAll('.toggle-pwd').forEach(btn => {
        btn.addEventListener('click', () => {
            const f = document.getElementById(btn.dataset.target);
            if (!f) return;
            const i = btn.querySelector('i');
            if (f.type === 'password') { f.type = 'text'; i.className = 'bx bx-hide'; }
            else                       { f.type = 'password'; i.className = 'bx bx-show'; }
        });
    });

    const form = document.getElementById('customerForm') || document.getElementById('editForm');
    if (!form) return;

    const isEdit = form.id === 'editForm';

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Password match check (only on new form)
        const pw  = document.getElementById('passwordField');
        const cpw = document.getElementById('confirmField');
        if (pw && cpw && pw.value !== cpw.value) {
            showToast('Passwords do not match.', 'error');
            return;
        }

        const btn     = document.getElementById('submitBtn');
        const spinner = document.getElementById('submitSpinner');
        const icon    = document.getElementById('submitIcon');
        if (btn) btn.disabled = true;
        spinner?.classList.remove('d-none');
        icon?.classList.add('d-none');

        try {
            const res  = await fetch(this.action, { method: 'POST', body: new FormData(this) });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || (isEdit ? 'Customer updated.' : 'Customer created.'), 'success');
                setTimeout(() => window.location.href = 'customers', 1200);
            } else {
                showToast(data.message || (isEdit ? 'Could not update customer.' : 'Could not create customer.'), 'error');
                if (btn) btn.disabled = false;
                spinner?.classList.add('d-none');
                icon?.classList.remove('d-none');
            }
        } catch {
            showToast('Network error.', 'error');
            if (btn) btn.disabled = false;
            spinner?.classList.add('d-none');
            icon?.classList.remove('d-none');
        }
    });
})();
