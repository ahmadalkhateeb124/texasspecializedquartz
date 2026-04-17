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

            <!-- Page header -->
            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Add Remnants</h1>
                    <p class="page-subtitle">Create a new granite slab or remnant listing</p>
                </div>
                <div class="page-actions">
                    <a href="products.php" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> Back to Remnants
                    </a>
                </div>
            </div>

            <form method="POST" id="productForm" action="../auth/add_product.php" enctype="multipart/form-data">

                <div class="row g-4">

                    <!-- Left column: image -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title"><i class='bx bx-image text-primary'></i> Remnants Image</h6>
                            </div>
                            <div class="card-section">
                                <div class="image-upload-zone" id="uploadZone">
                                    <div id="uploadPlaceholder">
                                        <i class='bx bx-cloud-upload' style="font-size:36px;color:var(--color-text-sub);"></i>
                                        <p style="margin:8px 0 4px;font-weight:500;color:var(--color-text);">Click to upload image</p>
                                        <p style="font-size:12px;color:var(--color-text-sub);">JPG, PNG, WebP — max 5 MB</p>
                                    </div>
                                    <img id="imagePreviewImg" src="" alt="Preview"
                                        style="display:none;max-height:280px;width:100%;object-fit:contain;border-radius:var(--radius-sm);">
                                    <input type="file" name="product_image" id="productImageInput"
                                        accept="image/*" required
                                        style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;">
                                </div>
                                <p class="mt-2" style="font-size:12px;color:var(--color-text-sub);">
                                    Recommended: 800×800px square crop
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right column: details -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title"><i class='bx bx-detail text-primary'></i> Remnants Details</h6>
                            </div>
                            <div class="card-section">

                                <!-- Title -->
                                <div class="mb-3">
                                    <label class="form-label">Product Title <span class="text-danger">*</span></label>
                                    <input type="text" name="product_title" class="form-control"
                                        placeholder="e.g. Calacatta White 3cm Slab" required>
                                </div>

                                <div class="row g-3">
                                    <!-- Qty -->
                                    <div class="col-sm-12">
                                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                        <input type="number" name="Quantity" class="form-control"
                                            placeholder="0" min="0" required>
                                    </div>
                              
                                </div>

                                <div class="row g-3 mt-1">
                                    <!-- Color -->
                                    <div class="col-sm-6">
                                        <label class="form-label">Color Name <span class="text-danger">*</span></label>
                                        <input type="text" name="ColorName" class="form-control"
                                            placeholder="e.g. Calacatta White" required>
                                    </div>
                                    <!-- Size -->
                                    <div class="col-sm-6">
                                        <label class="form-label">Size <span class="text-danger">*</span></label>
                                        <input type="text" name="Size" class="form-control"
                                            placeholder="e.g. 126 x 63" required>
                                    </div>
                                </div>

                                <!-- Availability -->
                                <div class="mt-3">
                                    <label class="form-label">Availability</label>
                                    <select name="availability" class="form-select">
                                        <option value="Available in store">Available in store</option>
                                        <option value="Reserved">Reserved</option>
                                        <option value="Sold Out">Sold Out</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2 justify-content-end mt-3">
                            <a href="products.php" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span id="submitSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                                <i class='bx bx-plus' id="submitIcon"></i> Add Remnants
                            </button>
                        </div>
                        <div>
                                  <!-- Price -->
                                    <div class="col-sm-6" style="opacity : 0;">
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

        <script>
            (function() {
                /* Image preview */
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

                /* Form submit */
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
                            showToast(data.message || 'Product created.', 'success');
                            setTimeout(() => window.location.href = 'products.php', 1200);
                        } else {
                            if (data.errors) {
                                const errors = Object.values(data.errors).join('. ');
                                showToast(errors || data.message || 'Could not create product.', 'error');
                            } else {
                                showToast(data.message || 'Could not create product.', 'error');
                            }
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