<?php
/**
 * customer/includes/header.php — optional breadcrumbs (topbar lives in sidebar.php).
 */
?>
<?php if (!empty($breadcrumb)): ?>
    <nav class="page-breadcrumb" aria-label="breadcrumb"
        style="padding:14px 24px 0;max-width:1400px;margin:0 auto;display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-sub);">
        <a href="index" style="color:var(--text-sub);display:inline-flex;align-items:center;">
            <i class='bx bxs-home' style="font-size:13px;"></i>
        </a>
        <?php foreach ($breadcrumb as $bc): ?>
            <i class='bx bx-chevron-right' style="font-size:13px;"></i>
            <?php if (!empty($bc['url'])): ?>
                <a href="<?= htmlspecialchars($bc['url']) ?>" style="color:var(--text-sub);">
                    <?= htmlspecialchars($bc['label']) ?>
                </a>
            <?php else: ?>
                <span style="color:var(--text);font-weight:500;">
                    <?= htmlspecialchars($bc['label']) ?>
                </span>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>
<?php endif; ?>
