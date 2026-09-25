<?php
/**
 * Article body — editorial layout (nd-* prefix).
 * Expects: $post, $imgPath, $readingMinutes, $tagsList, $canonicalUrl, $base_url
 */
$baseHref = $base_url ?? '/';
$heroImg  = $post['image']
    ? $baseHref . ltrim($post['image'], '/')
    : $baseHref . 'images/Granit-Img/logo-gg.jpg';
$articleImg = $heroImg;
?>

<header class="nd-hero" style="background-image: url('<?= htmlspecialchars($heroImg) ?>');">
    <div class="nd-hero-overlay"></div>
    <div class="nd-hero-inner">
        <nav class="nd-crumbs" aria-label="breadcrumb">
            <a href="<?= htmlspecialchars($baseHref) ?>">Home</a>
            <span class="nd-sep">›</span>
            <a href="<?= htmlspecialchars($baseHref) ?>blog">Journal</a>
            <span class="nd-sep">›</span>
            <span class="nd-current"><?= htmlspecialchars($post['title']) ?></span>
        </nav>

        <?php if (!empty($tagsList)): ?>
            <div class="nd-hero-pill"><?= htmlspecialchars($tagsList[0]) ?></div>
        <?php endif; ?>

        <h1 class="nd-hero-title"><?= htmlspecialchars($post['title']) ?></h1>

        <div class="nd-hero-meta">
            <span><i class="far fa-calendar"></i> <?= date("F j, Y", strtotime($post['date'])) ?></span>
            <span class="nd-dot">·</span>
            <span><i class="far fa-clock"></i> <?= $readingMinutes ?> min read</span>
            <span class="nd-dot">·</span>
            <span><i class="far fa-building"></i> Texas Specialized Quartz &amp; Granite</span>
        </div>
    </div>
</header>

<section class="nd-article">
    <div class="nd-shell">

        <article class="nd-content" itemscope itemtype="https://schema.org/Article">
            <meta itemprop="datePublished" content="<?= date('c', strtotime($post['date'])) ?>">
            <meta itemprop="headline" content="<?= htmlspecialchars($post['title']) ?>">

            <?php if (!empty($articleImg)): ?>
                <figure class="nd-figure">
                    <img src="<?= htmlspecialchars($articleImg) ?>"
                         alt="<?= htmlspecialchars($post['title']) ?>"
                         width="1200" height="630"
                         loading="eager" itemprop="image">
                </figure>
            <?php endif; ?>

            <div class="nd-prose" itemprop="articleBody">
                <?= $post['content'] ?>
            </div>

            <?php if (!empty($tagsList)): ?>
                <div class="nd-tags">
                    <span class="nd-tags-label"><i class="fas fa-tag"></i> Topics</span>
                    <?php foreach ($tagsList as $word): ?>
                        <a class="nd-tag" href="<?= htmlspecialchars($baseHref) ?>blog?tag=<?= urlencode($word) ?>"><?= htmlspecialchars($word) ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="nd-share" aria-label="Share this article">
                <span class="nd-share-label"><i class="fas fa-share-alt"></i> Share</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($canonicalUrl) ?>"
                   target="_blank" rel="noopener noreferrer"
                   class="nd-share-btn nd-fb" aria-label="Share on Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
           
             
                <a href="https://wa.me/?text=<?= urlencode($post['title'] . ' — ' . $canonicalUrl) ?>"
                   target="_blank" rel="noopener noreferrer"
                   class="nd-share-btn nd-wa" aria-label="Share on WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <button type="button" class="nd-share-btn nd-copy" aria-label="Copy link"
                        data-link="<?= htmlspecialchars($canonicalUrl) ?>"
                        onclick="navigator.clipboard.writeText(this.dataset.link);this.classList.add('is-copied');setTimeout(()=>this.classList.remove('is-copied'),1500);">
                    <i class="fas fa-link"></i>
                    <span class="nd-copy-toast">Link copied</span>
                </button>
            </div>
        </article>

        <aside class="nd-cta">
            <div class="nd-cta-eyebrow">Free in-home estimate</div>
            <h3>Planning a countertop project?</h3>
            <p>Tell us about your kitchen, bath, or commercial space and our team will follow up with material ideas, pricing and a timeline.</p>
            <div class="nd-cta-info">
                <a href="tel:+14698140555"><i class="fas fa-phone"></i> (469) 814-0555</a>
                <a href="mailto:Cs@TexasSpecializedQuartz.com"><i class="fas fa-envelope"></i> Cs@TexasSpecializedQuartz.com</a>
                <span><i class="fas fa-map-marker-alt"></i>
2943 Ladybird Ln, Dallas, TX 75220, United States</span>
            </div>
            <a href="<?= htmlspecialchars($baseHref) ?>contact" class="nd-cta-btn">Request a free quote <i class="fas fa-arrow-right"></i></a>
        </aside>

    </div>
</section>
