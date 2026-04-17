<?php

/**
 * delete-video.php
 * Handle the deletion of an existing video entry (from database + server).
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../auth/videos-db.php';

try {
    requireAdmin();

    // Validate video ID
    if (!isset($_POST['video_id']) || empty($_POST['video_id'])) {
        throw new Exception('Missing video ID.');
    }

    $videoId = (int) $_POST['video_id'];

    $videoManager = new VideoManager($pdo);
    $videoManager->deleteVideo($videoId);

    echo json_encode(['success' => true, 'message' => 'Video deleted successfully']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
