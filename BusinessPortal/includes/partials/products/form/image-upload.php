<?php
/** Expects optional $product for prefill (existing image). */
$p = $product ?? [];
$existing = $p['image'] ?? '';
$hasImage = $existing && file_exists(__DIR__ . '/../../../../assets/products/' . $existing);
$imgSrc   = $hasImage ? '../assets/products/' . $existing : '';
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-image text-primary'></i> Remnants Image</h6>
    </div>
    <div class="card-section">
        <div class="image-upload-zone" id="uploadZone">
            <div id="uploadPlaceholder" <?= $hasImage ? 'style="display:none;"' : '' ?>>
                <i class='bx bx-cloud-upload' style="font-size:36px;color:var(--color-text-sub);"></i>
                <p style="margin:8px 0 4px;font-weight:500;color:var(--color-text);">Click to upload image</p>
                <p style="font-size:12px;color:var(--color-text-sub);">JPG, PNG, WebP — max 5 MB</p>
            </div>
            <img id="imagePreviewImg" src="<?= htmlspecialchars($imgSrc) ?>" alt="Preview"
                style="<?= $hasImage ? '' : 'display:none;' ?>max-height:280px;width:100%;object-fit:contain;border-radius:var(--radius-sm);">
            <input type="file" name="product_image" id="productImageInput"
                accept="image/*" <?= $hasImage ? '' : 'required' ?>
                style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;">
        </div>
        <p class="mt-2" style="font-size:12px;color:var(--color-text-sub);">
            Recommended: 800×800px square crop
        </p>
    </div>
</div>
