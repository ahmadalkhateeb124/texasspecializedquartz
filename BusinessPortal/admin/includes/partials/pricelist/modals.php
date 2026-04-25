<?php /** Expects: $accounts */ ?>

<!-- ═══ Upload Price List Modal ═══ -->
<div class="modal fade" id="uploadPriceListModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content pl-modal">
            <div class="modal-header pl-modal-header">
                <div class="pl-header-icon"><i class='bx bx-upload'></i></div>
                <div>
                    <h5 class="modal-title pl-modal-title">Upload Price List</h5>
                    <p class="pl-modal-sub">Add a new price list and assign it to customers</p>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadPriceListForm" enctype="multipart/form-data">
                <div class="modal-body pl-modal-body">
                    <div class="pl-field">
                        <label for="fileName" class="pl-label">
                            Price List Name <span class="pl-required">*</span>
                        </label>
                        <input type="text" class="pl-input" id="fileName" name="file_name"
                            placeholder="e.g., Q1 2026 Price List" required>
                    </div>

                    <div class="pl-field">
                        <label for="priceListFile" class="pl-label">
                            File <span class="pl-required">*</span>
                        </label>
                        <div class="pl-file-zone" id="uploadFileZone">
                            <input type="file" class="pl-file-input" id="priceListFile" name="file"
                                accept=".pdf,.xls,.xlsx,.doc,.docx,.jpg,.jpeg,.png,.gif" required>
                            <div class="pl-file-placeholder">
                                <i class='bx bx-cloud-upload'></i>
                                <div class="pl-file-title">Click to upload or drag a file here</div>
                                <div class="pl-file-hint">PDF, XLS, XLSX, DOC, DOCX, JPG, PNG, GIF — up to 50 MB</div>
                            </div>
                            <div class="pl-file-selected" id="uploadFileSelected" style="display:none;">
                                <i class='bx bx-file'></i>
                                <span class="pl-file-name"></span>
                                <button type="button" class="pl-file-clear" title="Remove">
                                    <i class='bx bx-x'></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pl-field">
                        <?php include __DIR__ . '/customer-picker.php'; ?>
                    </div>
                </div>
                <div class="modal-footer pl-modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">
                        <i class='bx bx-upload me-1'></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══ Edit Price List Modal ═══ -->
<div class="modal fade" id="editPriceListModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content pl-modal">
            <div class="modal-header pl-modal-header">
                <div class="pl-header-icon"><i class='bx bx-edit'></i></div>
                <div>
                    <h5 class="modal-title pl-modal-title">Edit Price List</h5>
                    <p class="pl-modal-sub">Update details and customer access</p>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPriceListForm" enctype="multipart/form-data">
                <input type="hidden" id="editPriceListId" name="price_list_id">
                <div class="modal-body pl-modal-body">
                    <div class="pl-field">
                        <label for="editFileName" class="pl-label">
                            Price List Name <span class="pl-required">*</span>
                        </label>
                        <input type="text" class="pl-input" id="editFileName" name="file_name" required>
                    </div>

                    <div class="pl-field">
                        <label for="editPriceListFile" class="pl-label">Replace File <span class="pl-optional">(optional)</span></label>
                        <div class="pl-file-zone" id="editFileZone">
                            <input type="file" class="pl-file-input" id="editPriceListFile" name="file"
                                accept=".pdf,.xls,.xlsx,.doc,.docx,.jpg,.jpeg,.png,.gif">
                            <div class="pl-file-placeholder">
                                <i class='bx bx-cloud-upload'></i>
                                <div class="pl-file-title">Click to upload a new file</div>
                                <div class="pl-file-hint">Leave empty to keep the current file — max 50 MB</div>
                            </div>
                            <div class="pl-file-selected" id="editFileSelected" style="display:none;">
                                <i class='bx bx-file'></i>
                                <span class="pl-file-name"></span>
                                <button type="button" class="pl-file-clear" title="Remove">
                                    <i class='bx bx-x'></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pl-field">
                        <?php
                        $pickerId     = 'editPicker';
                        $pickerSelect = 'editAssignAccounts';
                        $pickerLoadingId = 'editAssignLoading';
                        include __DIR__ . '/customer-picker.php';
                        ?>
                    </div>
                </div>
                <div class="modal-footer pl-modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="editBtn">
                        <i class='bx bx-check me-1'></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══ View Assigned Customers Modal ═══ -->
<div class="modal fade" id="viewAssignedModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content pl-modal">
            <div class="modal-header pl-modal-header">
                <div class="pl-header-icon"><i class='bx bx-user-check'></i></div>
                <div>
                    <h5 class="modal-title pl-modal-title">Assigned Customers</h5>
                    <p class="pl-modal-sub">Customers with access to this price list</p>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pl-modal-body" id="assignedAccountsList">
                <div class="pl-picker-empty">
                    <div class="pl-spinner"></div>
                    <div style="font-size:13px;color:var(--text-sub);">Loading…</div>
                </div>
            </div>
        </div>
    </div>
</div>

<meta name="pricelist-available-count" content="<?= count($accounts) ?>">

