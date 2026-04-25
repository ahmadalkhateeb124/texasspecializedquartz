<?php

/**
 * admin/products-new.php — Add New Product
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$currentUser = currentUser();
$pageTitle   = 'Add Product';
$breadcrumb  = [['label' => 'Products', 'url' => 'products.php'], ['label' => 'Add Product']];

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Add Remnants</h1>
                    <p class="page-subtitle">Create a new granite slab or remnant listing</p>
                </div>
                <div class="page-actions">
                    <a href="products" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Remnants
                    </a>
                </div>
            </div>

            <form method="POST" id="productForm" action="../auth/add_product.php" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <?php include __DIR__ . '/../includes/partials/products/form/image-upload.php'; ?>
                    </div>

                    <div class="col-lg-8">
                        <?php include __DIR__ . '/../includes/partials/products/form/details.php'; ?>

                        <div class="d-flex gap-2 justify-content-end mt-3">
                            <a href="products" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span id="submitSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                                <i class='bx bx-plus' id="submitIcon"></i> Add Remnants
                            </button>
                        </div>

                        <div>
                            <div class="col-sm-6" style="opacity:0;">
                                <label class="form-label">Price (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input value="12" type="number" name="price" class="form-control"
                                        placeholder="0.00" min="0" step="0.01">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/product-form.js"></script>
