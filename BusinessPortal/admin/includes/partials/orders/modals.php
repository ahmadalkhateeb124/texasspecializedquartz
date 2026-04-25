<?php
/**
 * Orders page modals: delete confirm + view details.
 */
?>
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-danger">
                    <i class='bx bx-trash me-2'></i>Delete Order
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class='bx bx-error-circle' style="font-size:48px;color:var(--danger);"></i>
                <p class="mt-3 mb-1 fw-600" id="deleteLabel" style="font-weight:600;"></p>
                <p class="text-muted mb-0" style="font-size:13px;">
                    This action will permanently delete the order and all associated job sections.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <span id="deleteSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                    Delete Order
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewOrderModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class='bx bx-show me-2'></i>Order Details
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetails">
                <div class="text-center py-5">
                    <div class="spinner-border" role="status"></div>
                    <p class="mt-2">Loading order details...</p>
                </div>
            </div>
        </div>
    </div>
</div>
