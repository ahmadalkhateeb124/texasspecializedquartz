<?php

/**
 * auth/update_sink.php — Update an existing sink (admin only).
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => '', 'errors' => []];

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

$id = (int)($_POST['sink_id'] ?? 0);
if ($id <= 0) {
    $response['message'] = 'Invalid sink id.';
    echo json_encode($response);
    exit;
}

$name        = trim($_POST['name']        ?? '');
$model       = trim($_POST['model']       ?? '');
$category    = in_array($_POST['category'] ?? 'kitchen', SinkRepository::CATEGORIES, true)
                   ? $_POST['category'] : 'kitchen';
$subcategory = trim($_POST['subcategory'] ?? '');
$sortOrder   = (int)($_POST['sort_order'] ?? 0);
$status      = in_array($_POST['status'] ?? 'active', ['active', 'inactive'], true)
                   ? $_POST['status'] : 'active';
$deleteImage = !empty($_POST['delete_image']) && $_POST['delete_image'] === '1';

$errors = [];
if (!$name)        $errors[] = 'Sink name is required';
if (!$subcategory) $errors[] = 'Subcategory is required';

try {
    $repo    = new SinkRepository($pdo);
    $current = $repo->findById($id);
    if (!$current) {
        $response['message'] = 'Sink not found.';
        echo json_encode($response);
        exit;
    }

    if ($errors) {
        $response['message'] = 'Validation failed';
        $response['errors']  = $errors;
        echo json_encode($response);
        exit;
    }

    $image = $current['image'] ?? '';
    if ($deleteImage) $image = '';

    if (!empty($_FILES['image']['name'])) {
        $uploader = new FileUploader(
            __DIR__ . '/../../images/sinks',
            10 * 1024 * 1024,
            ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
        );
        $uploaded = $uploader->save($_FILES['image']);
        $image    = 'images/sinks/' . $uploaded['stored_name'];
    }

    $repo->update($id, [
        'name'        => $name,
        'model'       => $model,
        'image'       => $image,
        'category'    => $category,
        'subcategory' => $subcategory,
        'sort_order'  => $sortOrder,
        'status'      => $status,
    ]);

    $response['success'] = true;
    $response['message'] = 'Sink updated successfully';
    echo json_encode($response);
} catch (RuntimeException $e) {
    $response['message'] = $e->getMessage();
    echo json_encode($response);
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
    echo json_encode($response);
}
