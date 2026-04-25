<?php

/**
 * auth/update_blog_post.php — Update an existing blog post (admin only).
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

$id = (int)($_POST['post_id'] ?? 0);
if ($id <= 0) {
    $response['message'] = 'Invalid post id.';
    echo json_encode($response);
    exit;
}

$title       = trim($_POST['title']        ?? '');
$slug        = trim($_POST['slug']         ?? '');
$content     = $_POST['content']           ?? '';
$tags        = trim($_POST['tags']         ?? '');
$publishDate = trim($_POST['publish_date'] ?? date('Y-m-d'));
$status      = in_array($_POST['status'] ?? 'published', ['published', 'draft'], true)
                   ? $_POST['status']
                   : 'published';
$deleteImage = !empty($_POST['delete_image']) && $_POST['delete_image'] === '1';

$errors = [];
if (!$title)   $errors[] = 'Title is required';
if (!$content) $errors[] = 'Content is required';
if (!$slug && $title) $slug = BlogRepository::makeSlug($title);

try {
    $repo    = new BlogRepository($pdo);
    $current = $repo->findById($id);
    if (!$current) {
        $response['message'] = 'Post not found.';
        echo json_encode($response);
        exit;
    }

    if ($slug && $repo->slugTaken($slug, $id)) {
        $errors[] = 'Slug is already used by another post';
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
            __DIR__ . '/../../images/blog',
            10 * 1024 * 1024,
            ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
        );
        $uploaded = $uploader->save($_FILES['image']);
        $image    = 'images/blog/' . $uploaded['stored_name'];
    }

    $repo->update($id, [
        'title'        => $title,
        'slug'         => $slug,
        'image'        => $image,
        'content'      => $content,
        'tags'         => $tags,
        'publish_date' => $publishDate,
        'status'       => $status,
    ]);

    $response['success'] = true;
    $response['message'] = 'Blog post updated successfully';
    echo json_encode($response);
} catch (RuntimeException $e) {
    $response['message'] = $e->getMessage();
    echo json_encode($response);
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
    echo json_encode($response);
}
