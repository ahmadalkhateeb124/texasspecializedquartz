<?php

/**
 * admin/inventory-new.php — Add a new inventory slab.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Add Slab';
$breadcrumb  = [['label' => 'Inventory', 'url' => 'inventory.php'], ['label' => 'Add']];

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Add Inventory Slab</h1>
                    <p class="page-subtitle">Add a new slab to the natural stone catalogue.</p>
                </div>
                <div class="page-actions">
                    <a href="inventory" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Inventory
                    </a>
                </div>
            </div>

            <form method="POST" id="slabForm" action="../auth/add_inventory_slab.php"
                enctype="multipart/form-data" data-mode="new">
                <?php include __DIR__ . '/../includes/partials/inventory/form/fields.php'; ?>
            </form>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/inventory-form.js"></script>
