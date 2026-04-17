<?php
// auth/UpdateCustomer.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}
if (!isset($_POST['csrf_token']) || !hash_equals(csrfToken(), $_POST['csrf_token'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}
// جلب البيانات من POST
$company_id = $_POST['company_id'] ?? 0;
$company_name = trim($_POST['company_name'] ?? '');
$contact_name = trim($_POST['contact_name'] ?? '');
$contact_position = trim($_POST['contact_position'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$city = trim($_POST['city'] ?? '');
$state = trim($_POST['state'] ?? '');
$zip_code = trim($_POST['zip_code'] ?? '');
$address = trim($_POST['address'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$account_status = $_POST['account_status'] ?? 'Inactive';
$permissions = isset($_POST['permissions']) ? json_encode($_POST['permissions']) : json_encode([]);
// تحقق من وجود ID
if ($company_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid customer ID']);
    exit;
}
// تحقق من الحقول المطلوبة
$required_fields = [
    'company_name' => 'Company Name',
    'contact_name' => 'Contact Name',
    'phone' => 'Phone',
    'email' => 'Email'
];
foreach ($required_fields as $field => $name) {
    if (empty($$field)) {
        echo json_encode(['success' => false, 'message' => $name . ' is required']);
        exit;
    }
}
// تحقق من صحة البريد الإلكتروني
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}
// إذا تم إدخال كلمة مرور، تحقق منها
if (!empty($password)) {
    if ($password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
        exit;
    }
    if (strlen($password) < 8) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
        exit;
    }
}
try {
    // بدء المعاملة
    $pdo->beginTransaction();
    // 1️⃣ تحقق من تكرار Email (لشركات أخرى غير هذه الشركة)
    $checkEmail = $pdo->prepare("
        SELECT id FROM customers_companies WHERE email = ? AND id != ?
        UNION
        SELECT a.id FROM accounts a 
        JOIN customers_companies c ON a.company_id = c.id 
        WHERE a.email = ? AND c.id != ?
    ");
    $checkEmail->execute([$email, $company_id, $email, $company_id]);
    if ($checkEmail->rowCount() > 0) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Email address already exists']);
        exit;
    }
    // 2️⃣ تحديث بيانات الشركة
    $stmtCompany = $pdo->prepare("
        UPDATE customers_companies SET
            company_name = :company_name,
            contact_name = :contact_name,
            contact_position = :contact_position,
            phone = :phone,
            email = :email,
            city = :city,
            state = :state,
            zip_code = :zip_code,
            address = :address
        WHERE id = :company_id
    ");
    $stmtCompany->execute([
        ':company_name' => $company_name,
        ':contact_name' => $contact_name,
        ':contact_position' => $contact_position,
        ':phone' => $phone,
        ':email' => $email,
        ':city' => $city,
        ':state' => $state,
        ':zip_code' => $zip_code,
        ':address' => $address,
        ':company_id' => $company_id
    ]);
    // 3️⃣ تحديث بيانات الحساب
    if (!empty($password)) {
        // إذا تم إدخال كلمة مرور جديدة، قم بتحديثها
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmtAccount = $pdo->prepare("
            UPDATE accounts SET
                name = :name,
                email = :email,
                password = :password,
                permissions = :permissions,
                status = :status
            WHERE company_id = :company_id
        ");
        $stmtAccount->execute([
            ':name' => $contact_name,
            ':email' => $email,
            ':password' => $hashed_password,
            ':permissions' => $permissions,
            ':status' => $account_status,
            ':company_id' => $company_id
        ]);
    } else {
        // إذا لم يتم إدخال كلمة مرور جديدة، احتفظ بالكلمة القديمة
        $stmtAccount = $pdo->prepare("
            UPDATE accounts SET
                name = :name,
                email = :email,
                permissions = :permissions,
                status = :status
            WHERE company_id = :company_id
        ");
        $stmtAccount->execute([
            ':name' => $contact_name,
            ':email' => $email,
            ':permissions' => $permissions,
            ':status' => $account_status,
            ':company_id' => $company_id
        ]);
    }
    // تأكيد المعاملة
    $pdo->commit();
    echo json_encode([
        'success' => true,
        'message' => 'Customer updated successfully'
    ]);
    exit;
} catch (PDOException $e) {
    // التراجع عن المعاملة في حالة الخطأ
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // تسجيل الخطأ
    error_log("Update Customer Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
    exit;
}
