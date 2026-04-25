<?php

/**
 * admin/sinks.php — Sinks Catalog Index
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Sinks';
$breadcrumb  = [['label' => 'Sinks']];

$dbError = null;
try {
    $sinks = (new SinkRepository($pdo))->all();
} catch (PDOException $e) {
    $sinks   = [];
    $dbError = htmlspecialchars($e->getMessage());
}

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="page-title">Sinks</h1>
                    <p class="page-desc">
                        <?= count($sinks) ?> total sink<?= count($sinks) !== 1 ? 's' : '' ?>
                    </p>
                </div>
                <a href="sinks-new" class="btn btn-primary">
                    <i class='bx bx-plus'></i> Add Sink
                </a>
            </div>

            <?php if ($dbError): ?>
                <div class="alert alert-danger mb-3">
                    <i class='bx bx-error me-2'></i><?= $dbError ?>
                </div>
            <?php endif; ?>

            <?php include __DIR__ . '/includes/partials/sinks/table.php'; ?>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/sinks.js"></script>
