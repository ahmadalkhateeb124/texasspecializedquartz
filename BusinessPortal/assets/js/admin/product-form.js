/**
 * admin/product-form.js — used by products-new + products-edit.
 * Behaviour differs by form id.
 */
(function () {
    document.getElementById('productImageInput')?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) {
            showToast('Image must be under 5 MB.', 'error');
            this.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = e => {
            const ph  = document.getElementById('uploadPlaceholder');
            const img = document.getElementById('imagePreviewImg');
            if (ph)  ph.style.display = 'none';
            if (img) { img.src = e.target.result; img.style.display = 'block'; }
        };
        reader.readAsDataURL(file);
    });

    const form = document.getElementById('productForm') || document.getElementById('editForm');
    if (!form) return;

    const isEdit = form.id === 'editForm';

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

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
                showToast(data.message || (isEdit ? 'Product updated.' : 'Product created.'), 'success');
                setTimeout(() => window.location.href = 'products', 1200);
            } else {
                const msg = (data.errors && Object.values(data.errors).join('. ')) || data.message ||
                            (isEdit ? 'Could not update product.' : 'Could not create product.');
                showToast(msg, 'error');
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
