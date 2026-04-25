<?php

/**
 * auth/delete_sink.php — Delete a sink (admin only).
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    $response['message'] = 'Access denied.';
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $response['message'] = 'Method not allowed.';
    echo json_encode($response);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    $response['message'] = 'Invalid sink id.';
    echo json_encode($response);
    exit;
}

try {
    (new SinkRepository($pdo))->delete($id);
    $response['success'] = true;
    $response['message'] = 'Sink deleted.';
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}
echo json_encode($response);
