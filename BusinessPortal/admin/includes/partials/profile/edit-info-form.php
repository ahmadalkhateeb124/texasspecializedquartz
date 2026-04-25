<?php /** Expects: $admin */ ?>
<div class="card mb-4">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-user text-primary'></i> Profile Information</h6>
    </div>
    <div class="card-section">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update_profile">
            <div class="row g-3">
                <div class="col-sm-12">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" name="avatar" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                    <div style="font-size:11px;color:var(--color-text-sub);margin-top:4px;">
                        Optional. JPG, PNG, GIF or WEBP. Max size 2MB.
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="fullname" class="form-control"
                        value="<?= htmlspecialchars($admin['fullname'] ?? '') ?>" required>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" disabled style="opacity:.65;"
                        value="<?= htmlspecialchars($admin['username'] ?? '') ?>">
                    <div style="font-size:11px;color:var(--color-text-sub);margin-top:4px;">
                        Username cannot be changed.
                    </div>
                </div>
                <div class="col-sm-12">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control"
                        value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required>
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
