<?php
/**
 * pages/404.php — Friendly not-found page (sets real HTTP 404 status).
 */
if (!headers_sent()) {
    http_response_code(404);
    /* Don't index 404s — Google should ignore them. */
    header('X-Robots-Tag: noindex, follow', true);
}

$base = $base_url ?? '/';

/* Pull a few recent blog posts for "you may like instead" suggestions */
$suggestedPosts = [];
try {
    if (isset($pdo)) {
        $stmt = $pdo->query(
            "SELECT title, slug, image, publish_date
             FROM blog_posts
             WHERE status = 'published'
             ORDER BY publish_date DESC, id DESC
             LIMIT 3"
        );
        $suggestedPosts = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
} catch (Throwable $e) { /* silent */ }

/* Quick links most likely to help a lost visitor */
$quickLinks = [
    ['Countertops',      'Countertops',       'Granite, quartz, marble & quartzite'],
    ['Inventory',        'Inventory',         'Browse our in-stock slabs'],
    ['Remnants',         'Remnants',          'Discounted leftover pieces'],
    ['Sinks',            'Sinks',             'Stainless steel, composite & fireclay'],
    ['Visualizer',       'kitchenVisualizer', 'See how a stone looks in your kitchen'],
    ['Get a Free Quote', 'contact',           'Tell us about your project'],
];
?>

<header class="page-header" data-background="images/Granit-Img/ccccc.png">
    <div class="container ee">
        <h1>Page Not Found</h1>
        <p class="page-subtitle">We couldn't find the page you were looking for — but we can point you somewhere useful.</p>
    </div>
</header>

<section class="nf" aria-label="Page not found">
    <div class="nf-container">

        <div class="nf-hero">
            <div class="nf-code">404</div>
            <h2 class="nf-title">This page took the day off.</h2>
            <p class="nf-sub">
                The link may be outdated, mistyped, or the page might have moved.
                Try searching, or pick one of the popular pages below.
            </p>

            <form class="nf-search" action="<?= htmlspecialchars($base) ?>blog" method="GET" role="search">
                <i class="fas fa-search nf-search-icon"></i>
                <input type="search" name="tag" autocomplete="off"
                       placeholder="Search articles — e.g. granite, quartz, kitchen…"
                       aria-label="Search the site">
                <button type="submit">Search</button>
            </form>

            <div class="nf-cta-row">
                <a class="nf-btn nf-btn-primary" href="<?= htmlspecialchars($base) ?>Home">
                    <i class="fas fa-home"></i> Go home
                </a>
                <a class="nf-btn nf-btn-ghost" href="<?= htmlspecialchars($base) ?>contact">
                    <i class="fas fa-phone"></i> Talk to us
                </a>
            </div>
        </div>

        <div class="nf-grid-section">
            <h3 class="nf-section-title">Popular pages</h3>
            <ul class="nf-link-list">
                <?php foreach ($quickLinks as [$label, $slug, $desc]): ?>
                    <li>
                        <a href="<?= htmlspecialchars($base . $slug) ?>">
                            <span class="nf-link-arrow"><i class="fas fa-arrow-right"></i></span>
                            <span class="nf-link-body">
                                <strong><?= htmlspecialchars($label) ?></strong>
                                <small><?= htmlspecialchars($desc) ?></small>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if (!empty($suggestedPosts)): ?>
            <div class="nf-articles">
                <h3 class="nf-section-title">Latest from the journal</h3>
                <div class="nf-articles-grid">
                    <?php foreach ($suggestedPosts as $p):
                        $img = !empty($p['image'])
                            ? $base . ltrim($p['image'], '/')
                            : $base . 'images/Granit-Img/default-blog.jpg';
                    ?>
                        <a class="nf-article" href="<?= htmlspecialchars($base . 'newsdetail/' . rawurlencode($p['slug'])) ?>">
                            <div class="nf-article-media">
                                <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy">
                            </div>
                            <div class="nf-article-body">
                                <time><?= date('M j, Y', strtotime($p['publish_date'])) ?></time>
                                <h4><?= htmlspecialchars($p['title']) ?></h4>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="nf-help">
            <p>
                Still stuck? Call us at
                <a href="tel:+14698140555"><strong>(469) 814-0555</strong></a>
                or email
                <a href="mailto:Cs@TexasSpecializedQuartz.com"><strong>Cs@TexasSpecializedQuartz.com</strong></a>
                — we'll point you in the right direction.
            </p>
        </div>

    </div>
</section>
