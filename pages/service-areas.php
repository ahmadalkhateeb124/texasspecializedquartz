<?php
/**
 * pages/service-areas.php — Texas service-area hub.
 * Lists every city we serve, internally links city landing pages.
 */

$base    = $base_url ?? '/';
$cities  = require __DIR__ . '/../parts/locations-data.php';

$primary   = array_filter($cities, fn($c) => ($c['tier'] ?? '') === 'primary');
$secondary = array_filter($cities, fn($c) => ($c['tier'] ?? '') === 'secondary');
$extended  = array_filter($cities, fn($c) => ($c['tier'] ?? '') === 'extended');

$totalCount = count($cities);

// Group secondary by county for display
$byCounty = [];
foreach ($secondary as $slug => $c) {
    $byCounty[$c['county']][$slug] = $c;
}
ksort($byCounty);
?>

<header class="page-header" data-background="images/Granit-Img/ccccc.png">
    <div class="container ee">
        <h1>Granite, Quartz &amp; Marble Countertops Across Texas</h1>
        <p class="page-subtitle">
            We serve <?= $totalCount ?>+ Texas cities — from our Carrollton showroom to communities across DFW, Houston, Austin and beyond.
        </p>
    </div>
</header>

<section class="sa" aria-label="Texas service areas">
    <div class="sa-container">

        <!-- Intro / value prop -->
        <div class="sa-intro">
            <div class="sa-intro-eyebrow">— Texas Statewide</div>
            <h2 class="sa-intro-title">Our trucks reach every corner of the Lone Star State.</h2>
            <p class="sa-intro-text">
                Texas Specialized Quartz &amp; Granite is a Carrollton-based fabricator with delivery and installation
                across the DFW Metroplex, Houston, Austin, San Antonio and beyond. Whether you're remodeling a Plano
                kitchen, finishing a Frisco new-build, or redoing a downtown Dallas penthouse — we'll template,
                fabricate and install with the same care, materials and craftsmanship.
            </p>
            <div class="sa-intro-stats">
                <div><strong>20+</strong><span>Years experience</span></div>
                <div><strong>5,000+</strong><span>Texas projects completed</span></div>
                <div><strong>300+</strong><span>Slabs in Carrollton showroom</span></div>
                <div><strong>1 week</strong><span>Typical install timeline</span></div>
            </div>
        </div>

        <!-- Primary cities — full landing pages -->
        <div class="sa-section">
            <h2 class="sa-section-title">DFW Metroplex — Premier Service Areas</h2>
            <p class="sa-section-sub">Same-week installation. Free in-home measurement. Lifetime craftsmanship warranty.</p>
            <div class="sa-grid sa-grid-primary">
                <?php foreach ($primary as $slug => $c): ?>
                    <a href="<?= htmlspecialchars($base . 'locations/' . $slug) ?>" class="sa-card sa-card-primary">
                        <div class="sa-card-eyebrow"><?= htmlspecialchars($c['county']) ?></div>
                        <h3><?= htmlspecialchars($c['name']) ?>, TX</h3>
                        <p><?= htmlspecialchars($c['tagline'] ?? "Granite, quartz & marble countertops in {$c['name']}, TX") ?></p>
                        <span class="sa-card-cta">View <?= htmlspecialchars($c['name']) ?> services <i class="fas fa-arrow-right"></i></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Secondary — grouped by county -->
        <div class="sa-section">
            <h2 class="sa-section-title">More DFW &amp; North Texas Communities</h2>
            <p class="sa-section-sub">Full-service fabrication and installation throughout these cities.</p>
            <?php foreach ($byCounty as $county => $citiesInCounty): ?>
                <div class="sa-county-block">
                    <h3 class="sa-county-title"><?= htmlspecialchars($county) ?></h3>
                    <div class="sa-pills">
                        <?php foreach ($citiesInCounty as $slug => $c): ?>
                            <a href="<?= htmlspecialchars($base . 'locations/' . $slug) ?>" class="sa-pill">
                                <?= htmlspecialchars($c['name']) ?>, TX
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Extended — Texas-wide footer list -->
        <div class="sa-section">
            <h2 class="sa-section-title">Texas-Wide Coverage</h2>
            <p class="sa-section-sub">For larger projects we travel across Texas. Contact us for delivery and install pricing in any of these cities:</p>
            <ul class="sa-extended">
                <?php foreach ($extended as $slug => $c): ?>
                    <li><?= htmlspecialchars($c['name']) ?>, TX</li>
                <?php endforeach; ?>
            </ul>
            <p class="sa-section-note">
                Don't see your city? <a href="<?= htmlspecialchars($base) ?>contact">Contact us</a> — chances are we can help.
            </p>
        </div>

        <!-- CTA -->
        <div class="sa-cta">
            <h3>Ready to start your countertop project?</h3>
            <p>Free in-home measurement. Same-week installation. Lifetime craftsmanship warranty.</p>
            <div class="sa-cta-row">
                <a class="sa-btn sa-btn-primary" href="<?= htmlspecialchars($base) ?>contact">
                    <i class="fas fa-arrow-right"></i> Request a free quote
                </a>
                <a class="sa-btn sa-btn-ghost" href="tel:+14698140555">
                    <i class="fas fa-phone"></i> (469) 814-0555
                </a>
            </div>
        </div>

    </div>
</section>

<?php
// Schema markup — ItemList of all service area cities
$itemList = [
    '@context' => 'https://schema.org',
    '@type'    => 'ItemList',
    'name'     => 'Texas Cities Served by Texas Specialized Quartz & Granite',
    'numberOfItems' => $totalCount,
    'itemListElement' => array_values(array_map(function ($c, $slug) use ($base) {
        $isPrimary = ($c['tier'] ?? '') === 'primary';
        return [
            '@type'    => 'ListItem',
            'position' => null, // assigned below
            'item'     => array_filter([
                '@type'   => 'Place',
                'name'    => "{$c['name']}, {$c['state']}",
                'url'     => $isPrimary ? $base . 'locations/' . $slug : null,
                'address' => [
                    '@type'           => 'PostalAddress',
                    'addressLocality' => $c['name'],
                    'addressRegion'   => $c['state'],
                    'addressCountry'  => 'US',
                ],
                'geo' => isset($c['lat']) ? [
                    '@type'     => 'GeoCoordinates',
                    'latitude'  => $c['lat'],
                    'longitude' => $c['lon'],
                ] : null,
            ]),
        ];
    }, $cities, array_keys($cities))),
];
foreach ($itemList['itemListElement'] as $i => &$item) {
    $item['position'] = $i + 1;
}
unset($item);
?>
<script type="application/ld+json"><?= json_encode($itemList, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?></script>
