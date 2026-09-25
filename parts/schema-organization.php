<?php
/**
 * Organization JSON-LD — separate from LocalBusiness so Google
 * can match either entity type when ranking the brand.
 */
$orgName  = setting('business_name', 'Texas Specialized Quartz & Granite');
$orgPhone = setting('business_phone', '+1-469-814-0555');
$orgEmail = setting('business_email', 'Cs@TexasSpecializedQuartz.com');

$socials = array_filter([
    setting('social_facebook'),
    setting('social_instagram'),
    setting('social_youtube'),
    setting('social_twitter'),
    setting('social_linkedin'),
    setting('social_tiktok'),
]);

$org = [
    '@context'      => 'https://schema.org',
    '@type'         => 'Organization',
    '@id'           => 'https://texasspecializedquartz.com/#organization',
    'name'          => $orgName,
    'alternateName' => ['Texas Specialized Quartz', 'TSQG'],
    'url'           => 'https://texasspecializedquartz.com/',
    'logo'          => [
        '@type'  => 'ImageObject',
        'url'    => 'https://texasspecializedquartz.com/images/Granit-Img/logo-gg.jpg',
        'width'  => 250,
        'height' => 250,
    ],
    'description'   => 'Premium granite, quartz, marble & quartzite countertop fabrication and installation across Texas. Family-owned since 2005.',
    'foundingDate'  => '2005',
    'foundingLocation' => [
        '@type' => 'Place',
        'name'  => 'Carrollton, Texas, USA',
    ],
    'address' => [
        '@type'           => 'PostalAddress',
        'streetAddress'   => '2943 Ladybird Ln',
        'addressLocality' => 'Dallas',
        'addressRegion'   => 'TX',
        'postalCode'      => '75220',
        'addressCountry'  => 'US',
    ],
    'contactPoint' => [
        '@type'             => 'ContactPoint',
        'telephone'         => $orgPhone,
        'email'             => $orgEmail,
        'contactType'       => 'customer service',
        'areaServed'        => 'US-TX',
        'availableLanguage' => ['English', 'Spanish'],
    ],
    'knowsAbout' => [
        'Granite countertops',
        'Quartz countertops',
        'Marble countertops',
        'Quartzite countertops',
        'Stone fabrication',
        'Kitchen countertops',
        'Bathroom vanities',
        'Edge profiles',
        'CNC stone cutting',
        'Slab installation',
    ],
];

if ($socials) {
    $org['sameAs'] = array_values($socials);
}
?>
<script type="application/ld+json"><?= json_encode($org, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
