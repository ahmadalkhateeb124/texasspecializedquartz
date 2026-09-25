<?php
/**
 * auth/assign-order-employee.php — assign (or unassign) the single employee
 * responsible for an order (and, by extension, every job section in it).
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

header('Content-Type: application/json');

/** Email the employee that an order has been assigned to them. Failures are logged, never fatal. */
function notifyEmployeeOfAssignment(PDO $pdo, array $employee, int $orderId): void
{
    if (empty($employee['email'])) {
        return;
    }
    try {
        $mailConfig = require __DIR__ . '/../config/mail.php';
        if (empty($mailConfig['enabled']) || empty($mailConfig['password'])) {
            return;
        }

        $stmt = $pdo->prepare("SELECT id, customer_name, address, city FROM fabrication_orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) {
            return;
        }

        $built  = EmployeeAssignmentEmailBuilder::build($employee, $order);
        $mailer = new Mailer($mailConfig);
        $mailer->send($employee['email'], $built['subject'], $built['body']);
    } catch (\Throwable $e) {
        Logger::exception($e, 'employee-assignment-mail');
    }
}

if (!isAdmin()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}
if (!isset($_POST['csrf_token']) || !hash_equals(csrfToken(), $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

try {
    $orderId = (int)($_POST['order_id'] ?? 0);
    if ($orderId <= 0) {
        throw new RuntimeException('Missing order_id');
    }
    $employeeId = !empty($_POST['employee_id']) ? (int)$_POST['employee_id'] : null;

    $repo = new EmployeeRepository($pdo);
    $repo->assignEmployee($orderId, $employeeId);

    $assigned = $repo->employeeForOrder($orderId);

    if ($assigned) {
        notifyEmployeeOfAssignment($pdo, $assigned, $orderId);
    }

    echo json_encode(['success' => true, 'name' => $assigned['fullname'] ?? null]);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
