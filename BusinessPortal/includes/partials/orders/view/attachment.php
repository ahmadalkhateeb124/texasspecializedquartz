<?php
/**
 * Expects $order (with ['image'] possibly set).
 */
if (empty($order['image'])) return;

$ext     = strtolower(pathinfo($order['image'], PATHINFO_EXTENSION));
$imgUrl  = '../assets/products/' . $order['image'];
$isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><i class='bx bx-image text-primary'></i> Attached File</h6>
    </div>
    <div class="card-section">
        <?php if ($isImage): ?>
            <img src="<?= htmlspecialchars($imgUrl) ?>"
                 style="max-height:300px;max-width:100%;border-radius:var(--radius-sm);"
                 alt="Order attachment">
            <div class="mt-3">
                <a href="<?= htmlspecialchars($imgUrl) ?>" download class="btn btn-primary btn-sm">
                    <i class='bx bx-download'></i> Download Image
                </a>
            </div>
        <?php else: ?>
            <div style="display:flex;align-items:center;gap:10px;padding:12px;
                        background:var(--color-bg);border-radius:var(--radius-sm);">
                <i class='bx bx-file' style="font-size:24px;color:var(--color-primary);"></i>
                <div>
                    <div style="font-weight:500;"><?= htmlspecialchars($order['image']) ?></div>
                    <div style="font-size:12px;color:var(--color-text-sub);"><?= strtoupper($ext) ?> file</div>
                </div>
            </div>
            <div class="mt-3">
                <a href="download?file=<?= urlencode($order['image']) ?>" class="btn btn-primary btn-sm">
                    <i class='bx bx-download'></i> Download File
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
