<?php
/**
 * BreadcrumbList JSON-LD — derived from the URL path.
 */
$base = 'https://texasspecializedquartz.com';
$bcPath = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/', '/');

if ($bcPath === '' || $bcPath === 'Home' || $bcPath === 'index.php') {
    return; // homepage — no breadcrumb needed
}

$items = [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $base . '/'],
];

$segments = explode('/', $bcPath);
$accum    = $base;
$pos      = 2;
foreach ($segments as $seg) {
    if ($seg === '') continue;
    $accum .= '/' . $seg;
    $label = ucwords(str_replace(['-', '_'], ' ', $seg));
    $items[] = [
        '@type'    => 'ListItem',
        'position' => $pos++,
        'name'     => $label,
        'item'     => $accum,
    ];
}

$schema = [
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => $items,
];
?>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
