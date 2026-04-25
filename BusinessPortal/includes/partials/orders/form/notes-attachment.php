<?php
/**
 * Notes + attachment upload.
 * Prefills from $order if set (existing notes + existing image preview).
 */
$o = $order ?? [];
$existingImage = $o['image'] ?? '';
$attachmentUrl = '';
if ($existingImage && file_exists(__DIR__ . '/../../../../assets/products/' . $existingImage)) {
    $attachmentUrl = '../assets/products/' . $existingImage;
}
$hasAttachment = !empty($attachmentUrl);
$ext = $existingImage ? strtolower(pathinfo($existingImage, PATHINFO_EXTENSION)) : '';
$isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
?>
<div class="card mb-4">
    <div class="card-header">
        <h6 class="card-title">
            <i class='bx bx-note text-primary'></i> Notes &amp; Attachment
        </h6>
    </div>
    <div class="card-section">
        <div class="mb-3">
            <label class="form-label">Special Instructions</label>
            <textarea name="notes" rows="3" class="form-control"
                placeholder="Any special requirements or notes…"
                maxlength="500"><?= htmlspecialchars($o['notes'] ?? '') ?></textarea>
            <div style="font-size:11px;color:var(--color-text-sub);text-align:right;margin-top:4px;">
                <span id="notesCounter">0</span>/500
            </div>
        </div>

        <label class="form-label">Attachment
            <span style="font-size:11px;color:var(--color-text-sub);">(optional)</span>
        </label>
        <div class="image-upload-zone" id="fileUploadZone" style="cursor:pointer;position:relative;">
            <div id="filePlaceholder" <?= $hasAttachment ? 'style="display:none;"' : '' ?>>
                <i class='bx bx-cloud-upload' style="font-size:36px;color:var(--color-text-sub);"></i>
                <p style="margin:8px 0 4px;font-weight:500;">Click to upload image or document</p>
                <p style="font-size:12px;color:var(--color-text-sub);">
                    JPG, PNG, WebP, PDF, Word, Excel — max 20 MB
                </p>
            </div>
            <div id="filePreview" <?= $hasAttachment ? '' : 'style="display:none;"' ?>>
                <?php if ($hasAttachment): ?>
                    <div style="position:relative;">
                        <?php if ($isImage): ?>
                            <img src="<?= htmlspecialchars($attachmentUrl) ?>"
                                style="max-height:220px;max-width:100%;border-radius:var(--radius-sm);object-fit:contain;">
                        <?php else: ?>
                            <div style="display:flex;align-items:center;gap:12px;padding:12px;
                                        background:var(--color-bg);border-radius:var(--radius-sm);">
                                <i class='bx bx-file' style="font-size:32px;color:var(--color-primary);"></i>
                                <div>
                                    <div style="font-weight:600;font-size:13px;">
                                        <a href="<?= htmlspecialchars($attachmentUrl) ?>" download
                                            style="color:inherit;text-decoration:none;">
                                            <?= htmlspecialchars($existingImage) ?>
                                        </a>
                                    </div>
                                    <div style="font-size:11px;color:var(--color-text-sub);">Attached document</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <button type="button" class="file-remove-btn" onclick="removeCurrentFile(event)"
                            style="position:absolute;top:8px;right:8px;background:#fff;border:none;
                                   border-radius:50%;width:24px;height:24px;display:flex;align-items:center;
                                   justify-content:center;cursor:pointer;box-shadow:0 2px 4px rgba(0,0,0,0.1);z-index:2;">
                            <i class='bx bx-x' style="font-size:16px;color:#666;"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <input type="file" name="image" id="fileInput"
                accept="image/*,.pdf,.doc,.docx,.xls,.xlsx"
                style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;">
            <?php if ($hasAttachment): ?>
                <input type="hidden" name="delete_image" id="deleteImageInput" value="0">
            <?php endif; ?>
        </div>
    </div>
</div>
