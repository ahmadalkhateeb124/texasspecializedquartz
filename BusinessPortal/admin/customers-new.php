<?php
/**
 * admin/customers-new.php — Add New Customer
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = csrfToken();
}

$currentUser = currentUser();
$pageTitle   = 'Add Customer';
$breadcrumb  = [['label' => 'Customers', 'url' => 'customers.php'], ['label' => 'Add Customer']];

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
            <h1 class="page-title">Add Customer</h1>
            <p class="page-subtitle">Register a new US company account</p>
        </div>
        <div class="page-actions">
            <a href="customers.php" class="btn btn-default">
                <i class='bx bx-arrow-back'></i> Back to Customers
            </a>
        </div>
    </div>

    <form method="POST" id="customerForm" action="../auth/AddCustomer.php">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="customer_type" value="company_us">

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
                                   placeholder="Enter company legal name" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                <input type="text" name="contact_name" class="form-control"
                                       placeholder="Full name" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Contact Position</label>
                                <input type="text" name="contact_position" class="form-control"
                                       placeholder="Manager, Owner, Director…">
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
                                       placeholder="+1 (555) 000-0000" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control"
                                       placeholder="company@example.com" required>
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
                                   placeholder="123 Main Street">
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" placeholder="City">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" placeholder="TX">
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">ZIP Code</label>
                                <input type="text" name="zip_code" class="form-control" placeholder="78201">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right: account settings -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title"><i class='bx bx-lock-alt text-primary'></i> Account Credentials</h6>
                    </div>
                    <div class="card-section">
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="passwordField"
                                       class="form-control" placeholder="Min. 8 characters" required>
                                <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePwd('passwordField',this)">
                                    <i class='bx bx-show'></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="confirm_password" id="confirmField"
                                       class="form-control" placeholder="Repeat password" required>
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
                            <i class='bx bx-user-plus' id="submitIcon"></i> Create Customer
                        </button>
                        <a href="customers.php" class="btn btn-default w-100 mt-2">Cancel</a>
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

document.getElementById('customerForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const pw  = document.getElementById('passwordField').value;
    const cpw = document.getElementById('confirmField').value;
    if (pw !== cpw) { showToast('Passwords do not match.', 'error'); return; }

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
            showToast(data.message || 'Customer created.', 'success');
            setTimeout(() => window.location.href = 'customers.php', 1200);
        } else {
            showToast(data.message || 'Could not create customer.', 'error');
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
