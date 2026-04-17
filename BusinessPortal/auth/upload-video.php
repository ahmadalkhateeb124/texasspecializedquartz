<?php

/**
 * upload-video.php
 * Handle the upload of a new video file.
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../auth/videos-db.php';

try {
    requireAdmin();

    // Validate CSRF token
    if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        throw new Exception('Invalid CSRF token. Please reload the page.');
    }

    // Ensure file and video name exist
    if (!isset($_FILES['video'])) {
        throw new Exception('No video file received in upload request.');
    }

    if (!isset($_POST['video_name']) || empty(trim($_POST['video_name']))) {
        throw new Exception('Video name is required.');
    }

    // Upload video using VideoManager class
    $videoManager = new VideoManager($pdo);
    $videoId = $videoManager->uploadVideo($_FILES['video'], trim($_POST['video_name']));

    echo json_encode(['success' => true, 'video_id' => $videoId]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
