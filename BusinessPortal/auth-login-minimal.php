<?php

/**
 * auth-login-minimal.php — Luxury Login Page
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Granite Artists</title>
    <link rel="icon" type="image/png" href="https://texasspecializedquartz.com/BusinessPortal/auth/uploads/<?= htmlspecialchars($adminAvatar) ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --gold: #767676;
            --gold-dark: #767676;
            --gold-light: #f5eedf;
            --cream: #f8f6f2;
            --surface: #ffffff;
            --border: #e8e3dc;
            --text: #1c1917;
            --text-sub: #78716c;
            --text-dis: #a8a29e;
            --danger: #b91c1c;
            --danger-bg: #fee2e2;
            --radius: 10px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--cream);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Decorative background pattern ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 40%, rgba(201, 169, 110, .08) 0%, transparent 60%),
                radial-gradient(ellipse 50% 60% at 80% 60%, rgba(201, 169, 110, .06) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
        }

        /* ── Brand ── */
        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-logo {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 28px;
            margin-bottom: 16px;
        }

        .brand-name {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -.4px;
            margin: 0 0 4px;
        }

        .brand-tagline {
            font-size: 13px;
            color: var(--text-sub);
            letter-spacing: .1px;
        }

        /* ── Card ── */
        .login-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(28, 25, 23, .08), 0 0 0 1px rgba(28, 25, 23, .02);
            padding: 36px;
        }

        .login-card-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 4px;
        }

        .login-card-sub {
            font-size: 13px;
            color: var(--text-sub);
            margin: 0 0 28px;
        }

        /* ── Type switcher ── */
        .type-switcher {
            display: flex;
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 3px;
            margin-bottom: 24px;
        }

        .type-switcher input[type="radio"] {
            display: none;
        }

        .type-switcher label {
            flex: 1;
            text-align: center;
            padding: 9px 12px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-sub);
            cursor: pointer;
            border-radius: 6px;
            transition: all .15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            user-select: none;
        }

        .type-switcher input[type="radio"]:checked+label {
            background: var(--surface);
            color: var(--text);
            font-weight: 600;
            box-shadow: 0 1px 4px rgba(28, 25, 23, .08), 0 0 0 1px rgba(28, 25, 23, .04);
        }

        .type-switcher input[type="radio"]:checked+label i {
            color: var(--gold);
        }

        /* ── Alert ── */
        .alert-login {
            background: var(--danger-bg);
            border: 1px solid rgba(185, 28, 28, .2);
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 13px;
            color: var(--danger);
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 20px;
        }

        /* ── Form ── */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 6px;
            display: block;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .icon-left {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 13px;
            font-size: 16px;
            color: var(--text-dis);
            pointer-events: none;
            line-height: 1;
        }

        .input-wrap .form-control {
            padding-left: 38px;
        }

        .input-wrap .toggle-pwd {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 12px;
            font-size: 16px;
            color: var(--text-dis);
            cursor: pointer;
            background: none;
            border: none;
            padding: 2px;
            transition: color .12s;
        }

        .input-wrap .toggle-pwd:hover {
            color: var(--text-sub);
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            font-size: 14px;
            color: var(--text);
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            font-family: 'Inter', sans-serif;
        }

        .form-control:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201, 169, 110, .15);
        }

        .form-control::placeholder {
            color: var(--text-dis);
        }

        /* ── Submit button ── */
        .btn-login {
            width: 100%;
            padding: 12px 20px;
            background: linear-gradient(135deg, #767676, #767676);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            letter-spacing: .1px;
            transition: opacity .15s, box-shadow .15s, transform .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
            box-shadow: 0 2px 8px rgba(125, 125, 125, 0.4);
        }

        .btn-login:hover {
            opacity: .92;
            box-shadow: 0 4px 16px rgba(130, 130, 130, 0.45);
        }

        .btn-login:active {
            transform: none;
        }

        .btn-login:disabled {
            opacity: .65;
            cursor: not-allowed;
            transform: none;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, .35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .65s linear infinite;
            flex-shrink: 0;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ── Divider ── */
        .card-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 24px 0;
        }

        /* ── Footer ── */
        .login-footer {
            text-align: center;
            margin-top: 22px;
            font-size: 12px;
            color: var(--text-dis);
            letter-spacing: .1px;
        }

        .login-footer a {
            color: var(--gold-dark);
            font-weight: 500;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="login-wrapper">



        <!-- Card -->
        <div class="login-card">
            <!-- Brand -->
            <div class="brand-header">
                <div class="brand-logo" style="padding:0; overflow:hidden;">
                    <img src="auth/uploads/<?= htmlspecialchars($adminAvatar) ?>"
                        alt="Avatar"
                        style="width:100%; height:100%; object-fit:cover; border-radius:16px;">
                </div>
                <h1 class="brand-name">
                    <?php echo !empty($adminData['fullname']) ? $adminData['fullname'] : 'Granite Artists'; ?>
                </h1>
                <p class="brand-tagline">Fabrication order management portal</p>
            </div>


            <!-- Type switcher -->
            <div class="type-switcher">
                <input type="radio" name="account_type_ui" id="type_admin" value="user" checked>
                <label for="type_admin" onclick="setType('user')">
                    <i class='bx bx-shield-quarter'></i> Admin
                </label>
                <input type="radio" name="account_type_ui" id="type_company" value="company">
                <label for="type_company" onclick="setType('company')">
                    <i class='bx bx-buildings'></i> Company
                </label>
            </div>

            <!-- Error -->
            <?php if ($error): ?>
                <div class="alert-login">
                    <i class='bx bx-error-circle' style="font-size:16px;flex-shrink:0;margin-top:1px;"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="auth/login.php" method="POST" id="loginForm">
                <input type="hidden" name="account_type" id="accountTypeInput" value="user">

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="input-wrap">
                        <i class='bx bx-envelope icon-left'></i>
                        <input type="email" name="email" id="email" class="form-control"
                            placeholder="you@company.com" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrap">
                        <i class='bx bx-lock-alt icon-left'></i>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="••••••••" required>
                        <button type="button" class="toggle-pwd" onclick="togglePwd()" tabindex="-1">
                            <i class='bx bx-show' id="pwdEye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="spinner d-none" id="loginSpinner"></span>
                    <span id="loginText">Sign In</span>
                </button>
            </form>

            <hr class="card-divider">

            <p style="font-size:12px;color:var(--text-dis);text-align:center;margin:0;">
                Protected area — authorized users only
            </p>

        </div>

        <div class="login-footer">
            Need assistance?
            <a href="https://mail.google.com/mail/?view=cm&to=<?php echo urlencode(!empty($adminData['email']) ? $adminData['email'] : 'cs@webkoit.com'); ?>" target="_blank">
                Contact support
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function setType(val) {
            document.getElementById('accountTypeInput').value = val;
        }

        function togglePwd() {
            const inp = document.getElementById('password');
            const eye = document.getElementById('pwdEye');
            inp.type = inp.type === 'password' ? 'text' : 'password';
            eye.className = inp.type === 'text' ? 'bx bx-hide' : 'bx bx-show';
        }
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            const spinner = document.getElementById('loginSpinner');
            const text = document.getElementById('loginText');
            btn.disabled = true;
            spinner.classList.remove('d-none');
            text.textContent = 'Signing in…';
        });
    </script>