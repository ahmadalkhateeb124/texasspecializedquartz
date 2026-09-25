<?php

/**
 * employee/profile.php — Employee Profile & Password
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireEmployee('../auth-login-minimal.php');

$currentUser = currentUser();
$employeeId  = $currentUser['id'];
$pageTitle   = 'My Profile';
$breadcrumb  = [['label' => 'Profile']];
$success     = '';
$error       = '';

$repo = new EmployeeRepository($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'update_profile') {
            $fullname = trim($_POST['fullname'] ?? '');
            $email    = trim($_POST['email']    ?? '');
            $phone    = trim($_POST['phone']    ?? '');

            if (!$fullname || !$email) {
                $error = 'Name and email are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address.';
            } elseif ($repo->emailTakenByOther($email, $employeeId)) {
                $error = 'That email is already used by another account.';
            } else {
                $current = $repo->find($employeeId);
                $repo->update($employeeId, [
                    'fullname'    => $fullname,
                    'email'       => $email,
                    'phone'       => $phone,
                    'username'    => $current['username'],
                    'designation' => $current['designation'],
                    'status'      => $current['status'],
                ]);
                $_SESSION['fullname'] = $fullname;
                $_SESSION['email']    = $email;
                $currentUser['name']  = $fullname;
                $currentUser['email'] = $email;
                $success = 'Profile updated successfully.';
            }
        } elseif ($_POST['action'] === 'change_password') {
            $currentPw = $_POST['current_password'] ?? '';
            $newPw     = $_POST['new_password']     ?? '';
            $confirmPw = $_POST['confirm_password'] ?? '';
            $employee  = $repo->find($employeeId);

            if (!$employee || !password_verify($currentPw, $employee['password'])) {
                $error = 'Current password is incorrect.';
            } elseif (strlen($newPw) < 8) {
                $error = 'New password must be at least 8 characters.';
            } elseif ($newPw !== $confirmPw) {
                $error = 'New passwords do not match.';
            } else {
                $repo->updatePassword($employeeId, password_hash($newPw, PASSWORD_DEFAULT));
                $success = 'Password updated successfully.';
            }
        }
    } catch (PDOException $e) {
        $error = 'Database error: ' . htmlspecialchars($e->getMessage());
    }
}

try {
    $profile = $repo->find($employeeId);
} catch (PDOException $e) {
    $profile = [];
    $error   = $error ?: 'Could not load profile data.';
}

$initials = strtoupper(substr($profile['fullname'] ?? $currentUser['name'] ?? 'E', 0, 2));

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
                    <div class="card">
                        <div class="card-section text-center">
                            <div style="width:72px;height:72px;border-radius:50%;background:#000000;color:#fff;
                                        display:flex;align-items:center;justify-content:center;font-size:22px;
                                        font-weight:700;margin:0 auto 12px;">
                                <?= htmlspecialchars($initials) ?>
                            </div>
                            <div style="font-weight:600;font-size:15px;"><?= htmlspecialchars($profile['fullname'] ?? '') ?></div>
                            <?php if (!empty($profile['designation'])): ?>
                                <div style="font-size:12px;color:var(--text-sub);margin-top:2px;"><?= htmlspecialchars($profile['designation']) ?></div>
                            <?php endif; ?>
                            <div style="font-size:12px;color:var(--text-sub);margin-top:8px;"><?= htmlspecialchars($profile['email'] ?? '') ?></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
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
                                        <input type="text" name="fullname" class="form-control" required
                                            value="<?= htmlspecialchars($profile['fullname'] ?? '') ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" required
                                            value="<?= htmlspecialchars($profile['email'] ?? '') ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control"
                                            value="<?= htmlspecialchars($profile['phone'] ?? '') ?>">
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

                    <?php include __DIR__ . '/includes/partials/profile/change-password-form.php'; ?>
                </div>
            </div>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>
