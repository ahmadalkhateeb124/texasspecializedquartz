<!--  HOME PAGE STRUCTURED DATA         -->
<!-- ═══════════════════════════════════ -->
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "@id": "<?= $DOMAIN ?>/Home",
        "name": "Texas Specialized Quartz & Granite — Premium Countertop Fabrication & Installation",
        "description": "Professional granite, quartz, and marble countertop fabrication and installation services for residential and commercial projects across the Dallas-Fort Worth metroplex.",
        "url": "<?= $DOMAIN ?>",
        "breadcrumb": {
            "@type": "BreadcrumbList",
            "itemListElement": [{
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "<?= $DOMAIN ?>"
            }]
        },
        "mainEntity": {
            "@type": "LocalBusiness",
            "@id": "<?= $DOMAIN ?>/#localbusiness",
            "name": "Texas Specialized Quartz & Granite",
            "description": "Expert countertop fabrication and installation — granite, quartz, marble & quartzite for kitchens, bathrooms & commercial spaces.",
            "url": "<?= $DOMAIN ?>",
            "telephone": "+1-469-814-0555",
            "email": "Cs@TexasSpecializedQuartz.com",
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
                "latitude": "32.9537",
                "longitude": "-96.8903"
            },
            "openingHoursSpecification": [{
                    "@type": "OpeningHoursSpecification",
                    "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
                    "opens": "08:00",
                    "closes": "18:00"
                },
                {
                    "@type": "OpeningHoursSpecification",
                    "dayOfWeek": "Saturday",
                    "opens": "09:00",
                    "closes": "14:00"
                }
            ],
            "priceRange": "$$",
            "hasOfferCatalog": {
                "@type": "OfferCatalog",
                "name": "Countertop Services",
                "itemListElement": [{
                        "@type": "Offer",
                        "itemOffered": {
                            "@type": "Service",
                            "name": "Granite Countertop Fabrication & Installation"
                        }
                    },
                    {
                        "@type": "Offer",
                        "itemOffered": {
                            "@type": "Service",
                            "name": "Quartz Countertop Fabrication & Installation"
                        }
                    },
                    {
                        "@type": "Offer",
                        "itemOffered": {
                            "@type": "Service",
                            "name": "Marble Countertop Fabrication & Installation"
                        }
                    },
                    {
                        "@type": "Offer",
                        "itemOffered": {
                            "@type": "Service",
                            "name": "Countertop Repair & Maintenance"
                        }
                    },
                    {
                        "@type": "Offer",
                        "itemOffered": {
                            "@type": "Service",
                            "name": "Free In-Home Estimate & Consultation"
                        }
                    }
                ]
            }
        }
    }
</script>