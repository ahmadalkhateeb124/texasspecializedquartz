<?php
/**
 * admin/customers-edit.php — Edit Customer
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) { header('Location: customers.php'); exit; }

try {
    $stmt = $pdo->prepare("SELECT * FROM customers_companies WHERE id = ?");
    $stmt->execute([$id]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$company) { header('Location: customers.php'); exit; }

    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE company_id = ?");
    $stmt->execute([$id]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = csrfToken();
}

$currentUser = currentUser();
$pageTitle   = 'Edit Customer';
$breadcrumb  = [
    ['label' => 'Customers', 'url' => 'customers.php'],
    ['label' => htmlspecialchars($company['company_name']), 'url' => 'customers-view.php?id=' . $id],
    ['label' => 'Edit']
];

include __DIR__ . '/includes/head.php';
?>
<body>
<?php include __DIR__ . '/includes/sidebar.php'; ?>
<div class="main-wrapper">
<?php include __DIR__ . '/includes/header.php'; ?>

<main class="page-content fade-up">

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Edit Customer</h1>
            <p class="page-subtitle"><?= htmlspecialchars($company['company_name']) ?></p>
        </div>
        <div class="page-actions">
            <a href="customers-view.php?id=<?= $id ?>" class="btn btn-default">
                <i class='bx bx-arrow-back'></i> Back to Profile
            </a>
        </div>
    </div>

    <form method="POST" id="editForm" action="../auth/UpdateCustomer.php">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="customer_type" value="company_us">
        <input type="hidden" name="company_id" value="<?= $id ?>">

        <div class="row g-4">
            <div class="col-lg-8">

                <!-- Company Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title"><i class='bx bx-buildings text-primary'></i> Company Information</h6>
                    </div>
                    <div class="card-section">
                        <div class="mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control"
                                   value="<?= htmlspecialchars($company['company_name']) ?>" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                <input type="text" name="contact_name" class="form-control"
                                       value="<?= htmlspecialchars($company['contact_name'] ?? '') ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Contact Position</label>
                                <input type="text" name="contact_position" class="form-control"
                                       value="<?= htmlspecialchars($company['contact_position'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title"><i class='bx bx-phone text-primary'></i> Contact Details</h6>
                    </div>
                    <div class="card-section">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control"
                                       value="<?= htmlspecialchars($company['phone'] ?? '') ?>" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control"
                                       value="<?= htmlspecialchars($company['email'] ?? '') ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title"><i class='bx bx-map text-primary'></i> Address</h6>
                    </div>
                    <div class="card-section">
                        <div class="mb-3">
                            <label class="form-label">Street Address</label>
                            <input type="text" name="address" class="form-control"
                                   value="<?= htmlspecialchars($company['address'] ?? '') ?>">
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control"
                                       value="<?= htmlspecialchars($company['city'] ?? '') ?>">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control"
                                       value="<?= htmlspecialchars($company['state'] ?? '') ?>">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">ZIP Code</label>
                                <input type="text" name="zip_code" class="form-control"
                                       value="<?= htmlspecialchars($company['zip_code'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right: account settings -->
            <div class="col-lg-4">
                <!-- Account status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title"><i class='bx bx-shield text-primary'></i> Account Status</h6>
                    </div>
                    <div class="card-section">
                        <label class="form-label">Status</label>
                        <select name="account_status" class="form-select">
                            <?php foreach (['Active', 'Inactive', 'Blacklisted'] as $s): ?>
                            <option value="<?= $s ?>"
                                <?= ($account['status'] ?? 'Inactive') === $s ? 'selected' : '' ?>>
                                <?= $s ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="mt-2" style="font-size:12px;color:var(--color-text-sub);">
                            Inactive accounts cannot log in. Blacklisted accounts are permanently blocked.
                        </p>
                    </div>
                </div>

                <!-- Reset password (optional) -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title"><i class='bx bx-lock-alt text-primary'></i> Reset Password</h6>
                    </div>
                    <div class="card-section">
                        <p style="font-size:13px;color:var(--color-text-sub);margin-bottom:12px;">
                            Leave blank to keep the current password.
                        </p>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="passwordField"
                                       class="form-control" placeholder="New password">
                                <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePwd('passwordField',this)">
                                    <i class='bx bx-show'></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" name="confirm_password" id="confirmField"
                                       class="form-control" placeholder="Repeat new password">
                                <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePwd('confirmField',this)">
                                    <i class='bx bx-show'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save -->
                <div class="card">
                    <div class="card-section">
                        <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                            <span id="submitSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                            <i class='bx bx-save' id="submitIcon"></i> Save Changes
                        </button>
                        <a href="customers-view.php?id=<?= $id ?>" class="btn btn-default w-100 mt-2">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script>
function togglePwd(fieldId, btn) {
    const f = document.getElementById(fieldId);
    if (f.type === 'password') {
        f.type = 'text';
        btn.querySelector('i').className = 'bx bx-hide';
    } else {
        f.type = 'password';
        btn.querySelector('i').className = 'bx bx-show';
    }
}

document.getElementById('editForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const pw  = document.getElementById('passwordField').value;
    const cpw = document.getElementById('confirmField').value;
    if (pw && pw !== cpw) { showToast('Passwords do not match.', 'error'); return; }

    const btn     = document.getElementById('submitBtn');
    const spinner = document.getElementById('submitSpinner');
    const icon    = document.getElementById('submitIcon');
    btn.disabled  = true;
    spinner.classList.remove('d-none');
    icon.classList.add('d-none');

    try {
        const res  = await fetch(this.action, { method: 'POST', body: new FormData(this) });
        const data = await res.json();
        if (data.success) {
            showToast(data.message || 'Customer updated.', 'success');
            setTimeout(() => window.location.href = 'customers-view.php?id=<?= $id ?>', 1200);
        } else {
            showToast(data.message || 'Could not update customer.', 'error');
            btn.disabled = false;
            spinner.classList.add('d-none');
            icon.classList.remove('d-none');
        }
    } catch {
        showToast('Network error.', 'error');
        btn.disabled = false;
        spinner.classList.add('d-none');
        icon.classList.remove('d-none');
    }
});
</script>
