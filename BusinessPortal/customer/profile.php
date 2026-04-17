<?php

/**
 * customer/profile.php — Customer Profile & Settings
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

requireCustomer('../auth-login-minimal.php');

$currentUser = currentUser();
$accountId   = $currentUser['id'];
$pageTitle   = 'My Profile';
$breadcrumb  = [['label' => 'Profile']];
$success     = '';
$error       = '';

/* ── Handle POST actions ─────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'update_profile') {
        $name  = trim($_POST['name']  ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($name) || empty($email)) {
            $error = 'Name and email are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address.';
        } else {
            try {
                $chk = $pdo->prepare("SELECT id FROM accounts WHERE email = ? AND id != ?");
                $chk->execute([$email, $accountId]);
                if ($chk->fetch()) {
                    $error = 'That email is already used by another account.';
                } else {
                    $upd = $pdo->prepare("UPDATE accounts SET name = ?, email = ? WHERE id = ?");
                    $upd->execute([$name, $email, $accountId]);
                    $_SESSION['name']  = $name;
                    $_SESSION['email'] = $email;
                    $currentUser['name']  = $name;
                    $currentUser['email'] = $email;
                    $success = 'Profile updated successfully.';
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . htmlspecialchars($e->getMessage());
            }
        }
    }

    if ($_POST['action'] === 'change_password') {
        $currentPw = $_POST['current_password'] ?? '';
        $newPw     = $_POST['new_password']      ?? '';
        $confirmPw = $_POST['confirm_password']  ?? '';

        try {
            $stmt = $pdo->prepare("SELECT password FROM accounts WHERE id = ?");
            $stmt->execute([$accountId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row || !password_verify($currentPw, $row['password'])) {
                $error = 'Current password is incorrect.';
            } elseif (strlen($newPw) < 8) {
                $error = 'New password must be at least 8 characters.';
            } elseif ($newPw !== $confirmPw) {
                $error = 'New passwords do not match.';
            } else {
                $hash = password_hash($newPw, PASSWORD_DEFAULT);
                $upd  = $pdo->prepare("UPDATE accounts SET password = ? WHERE id = ?");
                $upd->execute([$hash, $accountId]);
                $success = 'Password updated successfully.';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . htmlspecialchars($e->getMessage());
        }
    }
}

/* ── Load full profile ──────────────────────────────────────── */
try {
    $stmt = $pdo->prepare("
        SELECT
            a.id, a.name, a.email, a.status, a.created_at,
            c.id        AS company_id,
            c.company_name,
            c.phone,
            c.address,
            c.city,
            c.state,
            c.zip_code
        FROM accounts a
        JOIN customers_companies c ON c.id = a.company_id
        WHERE a.id = ?
    ");
    $stmt->execute([$accountId]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
    $profile = [];
    $error   = 'Could not load profile data.';
}

/* ── Order count ────────────────────────────────────────────── */
try {
    $s = $pdo->prepare("SELECT COUNT(*) FROM fabrication_orders WHERE account_id = ?");
    $s->execute([$accountId]);
    $orderCount = (int)$s->fetchColumn();
} catch (PDOException $e) {
    $orderCount = 0;
}

$initials = strtoupper(substr($profile['name'] ?? $currentUser['name'] ?? 'C', 0, 2));

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
                    <p class="page-subtitle">Manage your account and security settings</p>
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

                <!-- ── Left: profile card ──────────────────────────── -->
                <div class="col-lg-4">

                    <div class="card mb-4">
                        <!-- Cover strip -->
                        <div style="height:80px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));
                            border-radius:var(--radius-md) var(--radius-md) 0 0;"></div>

                        <div class="card-body" style="padding-top:0;text-align:center;">
                            <!-- Avatar -->
                            <div style="margin-top:-36px;margin-bottom:12px;">
                                <div style="width:72px;height:72px;border-radius:50%;background:var(--primary);
                                    border:3px solid var(--surface);display:inline-flex;align-items:center;
                                    justify-content:center;color:#fff;font-size:26px;font-weight:700;margin:0 auto;">
                                    <?= htmlspecialchars($initials) ?>
                                </div>
                            </div>

                            <h5 style="font-weight:700;margin:0 0 3px;"><?= htmlspecialchars($profile['name'] ?? $currentUser['name'] ?? '—') ?></h5>
                            <p style="font-size:13px;color:var(--muted);margin:0 0 6px;">
                                <?= htmlspecialchars($profile['email'] ?? $currentUser['email'] ?? '') ?>
                            </p>
                            <span class="badge <?= ($profile['status'] ?? '') === 'Active' ? 'badge-success' : 'badge-muted' ?>">
                                <span class="dot"></span><?= htmlspecialchars($profile['status'] ?? 'Active') ?>
                            </span>

                            <hr style="border-color:var(--border);margin:16px 0;">

                            <!-- Stats strip -->
                            <div class="row g-0 text-center">
                                <div class="col">
                                    <div style="font-size:22px;font-weight:800;color:var(--text);"><?= $orderCount ?></div>
                                    <div style="font-size:11px;color:var(--muted);font-weight:500;">Orders</div>
                                </div>
                                <div class="col" style="border-left:1px solid var(--border);">
                                    <div style="font-size:14px;font-weight:700;color:var(--text);">
                                        <?= !empty($profile['created_at']) ? date('M Y', strtotime($profile['created_at'])) : '—' ?>
                                    </div>
                                    <div style="font-size:11px;color:var(--muted);font-weight:500;">Member Since</div>
                                </div>
                            </div>

                            <hr style="border-color:var(--border);margin:16px 0;">

                            <a href="orders.php" class="btn btn-primary w-100 btn-sm mb-2">
                                <i class='bx bx-file'></i> View My Orders
                            </a>
                            <a href="order-new.php" class="btn btn-light w-100 btn-sm">
                                <i class='bx bx-plus'></i> New Order
                            </a>
                        </div>
                    </div>

                    <!-- Company info (view-only) -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title"><i class='bx bx-buildings text-primary'></i> Company</h6>
                        </div>
                        <div class="card-body" style="font-size:13px;">
                            <div class="mb-2">
                                <div style="font-size:10px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:2px;">Company Name</div>
                                <div style="font-weight:600;"><?= htmlspecialchars($profile['company_name'] ?? '—') ?></div>
                            </div>
                            <div class="mb-2">
                                <div style="font-size:10px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:2px;">Phone</div>
                                <div style="font-weight:500;"><?= htmlspecialchars($profile['phone'] ?? '—') ?></div>
                            </div>
                            <div>
                                <div style="font-size:10px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:2px;">Address</div>
                                <div style="font-weight:500;">
                                    <?php
                                    $parts = array_filter([
                                        $profile['address'] ?? '',
                                        $profile['city']    ?? '',
                                        $profile['state']   ?? '',
                                        $profile['zip_code'] ?? '',
                                    ]);
                                    echo htmlspecialchars(implode(', ', $parts) ?: '—');
                                    ?>
                                </div>
                            </div>
                            <?php
                            $adminAvatar = 'default-avatar.png';
                            $adminData = [];

                            try {
                                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                                $stmt->execute([2]); // غير الرقم حسب الأدمن
                                $adminData = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($adminData) {
                                    if (!empty($adminData['avatar']) && file_exists(__DIR__ . '/auth/uploads/' . $adminData['avatar'])) {
                                        $adminAvatar = $adminData['avatar'];
                                    }
                                }
                            } catch (PDOException $e) {
                                // ممكن تطبع الخطأ للتجربة
                                // echo $e->getMessage();
                            }
                            ?>
                            <div class="mt-3 pt-2" style="border-top:1px solid var(--border);font-size:11px;color:var(--muted);">
                                To update company info,
                                <a href="https://mail.google.com/mail/?view=cm&to=<?php echo urlencode(!empty($adminData['email']) ? $adminData['email'] : 'cs@webkoit.com'); ?>"
                                    target="_blank"
                                    style="color:var(--primary);">
                                    contact support
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ── Right: edit forms ──────────────────────────── -->
                <div class="col-lg-8">

                    <!-- Edit Profile Info -->
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
                                        <input type="text" name="name" class="form-control"
                                            value="<?= htmlspecialchars($profile['name'] ?? $currentUser['name'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control"
                                            value="<?= htmlspecialchars($profile['email'] ?? $currentUser['email'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Company</label>
                                        <input type="text" class="form-control"
                                            value="<?= htmlspecialchars($profile['company_name'] ?? '—') ?>"
                                            disabled style="opacity:.65;">
                                        <div style="font-size:11px;color:var(--muted);margin-top:4px;">Contact support to change company info.</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Account Status</label>
                                        <input type="text" class="form-control"
                                            value="<?= htmlspecialchars($profile['status'] ?? 'Active') ?>"
                                            disabled style="opacity:.65;">
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
                                <div style="margin-top:12px;padding:10px 14px;background:var(--bg);
                                    border-radius:var(--radius-sm);font-size:12px;color:var(--muted);">
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

</body>

</html>