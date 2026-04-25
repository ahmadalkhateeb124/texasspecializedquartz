<?php
/**
 * pages/blog.php — Blog listing (editorial redesign, bz-* prefix).
 */
require_once __DIR__ . '/../BusinessPortal/src/bootstrap.php';

$repo      = new BlogRepository($pdo);
$baseHref  = $base_url ?? '/';
$limit     = 9;
$activeTag = isset($_GET['tag']) ? trim((string)$_GET['tag']) : '';

/* Counts + tag chips (cheap aggregate query, runs once) */
$totalPosts = $repo->countPublished($activeTag !== '' ? $activeTag : null);
$totalPages = max(1, (int)ceil($totalPosts / $limit));
$page       = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$page       = min($page, $totalPages);
$offset     = ($page - 1) * $limit;

/* DB-paginated fetch — only the rows we render */
$rows = $repo->listPublished($limit, $offset, $activeTag !== '' ? $activeTag : null);

$tagCountsAll = $repo->tagCounts(50);
$topTags      = array_slice(array_keys($tagCountsAll), 0, 8);

$shape = function (array $row) use ($baseHref): array {
    $plain = trim(strip_tags($row['content']));
    $words = max(1, str_word_count($plain));
    $img   = $row['image']
        ? $baseHref . ltrim($row['image'], '/')
        : $baseHref . 'images/Granit-Img/default-blog.jpg';
    return [
        'title'    => $row['title'],
        'slug'     => $row['slug'],
        'id'       => (int)$row['id'],
        'image'    => $img,
        'date'     => date('M j, Y', strtotime($row['publish_date'])),
        'datetime' => date('Y-m-d', strtotime($row['publish_date'])),
        'intro'    => mb_substr($plain, 0, 170) . (mb_strlen($plain) > 170 ? '…' : ''),
        'tags'     => array_values(array_filter(array_map('trim', explode(',', (string)($row['tags'] ?? ''))))),
        'minutes'  => max(1, (int)ceil($words / 220)),
    ];
};
$currentPosts = array_map($shape, $rows);

$featured = ($page === 1 && $activeTag === '' && !empty($currentPosts)) ? $currentPosts[0] : null;
$rest     = $featured ? array_slice($currentPosts, 1) : $currentPosts;
$tagCounts = $tagCountsAll;

$buildUrl = function ($params) use ($activeTag) {
    $q = array_filter(array_merge(['tag' => $activeTag], $params), fn($v) => $v !== '' && $v !== null);
    return '?' . http_build_query($q);
};
?>

<header class="page-header" data-background="images/Granit-Img/ccccc.png">
    <div class="container ee">
        <h1>Countertop Insights — Design Trends, Care Tips &amp; Industry Excellence</h1>
        <p class="page-subtitle">Expert articles on granite, quartz, and marble countertops from Texas stone professionals.</p>
    </div>
</header>

<section class="bz" aria-label="Blog Articles">
    <div class="bz-container">

        <div class="bz-head">
            <div class="bz-eyebrow">— Journal</div>
            <h2 class="bz-title">Stories, guides &amp; expertise.</h2>
            <p class="bz-sub">
                <?= $totalPosts ?> article<?= $totalPosts !== 1 ? 's' : '' ?>
                <?= $activeTag !== '' ? 'tagged <em>' . htmlspecialchars($activeTag) . '</em>' : 'on stone countertops, edges, maintenance and trends.' ?>
            </p>
        </div>

        <?php if (!empty($topTags)): ?>
            <nav class="bz-filters" aria-label="Filter by topic">
                <a href="<?= htmlspecialchars($buildUrl(['tag' => null, 'page' => null])) ?>"
                   class="bz-chip <?= $activeTag === '' ? 'is-active' : '' ?>">All</a>
                <?php foreach ($topTags as $t): ?>
                    <a href="?tag=<?= urlencode($t) ?>"
                       class="bz-chip <?= $activeTag === $t ? 'is-active' : '' ?>">
                        <?= htmlspecialchars($t) ?>
                        <span class="bz-chip-count"><?= $tagCounts[$t] ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>

        <?php if ($featured): ?>
            <a href="<?= htmlspecialchars($baseHref) ?>newsdetail/<?= htmlspecialchars($featured['slug']) ?>" class="bz-hero">
                <div class="bz-hero-media">
                    <img src="<?= htmlspecialchars($featured['image']) ?>"
                         alt="<?= htmlspecialchars($featured['title']) ?>"
                         fetchpriority="high">
                </div>
                <div class="bz-hero-overlay"></div>
                <div class="bz-hero-body">
                    <span class="bz-hero-pill">Featured Story</span>
                    <h3><?= htmlspecialchars($featured['title']) ?></h3>
                    <p><?= htmlspecialchars($featured['intro']) ?></p>
                    <div class="bz-hero-meta">
                        <time datetime="<?= $featured['datetime'] ?>">
                            <i class="far fa-calendar"></i> <?= $featured['date'] ?>
                        </time>
                        <span class="bz-dot">·</span>
                        <span><i class="far fa-clock"></i> <?= $featured['minutes'] ?> min read</span>
                        <span class="bz-hero-cta">Read article <i class="fas fa-arrow-right"></i></span>
                    </div>
                </div>
            </a>
        <?php endif; ?>

        <?php if (!empty($rest)): ?>
            <div class="bz-grid">
                <?php foreach ($rest as $post): ?>
                    <article class="bz-card" itemscope itemtype="https://schema.org/BlogPosting">
                        <a href="<?= htmlspecialchars($baseHref) ?>newsdetail/<?= htmlspecialchars($post['slug']) ?>" class="bz-card-media">
                            <img src="<?= htmlspecialchars($post['image']) ?>"
                                 alt="<?= htmlspecialchars($post['title']) ?>"
                                 loading="lazy" itemprop="image">
                            <?php if (!empty($post['tags'])): ?>
                                <span class="bz-card-tag"><?= htmlspecialchars($post['tags'][0]) ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="bz-card-body">
                            <div class="bz-card-meta">
                                <time datetime="<?= $post['datetime'] ?>" itemprop="datePublished">
                                    <i class="far fa-calendar"></i> <?= $post['date'] ?>
                                </time>
                                <span class="bz-dot">·</span>
                                <span><i class="far fa-clock"></i> <?= $post['minutes'] ?> min</span>
                            </div>
                            <h3 itemprop="headline">
                                <a href="<?= htmlspecialchars($baseHref) ?>newsdetail/<?= htmlspecialchars($post['slug']) ?>"><?= htmlspecialchars($post['title']) ?></a>
                            </h3>
                            <p itemprop="description"><?= htmlspecialchars($post['intro']) ?></p>
                            <a href="<?= htmlspecialchars($baseHref) ?>newsdetail/<?= htmlspecialchars($post['slug']) ?>" class="bz-readmore">
                                Read article <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($totalPages > 1): ?>
            <nav class="bz-pagination" aria-label="Blog pagination">
                <?php if ($page > 1): ?>
                    <a href="<?= htmlspecialchars($buildUrl(['page' => $page - 1])) ?>" rel="prev">
                        <i class="fas fa-arrow-left"></i> Prev
                    </a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <strong aria-current="page"><?= $i ?></strong>
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($buildUrl(['page' => $i])) ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="<?= htmlspecialchars($buildUrl(['page' => $page + 1])) ?>" rel="next">
                        Next <i class="fas fa-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
            <div class="bz-empty">
                <i class="far fa-newspaper"></i>
                <p>No articles<?= $activeTag !== '' ? ' tagged "' . htmlspecialchars($activeTag) . '"' : '' ?> yet — check back soon.</p>
                <?php if ($activeTag !== ''): ?>
                    <a class="bz-readmore" href="?">Show all articles <i class="fas fa-arrow-right"></i></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
