<?php

/**
 * admin/profile.php — Admin Profile & Settings
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();
$currentUser = currentUser();
$adminId     = $currentUser['id'];
$pageTitle   = 'My Profile';
$breadcrumb  = [['label' => 'Profile']];
$success = '';
$error   = '';
/* ── Handle password change ──────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'change_password') {
        $currentPw  = $_POST['current_password']  ?? '';
        $newPw      = $_POST['new_password']       ?? '';
        $confirmPw  = $_POST['confirm_password']   ?? '';
        try {
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$adminId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row || !password_verify($currentPw, $row['password'])) {
                $error = 'Current password is incorrect.';
            } elseif (strlen($newPw) < 8) {
                $error = 'New password must be at least 8 characters.';
            } elseif ($newPw !== $confirmPw) {
                $error = 'New passwords do not match.';
            } else {
                $hash = password_hash($newPw, PASSWORD_DEFAULT);
                $upd  = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $upd->execute([$hash, $adminId]);
                $success = 'Password updated successfully.';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . htmlspecialchars($e->getMessage());
        }
    }
    if ($_POST['action'] === 'update_profile') {
        $fullname = trim($_POST['fullname'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        if (empty($fullname) || empty($email)) {
            $error = 'Name and email are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address.';
        } else {
            $avatarName = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
                $avatarFile = $_FILES['avatar'];
                if ($avatarFile['error'] !== UPLOAD_ERR_OK) {
                    $error = 'Avatar upload failed. Please try again.';
                } else {
                    $allowedTypes = [
                        'image/jpeg' => 'jpg',
                        'image/png'  => 'png',
                        'image/gif'  => 'gif',
                        'image/webp' => 'webp'
                    ];
                    $imageInfo = @getimagesize($avatarFile['tmp_name']);
                    if ($imageInfo === false || !isset($allowedTypes[$imageInfo['mime']])) {
                        $error = 'Only JPG, PNG, GIF, or WEBP images are allowed.';
                    } elseif ($avatarFile['size'] > 2 * 1024 * 1024) {
                        $error = 'Avatar must be 2MB or smaller.';
                    } else {
                        $uploadDir = __DIR__ . '/../auth/uploads/';
                        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
                            $error = 'Unable to create upload directory.';
                        } else {
                            if (!is_writable($uploadDir)) {
                                @chmod($uploadDir, 0777);
                            }
                            if (!is_writable($uploadDir)) {
                                $error = 'Upload directory is not writable.';
                            } else {
                                $ext = $allowedTypes[$imageInfo['mime']];
                                $avatarName = 'avatar-' . $adminId . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
                                $target = $uploadDir . $avatarName;
                                if (!move_uploaded_file($avatarFile['tmp_name'], $target)) {
                                    $error = 'Failed to save uploaded avatar.';
                                    $avatarName = null;
                                }
                            }
                        }
                    }
                }
            }

            if (empty($error)) {
                try {
                    /* Check email not taken by another user */
                    $chk = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                    $chk->execute([$email, $adminId]);
                    if ($chk->fetch()) {
                        $error = 'That email is already used by another account.';
                        if (!empty($avatarName) && file_exists($target)) {
                            @unlink($target);
                        }
                    } else {
                        $oldAvatar = null;
                        if ($avatarName !== null) {
                            $stmt = $pdo->prepare("SELECT avatar FROM users WHERE id = ?");
                            $stmt->execute([$adminId]);
                            $oldAvatar = $stmt->fetchColumn();
                        }
                        if ($avatarName !== null) {
                            $upd = $pdo->prepare("UPDATE users SET fullname = ?, email = ?, avatar = ? WHERE id = ?");
                            $upd->execute([$fullname, $email, $avatarName, $adminId]);
                        } else {
                            $upd = $pdo->prepare("UPDATE users SET fullname = ?, email = ? WHERE id = ?");
                            $upd->execute([$fullname, $email, $adminId]);
                        }
                        $_SESSION['fullname'] = $fullname;
                        $_SESSION['email']    = $email;
                        $success = 'Profile updated successfully.';
                        if (!empty($avatarName) && !empty($oldAvatar) && $oldAvatar !== $avatarName) {
                            $previous = __DIR__ . '/../auth/uploads/' . $oldAvatar;
                            if (file_exists($previous)) {
                                @unlink($previous);
                            }
                        }
                    }
                } catch (PDOException $e) {
                    if (!empty($avatarName) && file_exists($target)) {
                        @unlink($target);
                    }
                    $error = 'Database error: ' . htmlspecialchars($e->getMessage());
                }
            }
        }
    }
}
/* ── Fetch admin record ──────────────────────────────────── */
try {
    $stmt = $pdo->prepare("SELECT id, fullname, username, email, created_at, avatar FROM users WHERE id = ?");
    $stmt->execute([$adminId]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
    $admin = [];
    $error = 'Could not load profile.';
}
/* ── Order / stats counts ────────────────────────────────── */
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM fabrication_orders");
    $totalOrders = (int)$stmt->fetchColumn();
    $stmt = $pdo->query("SELECT COUNT(*) FROM customers_companies");
    $totalCustomers = (int)$stmt->fetchColumn();
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $totalProducts = (int)$stmt->fetchColumn();
} catch (PDOException $e) {
    $totalOrders = $totalCustomers = $totalProducts = 0;
}
$initials = strtoupper(substr($admin['fullname'] ?? 'A', 0, 2));
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
                    <h1 class="page-title">My Profile</h1>
                    <p class="page-subtitle">Manage your admin account and security settings</p>
                </div>
            </div>
            <?php if ($success): ?>
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class='bx bx-check-circle'></i> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class='bx bx-error-circle'></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <div class="row g-4">
                <!-- ── Left: profile card ──────────────────────── -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <!-- Cover strip -->
                        <div style="height:150px;background:linear-gradient(135deg,var(--color-primary),var(--color-primary-h));
                            border-radius:var(--radius-md) var(--radius-md) 0 0;"></div>
                        <div class="card-section" style="padding-top:0;text-align:center; background-color: #FCFCFC; ">
                            <!-- Avatar -->
                            <div style="margin-top:-36px;margin-bottom:12px;  ">
                                <?php if (!empty($admin['avatar']) && file_exists(__DIR__ . '/../auth/uploads/' . $admin['avatar'])): ?>
                                    <img src="../auth/uploads/<?= htmlspecialchars($admin['avatar']) ?>"
                                        style="box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px; width:72px;height:72px;border-radius:50%;border:3px solid var(--color-surface);
                                        object-fit:cover;" alt="Avatar">
                                <?php else: ?>
                                    <div style="width:72px;height:72px;border-radius:50%;background:var(--color-primary);
                                        border:3px solid var(--color-surface);
                                        display:inline-flex;align-items:center;justify-content:center;
                                        color:#fff;font-size:26px;font-weight:700;">
                                        <?= htmlspecialchars($initials) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h5 style="font-weight:700;margin:0 0 3px;"><?= htmlspecialchars($admin['fullname'] ?? '—') ?></h5>
                            <p style="font-size:13px;color:var(--color-text-sub);margin:0 0 6px;">
                                @<?= htmlspecialchars($admin['username'] ?? '') ?>
                            </p>
                            <span class="badge badge-success"><span class="dot"></span>Administrator</span>
                            <hr style="border-color:var(--color-border);margin:16px 0;">
                            <!-- Stats strip -->
                            <div class="row g-0 text-center">
                                <div class="col">
                                    <div style="font-size:22px;font-weight:800;color:var(--color-text);"><?= $totalOrders ?></div>
                                    <div style="font-size:11px;color:var(--color-text-sub);font-weight:500;">Orders</div>
                                </div>
                                <div class="col" style="border-left:1px solid var(--color-border);">
                                    <div style="font-size:22px;font-weight:800;color:var(--color-text);"><?= $totalCustomers ?></div>
                                    <div style="font-size:11px;color:var(--color-text-sub);font-weight:500;">Customers</div>
                                </div>
                                <div class="col" style="border-left:1px solid var(--color-border);">
                                    <div style="font-size:22px;font-weight:800;color:var(--color-text);"><?= $totalProducts ?></div>
                                    <div style="font-size:11px;color:var(--color-text-sub);font-weight:500;">Remnants</div>
                                </div>
                            </div>
                            <hr style="border-color:var(--color-border);margin:16px 0;">
                            <p style="font-size:12px;color:var(--color-text-sub);margin:0;">
                                Member since <?= !empty($adminData['created_at']) ? date('M Y', strtotime($adminData['created_at'])) : '—' ?>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- ── Right: edit forms ────────────────────────── -->
                <div class="col-lg-8">
                    <!-- Edit Profile Info -->
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
                                        <input type="text" class="form-control"
                                            value="<?= htmlspecialchars($admin['username'] ?? '') ?>"
                                            disabled style="opacity:.65;">
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
                    <!-- Change Password -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-lock-alt text-primary'></i> Change Password</h6>
                        </div>
                        <div class="card-section">
                            <form method="POST">
                                <input type="hidden" name="action" value="change_password">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="current_password" id="cpField"
                                                class="form-control" placeholder="Enter current password" required>
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="togglePwd('cpField',this)">
                                                <i class='bx bx-show'></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="new_password" id="npField"
                                                class="form-control" placeholder="Min. 8 characters" required>
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="togglePwd('npField',this)">
                                                <i class='bx bx-show'></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="confirm_password" id="cnField"
                                                class="form-control" placeholder="Repeat new password" required>
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="togglePwd('cnField',this)">
                                                <i class='bx bx-show'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-top:12px;padding:10px 14px;background:var(--color-bg);
                                    border-radius:var(--radius-sm);font-size:12px;color:var(--color-text-sub);">
                                    <i class='bx bx-info-circle me-1'></i>
                                    Password must be at least 8 characters. Use a mix of letters, numbers, and symbols.
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class='bx bx-lock'></i> Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
        </script>