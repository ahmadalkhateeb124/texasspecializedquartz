<?php
/**
 * Local Business JSON-LD — dynamic from Settings + locations data.
 *
 * Uses the canonical "HomeAndConstructionBusiness" with sub-type
 * "GeneralContractor" for granite/quartz fabrication. Adds:
 *   - geo (lat/lon) for Map Pack ranking
 *   - areaServed (every TX city we cover)
 *   - service catalog (granite/quartz/marble/quartzite countertops)
 *   - aggregateRating (when available)
 */
$bizName    = setting('business_name', 'Texas Specialized Quartz & Granite');
$bizPhone   = setting('business_phone', '+1-469-814-0555');
$bizEmail   = setting('business_email', 'Cs@TexasSpecializedQuartz.com');
$bizAddr    = setting('business_address', '2943 Ladybird Ln ');
$bizCity    = setting('business_city', 'Dallas TX 75220, United States');
$bizState   = setting('business_state', 'TX');
$bizZip     = setting('business_zip', '75006');
$bizHours   = setting('business_hours', '');
$bizMapLink = setting('business_map_link', '');
$bizLat     = (float)setting('business_lat', '32.9537');
$bizLon     = (float)setting('business_lon', '-96.8903');
$bizFounded = setting('business_founded', '2005');
$bizLogo    = 'https://texasspecializedquartz.com/images/Granit-Img/logo-gg.jpg';

$socials = array_filter([
    setting('social_facebook'),
    setting('social_instagram'),
    setting('social_youtube'),
    setting('social_twitter'),
    setting('social_linkedin'),
    setting('social_tiktok'),
]);

/* Build areaServed list from locations-data.php */
$cities = [];
$cityFile = __DIR__ . '/locations-data.php';
if (is_file($cityFile)) {
    $rawCities = require $cityFile;
    foreach ($rawCities as $slug => $c) {
        $cities[] = array_filter([
            '@type' => 'City',
            'name'  => "{$c['name']}, {$c['state']}",
            'sameAs' => "https://en.wikipedia.org/wiki/" . str_replace(' ', '_', $c['name']) . ",_Texas",
        ]);
    }
}
/* Texas as the umbrella state — gives strong "all of Texas" signal */
array_unshift($cities, [
    '@type' => 'State',
    'name'  => 'Texas',
    'sameAs' => 'https://en.wikipedia.org/wiki/Texas',
]);

$schema = [
    '@context'    => 'https://schema.org',
    '@type'       => ['LocalBusiness', 'HomeAndConstructionBusiness', 'GeneralContractor'],
    '@id'         => 'https://texasspecializedquartz.com/#business',
    'name'        => $bizName,
    'alternateName' => ['Texas Specialized Quartz', 'TSQG'],
    'description' => 'Premium granite, quartz, marble & quartzite countertop fabrication and installation across Texas. Carrollton-based showroom with 300+ slabs in stock.',
    'url'         => 'https://texasspecializedquartz.com/',
    'logo'        => $bizLogo,
    'image'       => [
        $bizLogo,
        'https://texasspecializedquartz.com/images/Granit-Img/Ba-home2.png',
    ],
    'priceRange'  => '$$',
    'foundingDate' => $bizFounded,
    'currenciesAccepted' => 'USD',
    'paymentAccepted'    => 'Cash, Credit Card, Check, Financing',
];

if ($bizPhone)   $schema['telephone'] = $bizPhone;
if ($bizEmail)   $schema['email']     = $bizEmail;
if ($bizMapLink) $schema['hasMap']    = $bizMapLink;
if ($socials)    $schema['sameAs']    = array_values($socials);

if ($bizAddr || $bizCity) {
    $schema['address'] = array_filter([
        '@type'           => 'PostalAddress',
        'streetAddress'   => $bizAddr,
        'addressLocality' => $bizCity,
        'addressRegion'   => $bizState,
        'postalCode'      => $bizZip,
        'addressCountry'  => 'US',
    ]);
}

if ($bizLat && $bizLon) {
    $schema['geo'] = [
        '@type'     => 'GeoCoordinates',
        'latitude'  => $bizLat,
        'longitude' => $bizLon,
    ];
}

if ($bizHours) {
    $schema['openingHours'] = $bizHours;
} else {
    /* Sensible default if not configured */
    $schema['openingHoursSpecification'] = [
        ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday'], 'opens' => '09:00', 'closes' => '18:00'],
        ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => 'Saturday', 'opens' => '09:00', 'closes' => '15:00'],
    ];
}

$schema['areaServed'] = $cities;

/* Service radius — 200-mile circle from Carrollton covers most of Texas */
$schema['serviceArea'] = [
    '@type'  => 'GeoCircle',
    'geoMidpoint' => [
        '@type'     => 'GeoCoordinates',
        'latitude'  => $bizLat,
        'longitude' => $bizLon,
    ],
    'geoRadius' => '321868', // metres ≈ 200 miles
];

/* Service catalog — what Google should show in the rich result */
$schema['hasOfferCatalog'] = [
    '@type' => 'OfferCatalog',
    'name'  => 'Countertop Services',
    'itemListElement' => [
        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Granite Countertop Fabrication & Installation', 'serviceType' => 'Granite countertops']],
        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Quartz Countertop Fabrication & Installation', 'serviceType' => 'Quartz countertops']],
        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Marble Countertop Fabrication & Installation', 'serviceType' => 'Marble countertops']],
        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Quartzite Countertop Fabrication & Installation', 'serviceType' => 'Quartzite countertops']],
        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Custom Stone Edge Profiles', 'serviceType' => 'Edge profiles']],
        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Bathroom Vanity Countertops', 'serviceType' => 'Bathroom vanities']],
        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Kitchen Countertop Installation', 'serviceType' => 'Kitchen countertops']],
        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Stone Slab Sales (Granite, Quartz, Marble, Quartzite)', 'serviceType' => 'Stone slab sales']],
    ],
];

/* Optional aggregateRating — wire from settings if you collect reviews on-site */
$reviewCount = (int)setting('reviews_count', 0);
$reviewAvg   = (float)setting('reviews_average', 0);
if ($reviewCount >= 5 && $reviewAvg > 0) {
    $schema['aggregateRating'] = [
        '@type'       => 'AggregateRating',
        'ratingValue' => $reviewAvg,
        'reviewCount' => $reviewCount,
        'bestRating'  => 5,
        'worstRating' => 1,
    ];
}
?>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
