<?php
/**
 * rss.php — RSS 2.0 feed for blog posts.
 */
require_once __DIR__ . '/inc/conn.php';

header('Content-Type: application/rss+xml; charset=utf-8');

$base      = rtrim(setting('seo_canonical_base', 'https://texasspecializedquartz.com'), '/');
$siteTitle = setting('seo_default_title', 'Texas Specialized Quartz & Granite Blog');
$siteDesc  = setting('seo_default_description', 'Latest articles on granite and quartz countertops, design trends, and kitchen inspiration.');

$posts = [];
try {
    $stmt = $pdo->query("
        SELECT id, title, slug, content, image, publish_date, updated_at
        FROM blog_posts
        WHERE status = 'published'
        ORDER BY publish_date DESC, updated_at DESC
        LIMIT 50
    ");
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {}

$lastBuild = date(DATE_RSS, $posts ? strtotime($posts[0]['updated_at']) : time());

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title><?= htmlspecialchars($siteTitle) ?></title>
        <link><?= htmlspecialchars($base) ?>/</link>
        <atom:link href="<?= htmlspecialchars($base) ?>/rss.xml" rel="self" type="application/rss+xml" />
        <description><?= htmlspecialchars($siteDesc) ?></description>
        <language>en-us</language>
        <lastBuildDate><?= $lastBuild ?></lastBuildDate>
<?php foreach ($posts as $p):
    $url     = $base . '/newsdetail/' . rawurlencode($p['slug']);
    $pubDate = date(DATE_RSS, strtotime($p['publish_date'] ?: $p['updated_at']));
    $excerpt = trim(strip_tags($p['content']));
    if (mb_strlen($excerpt) > 300) $excerpt = mb_substr($excerpt, 0, 300) . '…';
    $img = $p['image'] ? $base . '/BusinessPortal/assets/blog/' . rawurlencode($p['image']) : '';
?>
        <item>
            <title><?= htmlspecialchars($p['title']) ?></title>
            <link><?= htmlspecialchars($url) ?></link>
            <description><![CDATA[<?php if ($img): ?><img src="<?= $img ?>" alt="<?= htmlspecialchars($p['title']) ?>" /><br><?php endif; ?><?= $excerpt ?>]]></description>
            <pubDate><?= $pubDate ?></pubDate>
            <guid isPermaLink="true"><?= htmlspecialchars($url) ?></guid>
        </item>
<?php endforeach; ?>
    </channel>
</rss>
