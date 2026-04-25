<?php

/**
 * admin/blog-edit.php — Edit an existing blog post.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: blog'); exit; }

try {
    $post = (new BlogRepository($pdo))->findById($id);
} catch (PDOException $e) {
    die('Database error: ' . htmlspecialchars($e->getMessage()));
}
if (!$post) { header('Location: blog'); exit; }

$currentUser = currentUser();
$pageTitle   = 'Edit Post';
$breadcrumb  = [
    ['label' => 'Blog', 'url' => 'blog.php'],
    ['label' => 'Edit'],
];

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Edit Blog Post</h1>
                    <p class="page-subtitle"><?= htmlspecialchars($post['title']) ?></p>
                </div>
                <div class="page-actions">
                    <a href="blog" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Posts
                    </a>
                    <a href="/newsdetail/<?= htmlspecialchars($post['slug']) ?>" target="_blank"
                        class="btn btn-light">
                        <i class='bx bx-show'></i> View Live
                    </a>
                </div>
            </div>

            <form method="POST" id="blogForm" action="../auth/update_blog_post.php"
                enctype="multipart/form-data" data-mode="edit">
                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                <?php include __DIR__ . '/../includes/partials/blog/form/post-fields.php'; ?>
            </form>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/blog-form.js"></script>
