<?php
require_once __DIR__ . '/../src/session.php';

require_once __DIR__ . '/../auth/auth-check.php';
require_once __DIR__ . '/../includes/db.php';

// CSRF Check
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    header("Location: ../Customer.php?error=csrf");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Customer.php");
    exit;
}

try {
    $pdo->beginTransaction();

    // Required fields
    $required_fields = [
        'company_name',
        'contact_name',
        'phone',
        'email',
        'password',
        'confirm_password'
    ];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing required field: ' . $field,
                'field' => $field
            ]);
            exit;
        }
    }

    // Sanitize data
    $customer_type     = trim($_POST['customer_type'] ?? '');
    $company_name      = trim($_POST['company_name']);
    $contact_name      = trim($_POST['contact_name']);
    $contact_position  = trim($_POST['contact_position'] ?? '');
    $phone             = trim($_POST['phone']);
    $email             = trim($_POST['email']);
    $city              = trim($_POST['city'] ?? '');
    $state             = trim($_POST['state'] ?? '');
    $zip_code          = trim($_POST['zip_code'] ?? '');
    $address           = trim($_POST['address'] ?? '');
    $password          = $_POST['password'];
    $confirm_password  = $_POST['confirm_password'];

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email address'
        ]);
        exit;
    }

    // Password validation
    if ($password !== $confirm_password) {
        echo json_encode([
            'success' => false,
            'message' => 'Passwords do not match'
        ]);
        exit;
    }

    if (
        strlen($password) < 8 ||
        !preg_match('/[A-Za-z]/', $password) ||
        !preg_match('/[0-9]/', $password)
    ) {
        echo json_encode([
            'success' => false,
            'message' => 'Password must be at least 8 characters and contain letters and numbers'
        ]);
        exit;
    }

    // Phone validation
    $cleaned_phone = preg_replace('/\D/', '', $phone);
    if (strlen($cleaned_phone) < 10) {
        echo json_encode([
            'success' => false,
            'message' => 'Phone number must be at least 10 digits'
        ]);
        exit;
    }

    // Permissions
    $permissions = isset($_POST['permissions'])
        ? json_encode($_POST['permissions'])
        : json_encode([]);

    // Check duplicate email
    $checkEmail = $pdo->prepare("SELECT id FROM accounts WHERE email = ?");
    $checkEmail->execute([$email]);
    if ($checkEmail->rowCount() > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Email address already exists'
        ]);
        exit;
    }

    // Insert company
    $stmt = $pdo->prepare("
        INSERT INTO customers_companies
        (
            customer_type, company_name,
            contact_name, contact_position,
            phone, email,
            city, state, zip_code, address
        )
        VALUES (?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $customer_type,
        $company_name,
        $contact_name,
        $contact_position,
        $phone,
        $email,
        $city,
        $state,
        $zip_code,
        $address
    ]);

    $company_id = $pdo->lastInsertId();

    // Create account
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt2 = $pdo->prepare("
        INSERT INTO accounts
        (company_id, name, email, password, permissions, status)
        VALUES (?,?,?,?,?,1)
    ");

    $stmt2->execute([
        $company_id,
        $contact_name,
        $email,
        $hashed_password,
        $permissions
    ]);

    $pdo->commit();

    error_log("New company created: {$company_name} (ID: {$company_id})");

    echo json_encode([
        'success' => true,
        'message' => 'Customer created successfully',
        'customer_id' => $company_id
    ]);
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Company creation error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred'
    ]);
    exit;
}
