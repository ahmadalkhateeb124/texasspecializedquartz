<?php

/**
 * admin/customers-edit.php — Edit Customer
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';
requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: customers'); exit; }

try {
    $repo    = new CustomerRepository($pdo);
    $company = $repo->find($id);
    if (!$company) { header('Location: customers'); exit; }

    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE company_id = ?");
    $stmt->execute([$id]);
    $account = $stmt->fetch() ?: [];
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
    ['label' => 'Edit'],
];

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Edit Customer</h1>
                    <p class="page-subtitle"><?= htmlspecialchars($company['company_name']) ?></p>
                </div>
                <div class="page-actions">
                    <a href="customers-view?id=<?= $id ?>" class="btn btn-default">
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
                        <?php include __DIR__ . '/../includes/partials/customers/form/company-info.php'; ?>
                        <?php include __DIR__ . '/../includes/partials/customers/form/contact-details.php'; ?>
                        <?php include __DIR__ . '/../includes/partials/customers/form/address.php'; ?>
                    </div>

                    <div class="col-lg-4">
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
                                        <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="passwordField">
                                            <i class='bx bx-show'></i>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="confirm_password" id="confirmField"
                                            class="form-control" placeholder="Repeat new password">
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
                                    <i class='bx bx-save' id="submitIcon"></i> Save Changes
                                </button>
                                <a href="customers-view?id=<?= $id ?>" class="btn btn-default w-100 mt-2">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/customer-form.js"></script>
