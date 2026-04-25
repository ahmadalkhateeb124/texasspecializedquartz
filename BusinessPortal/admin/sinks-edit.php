<?php

/**
 * admin/sinks-edit.php — Edit an existing sink.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: sinks'); exit; }

try {
    $sink = (new SinkRepository($pdo))->findById($id);
} catch (PDOException $e) {
    die('Database error: ' . htmlspecialchars($e->getMessage()));
}
if (!$sink) { header('Location: sinks'); exit; }

$currentUser = currentUser();
$pageTitle   = 'Edit Sink';
$breadcrumb  = [
    ['label' => 'Sinks', 'url' => 'sinks.php'],
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
                    <h1 class="page-title">Edit Sink</h1>
                    <p class="page-subtitle"><?= htmlspecialchars($sink['name']) ?></p>
                </div>
                <div class="page-actions">
                    <a href="sinks" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Sinks
                    </a>
                </div>
            </div>

            <form method="POST" id="sinkForm" action="../auth/update_sink.php"
                enctype="multipart/form-data" data-mode="edit">
                <input type="hidden" name="sink_id" value="<?= $sink['id'] ?>">
                <?php include __DIR__ . '/../includes/partials/sinks/form/fields.php'; ?>
            </form>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/sink-form.js"></script>
