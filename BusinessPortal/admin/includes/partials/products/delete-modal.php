<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Delete Product</h6>
                <button class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex gap-3 align-items-start">
                    <div style="width:40px;height:40px;background:var(--color-critical-l);border-radius:var(--radius-sm);
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class='bx bx-trash' style="font-size:20px;color:var(--color-critical);"></i>
                    </div>
                    <div>
                        <p style="font-weight:600;margin:0 0 4px;" id="deleteTitle"></p>
                        <p style="font-size:13px;color:var(--color-text-sub);margin:0;">
                            This product will be permanently deleted. This action cannot be undone.
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-critical btn-sm" id="confirmDeleteBtn">
                    <span id="deleteSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                    Delete product
                </button>
            </div>
        </div>
    </div>
</div>
