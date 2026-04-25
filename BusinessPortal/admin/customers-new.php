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

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Add Customer</h1>
                    <p class="page-subtitle">Register a new US company account</p>
                </div>
                <div class="page-actions">
                    <a href="customers" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Customers
                    </a>
                </div>
            </div>

            <form method="POST" id="customerForm" action="../auth/AddCustomer.php">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="customer_type" value="company_us">

                <div class="row g-4">
                    <div class="col-lg-8">
                        <?php include __DIR__ . '/../includes/partials/customers/form/company-info.php'; ?>
                        <?php include __DIR__ . '/../includes/partials/customers/form/contact-details.php'; ?>
                        <?php include __DIR__ . '/../includes/partials/customers/form/address.php'; ?>
                    </div>

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
                                        <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="passwordField">
                                            <i class='bx bx-show'></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="confirm_password" id="confirmField"
                                            class="form-control" placeholder="Repeat password" required>
                                        <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="confirmField">
                                            <i class='bx bx-show'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-section">
                                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                    <span id="submitSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                                    <i class='bx bx-user-plus' id="submitIcon"></i> Create Customer
                                </button>
                                <a href="customers" class="btn btn-default w-100 mt-2">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/customer-form.js"></script>
