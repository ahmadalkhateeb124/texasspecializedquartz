<?php

/**
 * admin/blog-new.php — Create a new blog post.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'New Post';
$breadcrumb  = [['label' => 'Blog', 'url' => 'blog.php'], ['label' => 'New Post']];

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">New Blog Post</h1>
                    <p class="page-subtitle">Write and publish an article.</p>
                </div>
                <div class="page-actions">
                    <a href="blog" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Posts
                    </a>
                </div>
            </div>

            <form method="POST" id="blogForm" action="../auth/add_blog_post.php"
                enctype="multipart/form-data" data-mode="new">
                <?php include __DIR__ . '/../includes/partials/blog/form/post-fields.php'; ?>
            </form>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/blog-form.js"></script>
