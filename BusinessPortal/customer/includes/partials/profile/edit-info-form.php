<?php /** Expects: $profile, $currentUser */ ?>
<div class="card mb-4">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-user text-primary'></i> Profile Information</h6>
    </div>
    <div class="card-section">
        <form method="POST">
            <input type="hidden" name="action" value="update_profile">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control"
                        value="<?= htmlspecialchars($profile['name'] ?? $currentUser['name'] ?? '') ?>" required>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control"
                        value="<?= htmlspecialchars($profile['email'] ?? $currentUser['email'] ?? '') ?>" required>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Company</label>
                    <input type="text" class="form-control" disabled style="opacity:.65;"
                        value="<?= htmlspecialchars($profile['company_name'] ?? '—') ?>">
                    <div style="font-size:11px;color:var(--muted);margin-top:4px;">
                        Contact support to change company info.
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Account Status</label>
                    <input type="text" class="form-control" disabled style="opacity:.65;"
                        value="<?= htmlspecialchars($profile['status'] ?? 'Active') ?>">
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class='bx bx-save'></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
