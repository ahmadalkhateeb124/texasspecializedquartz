<?php
/**
 * sitemap.php — Dynamic XML sitemap.
 * Generates: core pages + all published blog posts.
 */
require_once __DIR__ . '/inc/conn.php';

header('Content-Type: application/xml; charset=utf-8');

$base = rtrim(setting('seo_canonical_base', 'https://texasspecializedquartz.com'), '/');

$staticPages = [
    ['',                     'daily',   '1.0'],
    ['KitchenCountertops',   'weekly',  '0.95'],
    ['Services',             'monthly', '0.95'],
    ['InstallationServices', 'monthly', '0.90'],
    ['contact',              'monthly', '0.90'],
    ['about',                'monthly', '0.85'],
    ['faq',                  'monthly', '0.80'],
    ['CountertopMaterials',  'monthly', '0.85'],
    ['Countertops',          'weekly',  '0.90'],
    ['EdgeType',             'monthly', '0.75'],
    ['EdgeVisualizer',       'monthly', '0.75'],
    ['Inventory',            'weekly',  '0.85'],
    ['Remnants',             'weekly',  '0.85'],
    ['QuartzSlabs',          'weekly',  '0.85'],
    ['Sinks',                'weekly',  '0.80'],
    ['RoomDesigner',         'monthly', '0.70'],
    ['kitchenVisualizer',    'monthly', '0.70'],
    ['certificates',         'monthly', '0.70'],
    ['offers',               'weekly',  '0.80'],
    ['photogallery',         'weekly',  '0.75'],
    ['blog',                 'daily',   '0.85'],
    ['service-areas',        'weekly',  '0.85'],
];

$today = date('Y-m-d');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

/* Helper — attach images to a URL (Google image sitemap extension) */
$imgSitemap = function(array $images): string {
    $out = '';
    foreach ($images as [$url, $title]) {
        $out .= "    <image:image>\n";
        $out .= "      <image:loc>" . htmlspecialchars($url) . "</image:loc>\n";
        if ($title) $out .= "      <image:title>" . htmlspecialchars($title) . "</image:title>\n";
        $out .= "    </image:image>\n";
    }
    return $out;
};

/* Gather images for gallery-style pages */
$inventoryImages = $sinkImages = $productImages = [];
try {
    $stmt = $pdo->query("SELECT name, image FROM inventory_slabs WHERE status='active' AND image != '' ORDER BY sort_order, id");
    foreach ($stmt as $row) {
        $inventoryImages[] = ["$base/BusinessPortal/assets/inventory/" . rawurlencode($row['image']), $row['name']];
    }
} catch (PDOException $e) {}
try {
    $stmt = $pdo->query("SELECT name, image FROM sinks WHERE status='active' AND image != '' ORDER BY sort_order, id");
    foreach ($stmt as $row) {
        $sinkImages[] = ["$base/BusinessPortal/assets/sinks/" . rawurlencode($row['image']), $row['name']];
    }
} catch (PDOException $e) {}
try {
    $stmt = $pdo->query("SELECT title, image FROM products WHERE availability != 'Sold Out' AND image IS NOT NULL AND image != '' ORDER BY id DESC");
    foreach ($stmt as $row) {
        $productImages[] = ["$base/BusinessPortal/assets/products/" . rawurlencode($row['image']), $row['title']];
    }
} catch (PDOException $e) {}

$pageImages = [
    'Inventory' => $inventoryImages,
    'Sinks'     => $sinkImages,
    'Remnants'  => $productImages,
];

/* Static pages */
foreach ($staticPages as [$path, $freq, $priority]) {
    $loc = $path === '' ? $base . '/' : "$base/$path";
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
    echo "    <lastmod>$today</lastmod>\n";
    echo "    <changefreq>$freq</changefreq>\n";
    echo "    <priority>$priority</priority>\n";
    if (!empty($pageImages[$path])) {
        echo $imgSitemap($pageImages[$path]);
    }
    echo "  </url>\n";
}

/* City landing pages — primary + secondary tiers */
$cityFile = __DIR__ . '/parts/locations-data.php';
if (is_file($cityFile)) {
    $cities = require $cityFile;
    foreach ($cities as $slug => $c) {
        $tier = $c['tier'] ?? 'extended';
        if (!in_array($tier, ['primary', 'secondary'], true)) continue;
        $loc      = "$base/locations/$slug";
        $priority = $tier === 'primary' ? '0.85' : '0.65';
        echo "  <url>\n";
        echo "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
        echo "    <lastmod>$today</lastmod>\n";
        echo "    <changefreq>monthly</changefreq>\n";
        echo "    <priority>$priority</priority>\n";
        echo "  </url>\n";
    }
}

/* Blog posts */
try {
    $stmt = $pdo->query("
        SELECT slug, updated_at
        FROM blog_posts
        WHERE status = 'published' AND slug IS NOT NULL AND slug != ''
        ORDER BY updated_at DESC
    ");
    foreach ($stmt->fetchAll() as $post) {
        $loc     = "$base/newsdetail/" . rawurlencode($post['slug']);
        $lastmod = date('Y-m-d', strtotime($post['updated_at']));
        echo "  <url>\n";
        echo "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
        echo "    <lastmod>$lastmod</lastmod>\n";
        echo "    <changefreq>monthly</changefreq>\n";
        echo "    <priority>0.70</priority>\n";
        echo "  </url>\n";
    }
} catch (PDOException $e) {
    // silent fail — still return valid sitemap
}

echo '</urlset>' . "\n";
