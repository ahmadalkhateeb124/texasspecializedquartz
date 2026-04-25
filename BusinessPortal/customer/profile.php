<?php

/**
 * customer/profile.php — Customer Profile & Settings
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireCustomer('../auth-login-minimal.php');

$currentUser = currentUser();
$accountId   = $currentUser['id'];
$pageTitle   = 'My Profile';
$breadcrumb  = [['label' => 'Profile']];
$success     = '';
$error       = '';

$accounts = new AccountRepository($pdo);

/* ── POST handlers ─────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'update_profile') {
            $name  = trim($_POST['name']  ?? '');
            $email = trim($_POST['email'] ?? '');

            if (!$name || !$email) {
                $error = 'Name and email are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address.';
            } elseif ($accounts->emailTakenByOther($email, $accountId)) {
                $error = 'That email is already used by another account.';
            } else {
                $accounts->updateProfile($accountId, $name, $email);
                $_SESSION['name']  = $name;
                $_SESSION['email'] = $email;
                $currentUser['name']  = $name;
                $currentUser['email'] = $email;
                $success = 'Profile updated successfully.';
            }
        } elseif ($_POST['action'] === 'change_password') {
            $currentPw = $_POST['current_password'] ?? '';
            $newPw     = $_POST['new_password']     ?? '';
            $confirmPw = $_POST['confirm_password'] ?? '';
            $hash      = $accounts->currentPasswordHash($accountId);

            if (!$hash || !password_verify($currentPw, $hash)) {
                $error = 'Current password is incorrect.';
            } elseif (strlen($newPw) < 8) {
                $error = 'New password must be at least 8 characters.';
            } elseif ($newPw !== $confirmPw) {
                $error = 'New passwords do not match.';
            } else {
                $accounts->updatePassword($accountId, password_hash($newPw, PASSWORD_DEFAULT));
                $success = 'Password updated successfully.';
            }
        }
    } catch (PDOException $e) {
        $error = 'Database error: ' . htmlspecialchars($e->getMessage());
    }
}

/* ── Load view data ────────────────────────────────────────── */
try {
    $profile    = $accounts->findProfile($accountId);
    $orderCount = $accounts->orderCount($accountId);
} catch (PDOException $e) {
    $profile    = [];
    $orderCount = 0;
    $error      = $error ?: 'Could not load profile data.';
}

$initials = strtoupper(substr($profile['name'] ?? $currentUser['name'] ?? 'C', 0, 2));

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">My Profile</h1>
                    <p class="page-subtitle">Manage your account and security settings</p>
                </div>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                    <i class='bx bx-check-circle'></i> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                    <i class='bx bx-error-circle'></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-lg-4">
                    <?php include __DIR__ . '/includes/partials/profile/sidebar-card.php'; ?>
                    <?php include __DIR__ . '/includes/partials/profile/company-card.php'; ?>
                </div>

                <div class="col-lg-8">
                    <?php include __DIR__ . '/includes/partials/profile/edit-info-form.php'; ?>
                    <?php include __DIR__ . '/includes/partials/profile/change-password-form.php'; ?>
                </div>
            </div>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/customer/profile.js"></script>
