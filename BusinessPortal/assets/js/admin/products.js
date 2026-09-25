/**
 * admin/products.js — list page: search, filter, delete flow.
 */
(function () {
    const search    = document.getElementById('productSearch');
    const filterSel = document.getElementById('availFilter');
    const rows      = () => document.querySelectorAll('#productsTbody tr');

    function applyFilter() {
        const q = search?.value.toLowerCase() || '';
        const a = filterSel?.value || '';
        rows().forEach(tr => {
            const matchQ = !q || tr.textContent.toLowerCase().includes(q);
            const matchA = !a || tr.dataset.avail === a;
            tr.style.display = (matchQ && matchA) ? '' : 'none';
        });
    }
    search?.addEventListener('input', applyFilter);
    filterSel?.addEventListener('change', applyFilter);

    let deleteId = null;
    const modalEl = document.getElementById('deleteModal');
    const modal   = modalEl ? new bootstrap.Modal(modalEl) : null;

    document.querySelectorAll('.delete-product-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            deleteId = btn.dataset.id;
            document.getElementById('deleteTitle').textContent = 'Delete "' + btn.dataset.title + '"?';
            modal?.show();
        });
    });

    document.getElementById('confirmDeleteBtn')?.addEventListener('click', async () => {
        if (!deleteId) return;
        const btn     = document.getElementById('confirmDeleteBtn');
        const spinner = document.getElementById('deleteSpinner');
        btn.disabled = true;
        spinner.classList.remove('d-none');

        const fd = new FormData();
        fd.append('id', deleteId);

        try {
            const res  = await fetch('../auth/delete-product.php', { method: 'POST', body: fd });
            const data = await res.json();
            modal?.hide();
            if (data.success) {
                document.querySelector(`.delete-product-btn[data-id="${deleteId}"]`)
                    ?.closest('tr')?.remove();
                showToast(data.message || 'Product deleted.', 'success');
            } else {
                showToast(data.message || 'Could not delete product.', 'error');
            }
        } catch {
            modal?.hide();
            showToast('Network error.', 'error');
        }

        btn.disabled = false;
        spinner.classList.add('d-none');
        deleteId = null;
    });
})();
