<?php
/**
 * Inventory slab Product schema — emits ItemList of Products.
 */
$DOMAIN = 'https://texasspecializedquartz.com';
$brand  = setting('business_name', 'Texas Specialized Quartz & Granite');

try {
    $slabs = $pdo->query("
        SELECT id, name, material_type, size, image
        FROM inventory_slabs
        WHERE status = 'active' AND image != ''
        ORDER BY sort_order, id DESC
        LIMIT 50
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    return;
}
if (!$slabs) return;

$items = [];
$pos = 1;
foreach ($slabs as $s) {
    $img = "$DOMAIN/BusinessPortal/assets/inventory/" . rawurlencode($s['image']);
    $items[] = [
        '@type'    => 'ListItem',
        'position' => $pos++,
        'item'     => [
            '@type'    => 'Product',
            'name'     => $s['name'],
            'image'    => $img,
            'category' => ucfirst($s['material_type']),
            'brand'    => ['@type' => 'Brand', 'name' => $brand],
            'description' => sprintf('%s slab, %s thickness.', ucfirst($s['material_type']), $s['size']),
        ],
    ];
}

$schema = [
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'name'            => 'Stone Slab Inventory',
    'itemListElement' => $items,
];
?>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
