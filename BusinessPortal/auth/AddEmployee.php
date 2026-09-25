<?php
// auth/AddEmployee.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}
if (!isset($_POST['csrf_token']) || !hash_equals(csrfToken(), $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$fullname         = trim($_POST['fullname'] ?? '');
$username         = trim($_POST['username'] ?? '');
$email            = trim($_POST['email'] ?? '');
$phone            = trim($_POST['phone'] ?? '');
$designation      = trim($_POST['designation'] ?? '');
$password         = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$required = ['fullname' => 'Full name', 'username' => 'Username', 'email' => 'Email', 'password' => 'Password'];
foreach ($required as $field => $label) {
    if (empty($$field)) {
        echo json_encode(['success' => false, 'message' => "$label is required"]);
        exit;
    }
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

if ($password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit;
}

if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters and contain letters and numbers']);
    exit;
}

try {
    $repo = new EmployeeRepository($pdo);

    if ($repo->emailTakenByOther($email, 0)) {
        echo json_encode(['success' => false, 'message' => 'Email address already exists']);
        exit;
    }
    if ($repo->usernameTakenByOther($username, 0)) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        exit;
    }

    $id = $repo->create([
        'fullname'    => $fullname,
        'email'       => $email,
        'phone'       => $phone,
        'username'    => $username,
        'designation' => $designation,
        'password'    => $password,
    ]);

    echo json_encode(['success' => true, 'message' => 'Employee created successfully', 'employee_id' => $id]);
} catch (PDOException $e) {
    error_log('Add employee error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
