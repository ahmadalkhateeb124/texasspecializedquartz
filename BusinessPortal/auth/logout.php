<?php
require_once __DIR__ . '/../src/session.php';

// مسح جميع بيانات الجلسة
session_unset();
session_destroy();

// مسح كوكي "تذكرني"
setcookie('remember_token', '', time() - 3600, "/");

// منع الكاش لضمان عدم العودة للصفحات القديمة
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// إعادة التوجيه فورًا لصفحة تسجيل الدخول
header("Location: ../auth-login-minimal.php");
exit;