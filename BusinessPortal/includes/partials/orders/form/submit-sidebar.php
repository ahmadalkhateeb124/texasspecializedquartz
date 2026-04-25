<div class="card" style="position:sticky;top:calc(var(--header-h) + 16px);">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-check-shield text-primary'></i> Submit Order</h6>
    </div>
    <div class="card-section">
        <p style="font-size:13px;color:var(--color-text-sub);margin-bottom:16px;">
            Review all sections before submitting. Required fields are marked
            <span class="text-danger">*</span>.
        </p>
        <div id="summaryChips" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;"></div>
        <button type="submit" class="btn btn-primary w-100" id="submitBtn">
            <span id="submitSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
            <i class='bx bx-send' id="submitIcon"></i>
            Submit Order
        </button>
        <a href="orders" class="btn btn-default w-100 mt-2">Cancel</a>
    </div>
</div>
