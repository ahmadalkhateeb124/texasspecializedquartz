/**
 * admin/orders.js — behavior for the admin Orders listing page.
 * Reads CSRF token from <meta name="csrf-token">.
 */
function _ordersInit() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    /* ── Delete flow (uses our custom confirmDialog from footer.php) ── */
    let deleteId = null;

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            deleteId = btn.dataset.id;
            const label = btn.dataset.label || ('order #' + deleteId);

            const ok = (typeof confirmDialog === 'function')
                ? await confirmDialog({
                    title:       'Delete this order?',
                    message:     `${label} will be permanently removed. This cannot be undone.`,
                    confirmText: 'Delete',
                    cancelText:  'Cancel',
                    type:        'danger',
                    icon:        'bx-trash'
                  })
                : window.confirm(`Delete ${label}? This cannot be undone.`);

            if (!ok) { deleteId = null; return; }

            const fd = new FormData();
            fd.append('id', deleteId);
            fd.append('csrf_token', csrfToken);

            try {
                const res = await fetch('../auth/delete-order.php', { method: 'POST', body: fd });
                const data = await res.json();
                if (data.success) {
                    if (typeof showToast === 'function') {
                        showToast(data.message || 'Order deleted.', 'success');
                    }
                    document.querySelector(`.delete-btn[data-id="${deleteId}"]`)?.closest('.ord-card')?.remove();
                    document.querySelector(`.delete-btn[data-id="${deleteId}"]`)?.closest('tr')?.remove();
                } else {
                    (typeof showToast === 'function' ? showToast : alert)(
                        data.message || 'Could not delete order.', 'error'
                    );
                }
            } catch {
                (typeof showToast === 'function' ? showToast : alert)(
                    'Network error. Please try again.', 'error'
                );
            }

            deleteId = null;
        });
    });

    /* ── View order modal ── */
    const viewModalEl = document.getElementById('viewOrderModal');
    const viewModal = (viewModalEl && typeof bootstrap !== 'undefined')
        ? new bootstrap.Modal(viewModalEl)
        : null;

    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const orderId = btn.dataset.id;
            loadOrderDetails(orderId);
            viewModal?.show();
        });
    });

    async function loadOrderDetails(orderId) {
        const detailsEl = document.getElementById('orderDetails');

        detailsEl.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border" role="status"></div>
                <p class="mt-2">Loading order details...</p>
            </div>`;

        try {
            const res = await fetch(`../auth/view_order.php?id=${orderId}`);
            const data = await res.json();

            if (data.success) {
                detailsEl.innerHTML = generateOrderHTML(data.order);
            } else {
                detailsEl.innerHTML = `
                    <div class="text-center py-5 text-danger">
                        <i class='bx bx-error' style="font-size:48px;"></i>
                        <p class="mt-2">${data.message || 'Failed to load order details'}</p>
                    </div>`;
            }
        } catch {
            detailsEl.innerHTML = `
                <div class="text-center py-5 text-danger">
                    <i class='bx bx-error' style="font-size:48px;"></i>
                    <p class="mt-2">Network error</p>
                </div>`;
        }
    }

    function formatThickness(thickness, custom) {
        if (thickness === '2cm') return '2 Cm';
        if (thickness === '3cm') return '3 Cm';
        if (thickness === 'custom' && custom) return custom + ' Cm';
        return thickness;
    }

    function generateOrderHTML(order) {
        let html = `
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3">Customer Information</h6>
                    <p><strong>Name:</strong> ${order.customer_name}</p>
                    <p><strong>Phone:</strong> ${order.phone}</p>
                    <p><strong>Address:</strong> ${order.address}, ${order.city} ${order.zip_code}</p>
                    <p><strong>Sales Rep:</strong> ${order.sales_rep} (${order.sales_rep_phone})</p>
                    <p><strong>PO Number:</strong> ${order.po_number || 'N/A'}</p>
                    <p><strong>Notes:</strong> ${order.notes || 'N/A'}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3">Order Details</h6>
                    <p><strong>Order ID:</strong> #${String(order.id).padStart(4, '0')}</p>
                    <p><strong>Created:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                    <p><strong>Updated:</strong> ${new Date(order.updated_at).toLocaleString()}</p>
                    ${order.image ? `<p><strong>Attachment:</strong> <a href="/assets/products/${order.image}" download>Download File</a></p>` : ''}
                </div>
            </div>
            <hr>
            <h6 class="fw-bold mb-3">Job Sections</h6>`;

        if (order.jobs && order.jobs.length > 0) {
            order.jobs.forEach((job, index) => {
                html += `
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Job ${index + 1}: ${job.job_type} ${job.job_type_other ? `(${job.job_type_other})` : ''}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Material:</strong> ${job.material_type} ${job.material_other ? `(${job.material_other})` : ''}</p>
                                    <p><strong>Thickness:</strong> ${formatThickness(job.thickness, job.thickness_custom)}</p>
                                    <p><strong>Color:</strong> ${job.material_color}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Edge Profile:</strong> ${job.edge_profile} ${job.edge_profile_custom ? `(${job.edge_profile_custom})` : ''}</p>
                                    <p><strong>Sink:</strong> ${job.sink_provider} ${job.sink_type} ${job.sink_style} ${job.sink_style_other ? `(${job.sink_style_other})` : ''}</p>
                                    <p><strong>Tear Out:</strong> ${job.tear_out}</p>
                                </div>
                            </div>
                        </div>
                    </div>`;
            });
        } else {
            html += '<p>No job sections found.</p>';
        }

        return html;
    }
}

/* Wait for DOM + Bootstrap before wiring up handlers */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', _ordersInit);
} else {
    _ordersInit();
}
