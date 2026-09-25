<?php
// auth/delete-employee.php
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

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid employee ID']);
    exit;
}

try {
    $repo     = new EmployeeRepository($pdo);
    $employee = $repo->find($id);
    if (!$employee) {
        echo json_encode(['success' => false, 'message' => 'Employee not found']);
        exit;
    }

    $repo->delete($id);
    error_log("Employee deleted: {$employee['fullname']} (ID: {$id}) by user: " . ($_SESSION['user_id'] ?? 'unknown'));

    echo json_encode(['success' => true, 'message' => "Employee '{$employee['fullname']}' deleted successfully"]);
} catch (PDOException $e) {
    error_log('Delete employee error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
