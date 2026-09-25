<?php

/**
 * admin/employees-new.php — Add New Employee
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = csrfToken();
}

$currentUser = currentUser();
$pageTitle   = 'Add Employee';
$breadcrumb  = [['label' => 'Employees', 'url' => 'employees.php'], ['label' => 'Add Employee']];

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Add Employee</h1>
                    <p class="page-subtitle">Create a login for a staff member who works on job sections</p>
                </div>
                <div class="page-actions">
                    <a href="employees" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Employees
                    </a>
                </div>
            </div>

            <form method="POST" id="employeeForm" action="../auth/AddEmployee.php">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title"><i class='bx bx-user text-primary'></i> Employee Details</h6>
                            </div>
                            <div class="card-section">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="fullname" class="form-control" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Designation / Title</label>
                                        <input type="text" name="designation" class="form-control" placeholder="e.g. Fabricator, Installer">
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <i class='bx bx-user-plus' id="submitIcon"></i> Create Employee
                                </button>
                                <a href="employees" class="btn btn-default w-100 mt-2">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/employee-form.js"></script>
