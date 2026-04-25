<?php
$schemaOgImage    = $ogImageUrl ?? ('https://texasspecializedquartz.com/' . ltrim($post['image'] ?? '', '/'));
$schemaDesc       = trim(mb_substr(strip_tags($post['content'] ?? ''), 0, 155)) . '...';
$schemaKeywords   = $post['tags'] ?? '';
?>
    <!-- Schema.org Article Markup -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": "<?= htmlspecialchars($post['title']) ?>",
        "image": {
            "@type": "ImageObject",
            "url": "<?= $schemaOgImage ?>",
            "width": 1200,
            "height": 630
        },
        "author": {
            "@type": "Organization",
            "name": "Texas Specialized Quartz & Granite",
            "url": "https://texasspecializedquartz.com",
            "logo": {
                "@type": "ImageObject",
                "url": "https://texasspecializedquartz.com/images/logo.png"
            }
        },
        "publisher": {
            "@type": "Organization",
            "name": "Texas Specialized Quartz & Granite",
            "logo": {
                "@type": "ImageObject",
                "url": "https://texasspecializedquartz.com/images/logo.png",
                "width": 250,
                "height": 60
            }
        },
        "datePublished": "<?= date('c', strtotime($post['date'])) ?>",
        "dateModified": "<?= date('c', strtotime($post['date'])) ?>",
        "description": "<?= htmlspecialchars($schemaDesc) ?>",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?= $canonicalUrl ?>"
        },
        "keywords": "<?= htmlspecialchars($schemaKeywords) ?>",
        "articleSection": "Granite Countertops",
        "inLanguage": "en-US"
    }
    </script>
    
    <!-- LocalBusiness Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "Texas Specialized Quartz & Granite",
        "image": "https://texasspecializedquartz.com/images/logo.png",
        "@id": "https://texasspecializedquartz.com",
        "url": "https://texasspecializedquartz.com",
        "telephone": "+14698140555",
        "priceRange": "$$",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "10830 Composite Dr",
            "addressLocality": "Dallas",
            "addressRegion": "TX",
            "postalCode": "75220",
            "addressCountry": "US"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": 32.8998,
            "longitude": -96.8916
        },
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
                "opens": "08:00",
                "closes": "17:00"
            }
        ],
        "sameAs": [
            "https://www.facebook.com/texasspecializedquartz",
            "https://www.instagram.com/texasspecializedquartz"
        ]
    }
    </script>
    
    <!-- Breadcrumb Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "https://texasspecializedquartz.com"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "Blog",
                "item": "https://texasspecializedquartz.com/blog"
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": "<?= htmlspecialchars($post['title']) ?>",
                "item": "<?= $canonicalUrl ?>"
            }
        ]
    }
    </script>
