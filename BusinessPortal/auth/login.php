<?php
session_start();
include '../partials/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['rememberMe']);
    $account_type = $_POST['account_type'] ?? 'user'; // user أو company

    // التحقق من البيانات المدخلة
    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Please enter both email and password";
        header("Location: ../auth-login-minimal.php");
        exit;
    }

    if ($account_type === 'user') {
        // تسجيل الدخول كـ User
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['user_type'] = 'user';
            $_SESSION['logged_in'] = true;

            if ($remember) {
                $token = bin2hex(random_bytes(16));
                setcookie('remember_token', $token, time() + (86400 * 30), "/");
                setcookie('user_id', $user['id'], time() + (86400 * 30), "/");
                setcookie('user_type', 'user', time() + (86400 * 30), "/");

                $update = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $update->execute([$token, $user['id']]);
            }

            header("Location: ../index.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Incorrect email or password";
            header("Location: ../auth-login-minimal.php");
            exit;
        }

    }

    elseif ($account_type === 'company') {

        $stmt = $pdo->prepare("
        SELECT id, company_id, name, email, password, status
        FROM accounts
        WHERE email = ?
        LIMIT 1
    ");
        $stmt->execute([$email]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        // تحقق من وجود الحساب
        if (!$account) {
            $_SESSION['login_error'] = "Incorrect email or password";
            header("Location: ../auth-login-minimal.php");
            exit;
        }

        // تحقق من حالة الحساب
        if ($account['status'] !== 'Active') {

            if ($account['status'] === 'Inactive') {
                $_SESSION['login_error'] = "Your account is inactive. Please contact support. Cs@TexasSpecializedQuartz.com";
            } elseif ($account['status'] === 'Blacklisted') {
                $_SESSION['login_error'] = "Your account is Blacklisted. Please contact support. Cs@TexasSpecializedQuartz.com";
            } else {
                $_SESSION['login_error'] = "Account access denied.";
            }

            header("Location: ../auth-login-minimal.php");
            exit;
        }

        // تحقق من كلمة المرور
        if (!password_verify($password, $account['password'])) {
            $_SESSION['login_error'] = "Incorrect email or password";
            header("Location: ../auth-login-minimal.php");
            exit;
        }

        // ✅ تسجيل الدخول
        $_SESSION['account_id']   = $account['id'];
        $_SESSION['company_id']   = $account['company_id'];
        $_SESSION['email']        = $account['email'];
        $_SESSION['account_name'] = $account['name'];
        $_SESSION['user_type']    = 'account';
        $_SESSION['logged_in']    = true;

        // Remember Me
        if (!empty($remember)) {
            $token = bin2hex(random_bytes(16));
            setcookie('remember_token', $token, time() + (86400 * 30), "/", "", false, true);
            setcookie('account_id', $account['id'], time() + (86400 * 30), "/", "", false, true);
            setcookie('user_type', 'account', time() + (86400 * 30), "/", "", false, true);
        }

        header("Location: ../index.php");
        exit;
    }

}
?>