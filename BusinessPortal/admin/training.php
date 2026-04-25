<?php

/**
 * admin/training.php — Video Training Management
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../auth/videos-db.php';
require_once __DIR__ . '/includes/partials/training/_helpers.php';

requireAdmin();
csrfToken();

$currentUser = currentUser();
$pageTitle   = 'Video Training';
$breadcrumb  = [['label' => 'Video Training']];

$dbError = null;
try {
    $stmt   = $pdo->query("SELECT * FROM videos ORDER BY created_at DESC");
    $videos = $stmt->fetchAll();
} catch (PDOException $e) {
    $videos  = [];
    $dbError = htmlspecialchars($e->getMessage());
}
$videoCount   = count($videos);
$videoManager = new VideoManager($pdo);

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Training Videos</h1>
                    <p class="page-subtitle">
                        <?= $videoCount ?> video<?= $videoCount !== 1 ? 's' : '' ?> in the library
                    </p>
                </div>
                <div class="page-actions">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadVideoModal">
                        <i class='bx bx-plus'></i> Upload Video
                    </button>
                </div>
            </div>

            <?php if ($dbError): ?>
                <div class="alert alert-danger mb-3">
                    <i class='bx bx-error me-2'></i><?= $dbError ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title"><i class='bx bx-play-circle'></i> All Videos</h6>
                    <div class="pl-picker-search" style="width:240px;padding:0;border:0;">
                        <i class='bx bx-search' style="left:10px;"></i>
                        <input type="text" id="videoSearch" placeholder="Search videos…">
                    </div>
                </div>

                <?php if ($videoCount > 0): ?>
                    <?php include __DIR__ . '/includes/partials/training/grid-view.php'; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class='bx bx-video'></i></div>
                        <p class="empty-state-title">No videos uploaded yet</p>
                        <p class="empty-state-desc">Upload your first training video to get started.</p>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadVideoModal">
                            <i class='bx bx-plus'></i> Upload Video
                        </button>
                    </div>
                <?php endif; ?>
            </div>

        </main>

        <?php include __DIR__ . '/includes/partials/training/modals.php'; ?>
        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/training.js"></script>
