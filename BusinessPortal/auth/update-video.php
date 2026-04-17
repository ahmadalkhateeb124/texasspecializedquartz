<?php

/**
 * update-video.php
 * Handle the update of an existing video (edit name or replace file).
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../auth/videos-db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    requireAdmin();

    // Validate CSRF token
    if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        throw new Exception('Invalid CSRF token. Please reload the page.');
    }

    // Validate video ID
    if (!isset($_POST['video_id']) || empty($_POST['video_id'])) {
        throw new Exception('Missing video ID.');
    }

    $videoId = (int) $_POST['video_id'];
    $videoName = $_POST['video_name'] ?? null;

    $videoManager = new VideoManager($pdo);

    // Pass file only if one was actually uploaded
    $file = (isset($_FILES['video']) && $_FILES['video']['error'] !== UPLOAD_ERR_NO_FILE) ? $_FILES['video'] : null;
    $videoManager->updateVideo($videoId, $file, $videoName);

    echo json_encode(['success' => true, 'message' => 'Video updated successfully']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
