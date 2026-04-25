<?php

/**
 * customer/training.php — Video Training for Customers
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../auth/videos-db.php';

requireCustomer('../auth-login-minimal.php');

$currentUser = currentUser();
$pageTitle   = 'Video Training';
$breadcrumb  = [['label' => 'Video Training']];
$extraCss    = '';

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

            <div class="page-header d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="page-title"><i class='bx bx-video me-2'></i>Video Training</h1>
                    <p class="page-desc">Learn from our educational video library</p>
                </div>
            </div>

            <?php if ($dbError): ?>
                <div class="alert alert-danger mb-3">
                    <i class='bx bx-error me-2'></i><?= $dbError ?>
                </div>
            <?php endif; ?>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card text-center" style="padding:12px 10px;">
                        <div style="font-size:20px;font-weight:800;color:var(--text);"><?= $videoCount ?></div>
                        <div style="font-size:11px;color:var(--muted);font-weight:500;margin-top:2px;">
                            Available Videos
                        </div>
                    </div>
                </div>
            </div>

            <?php include __DIR__ . '/includes/partials/training/video-grid.php'; ?>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/customer/training.js"></script>
