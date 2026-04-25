<?php
/**
 * auth/inquiry-action.php — mark read / delete an inquiry.
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

$action = $_POST['action'] ?? '';
$id     = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Missing id']);
    exit;
}

try {
    $repo = new InquiryRepository($pdo);
    switch ($action) {
        case 'mark_read':   $repo->markRead($id, true);  break;
        case 'mark_unread': $repo->markRead($id, false); break;
        case 'delete':      $repo->delete($id);          break;
        default:
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
            exit;
    }
    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
