<?php

/**
 * includes/auth.php
 * ─────────────────────────────────────────────────────────────────────────────
 * Central authentication & role-based access control layer.
 *
 * Session contract (set by auth/login.php):
 *   $_SESSION['logged_in']   = true
 *   $_SESSION['user_type']   = 'user'    (admin) | 'account' (customer)
 *   $_SESSION['user_id']     = int       (admin only)
 *   $_SESSION['account_id']  = int       (customer only)
 *   $_SESSION['company_id']  = int       (customer only)
 *   $_SESSION['email']       = string
 *   $_SESSION['fullname']    = string    (admin)
 *   $_SESSION['account_name']= string    (customer)
 *
 * Usage in any page:
 *   require_once __DIR__ . '/../includes/auth.php';   // adjust depth
 *   requireLogin();      // any logged-in user
 *   requireAdmin();      // admin only
 *   requireCustomer();   // customer only
 */

require_once __DIR__ . '/../src/session.php';

/* ═══════════════════════════════════════════════════════════════
   STATUS CHECKERS
═══════════════════════════════════════════════════════════════ */

/** Returns true if a valid session exists. */
function isLoggedIn(): bool
{
    return !empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/** Returns true if the logged-in user is an admin (user_type = 'user'). */
function isAdmin(): bool
{
    return isLoggedIn() && (($_SESSION['user_type'] ?? '') === 'user');
}

/** Returns true if the logged-in user is a customer (user_type = 'account'). */
function isCustomer(): bool
{
    return isLoggedIn() && (($_SESSION['user_type'] ?? '') === 'account');
}

/* ═══════════════════════════════════════════════════════════════
   ROUTE GUARDS
═══════════════════════════════════════════════════════════════ */

/**
 * Redirect to login if the user is not authenticated.
 * Pass a custom $loginUrl if the auto-resolved path is wrong.
 */
function requireLogin(?string $loginUrl = null): void
{
    if (!isLoggedIn()) {
        _redirect($loginUrl ?? _loginUrl());
    }
}

/**
 * Allow only admin users. Redirects everyone else to login.
 */
function requireAdmin(?string $loginUrl = null): void
{
    requireLogin($loginUrl);

    if (!isAdmin()) {
        _redirect($loginUrl ?? _loginUrl());
    }
}

/**
 * Allow only customer users. Redirects everyone else to login.
 */
function requireCustomer(?string $loginUrl = null): void
{
    requireLogin($loginUrl);

    if (!isCustomer()) {
        _redirect($loginUrl ?? _loginUrl());
    }

    // Check account status
    $accountId = $_SESSION['account_id'] ?? 0;
    if ($accountId > 0) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT status FROM accounts WHERE id = ?");
        $stmt->execute([$accountId]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($account && $account['status'] !== 'Active') {
            // Log out the user
            $_SESSION['login_error'] = $account['status'] === 'Inactive'
                ? 'Your account has been deactivated. Please contact support.'
                : 'Your account has been suspended. Please contact support.';
            session_destroy();
            _redirect($loginUrl ?? _loginUrl());
        }
    }
}

/* ═══════════════════════════════════════════════════════════════
   CURRENT USER
═══════════════════════════════════════════════════════════════ */

/**
 * Returns a normalised array for the currently logged-in user.
 * Returns [] when not authenticated.
 */
function currentUser(): array
{
    if (!isLoggedIn()) {
        return [];
    }

    if (isAdmin()) {
        return [
            'id'     => (int) ($_SESSION['user_id'] ?? 0),
            'name'   => $_SESSION['fullname']  ?? $_SESSION['username'] ?? 'Admin',
            'email'  => $_SESSION['email']     ?? '',
            'role'   => 'admin',
            'avatar' => $_SESSION['avatar']    ?? null,
        ];
    }

    // customer
    return [
        'id'         => (int) ($_SESSION['account_id'] ?? 0),
        'company_id' => (int) ($_SESSION['company_id'] ?? 0),
        'name'       => $_SESSION['account_name'] ?? 'Customer',
        'email'      => $_SESSION['email']        ?? '',
        'role'       => 'customer',
        'avatar'     => null,
    ];
}

/**
 * Convenience: get a single field from currentUser().
 * e.g. authField('name')  →  'John Doe'
 */
function authField(string $field, $default = null)
{
    return currentUser()[$field] ?? $default;
}

/* ═══════════════════════════════════════════════════════════════
   CSRF HELPERS
═══════════════════════════════════════════════════════════════ */

/** Generate (or fetch cached) a CSRF token for the current session. */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Verify a submitted CSRF token; die on mismatch. */
function verifyCsrf(string $submitted): void
{
    if (!hash_equals(csrfToken(), $submitted)) {
        http_response_code(403);
        die('CSRF token mismatch.');
    }
}

/* ═══════════════════════════════════════════════════════════════
   INTERNAL HELPERS
═══════════════════════════════════════════════════════════════ */

/** Redirect and exit. */
function _redirect(string $url): void
{
    header("Location: $url");
    exit;
}

/**
 * Get the base app folder name dynamically.
 * Returns 'GrDa' on localhost but will adapt to any folder name on live server.
 */
function getBaseFolderName(): string
{
    static $folderName = null;

    if ($folderName === null) {
        // Get the project root directory (parent of includes/)
        $root = realpath(__DIR__ . '/..');
        $folderName = basename($root);
    }

    return $folderName;
}

/**
 * Get the base app URL path (e.g., /GrDa/ or /myapp/).
 */
function getBaseUrlPath(): string
{
    return '/' . getBaseFolderName() . '/';
}

/**
 * Auto-resolve the login URL relative to the calling script's directory depth.
 * Works for scripts at any nesting level inside /GrDa/.
 */
function _loginUrl(): string
{
    $root   = realpath(__DIR__ . '/..');          // e.g. /GrDa
    $script = realpath($_SERVER['SCRIPT_FILENAME'] ?? '');

    if (!$script || strpos($script, $root) !== 0) {
        return getBaseUrlPath() . 'auth-login-minimal.php';
    }

    // Count directory levels below root
    $relative = substr($script, strlen($root) + 1); // strip leading slash
    $depth    = max(0, substr_count(dirname($relative), DIRECTORY_SEPARATOR));

    return str_repeat('../', $depth) . 'auth-login-minimal.php';
}
