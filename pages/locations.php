<?php
/**
 * pages/locations.php — Dynamic city landing page.
 *
 * URL: /locations/<city-slug>   (handled by .htaccess → ?url=locations&puid=<slug>)
 * Falls back to /service-areas hub if slug missing or invalid.
 */

$base   = $base_url ?? '/';
$cities = require __DIR__ . '/../parts/locations-data.php';

$slug = strtolower(trim($SP ?? $_GET['puid'] ?? $_GET['city'] ?? ''));
if ($slug === '' || !isset($cities[$slug])) {
    header('Location: ' . $base . 'service-areas', true, 302);
    exit;
}

$c     = $cities[$slug];
$tier  = $c['tier'] ?? 'extended';

/* Extended cities don't have full landing pages — redirect to hub */
if ($tier === 'extended') {
    header('Location: ' . $base . 'service-areas', true, 302);
    exit;
}

$cityName     = $c['name'];
$state        = $c['state'] ?? 'TX';
$county       = $c['county'] ?? '';
$tagline      = $c['tagline'] ?? "Granite, quartz & marble countertops in {$cityName}, {$state}";
$intro        = $c['intro'] ?? '';
$nbhds        = $c['neighborhoods'] ?? [];
$zips         = $c['zips'] ?? [];
$distance     = $c['distance_min'] ?? null;
$lat          = $c['lat'] ?? null;
$lon          = $c['lon'] ?? null;
$canonicalUrl = 'https://texasspecializedquartz.com/locations/' . $slug;
?>

<header class="page-header" data-background="images/Granit-Img/ccccc.png">
    <div class="container ee">
        <h1>
            Granite, Quartz &amp; Marble Countertops in <strong><?= htmlspecialchars($cityName) ?>, <?= $state ?></strong>
        </h1>
        <p class="page-subtitle"><?= htmlspecialchars($tagline) ?></p>
    </div>
</header>

<nav class="loc-crumbs" aria-label="breadcrumb">
    <div class="loc-crumbs-inner">
        <a href="<?= htmlspecialchars($base) ?>">Home</a>
        <span class="sep">›</span>
        <a href="<?= htmlspecialchars($base) ?>service-areas">Service Areas</a>
        <span class="sep">›</span>
        <span class="cur"><?= htmlspecialchars($cityName) ?></span>
    </div>
</nav>

<section class="loc" aria-label="<?= htmlspecialchars($cityName) ?> service area">
    <div class="loc-container">

        <!-- Lead block: city intro + key facts -->
        <div class="loc-lead">
            <div class="loc-lead-text">
                <div class="loc-eyebrow">— Serving <?= htmlspecialchars($cityName) ?>, <?= $state ?></div>
                <h2>Premium stone countertops, fabricated for <?= htmlspecialchars($cityName) ?> homes.</h2>
                <?php if ($intro): ?><p><?= htmlspecialchars($intro) ?></p><?php endif; ?>
                <p>
                    Visit our showroom at <strong>10830 Composite Dr,
Dallas, TX 75220</strong>
                    <?php if ($distance !== null && $distance > 0): ?>
                        — about <strong><?= (int)$distance ?> minutes</strong> from <?= htmlspecialchars($cityName) ?>
                    <?php endif; ?>
                    — to see 300+ slabs in person, talk through your project, and leave with a same-day quote.
                </p>
                <div class="loc-lead-ctas">
                    <a class="loc-btn loc-btn-primary" href="<?= htmlspecialchars($base) ?>contact">
                        <i class="fas fa-arrow-right"></i> Free quote in <?= htmlspecialchars($cityName) ?>
                    </a>
                    <a class="loc-btn loc-btn-ghost" href="tel:+14698140555">
                        <i class="fas fa-phone"></i> (469) 814-0555
                    </a>
                </div>
            </div>
            <aside class="loc-lead-facts">
                <h3>Quick facts</h3>
                <dl>
                    <dt>City</dt><dd><?= htmlspecialchars($cityName) ?>, <?= $state ?></dd>
                    <?php if ($county): ?><dt>County</dt><dd><?= htmlspecialchars($county) ?></dd><?php endif; ?>
                    <?php if ($distance !== null): ?><dt>From showroom</dt><dd>~<?= (int)$distance ?> min</dd><?php endif; ?>
                    <?php if ($zips): ?>
                        <dt>ZIP codes</dt>
                        <dd><?= htmlspecialchars(implode(', ', array_slice($zips, 0, 6))) ?><?= count($zips) > 6 ? ' …' : '' ?></dd>
                    <?php endif; ?>
                    <dt>Install timeline</dt><dd>Typically 5–7 days from template</dd>
                    <dt>Free estimate</dt><dd>In-home or in-showroom</dd>
                </dl>
            </aside>
        </div>

        <!-- Services we provide in this city -->
        <div class="loc-services">
            <h2>What we install in <?= htmlspecialchars($cityName) ?></h2>
            <div class="loc-services-grid">
                <a href="<?= htmlspecialchars($base) ?>KitchenCountertops" class="loc-svc">
                    <div class="loc-svc-icon"><i class="fas fa-utensils"></i></div>
                    <h3>Kitchen Countertops</h3>
                    <p>Custom granite, quartz, marble &amp; quartzite kitchens — from islands to full L-shape layouts.</p>
                </a>
                <a href="<?= htmlspecialchars($base) ?>Countertops" class="loc-svc">
                    <div class="loc-svc-icon"><i class="fas fa-ruler-combined"></i></div>
                    <h3>Bathroom Vanities</h3>
                    <p>Marble, quartz &amp; quartzite vanity tops with integrated sink cutouts.</p>
                </a>
                <a href="<?= htmlspecialchars($base) ?>Inventory" class="loc-svc">
                    <div class="loc-svc-icon"><i class="fas fa-cubes"></i></div>
                    <h3>Slab Selection</h3>
                    <p>300+ slabs in our Carrollton showroom — schedule a visit and pick your exact piece.</p>
                </a>
                <a href="<?= htmlspecialchars($base) ?>EdgeType" class="loc-svc">
                    <div class="loc-svc-icon"><i class="fas fa-wave-square"></i></div>
                    <h3>Custom Edge Profiles</h3>
                    <p>25+ edge styles — bullnose, ogee, waterfall, beveled, eased and more.</p>
                </a>
                <a href="<?= htmlspecialchars($base) ?>Sinks" class="loc-svc">
                    <div class="loc-svc-icon"><i class="fas fa-tint"></i></div>
                    <h3>Undermount Sinks</h3>
                    <p>Stainless steel, composite &amp; ceramic sinks fitted with precision cutouts.</p>
                </a>
                <a href="<?= htmlspecialchars($base) ?>Remnants" class="loc-svc">
                    <div class="loc-svc-icon"><i class="fas fa-tag"></i></div>
                    <h3>Remnant Slabs</h3>
                    <p>Save up to 60% on smaller pieces — perfect for vanities, bar tops &amp; laundry rooms.</p>
                </a>
            </div>
        </div>

        <!-- Materials -->
        <div class="loc-materials">
            <h2>Materials available in <?= htmlspecialchars($cityName) ?></h2>
            <div class="loc-materials-grid">
                <div>
                    <h4>Granite</h4>
                    <p>Natural, durable, heat-resistant. 100+ colors from Brazil, India and Italy. Annual sealing recommended.</p>
                </div>
                <div>
                    <h4>Quartz</h4>
                    <p>Engineered, non-porous, zero maintenance. Cambria, Silestone, Caesarstone. Ideal for busy kitchens.</p>
                </div>
                <div>
                    <h4>Marble</h4>
                    <p>Timeless luxury — Calacatta, Carrara, Statuary. Soft &amp; porous, sealed for stain resistance.</p>
                </div>
                <div>
                    <h4>Quartzite</h4>
                    <p>Natural stone with marble looks &amp; granite durability. Perfect for show-stopping islands.</p>
                </div>
            </div>
        </div>

        <!-- Neighborhoods served -->
        <?php if (!empty($nbhds)): ?>
            <div class="loc-nbhds">
                <h2>Neighborhoods we serve in <?= htmlspecialchars($cityName) ?></h2>
                <div class="loc-nbhds-list">
                    <?php foreach ($nbhds as $n): ?>
                        <span><?= htmlspecialchars($n) ?></span>
                    <?php endforeach; ?>
                </div>
                <p class="loc-nbhds-note">
                    Don't see your neighborhood? We serve <strong>all of <?= htmlspecialchars($cityName) ?>, <?= $state ?></strong>
                    — call <a href="tel:+14698140555">(469) 814-0555</a> to confirm coverage.
                </p>
            </div>
        <?php endif; ?>

        <!-- Why us -->
        <div class="loc-why">
            <h2>Why <?= htmlspecialchars($cityName) ?> homeowners choose us</h2>
            <div class="loc-why-grid">
                <div><strong>Local fabricator</strong><span>Carrollton-based shop — no middlemen, no markups.</span></div>
                <div><strong>20+ years</strong><span>Experience installing across DFW since 2005.</span></div>
                <div><strong>5,000+ projects</strong><span>From condos to estates across Texas.</span></div>
                <div><strong>Same-week install</strong><span>Most kitchens template Mon, install by Friday.</span></div>
                <div><strong>Free measurement</strong><span>In-home digital templating at no cost.</span></div>
                <div><strong>Lifetime warranty</strong><span>On all craftsmanship — written, not verbal.</span></div>
            </div>
        </div>

        <!-- Final CTA -->
        <div class="loc-cta">
            <h3>Get your free <?= htmlspecialchars($cityName) ?> quote today.</h3>
            <p>Tell us about your project. We'll respond within one business day with material ideas, pricing, and a timeline.</p>
            <div class="loc-cta-row">
                <a class="loc-btn loc-btn-primary" href="<?= htmlspecialchars($base) ?>contact">
                    <i class="fas fa-arrow-right"></i> Request a free quote
                </a>
                <a class="loc-btn loc-btn-ghost" href="<?= htmlspecialchars($base) ?>service-areas">
                    <i class="fas fa-map"></i> All service areas
                </a>
            </div>
        </div>

    </div>
</section>

<?php
/* ── Local-business schema scoped to this city ── */
$bizName  = setting('business_name',  'Texas Specialized Quartz & Granite');
$bizPhone = setting('business_phone', '+14698140555');
$bizEmail = setting('business_email', 'Cs@TexasSpecializedQuartz.com');

$schema = [
    '@context' => 'https://schema.org',
    '@type'    => 'LocalBusiness',
    '@id'      => $canonicalUrl . '#business',
    'name'     => "{$bizName} — {$cityName}, {$state}",
    'url'      => $canonicalUrl,
    'telephone' => $bizPhone,
    'email'     => $bizEmail,
    'image'     => 'https://texasspecializedquartz.com/images/Granit-Img/logo-gg.jpg',
    'priceRange' => '$$',
    'address'   => [
        '@type'           => 'PostalAddress',
        'streetAddress'   => '10830 Composite Dr,
Dallas, TX 75220',
        'addressLocality' => 'Carrollton',
        'addressRegion'   => 'TX',
        'postalCode'      => '75006',
        'addressCountry'  => 'US',
    ],
    'areaServed' => array_filter([
        '@type' => 'City',
        'name'  => "{$cityName}, {$state}",
        'geo'   => ($lat && $lon) ? [
            '@type' => 'GeoCoordinates',
            'latitude'  => $lat,
            'longitude' => $lon,
        ] : null,
    ]),
];

$breadcrumbs = [
    '@context' => 'https://schema.org',
    '@type'    => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',          'item' => 'https://texasspecializedquartz.com/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Service Areas', 'item' => 'https://texasspecializedquartz.com/service-areas'],
        ['@type' => 'ListItem', 'position' => 3, 'name' => "{$cityName}, {$state}", 'item' => $canonicalUrl],
    ],
];
?>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES) ?></script>
<script type="application/ld+json"><?= json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES) ?></script>
