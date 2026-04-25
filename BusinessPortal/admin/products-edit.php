<?php

/**
 * admin/products-edit.php — Edit Product
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../src/bootstrap.php';

requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: products'); exit; }

try {
    $product = (new ProductRepository($pdo))->find($id);
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}
if (!$product) { header('Location: products'); exit; }

$currentUser = currentUser();
$pageTitle   = 'Edit Product';
$breadcrumb  = [
    ['label' => 'Products', 'url' => 'products.php'],
    ['label' => htmlspecialchars($product['title'])],
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
                    <h1 class="page-title">Edit Remnants</h1>
                    <p class="page-subtitle">
                        <?= htmlspecialchars($product['title']) ?> &middot; ID #<?= $product['id'] ?>
                    </p>
                </div>
                <div class="page-actions">
                    <a href="products" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Remnants
                    </a>
                </div>
            </div>

            <form method="POST" id="editForm" action="../auth/update_product.php" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

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
                                <i class='bx bx-save' id="submitIcon"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <script src="../assets/js/admin/product-form.js"></script>
