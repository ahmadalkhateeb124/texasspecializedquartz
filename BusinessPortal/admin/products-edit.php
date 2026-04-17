<?php

/**
 * admin/products-edit.php — Edit Product
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: products.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        header('Location: products.php');
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}

$currentUser = currentUser();
$pageTitle   = 'Edit Product';
$breadcrumb  = [
    ['label' => 'Products', 'url' => 'products.php'],
    ['label' => htmlspecialchars($product['title'])]
];

$imgPath  = '../assets/products/' . $product['image'];
$hasImage = !empty($product['image']) && file_exists(__DIR__ . '/../assets/products/' . $product['image']);

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <!-- Page header -->
            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Edit Remnants</h1>
                    <p class="page-subtitle"><?= htmlspecialchars($product['title']) ?> &middot; ID #<?= $product['id'] ?></p>
                </div>
                <div class="page-actions">
                    <a href="products.php" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Remnants
                    </a>
                </div>
            </div>

            <form method="POST" id="productForm" action="../auth/update_product.php" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                <div class="row g-4">

                    <!-- Left: image -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title"><i class='bx bx-image text-primary'></i> Remnants Image</h6>
                            </div>
                            <div class="card-section">
                                <div class="image-upload-zone" id="uploadZone">
                                    <?php if ($hasImage): ?>
                                        <img id="imagePreviewImg" src="<?= htmlspecialchars($imgPath) ?>"
                                            alt="<?= htmlspecialchars($product['title']) ?>"
                                            style="max-height:280px;width:100%;object-fit:contain;border-radius:var(--radius-sm);">
                                        <div id="uploadPlaceholder" style="display:none;"></div>
                                    <?php else: ?>
                                        <div id="uploadPlaceholder">
                                            <i class='bx bx-cloud-upload' style="font-size:36px;color:var(--color-text-sub);"></i>
                                            <p style="margin:8px 0 4px;font-weight:500;color:var(--color-text);">Click to upload image</p>
                                            <p style="font-size:12px;color:var(--color-text-sub);">JPG, PNG, WebP — max 5 MB</p>
                                        </div>
                                        <img id="imagePreviewImg" src="" alt="Preview"
                                            style="display:none;max-height:280px;width:100%;object-fit:contain;border-radius:var(--radius-sm);">
                                    <?php endif; ?>
                                    <input type="file" name="product_image" id="productImageInput"
                                        accept="image/*"
                                        style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;">
                                </div>
                                <p class="mt-2" style="font-size:12px;color:var(--color-text-sub);">
                                    Leave empty to keep the current image
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: details -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title"><i class='bx bx-detail text-primary'></i> Remnants Details</h6>
                            </div>
                            <div class="card-section">

                                <div class="mb-3">
                                    <label class="form-label">Product Title <span class="text-danger">*</span></label>
                                    <input type="text" name="product_title" class="form-control"
                                        value="<?= htmlspecialchars($product['title']) ?>" required>
                                </div>

                                <div class="row g-3">
                                    <div class="col-sm-12">
                                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                        <input type="number" name="Quantity" class="form-control"
                                            value="<?= intval($product['quantity']) ?>" min="0" required>
                                    </div>
                                  
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-sm-6">
                                        <label class="form-label">Color Name <span class="text-danger">*</span></label>
                                        <input type="text" name="ColorName" class="form-control"
                                            value="<?= htmlspecialchars($product['color_name']) ?>" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Size <span class="text-danger">*</span></label>
                                        <input type="text" name="Size" class="form-control"
                                            value="<?= htmlspecialchars($product['size']) ?>" required>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Availability</label>
                                    <select name="availability" class="form-select">
                                        <?php foreach (['Available in store', 'Reserved', 'Sold Out'] as $opt): ?>
                                            <option value="<?= $opt ?>" <?= $product['availability'] === $opt ? 'selected' : '' ?>>
                                                <?= $opt ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                           

                            </div>
                            
                        </div>
                                     <div>
                                      <div class="col-sm-6" style="opacity:0">
                                        <label class="form-label">Price (USD)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="price" class="form-control"
                                                value="<?= $product['price'] !== null ? htmlspecialchars($product['price']) : '' ?>"
                                                min="0" step="0.01">
                                        </div>
                                    </div>
                                </div>
                        <!-- Actions -->
                        <div class="d-flex gap-2 justify-content-end mt-3">
                            <a href="products.php" class="btn btn-default">Cancel</a>
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

        <script>
            (function() {
                document.getElementById('productImageInput').addEventListener('change', function() {
                    const file = this.files[0];
                    if (!file) return;
                    if (file.size > 5 * 1024 * 1024) {
                        showToast('Image must be under 5 MB.', 'error');
                        this.value = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = e => {
                        document.getElementById('uploadPlaceholder').style.display = 'none';
                        const img = document.getElementById('imagePreviewImg');
                        img.src = e.target.result;
                        img.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                });

                document.getElementById('productForm').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const btn = document.getElementById('submitBtn');
                    const spinner = document.getElementById('submitSpinner');
                    const icon = document.getElementById('submitIcon');
                    btn.disabled = true;
                    spinner.classList.remove('d-none');
                    icon.classList.add('d-none');

                    try {
                        const res = await fetch(this.action, {
                            method: 'POST',
                            body: new FormData(this)
                        });
                        const data = await res.json();
                        if (data.success) {
                            showToast(data.message || 'Product updated.', 'success');
                            setTimeout(() => window.location.href = 'products.php', 1200);
                        } else {
                            showToast(data.message || 'Could not update product.', 'error');
                            btn.disabled = false;
                            spinner.classList.add('d-none');
                            icon.classList.remove('d-none');
                        }
                    } catch {
                        showToast('Network error.', 'error');
                        btn.disabled = false;
                        spinner.classList.add('d-none');
                        icon.classList.remove('d-none');
                    }
                });
            })();
        </script>