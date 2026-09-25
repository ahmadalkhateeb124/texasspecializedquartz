/**
 * admin/sink-form.js — shared by sinks-new and sinks-edit.
 */
(function () {
    document.getElementById('imageInput')?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 10 * 1024 * 1024) {
            showToast('Image must be under 10MB.', 'error');
            this.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('sinkPreview');
            if (img) {
                img.src = e.target.result;
                img.style.display = 'block';
            }
        };
        reader.readAsDataURL(file);
    });

    const form = document.getElementById('sinkForm');
    if (!form) return;

    const isEdit = form.dataset.mode === 'edit';

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const btn     = document.getElementById('submitBtn');
        const spinner = document.getElementById('submitSpinner');
        const icon    = document.getElementById('submitIcon');
        btn.disabled  = true;
        spinner?.classList.remove('d-none');
        icon?.classList.add('d-none');

        try {
            const res  = await fetch(this.action, { method: 'POST', body: new FormData(this) });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || (isEdit ? 'Sink updated.' : 'Sink added.'), 'success');
                setTimeout(() => window.location.href = 'sinks', 1000);
            } else {
                const msg = (data.errors && data.errors.join('. ')) || data.message || 'Could not save sink.';
                showToast(msg, 'error');
                btn.disabled = false;
                spinner?.classList.add('d-none');
                icon?.classList.remove('d-none');
            }
        } catch {
            showToast('Network error.', 'error');
            btn.disabled = false;
            spinner?.classList.add('d-none');
            icon?.classList.remove('d-none');
        }
    });
})();
