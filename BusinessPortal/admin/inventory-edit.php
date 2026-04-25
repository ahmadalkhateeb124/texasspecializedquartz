<?php

/**
 * admin/inventory-edit.php — Edit an inventory slab.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: inventory'); exit; }

try {
    $slab = (new InventorySlabRepository($pdo))->findById($id);
} catch (PDOException $e) {
    die('Database error: ' . htmlspecialchars($e->getMessage()));
}
if (!$slab) { header('Location: inventory'); exit; }

$currentUser = currentUser();
$pageTitle   = 'Edit Slab';
$breadcrumb  = [
    ['label' => 'Inventory', 'url' => 'inventory.php'],
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
                    <h1 class="page-title">Edit Slab</h1>
                    <p class="page-subtitle"><?= htmlspecialchars($slab['name']) ?></p>
                </div>
                <div class="page-actions">
                    <a href="inventory" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Inventory
                    </a>
                </div>
            </div>

            <form method="POST" id="slabForm" action="../auth/update_inventory_slab.php"
                enctype="multipart/form-data" data-mode="edit">
                <input type="hidden" name="slab_id" value="<?= $slab['id'] ?>">
                <?php include __DIR__ . '/../includes/partials/inventory/form/fields.php'; ?>
            </form>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/inventory-form.js"></script>
