<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

requireAdmin();

// جلب بيانات المستخدم إذا موجود
$stmt = $pdo->prepare("SELECT * FROM users LIMIT 1");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$errors = []; // مصفوفة لتخزين الأخطاء

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // تنظيف المدخلات
    $fullname    = trim($_POST['fullname'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');
    $username    = trim($_POST['username'] ?? '');
    $company     = trim($_POST['company'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $website     = trim($_POST['website'] ?? '');
    $address     = trim($_POST['address'] ?? '');
    $about       = trim($_POST['about'] ?? '');
    $password    = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // التحقق من الحقول المطلوبة
    if (empty($fullname)) {
        $errors[] = "Full name is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    // التحقق من مطابقة كلمة السر إذا تم إدخالها
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        }
        if ($password !== $confirm_password) {
            $errors[] = "The password and confirmation password do not match.";
        }
    }

    // التحقق من البريد الإلكتروني الفريد
    if (!empty($email)) {
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $checkStmt->execute([$email, $user['id'] ?? 0]);
        if ($checkStmt->fetch()) {
            $errors[] = "Email already exists.";
        }
    }

    // التحقق من اسم المستخدم الفريد
    if (!empty($username)) {
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $checkStmt->execute([$username, $user['id'] ?? 0]);
        if ($checkStmt->fetch()) {
            $errors[] = "Username already exists.";
        }
    }

    // إذا لم تكن هناك أخطاء، تابع المعالجة
    if (empty($errors)) {
        // تشفير كلمة السر إذا تم إدخالها
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : ($user['password'] ?? '');

        $avatar = $user['avatar'] ?? NULL;

        // معالجة رفع الصورة
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            // التحقق من نوع الملف
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($_FILES['avatar']['tmp_name']);

            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = "Only JPG, PNG, and GIF files are allowed.";
            } else {
                // التحقق من حجم الملف (2MB كحد أقصى)
                if ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
                    $errors[] = "File size must be less than 2MB.";
                } else {
                    $targetDir = __DIR__ . "/uploads/";
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }

                    // حذف الصورة القديمة إذا كانت موجودة
                    if (!empty($user['avatar']) && file_exists($targetDir . $user['avatar'])) {
                        unlink($targetDir . $user['avatar']);
                    }

                    $fileExtension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
                    $filename = time() . "_" . uniqid() . "." . $fileExtension;

                    // رفع الملف
                    if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetDir . $filename)) {
                        $avatar = $filename;
                    } else {
                        $errors[] = "Failed to upload image.";
                    }
                }
            }
        }

        // إذا لم تكن هناك أخطاء في رفع الصورة، تابع الحفظ
        if (empty($errors)) {
            try {
                if ($user) {
                    // تحديث البيانات
                    $sql = "UPDATE users SET
                            fullname = :fullname,
                            email = :email,
                            phone = :phone,
                            username = :username,
                            company = :company,
                            designation = :designation,
                            website = :website,
                            address = :address,
                            about = :about,
                            avatar = :avatar,
                            password = :password,
                            updated_at = NOW()
                        WHERE id = :id";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':fullname'    => $fullname,
                        ':email'       => $email,
                        ':phone'       => $phone,
                        ':username'    => $username,
                        ':company'     => $company,
                        ':designation' => $designation,
                        ':website'     => $website,
                        ':address'     => $address,
                        ':about'       => $about,
                        ':avatar'      => $avatar,
                        ':password'    => $hashedPassword,
                        ':id'          => $user['id']
                    ]);

                    $_SESSION['success_message'] = "Profile updated successfully!";
                } else {
                    // إضافة مستخدم جديد
                    $sql = "INSERT INTO users (fullname, email, phone, username, company, designation, website, address, about, avatar, password, created_at)
                            VALUES (:fullname, :email, :phone, :username, :company, :designation, :website, :address, :about, :avatar, :password, NOW())";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':fullname'    => $fullname,
                        ':email'       => $email,
                        ':phone'       => $phone,
                        ':username'    => $username,
                        ':company'     => $company,
                        ':designation' => $designation,
                        ':website'     => $website,
                        ':address'     => $address,
                        ':about'       => $about,
                        ':avatar'      => $avatar,
                        ':password'    => $hashedPassword
                    ]);

                    $_SESSION['success_message'] = "Registration successful!";
                }

                header("Location: ../customers-create.php");
                exit;

            } catch (PDOException $e) {
                $errors[] = "Database error: " . $e->getMessage();
                $_SESSION['errors'] = $errors;
                header("Location: ../customers-create.php");
                exit;
            }
        }
    }

    // إذا كانت هناك أخطاء، احفظها في الجلسة وأعد التوجيه
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = $_POST; // حفظ المدخلات القديمة
        header("Location: ../customers-create.php");
        exit;
    }
}
?>