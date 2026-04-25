<?php
/**
 * Shared blog post form — used by blog-new.php and blog-edit.php.
 * Prefills from $post if set.
 */
$p = $post ?? [];
$v = fn(string $k): string => htmlspecialchars($p[$k] ?? '');
?>
<div class="row g-4">
    <div class="col-lg-8">

        <!-- Main fields -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title"><i class='bx bx-news text-primary'></i> Post Details</h6>
            </div>
            <div class="card-section">
                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control"
                        placeholder="Post title" maxlength="255" required value="<?= $v('title') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">URL Slug</label>
                    <div class="input-group">
                        <span class="input-group-text">/newsdetail/</span>
                        <input type="text" name="slug" class="form-control" placeholder="auto-generated"
                            value="<?= $v('slug') ?>">
                    </div>
                    <div style="font-size:11px;color:var(--color-text-sub);margin-top:4px;">
                        Leave empty to auto-generate from the title.
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Content <span class="text-danger">*</span></label>
                    <textarea name="content" id="contentTextarea" class="form-control" rows="18"
                        placeholder="HTML allowed — <p>, <h2>, <ul>, <strong>, etc." required><?= $v('content') ?></textarea>
                    <div style="font-size:11px;color:var(--color-text-sub);margin-top:4px;">
                        HTML is rendered as-is on the article page.
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Tags / Keywords</label>
                    <input type="text" name="tags" class="form-control"
                        placeholder="comma, separated, keywords" value="<?= $v('tags') ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Publishing -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title"><i class='bx bx-calendar-check text-primary'></i> Publishing</h6>
            </div>
            <div class="card-section">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="published" <?= ($p['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>
                            Published
                        </option>
                        <option value="draft" <?= ($p['status'] ?? '') === 'draft' ? 'selected' : '' ?>>
                            Draft
                        </option>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label">Publish Date</label>
                    <input type="date" name="publish_date" class="form-control"
                        value="<?= !empty($p['publish_date']) ? date('Y-m-d', strtotime($p['publish_date'])) : date('Y-m-d') ?>">
                </div>
            </div>
        </div>

        <!-- Cover image -->
        <?php
        $hasImage = !empty($p['image']) && file_exists(
            realpath(__DIR__ . '/../../../..') . '/../' . ltrim($p['image'], '/')
        );
        ?>
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title"><i class='bx bx-image text-primary'></i> Cover Image</h6>
            </div>
            <div class="card-section">
                <?php if ($hasImage): ?>
                    <img id="coverPreview" src="/texasspecializedquartz/<?= htmlspecialchars(ltrim($p['image'], '/')) ?>"
                        style="width:100%;max-height:200px;object-fit:cover;border-radius:6px;margin-bottom:10px;">
                <?php else: ?>
                    <img id="coverPreview" src="" style="display:none;width:100%;max-height:200px;object-fit:cover;border-radius:6px;margin-bottom:10px;">
                <?php endif; ?>

                <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                <div style="font-size:11px;color:var(--color-text-sub);margin-top:4px;">
                    JPG, PNG, WebP — max 10MB
                </div>
                <?php if ($hasImage): ?>
                    <div class="form-check mt-2">
                        <input type="checkbox" name="delete_image" value="1" id="deleteImg" class="form-check-input">
                        <label for="deleteImg" class="form-check-label" style="font-size:12px;">
                            Remove current image
                        </label>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Submit -->
        <div class="card">
            <div class="card-section">
                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                    <span id="submitSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                    <i class='bx bx-save' id="submitIcon"></i>
                    <span id="submitLabel"><?= !empty($p) ? 'Save Changes' : 'Publish Post' ?></span>
                </button>
                <a href="blog" class="btn btn-default w-100 mt-2">Cancel</a>
            </div>
        </div>
    </div>
</div>
