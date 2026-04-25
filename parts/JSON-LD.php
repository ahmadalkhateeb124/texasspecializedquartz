<?php
/**
 * JSON-LD — emits Article schema for blog posts from the DB
 *           and BlogPosting list on /blog.
 */
$DOMAIN         = 'https://texasspecializedquartz.com';
$PUBLISHER_LOGO = "$DOMAIN/images/Granit-Img/logo-gg.jpg";
$PUBLISHER_NAME = setting('business_name', 'Texas Specialized Quartz & Granite');

$currentUrl = $_SERVER['REQUEST_URI'] ?? '';
$isBlogList   = str_contains($currentUrl, '/blog');
$isArticleUrl = str_contains($currentUrl, '/newsdetail');

if (!$isBlogList && !$isArticleUrl) return;

/* Fetch published posts */
$posts = [];
try {
    $posts = $pdo->query("
        SELECT id, title, slug, image, content, tags, publish_date, created_at, updated_at
        FROM blog_posts
        WHERE status = 'published'
        ORDER BY publish_date DESC, id DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {}

$articleSchema = function (array $p) use ($DOMAIN, $PUBLISHER_NAME, $PUBLISHER_LOGO) {
    $url     = "$DOMAIN/newsdetail/" . rawurlencode($p['slug']);
    $img     = $p['image'] ? "$DOMAIN/BusinessPortal/assets/blog/" . rawurlencode($p['image']) : $PUBLISHER_LOGO;
    $excerpt = trim(strip_tags($p['content']));
    if (mb_strlen($excerpt) > 300) $excerpt = mb_substr($excerpt, 0, 300) . '…';

    return [
        '@context'         => 'https://schema.org',
        '@type'            => 'BlogPosting',
        'headline'         => $p['title'],
        'image'            => [$img],
        'datePublished'    => date('c', strtotime($p['publish_date'] ?: $p['created_at'])),
        'dateModified'     => date('c', strtotime($p['updated_at'])),
        'description'      => $excerpt,
        'keywords'         => $p['tags'] ?? '',
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $url],
        'url'              => $url,
        'author'           => ['@type' => 'Organization', 'name' => $PUBLISHER_NAME, 'url' => $DOMAIN],
        'publisher'        => [
            '@type' => 'Organization',
            'name'  => $PUBLISHER_NAME,
            'logo'  => ['@type' => 'ImageObject', 'url' => $PUBLISHER_LOGO],
        ],
    ];
};

if ($isArticleUrl) {
    $slug = $_GET['slug'] ?? '';
    if (!$slug && preg_match('#/newsdetail/([^/?#]+)#', $currentUrl, $m)) {
        $slug = rawurldecode($m[1]);
    }
    foreach ($posts as $p) {
        if ($p['slug'] === $slug) {
            echo '<script type="application/ld+json">' . json_encode($articleSchema($p), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
            return;
        }
    }
} else {
    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'Blog',
        'name'     => "$PUBLISHER_NAME Blog",
        'url'      => "$DOMAIN/blog",
        'blogPost' => array_map($articleSchema, array_slice($posts, 0, 10)),
    ];
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
