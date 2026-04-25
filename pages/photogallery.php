<?php
require_once __DIR__ . '/../BusinessPortal/src/bootstrap.php';
$photos = (new GalleryRepository($pdo))->all(true);
?>
<header class="page-header" data-background="images/Granit-Img/a.png">
    <div class="container ee">
        <h1>Granite &amp; Quartz Countertop Gallery</h1>
        <p class="page-subtitle">Premium granite, quartz, and marble countertop installations across Carrollton, Plano, Frisco, and North Texas.</p>
    </div>
</header>

<section class="gx" itemscope itemtype="https://schema.org/ImageGallery">
    <div class="gx-container">

        <div class="gx-head">
            <div class="gx-eyebrow">— Portfolio</div>
            <h2 class="gx-title">Our Countertop Installations</h2>
            <p class="gx-sub">Browse <?= count($photos) ?> completed kitchen, bathroom, bar, and commercial countertop projects across North Texas.</p>
        </div>

        <?php if (empty($photos)): ?>
            <p style="text-align:center;color:#5a524a;">No photos available yet.</p>
        <?php else: ?>
        <div class="gx-filter" role="tablist">
            <button class="gx-chip is-active" data-filter="all">All</button>
            <button class="gx-chip" data-filter="kitchen">Kitchens</button>
            <button class="gx-chip" data-filter="bathroom">Bathrooms</button>
            <button class="gx-chip" data-filter="commercial">Commercial</button>
            <button class="gx-chip" data-filter="granite">Granite</button>
            <button class="gx-chip" data-filter="quartz">Quartz</button>
            <button class="gx-chip" data-filter="marble">Marble</button>
        </div>

        <div class="gx-grid">
            <?php foreach ($photos as $p):
                $isAdminUploaded = str_starts_with((string)$p['image'], 'gx-');
                $src = $isAdminUploaded
                    ? 'BusinessPortal/assets/gallery/' . rawurlencode($p['image'])
                    : 'images/photo/' . rawurlencode($p['image']);
                $tags = trim(($p['category'] ?? '') . ' ' . ($p['material'] ?? ''));
            ?>
                <a class="gx-item <?= htmlspecialchars($tags) ?>"
                   href="<?= htmlspecialchars($src) ?>"
                   data-fancybox="gallery"
                   data-caption="<?= htmlspecialchars($p['caption']) ?>"
                   itemprop="associatedMedia"
                   itemscope itemtype="https://schema.org/ImageObject">
                    <img src="<?= htmlspecialchars($src) ?>"
                         alt="<?= htmlspecialchars($p['caption']) ?>"
                         loading="lazy"
                         itemprop="contentUrl">
                    <span class="gx-caption" itemprop="caption"><?= htmlspecialchars($p['caption']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
(function(){
    const chips = document.querySelectorAll('.gx-chip');
    const items = document.querySelectorAll('.gx-item');
    chips.forEach(ch => ch.addEventListener('click', () => {
        chips.forEach(c => c.classList.remove('is-active'));
        ch.classList.add('is-active');
        const f = ch.dataset.filter;
        items.forEach(it => {
            it.style.display = (f === 'all' || it.classList.contains(f)) ? '' : 'none';
        });
    }));
})();
</script>
