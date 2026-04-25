<?php
/**
 * auth/schedule.php — CRUD endpoint for job_schedules (admin only).
 * Actions: create | update | delete | set_order_status
 */
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
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

$action = $_POST['action'] ?? '';
$repo   = new JobScheduleRepository($pdo);

try {
    switch ($action) {
        case 'create': {
            $id = $repo->create([
                'order_id'       => (int)($_POST['order_id'] ?? 0),
                'job_section_id' => (int)($_POST['job_section_id'] ?? 0),
                'event_type'     => $_POST['event_type'] ?? 'inspection',
                'scheduled_date' => $_POST['scheduled_date'] ?? '',
                'status'         => $_POST['status'] ?? 'pending',
                'notes'          => $_POST['notes'] ?? null,
            ]);
            echo json_encode(['success' => true, 'id' => $id]);
            break;
        }
        case 'update': {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) throw new RuntimeException('Missing id');
            $repo->update($id, [
                'event_type'     => $_POST['event_type'] ?? 'inspection',
                'scheduled_date' => $_POST['scheduled_date'] ?? '',
                'status'         => $_POST['status'] ?? 'pending',
                'notes'          => $_POST['notes'] ?? null,
            ]);
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
