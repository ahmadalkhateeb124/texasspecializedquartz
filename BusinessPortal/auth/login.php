<?php
/**
 * auth/login.php — Credential handler with CSRF + rate limiting.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

/* ── Only POST is accepted ─────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../auth-login-minimal.php');
    exit;
}

/* ── CSRF verification ─────────────────────────────────────── */
$submitted = $_POST['csrf_token'] ?? '';
if (!hash_equals(csrfToken(), $submitted)) {
    $_SESSION['login_error'] = 'Session expired. Please try again.';
    header('Location: ../auth-login-minimal.php');
    exit;
}

/* ── Rate limiting (session-based) ──────────────────────────
   Max 5 failed attempts per 15 minutes; reset on success. */
const MAX_ATTEMPTS = 5;
const WINDOW_SEC   = 900;

$now  = time();
$attempts = $_SESSION['login_attempts'] ?? ['count' => 0, 'first' => $now];
if ($now - ($attempts['first'] ?? $now) > WINDOW_SEC) {
    $attempts = ['count' => 0, 'first' => $now];
}
if ($attempts['count'] >= MAX_ATTEMPTS) {
    $wait = WINDOW_SEC - ($now - $attempts['first']);
    $minutes = max(1, ceil($wait / 60));
    $_SESSION['login_error'] = "Too many failed attempts. Try again in {$minutes} minute" . ($minutes !== 1 ? 's' : '') . '.';
    header('Location: ../auth-login-minimal.php');
    exit;
}

/* ── Input validation ──────────────────────────────────────── */
$email    = trim((string)($_POST['email'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$remember = !empty($_POST['rememberMe']);

if ($email === '' || $password === '') {
    $_SESSION['login_error'] = 'Please enter both email and password.';
    header('Location: ../auth-login-minimal.php');
    exit;
}

/* ── Helper: record failure and bounce ─────────────────────── */
$fail = function (string $msg) use (&$attempts) {
    $attempts['count']++;
    $_SESSION['login_attempts'] = $attempts;
    $_SESSION['login_error']    = $msg;
    header('Location: ../auth-login-minimal.php');
    exit;
};

/* ── Admin / employee login — auto-detected by email, no manual type picker ── */
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    if (!password_verify($password, $user['password'])) {
        $fail('Incorrect email or password.');
    }

    if (($user['status'] ?? 'Active') !== 'Active') {
        $fail('Your account has been deactivated. Please contact your administrator.');
    }

    // Success — regenerate session & populate
    session_regenerate_id(true);
    unset($_SESSION['login_attempts'], $_SESSION['login_error']);

    $_SESSION['user_id']     = $user['id'];
    $_SESSION['email']       = $user['email'];
    $_SESSION['username']    = $user['username'];
    $_SESSION['fullname']    = $user['fullname'];
    $_SESSION['designation'] = $user['designation'];
    $_SESSION['user_type']   = 'user';
    $_SESSION['role']        = $user['role'];
    $_SESSION['logged_in']   = true;

    if ($remember) {
        $token = bin2hex(random_bytes(16));
        $opts  = ['expires' => time() + 86400 * 30, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax'];
        setcookie('remember_token', $token, $opts);
        setcookie('user_id',        (string)$user['id'], $opts);
        setcookie('user_type',      'user', $opts);
        $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?")->execute([$token, $user['id']]);
    }

    header('Location: ../index.php');
    exit;
}

/* ── Customer (company) login ──────────────────────────────── */
$stmt = $pdo->prepare("
    SELECT id, company_id, name, email, password, status
    FROM accounts
    WHERE email = ?
    LIMIT 1
");
$stmt->execute([$email]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$account) {
    $fail('Incorrect email or password.');
}

if ($account['status'] !== 'Active') {
    $msg = match ($account['status']) {
        'Inactive'     => 'Your account is inactive. Please contact support: Cs@TexasSpecializedQuartz.com',
        'Blacklisted'  => 'Your account is blacklisted. Please contact support: Cs@TexasSpecializedQuartz.com',
        default        => 'Account access denied.',
    };
    $fail($msg);
}

if (!password_verify($password, $account['password'])) {
    $fail('Incorrect email or password.');
}

// Success — regenerate session & populate
session_regenerate_id(true);
unset($_SESSION['login_attempts'], $_SESSION['login_error']);

$_SESSION['account_id']   = $account['id'];
$_SESSION['company_id']   = $account['company_id'];
$_SESSION['email']        = $account['email'];
$_SESSION['account_name'] = $account['name'];
$_SESSION['user_type']    = 'account';
$_SESSION['logged_in']    = true;

if ($remember) {
    $token = bin2hex(random_bytes(16));
    $opts  = ['expires' => time() + 86400 * 30, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax'];
    setcookie('remember_token', $token, $opts);
    setcookie('account_id',     (string)$account['id'], $opts);
    setcookie('user_type',      'account', $opts);
}

header('Location: ../index.php');
exit;
