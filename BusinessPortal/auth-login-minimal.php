<?php

/**
 * auth-login-minimal.php — Texas Specialized Quartz Sign-in
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Texas Specialized Quartz & Granite</title>
    <link rel="icon" type="image/png" href="/images/Granit-Img/logo-gg.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700;9..144,800;9..144,900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-dark:  #0f1b2d;
            --brand-dark2: #15283f;
            --brand-gold:  #c9a96e;
            --brand-gold-h:#a07840;
            --accent:      #4f8872;
            --text:        #1a1814;
            --text-sub:    #5c5650;
            --text-dis:    #8c877f;
            --border:      #e5e0d8;
            --surface:     #ffffff;
            --bg:          #faf7f2;
            --danger:      #c73734;
            --danger-bg:   #fdecec;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', -apple-system, Segoe UI, sans-serif;
            color: var(--text);
            background: var(--bg);
            -webkit-font-smoothing: antialiased;
        }

        /* ── Split layout ── */
        .split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ── Left panel (brand) ── */
        .brand-panel {
            position: relative;
            background:
                radial-gradient(circle at 30% 80%, rgba(201,169,110,.12), transparent 50%),
                linear-gradient(135deg, var(--brand-dark) 0%, var(--brand-dark2) 100%);
            color: #fff;
            padding: 64px 56px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
        }

        .brand-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(ellipse at top left, rgba(201,169,110,.08), transparent 40%);
            pointer-events: none;
        }

        .brand-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            background: rgba(255,255,255,.08);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: .4px;
            color: #e6dbc2;
            align-self: flex-start;
            margin-bottom: 36px;
            position: relative;
            z-index: 1;
        }

        .brand-pill i { color: var(--brand-gold); font-size: 14px; }

        .brand-title {
            font-family: 'Fraunces', Georgia, 'Times New Roman', serif;
            font-weight: 400;
            font-size: clamp(40px, 4vw, 54px);
            line-height: 1.15;
            color: #fff;
            margin-bottom: 24px;
            position: relative;
            z-index: 1;
        }
        .brand-title em {
            font-style: normal;
            font-weight: 700;
            color: var(--brand-gold);
        }
        em, i, cite, dfn, blockquote, q { font-style: normal !important; }

        .brand-sub {
            font-size: 16px;
            line-height: 1.7;
            color: #c9c4bc;
            max-width: 480px;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 16px;
            position: relative;
            z-index: 1;
        }
        .feature-list li {
            display: flex;
            align-items: center;
            gap: 14px;
            color: #d9d4cb;
            font-size: 15px;
        }
        .feature-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 0 3px rgba(79,136,114,.18);
            flex-shrink: 0;
        }

        .brand-footer {
            position: absolute;
            bottom: 32px;
            left: 56px;
            right: 56px;
            display: flex;
            justify-content: space-between;
            color: rgba(255,255,255,.4);
            font-size: 12px;
            z-index: 1;
        }

        /* ── Right panel (form) ── */
        .form-panel {
            background: var(--surface);
            padding: 56px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .form-wrap {
            max-width: 400px;
            margin: 0 auto;
            width: 100%;
        }

        .form-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
        }
        .form-logo img {
            height: 52px;
            width: auto;
            object-fit: contain;
        }
        .form-logo-name {
            font-family: 'Fraunces', Georgia, 'Times New Roman', serif;
            font-size: 20px;
            line-height: 1.15;
            color: var(--text);
            max-width: 200px;
        }


        .form-heading {
            font-family: 'Fraunces', Georgia, 'Times New Roman', serif;
            font-size: 36px;
            font-weight: 400;
            color: var(--text);
            margin-bottom: 8px;
        }
        .form-sub {
            font-size: 14px;
            color: var(--text-sub);
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 18px;
        }
        .form-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 6px;
        }
        .form-label a {
            font-size: 12px;
            color: var(--text-sub);
            text-decoration: none;
            font-weight: 400;
        }
        .form-label a:hover { color: var(--text); }

        .input-wrap {
            position: relative;
        }
        .form-control {
            width: 100%;
            height: 46px;
            padding: 0 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            font-size: 14px;
            color: var(--text);
            font-family: inherit;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control::placeholder { color: var(--text-dis); }
        .form-control:focus {
            outline: none;
            border-color: var(--text);
            box-shadow: 0 0 0 3px rgba(15,27,45,.06);
        }

        .pwd-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: 0;
            cursor: pointer;
            color: var(--text-dis);
            font-size: 18px;
            padding: 4px;
        }
        .pwd-toggle:hover { color: var(--text); }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-sub);
            margin-bottom: 24px;
        }
        .remember-row input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: var(--text);
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            height: 48px;
            background: var(--text);
            color: #fff;
            border: 0;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform .12s, background .15s;
        }
        .btn-login:hover { background: #000; }
        .btn-login:active { transform: translateY(1px); }
        .btn-login:disabled { opacity: .6; cursor: wait; }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
        }
        .spinner.d-none { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .alert-error {
            padding: 12px 14px;
            background: var(--danger-bg);
            color: var(--danger);
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }

        .unified-note {
            padding: 12px 14px;
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text-sub);
            border-radius: 10px;
            font-size: 12.5px;
            line-height: 1.5;
            margin-bottom: 20px;
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }
        .unified-note i { font-size: 15px; flex-shrink: 0; margin-top: 1px; color: #000000; }

        .form-footer {
            position: absolute;
            bottom: 32px;
            left: 48px;
            right: 48px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--text-dis);
        }
        .form-footer a { color: var(--text-sub); text-decoration: none; }
        .form-footer a:hover { color: var(--text); }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .split { grid-template-columns: 1fr; min-height: auto; align-content: start; }
            .brand-panel { display: none; }
            .form-panel {
                padding: 40px 24px 80px;
                min-height: 100vh;
            }
            .lang-switch { top: 16px; right: 16px; }
            .form-footer { left: 24px; right: 24px; bottom: 20px; }
        }
    </style>
</head>

<body>
    <div class="split">

        <!-- ═══ LEFT — BRAND PANEL ═══ -->
        <aside class="brand-panel">
            <span class="brand-pill">
                <i class='bx bxs-diamond'></i>
                Granite Order Management
            </span>

            <h1 class="brand-title">
                Manage your <em>fabrication</em> workflow with ease
            </h1>

            <p class="brand-sub">
                A complete platform for granite &amp; quartz fabrication —
                customer orders, job sections, pricing, and fleet of projects,
                all in one place.
            </p>

            <ul class="feature-list">
                <li><span class="feature-dot"></span>Multi-customer account management</li>
                <li><span class="feature-dot"></span>Track fabrication orders &amp; job sections</li>
                <li><span class="feature-dot"></span>Smart pricing &amp; file attachments</li>
                <li><span class="feature-dot"></span>Complete activity log per customer</li>
            </ul>

            <div class="brand-footer">
                <span>© <?= date('Y') ?> Texas Specialized Quartz &amp; Granite</span>
                <span>v1.1</span>
            </div>
        </aside>

        <!-- ═══ RIGHT — FORM PANEL ═══ -->
        <main class="form-panel">
            <div class="form-wrap">

                <div class="form-logo">
                    <img src="/images/Granit-Img/logo.png" alt="">
                    <span class="form-logo-name">Texas Specialized Quartz &amp; Granite</span>
                </div>

                <h2 class="form-heading">Welcome back</h2>
                <p class="form-sub">Sign in to your management dashboard</p>

                <!-- TEMP: remove once everyone's used to the unified login (no more separate tabs) -->
                <div class="unified-note">
                    <i class='bx bx-info-circle'></i>
                    One sign-in for everyone customers, employees, and admins all log in here. We'll detect your account automatically.
                </div>

                <?php if ($error): ?>
                    <div class="alert-error">
                        <i class='bx bx-error-circle' style="font-size:16px;flex-shrink:0;margin-top:1px;"></i>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <form action="auth/login.php" method="POST" id="loginForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">

                    <div class="form-group">
                        <label class="form-label" for="email">Email address</label>
                        <div class="input-wrap">
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="you@example.com" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">
                            Password
                        </label>
                        <div class="input-wrap">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="••••••••" required>
                            <button type="button" class="pwd-toggle" onclick="togglePwd()" tabindex="-1">
                                <i class='bx bx-show' id="pwdEye"></i>
                            </button>
                        </div>
                    </div>

                    <label class="remember-row">
                        <input type="checkbox" name="rememberMe" value="1">
                        Remember me
                    </label>

                    <button type="submit" class="btn-login" id="loginBtn">
                        <span class="spinner d-none" id="loginSpinner"></span>
                        <span id="loginText">Sign In</span>
                    </button>
                </form>
            </div>

            <div class="form-footer">
                <span>Protected area — authorized users only</span>
            </div>
        </main>

    </div>

    <script>
        // Password toggle
        function togglePwd() {
            const inp = document.getElementById('password');
            const eye = document.getElementById('pwdEye');
            inp.type = inp.type === 'password' ? 'text' : 'password';
            eye.className = inp.type === 'text' ? 'bx bx-hide' : 'bx bx-show';
        }

        // Submit button state
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('loginBtn');
            const spinner = document.getElementById('loginSpinner');
            const text = document.getElementById('loginText');
            btn.disabled = true;
            spinner.classList.remove('d-none');
            text.textContent = 'Signing in…';
        });
    </script>
</body>

</html>
