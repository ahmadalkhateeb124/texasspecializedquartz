<?php

/**
 * admin/sinks-new.php — Add a new sink.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Add Sink';
$breadcrumb  = [['label' => 'Sinks', 'url' => 'sinks.php'], ['label' => 'Add']];

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Add Sink</h1>
                    <p class="page-subtitle">Add a new sink to the catalog.</p>
                </div>
                <div class="page-actions">
                    <a href="sinks" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Sinks
                    </a>
                </div>
            </div>

            <form method="POST" id="sinkForm" action="../auth/add_sink.php"
                enctype="multipart/form-data" data-mode="new">
                <?php include __DIR__ . '/../includes/partials/sinks/form/fields.php'; ?>
            </form>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/sink-form.js"></script>
