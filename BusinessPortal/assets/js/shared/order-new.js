/**
 * admin/order-new.js — New Fabrication Order form logic.
 */
(function () {
    const activeJobs = new Set();

    const JOB_META = [
        { id: 'jobKitchen',        key: 'kitchen',        label: 'Kitchen' },
        { id: 'jobBathroom',       key: 'bathroom',       label: 'Bathroom' },
        { id: 'jobMasterBathroom', key: 'MasterBathroom', label: 'Master Bath' },
        { id: 'jobOther',          key: 'other',          label: 'Other' },
    ];

    window.toggleJobCard = function (jobKey) {
        const cbId = 'job' + jobKey.charAt(0).toUpperCase() + jobKey.slice(1);
        const cb = document.getElementById(cbId);
        const card = document.getElementById('card-' + jobKey);
        if (!cb) return;
        cb.checked = !cb.checked;
        card?.classList.toggle('active', cb.checked);
        toggleJobSections();
        updateSummaryChips();
    };

    window.toggleJobSections = function () {
        const container = document.getElementById('jobSectionsContainer');
        const template  = document.getElementById('jobSectionTemplate');
        const alertEl   = document.getElementById('jobSelectionAlert');
        const alertText = document.getElementById('jobAlertText');

        const selected = JOB_META.filter(j => document.getElementById(j.id)?.checked);

        if (selected.length) {
            alertEl.classList.remove('d-none');
            alertText.textContent = 'Selected: ' + selected.map(j => j.label).join(', ');
        } else {
            alertEl.classList.add('d-none');
        }

        container.querySelectorAll('.job-section').forEach(sec => {
            const k = sec.getAttribute('data-job-type');
            if (!selected.some(j => j.key === k)) {
                sec.remove();
                activeJobs.delete(k);
            }
        });

        selected.forEach(j => {
            if (!activeJobs.has(j.key)) {
                const frag = template.content.cloneNode(true);
                const div = frag.querySelector('.job-section');
                div.setAttribute('data-job-type', j.key);
                div.querySelector('.job-type-label').textContent = j.label;
                if (j.key === 'other') {
                    const f = div.querySelector('.job-type-other-field');
                    const i = div.querySelector('.job-type-other-input');
                    if (f) f.style.display = 'block';
                    if (i) i.required = true;
                }
                container.appendChild(div);
                activeJobs.add(j.key);
                initSectionHandlers(div);
            }
        });

        updateSummaryChips();
    };

    function initSectionHandlers(sec) {
        bindToggle(sec.querySelector('select[name="material_type[]"]'),
            sec.querySelector('input[name="material_other[]"]'),
            sec.querySelector('.other-material-input'));
        bindToggle(sec.querySelector('select[name="thickness[]"]'),
            sec.querySelector('input[name="thickness_custom[]"]'),
            sec.querySelector('.custom-thickness-input'));
        bindToggle(sec.querySelector('select[name="edge_profile[]"]'),
            sec.querySelector('input[name="edge_profile_custom[]"]'),
            sec.querySelector('.custom-edge-input'));
        bindToggle(sec.querySelector('select[name="sink_style[]"]'),
            sec.querySelector('input[name="sink_style_other[]"]'),
            sec.querySelector('.other-sink-style-input'));

        const provSel = sec.querySelector('select[name="sink_provider[]"]');
        if (provSel) {
            provSel.addEventListener('change', () => {
                const col = sec.querySelector('.sink-style-field');
                const sel = sec.querySelector('select[name="sink_style[]"]');
                const isAA = provSel.value === 'ts_granite';
                if (col) col.style.display = isAA ? 'block' : 'none';
                if (sel) {
                    sel.required = isAA;
                    if (!isAA) {
                        sel.value = '';
                        const oi = sec.querySelector('input[name="sink_style_other[]"]');
                        const ob = sec.querySelector('.other-sink-style-input');
                        if (oi) { oi.value = ''; oi.required = false; }
                        if (ob) ob.style.display = 'none';
                    }
                }
            });
        }
    }

    function bindToggle(sel, inp, box) {
        if (!sel || !inp || !box) return;
        sel.addEventListener('change', () => {
            const isSpec = ['other', 'custom'].includes(sel.value);
            box.style.display = isSpec ? 'block' : 'none';
            inp.required = isSpec;
            if (!isSpec) inp.value = '';
            if (isSpec) inp.focus();
        });
    }

    function updateSummaryChips() {
        const chips = document.getElementById('summaryChips');
        if (!chips) return;
        const sel = JOB_META.filter(j => document.getElementById(j.id)?.checked);
        chips.innerHTML = sel.map(j =>
            `<span class="summary-chip"><i class='bx bx-layer'></i>${j.label}</span>`
        ).join('');
    }

    /* ── File upload preview ── */
    document.getElementById('fileInput').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 20 * 1024 * 1024) {
            showToast('File must be under 20 MB.', 'error');
            this.value = '';
            return;
        }
        const allowed = [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];
        if (!allowed.includes(file.type)) {
            showToast('Invalid file type.', 'error');
            this.value = '';
            return;
        }

        document.getElementById('filePlaceholder').style.display = 'none';
        const preview = document.getElementById('filePreview');
        preview.style.display = 'block';

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML = `<img src="${e.target.result}"
                    style="max-height:200px;max-width:100%;border-radius:var(--radius-sm);object-fit:contain;">`;
            };
            reader.readAsDataURL(file);
        } else {
            const ext = file.name.split('.').pop().toLowerCase();
            const icons = {
                pdf: 'bx-file-pdf', doc: 'bx-file-doc', docx: 'bx-file-doc',
                xls: 'bxs-spreadsheet', xlsx: 'bxs-spreadsheet'
            };
            preview.innerHTML = `
                <div style="display:flex;align-items:center;gap:12px;padding:12px;
                            background:var(--color-bg);border-radius:var(--radius-sm);">
                    <i class='bx ${icons[ext] || "bx-file"}' style="font-size:32px;color:var(--color-primary);"></i>
                    <div>
                        <div style="font-weight:600;font-size:13px;">${file.name}</div>
                        <div style="font-size:11px;color:var(--color-text-sub);">
                            ${ext.toUpperCase()} · ${(file.size / 1024 / 1024).toFixed(2)} MB
                        </div>
                    </div>
                </div>`;
        }
    });

    /* ── Form validation ── */
    function validateForm() {
        const errors = [];
        [
            ['input[name="customer_name"]',   'Customer name is required'],
            ['input[name="address"]',         'Address is required'],
            ['input[name="sales_rep"]',       'Sales rep is required'],
            ['input[name="sales_rep_phone"]', 'Sales rep phone is required'],
            ['input[name="city"]',            'City is required'],
            ['input[name="ZipCode"]',         'ZIP code is required'],
        ].forEach(([s, m]) => {
            const el = document.querySelector(s);
            if (!el || !el.value.trim()) {
                errors.push(m);
                markInvalid(el);
            }
        });

        if (activeJobs.size === 0) {
            errors.push('Select at least one job area.');
        } else {
            document.querySelectorAll('.job-section').forEach(sec => {
                const label = sec.querySelector('.job-type-label')?.textContent || 'Job';
                if (sec.getAttribute('data-job-type') === 'other') {
                    const inp = sec.querySelector('input[name="job_type_other[]"]');
                    if (inp && !inp.value.trim()) {
                        errors.push(`Specify custom job type in "${label}" section`);
                        markInvalid(inp);
                    }
                }
                sec.querySelectorAll('[required]').forEach(inp => {
                    const hidden = inp.closest('[style*="display: none"]') || inp.closest('[style*="display:none"]');
                    if (!hidden && !inp.value.trim()) {
                        const lbl = inp.closest('.col-sm-4,.col-sm-6')?.querySelector('label')?.textContent?.trim() || 'Field';
                        errors.push(`"${lbl}" is required in ${label} section`);
                        markInvalid(inp);
                    }
                });
            });
        }
        return errors;
    }

    function markInvalid(el) {
        if (!el) return;
        el.classList.add('is-invalid');
        el.addEventListener('input', () => el.classList.remove('is-invalid'), { once: true });
    }

    /* ── Form submit ── */
    document.getElementById('orderForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const errors = validateForm();
        if (errors.length) {
            errors.forEach(m => showToast(m, 'error'));
            return;
        }

        const btn     = document.getElementById('submitBtn');
        const spinner = document.getElementById('submitSpinner');
        const icon    = document.getElementById('submitIcon');
        btn.disabled = true;
        spinner.classList.remove('d-none');
        icon.classList.add('d-none');

        const fd = new FormData(this);
        fd.delete('job_types[]');
        fd.delete('job_type_other[]');
        document.querySelectorAll('.job-section').forEach(sec => {
            fd.append('job_types[]', sec.getAttribute('data-job-type'));
            const oi = sec.querySelector('input[name="job_type_other[]"]');
            fd.append('job_type_other[]', oi ? oi.value.trim() : '');
        });

        try {
            const res  = await fetch(this.action, { method: 'POST', body: fd });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Order submitted!', 'success');
                setTimeout(() => window.location.href = 'orders', 1400);
            } else {
                showToast(data.message || 'Could not submit order.', 'error');
                btn.disabled = false;
                spinner.classList.add('d-none');
                icon.classList.remove('d-none');
            }
        } catch {
            showToast('Network error. Please try again.', 'error');
            btn.disabled = false;
            spinner.classList.add('d-none');
            icon.classList.remove('d-none');
        }
    });

    /* ── Misc UI wiring ── */
    document.addEventListener('DOMContentLoaded', () => {
        const notes   = document.querySelector('textarea[name="notes"]');
        const counter = document.getElementById('notesCounter');
        if (notes && counter) {
            notes.addEventListener('input', () => {
                if (notes.value.length > 500) notes.value = notes.value.slice(0, 500);
                counter.textContent = notes.value.length;
            });
        }
        document.querySelectorAll('#orderForm input:not([type="submit"])').forEach(inp => {
            inp.addEventListener('keydown', e => {
                if (e.key === 'Enter') e.preventDefault();
            });
        });
    });
})();
