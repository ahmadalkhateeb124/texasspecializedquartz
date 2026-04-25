<?php

/**
 * auth/add_blog_post.php — Create a new blog post (admin only).
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

$title        = trim($_POST['title']        ?? '');
$slug         = trim($_POST['slug']         ?? '');
$content      = $_POST['content']           ?? '';
$tags         = trim($_POST['tags']         ?? '');
$publishDate  = trim($_POST['publish_date'] ?? date('Y-m-d'));
$status       = in_array($_POST['status'] ?? 'published', ['published', 'draft'], true)
                    ? $_POST['status']
                    : 'published';

$errors = [];
if (!$title)   $errors[] = 'Title is required';
if (!$content) $errors[] = 'Content is required';

if (!$slug && $title) $slug = BlogRepository::makeSlug($title);

try {
    $repo = new BlogRepository($pdo);

    if ($slug && $repo->slugTaken($slug)) {
        $errors[] = 'Slug is already used by another post';
    }

    if ($errors) {
        $response['message'] = 'Validation failed';
        $response['errors']  = $errors;
        echo json_encode($response);
        exit;
    }

    // Optional image upload
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $uploader = new FileUploader(
            __DIR__ . '/../../images/blog',
            10 * 1024 * 1024, // 10MB
            ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
        );
        $uploaded = $uploader->save($_FILES['image']);
        $image    = 'images/blog/' . $uploaded['stored_name'];
    }

    $id = $repo->create([
        'title'        => $title,
        'slug'         => $slug,
        'image'        => $image,
        'content'      => $content,
        'tags'         => $tags,
        'publish_date' => $publishDate,
        'status'       => $status,
    ]);

    $response['success']  = true;
    $response['message']  = 'Blog post created successfully';
    $response['post_id']  = $id;
    echo json_encode($response);
} catch (RuntimeException $e) {
    $response['message'] = $e->getMessage();
    echo json_encode($response);
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
    echo json_encode($response);
}
