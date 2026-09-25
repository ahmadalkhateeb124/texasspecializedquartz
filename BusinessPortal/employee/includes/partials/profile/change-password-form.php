<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-lock-alt text-primary'></i> Change Password</h6>
    </div>
    <div class="card-section">
        <form method="POST">
            <input type="hidden" name="action" value="change_password">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Current Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="current_password" id="cpField"
                            class="form-control" placeholder="Enter current password" required>
                        <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="cpField">
                            <i class='bx bx-show'></i>
                        </button>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="new_password" id="npField"
                            class="form-control" placeholder="Min. 8 characters" required>
                        <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="npField">
                            <i class='bx bx-show'></i>
                        </button>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="confirm_password" id="cnField"
                            class="form-control" placeholder="Repeat new password" required>
                        <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="cnField">
                            <i class='bx bx-show'></i>
                        </button>
                    </div>
                </div>
            </div>
            <div style="margin-top:12px;padding:10px 14px;background:var(--bg);
                        border-radius:var(--radius-sm);font-size:12px;color:var(--muted);">
                <i class='bx bx-info-circle me-1'></i>
                Password must be at least 8 characters. Use a mix of letters, numbers, and symbols.
            </div>
            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-lock'></i> Update Password
                </button>
            </div>
        </form>
    </div>
</div>
