<?php

require_once __DIR__ . '/../includes/db.php';

// التحقق من تسجيل الدخول
function isLoggedIn() {
    // التحقق من الجلسة
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        return true;
    }

    // التحقق من الكوكيز (Remember Me)
    if (isset($_COOKIE['remember_token']) && isset($_COOKIE['user_type'])) {
        if ($_COOKIE['user_type'] === 'user' && isset($_COOKIE['user_id'])) {
            $token = $_COOKIE['remember_token'];
            $user_id = $_COOKIE['user_id'];

            try {
                global $pdo;
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND remember_token = ?");
                $stmt->execute([$user_id, $token]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['user_type'] = 'user';
                    $_SESSION['logged_in'] = true;
                    return true;
                }
            } catch (PDOException $e) {
                // تجاهل الخطأ
            }
        }

        if ($_COOKIE['user_type'] === 'account' && isset($_COOKIE['account_id'])) {
            $account_id = $_COOKIE['account_id'];

            try {
                global $pdo;
                $stmt = $pdo->prepare("SELECT * FROM accounts WHERE id = ? AND status = 'Active'");
                $stmt->execute([$account_id]);
                $account = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($account) {
                    $_SESSION['account_id'] = $account['id'];
                    $_SESSION['company_id'] = $account['company_id'];
                    $_SESSION['email'] = $account['email'];
                    $_SESSION['account_name'] = $account['name'];
                    $_SESSION['user_type'] = 'account';
                    $_SESSION['logged_in'] = true;
                    return true;
                }
            } catch (PDOException $e) {
                // تجاهل الخطأ
            }
        }
    }

    return false;
}

// إعادة التوجيه إذا لم يكن مسجل الدخول
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../auth-login-minimal.php');
        exit;
    }
}

// الحصول على معلومات المستخدم الحالي
function getCurrentUser() {
    if (isset($_SESSION['user_type'])) {
        if ($_SESSION['user_type'] === 'user') {
            return [
                'id' => $_SESSION['user_id'] ?? null,
                'email' => $_SESSION['email'] ?? null,
                'username' => $_SESSION['username'] ?? null,
                'fullname' => $_SESSION['fullname'] ?? null,
                'type' => 'user'
            ];
        } elseif ($_SESSION['user_type'] === 'account') {
            return [
                'id' => $_SESSION['account_id'] ?? null,
                'company_id' => $_SESSION['company_id'] ?? null,
                'email' => $_SESSION['email'] ?? null,
                'name' => $_SESSION['account_name'] ?? null,
                'type' => 'account'
            ];
        }
    }
    return null;
}

// تسجيل الخروج
function logout() {
    // مسح الجلسة
    session_unset();
    session_destroy();

    // مسح الكوكيز
    setcookie('remember_token', '', time() - 3600, '/');
    setcookie('user_id', '', time() - 3600, '/');
    setcookie('account_id', '', time() - 3600, '/');
    setcookie('user_type', '', time() - 3600, '/');

    // إعادة التوجيه لصفحة تسجيل الدخول
    header('Location: ../auth-login-minimal.php');
    exit;
}
?>