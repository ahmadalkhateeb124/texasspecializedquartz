<?php
/**
 * auth/faq-action.php — create / update / delete FAQ items.
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
$repo   = new FaqRepository($pdo);

try {
    switch ($action) {
        case 'create': {
            $id = $repo->create([
                'question'   => trim($_POST['question'] ?? ''),
                'answer'     => trim($_POST['answer'] ?? ''),
                'category'   => trim($_POST['category'] ?? '') ?: null,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'status'     => $_POST['status'] ?? 'published',
            ]);
            echo json_encode(['success' => true, 'id' => $id]);
            break;
        }
        case 'update': {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) throw new RuntimeException('Missing id');
            $repo->update($id, [
                'question'   => trim($_POST['question'] ?? ''),
                'answer'     => trim($_POST['answer'] ?? ''),
                'category'   => trim($_POST['category'] ?? '') ?: null,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'status'     => $_POST['status'] ?? 'published',
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
        default:
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
    }
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
