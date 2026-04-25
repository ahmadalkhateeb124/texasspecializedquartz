<?php

/**
 * admin/profile.php — Admin Profile & Settings
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$currentUser = currentUser();
$adminId     = $currentUser['id'];
$pageTitle   = 'My Profile';
$breadcrumb  = [['label' => 'Profile']];
$success     = '';
$error       = '';

$users = new AdminUserRepository($pdo);

/* ── POST handlers ─────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'change_password') {
            $currentPw = $_POST['current_password'] ?? '';
            $newPw     = $_POST['new_password']     ?? '';
            $confirmPw = $_POST['confirm_password'] ?? '';
            $hash      = $users->currentPasswordHash($adminId);

            if (!$hash || !password_verify($currentPw, $hash)) {
                $error = 'Current password is incorrect.';
            } elseif (strlen($newPw) < 8) {
                $error = 'New password must be at least 8 characters.';
            } elseif ($newPw !== $confirmPw) {
                $error = 'New passwords do not match.';
            } else {
                $users->updatePassword($adminId, password_hash($newPw, PASSWORD_DEFAULT));
                $success = 'Password updated successfully.';
            }
        } elseif ($_POST['action'] === 'update_profile') {
            $fullname = trim($_POST['fullname'] ?? '');
            $email    = trim($_POST['email']    ?? '');

            if (!$fullname || !$email) {
                $error = 'Name and email are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address.';
            } elseif ($users->emailTakenByOther($email, $adminId)) {
                $error = 'That email is already used by another account.';
            } else {
                $avatarName = null;
                $target     = null;
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $avatarFile = $_FILES['avatar'];
                    if ($avatarFile['error'] !== UPLOAD_ERR_OK) {
                        $error = 'Avatar upload failed. Please try again.';
                    } else {
                        $allowed = [
                            'image/jpeg' => 'jpg', 'image/png' => 'png',
                            'image/gif'  => 'gif', 'image/webp' => 'webp',
                        ];
                        $info = @getimagesize($avatarFile['tmp_name']);
                        if ($info === false || !isset($allowed[$info['mime']])) {
                            $error = 'Only JPG, PNG, GIF, or WEBP images are allowed.';
                        } elseif ($avatarFile['size'] > 2 * 1024 * 1024) {
                            $error = 'Avatar must be 2MB or smaller.';
                        } else {
                            $uploadDir = __DIR__ . '/../auth/uploads/';
                            if (!is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);
                            if (!is_writable($uploadDir)) @chmod($uploadDir, 0777);
                            if (!is_writable($uploadDir)) {
                                $error = 'Upload directory is not writable.';
                            } else {
                                $avatarName = 'avatar-' . $adminId . '-' . time() . '-' . bin2hex(random_bytes(4))
                                            . '.' . $allowed[$info['mime']];
                                $target = $uploadDir . $avatarName;
                                if (!move_uploaded_file($avatarFile['tmp_name'], $target)) {
                                    $error      = 'Failed to save uploaded avatar.';
                                    $avatarName = null;
                                }
                            }
                        }
                    }
                }

                if (!$error) {
                    $oldAvatar = $avatarName ? $users->currentAvatar($adminId) : null;
                    $users->updateProfile($adminId, $fullname, $email, $avatarName);
                    $_SESSION['fullname'] = $fullname;
                    $_SESSION['email']    = $email;
                    $success = 'Profile updated successfully.';
                    if ($avatarName && $oldAvatar && $oldAvatar !== $avatarName) {
                        $prev = __DIR__ . '/../auth/uploads/' . $oldAvatar;
                        if (file_exists($prev)) @unlink($prev);
                    }
                } elseif ($avatarName && $target && file_exists($target)) {
                    @unlink($target);
                }
            }
        }
    } catch (PDOException $e) {
        $error = 'Database error: ' . htmlspecialchars($e->getMessage());
    }
}

/* ── Load view data ────────────────────────────────────────── */
try {
    $admin          = $users->find($adminId);
    $totalOrders    = (int)$pdo->query("SELECT COUNT(*) FROM fabrication_orders")->fetchColumn();
    $totalCustomers = (int)$pdo->query("SELECT COUNT(*) FROM customers_companies")->fetchColumn();
    $totalProducts  = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
} catch (PDOException $e) {
    $admin = [];
    $totalOrders = $totalCustomers = $totalProducts = 0;
    $error = $error ?: 'Could not load profile.';
}

$initials = strtoupper(substr($admin['fullname'] ?? 'A', 0, 2));

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
                    <p class="page-subtitle">Manage your admin account and security settings</p>
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
                </div>

                <div class="col-lg-8">
                    <?php include __DIR__ . '/includes/partials/profile/edit-info-form.php'; ?>
                    <?php include __DIR__ . '/includes/partials/profile/change-password-form.php'; ?>
                </div>
            </div>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/profile.js"></script>
