<?php
/**
 * auth/schedule.php — CRUD endpoint for job_schedules (admin only).
 * Actions: create | update | delete | set_order_status
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

header('Content-Type: application/json');

/** Whether the order that owns this job section has been closed (signed off / completed). */
function isOrderLockedForSection(PDO $pdo, int $jobSectionId): bool
{
    $stmt = $pdo->prepare("
        SELECT fo.admin_status
        FROM job_sections js
        JOIN fabrication_orders fo ON fo.id = js.order_id
        WHERE js.id = ?
    ");
    $stmt->execute([$jobSectionId]);
    return $stmt->fetchColumn() === 'completed';
}

/** Email the customer who placed the order about a job-section schedule update. Failures are logged, never fatal. */
function notifyCustomerOfJobSectionEvent(PDO $pdo, int $jobSectionId, array $eventData): void
{
    try {
        $mailConfig = require __DIR__ . '/../config/mail.php';
        if (empty($mailConfig['enabled']) || empty($mailConfig['password'])) {
            return;
        }

        $stmt = $pdo->prepare("
            SELECT js.job_type, js.job_type_other,
                   fo.id AS order_id, fo.customer_name, a.email AS customer_email
            FROM job_sections js
            JOIN fabrication_orders fo ON fo.id = js.order_id
            LEFT JOIN accounts a       ON a.id = fo.account_id
            WHERE js.id = ?
        ");
        $stmt->execute([$jobSectionId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row || empty($row['customer_email'])) {
            return;
        }

        $order = ['id' => $row['order_id'], 'customer_name' => $row['customer_name']];
        $job   = ['job_type' => $row['job_type'], 'job_type_other' => $row['job_type_other']];

        $built  = JobSectionEventEmailBuilder::build($order, $job, $eventData);
        $mailer = new Mailer($mailConfig);
        $mailer->send($row['customer_email'], $built['subject'], $built['body']);
    } catch (\Throwable $e) {
        Logger::exception($e, 'job-section-event-mail');
    }
}

if (!isAdmin() && !isEmployee()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

$action  = $_POST['action'] ?? '';
$repo    = new JobScheduleRepository($pdo);
$empRepo = new EmployeeRepository($pdo);

/* Employees may only create/update/delete events on job sections assigned to them,
   and may never touch the overall order status. */
if (isEmployee()) {
    $employeeId = (int)($_SESSION['user_id'] ?? 0);

    if ($action === 'set_order_status') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    if ($action === 'create') {
        $jobSectionId = (int)($_POST['job_section_id'] ?? 0);
        if (!$empRepo->ownsSection($employeeId, $jobSectionId)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'You are not assigned to this job section']);
            exit;
        }
        if (isOrderLockedForSection($pdo, $jobSectionId)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'This order is closed and read-only.']);
            exit;
        }
    }

    if ($action === 'update' || $action === 'delete') {
        $existing = $repo->find((int)($_POST['id'] ?? 0));
        if (!$existing || !$empRepo->ownsSection($employeeId, (int)$existing['job_section_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'You are not assigned to this job section']);
            exit;
        }
        if (isOrderLockedForSection($pdo, (int)$existing['job_section_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'This order is closed and read-only.']);
            exit;
        }
    }
}

try {
    switch ($action) {
        case 'create': {
            $jobSectionId = (int)($_POST['job_section_id'] ?? 0);
            $orderId = $pdo->prepare("SELECT order_id FROM job_sections WHERE id = ?");
            $orderId->execute([$jobSectionId]);
            $realOrderId = (int)$orderId->fetchColumn();

            $eventData = [
                'event_type'     => $_POST['event_type'] ?? 'inspection',
                'scheduled_date' => $_POST['scheduled_date'] ?? '',
                'status'         => $_POST['status'] ?? 'pending',
                'notes'          => $_POST['notes'] ?? null,
            ];

            $id = $repo->create([
                'order_id'       => $realOrderId ?: (int)($_POST['order_id'] ?? 0),
                'job_section_id' => $jobSectionId,
            ] + $eventData);

            if (isEmployee()) {
                notifyCustomerOfJobSectionEvent($pdo, $jobSectionId, $eventData);
            }

            echo json_encode(['success' => true, 'id' => $id]);
            break;
        }
        case 'update': {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) throw new RuntimeException('Missing id');

            $eventData = [
                'event_type'     => $_POST['event_type'] ?? 'inspection',
                'scheduled_date' => $_POST['scheduled_date'] ?? '',
                'status'         => $_POST['status'] ?? 'pending',
                'notes'          => $_POST['notes'] ?? null,
            ];

            $repo->update($id, $eventData);

            if (isEmployee()) {
                $jobSectionId = (int)($existing['job_section_id'] ?? 0);
                notifyCustomerOfJobSectionEvent($pdo, $jobSectionId, $eventData);
            }

            echo json_encode(['success' => true]);
            break;
        }
        case 'delete': {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) throw new RuntimeException('Missing id');
            $repo->delete($id);
            echo json_encode(['success' => true]);
            break;
        }
        case 'set_order_status': {
            $oid    = (int)($_POST['order_id'] ?? 0);
            $status = $_POST['status'] ?? 'new';
            if ($oid <= 0) throw new RuntimeException('Missing order_id');
            $repo->setOrderStatus($oid, $status);
            echo json_encode(['success' => true]);
            break;
        }
        default:
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
    }
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
