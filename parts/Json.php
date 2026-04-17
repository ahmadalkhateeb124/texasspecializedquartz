<?php
// ═══════════════════════════════════════════════════════
//  Structured Data (JSON-LD) — texasspecializedquartz.com
// ═══════════════════════════════════════════════════════

$DOMAIN = 'https://texasspecializedquartz.com';

// Organization
$organization = [
    "@type" => "Organization",
    "@id" => "$DOMAIN/#organization",
    "name" => "Texas Specialized Quartz & Granite",
    "url" => $DOMAIN,
    "logo" => [
        "@type" => "ImageObject",
        "url" => "$DOMAIN/images/Granit-Img/logo-gg.jpg",
        "width" => "300",
        "height" => "100"
    ],
    "description" => "Premium granite, quartz & marble countertop fabrication and installation across Texas. Custom kitchens, bathrooms & commercial projects with 15+ years experience.",
    "foundingDate" => "2015",
    "founder" => "Texas Specialized Quartz & Granite Team",
    "numberOfEmployees" => "10-50",
    "contactPoint" => [
        [
            "@type" => "ContactPoint",
            "telephone" => "+1-469-814-0555",
            "contactType" => "customer service",
            "email" => "Cs@TexasSpecializedQuartz.com",
            "contactOption" => "TollFree",
            "areaServed" => ["US"],
            "availableLanguage" => ["English", "Spanish"]
        ],
        [
            "@type" => "ContactPoint",
            "telephone" => "+1-469-814-0555",
            "contactType" => "sales",
            "contactOption" => "HearingImpairedSupported"
        ]
    ],
    "address" => [
        "@type" => "PostalAddress",
        "streetAddress" => "1225 W College Ave, Suite #616",
        "addressLocality" => "Carrollton",
        "addressRegion" => "TX",
        "postalCode" => "75006",
        "addressCountry" => "US"
    ],
    "sameAs" => [
        "https://www.instagram.com/texas.specialized.quartz/",
        "https://www.youtube.com/@Granite_Artists",
        "https://www.pinterest.com/TexasSpecializedQuartz/",
    ],
    "knowsAbout" => [
        "Granite Countertops",
        "Quartz Countertops",
        "Marble Countertops",
        "Quartzite Countertops",
        "Stone Fabrication",
        "CNC Stone Cutting",
        "Countertop Installation",
        "Kitchen Remodeling",
        "Bathroom Vanities",
        "Commercial Countertops"
    ],
    "serviceArea" => [
        "@type" => "GeoCircle",
        "geoMidpoint" => [
            "@type" => "GeoCoordinates",
            "latitude" => "32.9537",
            "longitude" => "-96.8903"
        ],
        "geoRadius" => "80000"
    ]
];

// Current page
$currentPage = strtolower(basename($_SERVER['PHP_SELF'], '.php'));
if ($currentPage === 'index') {
    $currentPage = 'home';
}

// Page-specific structured data
if (isset($pagesContent[$currentPage])) {
    $data = $pagesContent[$currentPage];
    $pageUrl = ($currentPage !== 'home') ? "$DOMAIN/$currentPage" : $DOMAIN;

    switch ($currentPage) {
        case 'home':
            $type = 'WebSite';
            $additionalData = [
                "potentialAction" => [
                    [
                        "@type" => "SearchAction",
                        "target" => "$DOMAIN/search?q={search_term_string}",
                        "query-input" => "required name=search_term_string"
                    ]
                ]
            ];
            break;

        case 'services':
        case 'installationservices':
            $type = 'Service';
            $additionalData = [
                "serviceType" => "Countertop Fabrication & Installation",
                "provider" => ["@id" => "$DOMAIN/#organization"],
                "areaServed" => [
                    "@type" => "State",
                    "name" => "Texas"
                ],
                "offers" => [
                    "@type" => "AggregateOffer",
                    "priceCurrency" => "USD",
                    "lowPrice" => "35",
                    "highPrice" => "150",
                    "offerCount" => "4",
                    "priceSpecification" => [
                        "@type" => "UnitPriceSpecification",
                        "unitText" => "per square foot"
                    ]
                ]
            ];
            break;

        case 'about':
            $type = 'AboutPage';
            $additionalData = [
                "mainEntity" => ["@id" => "$DOMAIN/#organization"]
            ];
            break;

        case 'contact':
            $type = 'ContactPage';
            $additionalData = [
                "mainEntity" => ["@id" => "$DOMAIN/#organization"]
            ];
            break;

        case 'photogallery':
            $type = 'CollectionPage';
            $additionalData = [
                "about" => "Granite, quartz and marble countertop installation photos"
            ];
            break;

        case 'blog':
            $type = 'Blog';
            $additionalData = [
                "blogPost" => []
            ];
            break;

        case 'faq':
            $type = 'FAQPage';
            $additionalData = [];
            break;

        case 'reviews':
            $type = 'ReviewPage';
            $additionalData = [
                "about" => ["@id" => "$DOMAIN/#localbusiness"]
            ];
            break;

        case 'kitchenvisualizer':
        case 'edgevisualizer':
            $type = 'WebApplication';
            $additionalData = [
                "applicationCategory" => "DesignApplication",
                "operatingSystem" => "Web Browser",
                "offers" => [
                    "@type" => "Offer",
                    "price" => "0",
                    "priceCurrency" => "USD"
                ]
            ];
            break;

        default:
            $type = 'WebPage';
            $additionalData = [];
    }

    $schemaData = [
        "@context" => "https://schema.org",
        "@graph" => [
            array_merge([
                "@type" => $type,
                "@id" => $pageUrl,
                "name" => $data['title'],
                "headline" => $data['title'],
                "description" => $data['description'],
                "publisher" => ["@id" => "$DOMAIN/#organization"],
                "url" => $pageUrl,
                "datePublished" => "2023-01-01",
                "dateModified" => date('Y-m-d'),
                "inLanguage" => "en-US",
                "isPartOf" => ["@id" => "$DOMAIN/#website"]
            ], $additionalData),
            $organization
        ]
    ];

    if (!empty($data['keywords'])) {
        $schemaData["@graph"][0]["keywords"] = $data['keywords'];
    }

    // Breadcrumb for non-home pages
    if ($currentPage !== 'home') {
        $schemaData["@graph"][] = [
            "@type" => "BreadcrumbList",
            "@id" => "$pageUrl/#breadcrumb",
            "itemListElement" => [
                [
                    "@type" => "ListItem",
                    "position" => 1,
                    "name" => "Home",
                    "item" => $DOMAIN
                ],
                [
                    "@type" => "ListItem",
                    "position" => 2,
                    "name" => $data['title'],
                    "item" => $pageUrl
                ]
            ]
        ];
    }

    echo '<script type="application/ld+json">' .
        json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) .
        '</script>' . PHP_EOL;
}

// ═══════════════════════════════════════
//  FAQ Schema
// ═══════════════════════════════════════
$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        [
            "@type" => "Question",
            "name" => "What countertop services does Texas Specialized Quartz offer?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "We specialize in custom fabrication and professional installation of granite, quartz, marble, and quartzite countertops for kitchens, bathrooms, and commercial spaces across Texas. Services include CNC precision cutting, templating, installation, sealing, and repair."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "How long does countertop installation take?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Most residential installations are completed in a single day. The full process from template to installation typically takes 5-7 business days. Complex commercial projects may require additional time depending on scope."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "What is the difference between granite, quartz, and marble countertops?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Granite is a natural stone with unique patterns and excellent heat resistance. Quartz is engineered stone that's non-porous and low-maintenance. Marble offers elegant veining but requires regular sealing. Each material has distinct advantages depending on your usage, style, and budget."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "How much do granite countertops cost in Texas?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Granite countertops in Texas typically range from $35 to $100+ per square foot installed, depending on the stone grade, edge profile, and project complexity. We offer free in-home estimates and competitive pricing with financing options available."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Do you offer free countertop estimates?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Yes, we provide free in-home measurements and estimates with no obligation. Call (469) 814-0555 or email Cs@TexasSpecializedQuartz.com to schedule your free consultation. We also offer virtual consultations."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "What areas in Texas do you serve?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "We serve the greater Dallas-Fort Worth metroplex and surrounding areas including Carrollton, Plano, Frisco, McKinney, Allen, Richardson, Garland, Murphy, Irving, Arlington, and more. Contact us to confirm service availability in your area."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Do you provide warranties on countertop installation?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Yes, we provide a craftsmanship warranty on all installations. Material warranties vary by manufacturer and stone type. Full warranty details are included with every project estimate."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Can I visit your showroom to see countertop samples?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Yes! Visit our showroom at 1225 W College Ave, Suite #616, Carrollton, TX 75006. We have 1000+ slab samples of granite, quartz, marble, and quartzite. Open Monday-Friday 8am-6pm and Saturday 9am-2pm."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "What edge profiles are available for my countertops?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "We offer 25+ edge profiles including eased, beveled, bullnose, half bullnose, ogee, dupont, laminated, and waterfall edges. Use our free Edge Visualizer tool online or visit our showroom to see samples in person."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Do you offer countertop remnants for small projects?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Yes, we maintain a large inventory of granite and quartz remnants at up to 60% off regular prices. Perfect for bathroom vanities, bar tops, laundry rooms, and small kitchen projects. Inventory updates weekly."
            ]
        ]
    ]
];

echo '<script type="application/ld+json">' .
    json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) .
    '</script>' . PHP_EOL;

// ═══════════════════════════════════════
//  LocalBusiness + Reviews Schema
// ═══════════════════════════════════════
$reviewSchema = [
    "@context" => "https://schema.org",
    "@type" => "LocalBusiness",
    "@id" => "$DOMAIN/#localbusiness",
    "name" => "Texas Specialized Quartz & Granite",
    "image" => [
        "$DOMAIN/images/countertop.png",
        "$DOMAIN/images/countertop (1).png",
        "$DOMAIN/images/countertop (2).png"
    ],
    "description" => "Premium granite, quartz & marble countertop fabrication and installation across Texas. Expert craftsmanship, competitive pricing & free estimates.",
    "url" => $DOMAIN,
    "telephone" => "+1-469-814-0555",
    "email" => "Cs@TexasSpecializedQuartz.com",
    "priceRange" => "$$",
    "currenciesAccepted" => "USD",
    "paymentAccepted" => "Cash, Credit Card, Check, Financing",
    "address" => [
        "@type" => "PostalAddress",
        "streetAddress" => "10830 Composite Dr. Dallas Tx",
        "addressLocality" => "Carrollton",
        "addressRegion" => "TX",
        "postalCode" => "75220",
        "addressCountry" => "US"
    ],
    "geo" => [
        "@type" => "GeoCoordinates",
        "latitude" => "32.9537",
        "longitude" => "-96.8903"
    ],
    "openingHoursSpecification" => [
        [
            "@type" => "OpeningHoursSpecification",
            "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
            "opens" => "08:00",
            "closes" => "18:00"
        ],
        [
            "@type" => "OpeningHoursSpecification",
            "dayOfWeek" => ["Saturday"],
            "opens" => "09:00",
            "closes" => "14:00"
        ]
    ],
    "aggregateRating" => [
        "@type" => "AggregateRating",
        "ratingValue" => "4.9",
        "reviewCount" => "200",
        "bestRating" => "5",
        "worstRating" => "1"
    ],
    "review" => [
        [
            "@type" => "Review",
            "author" => ["@type" => "Person", "name" => "Sarah Johnson"],
            "datePublished" => "2025-11-15",
            "reviewBody" => "Excellent craftsmanship and professional installation. Our kitchen looks amazing with the new granite countertops! The team was on time and cleaned up perfectly.",
            "reviewRating" => ["@type" => "Rating", "ratingValue" => "5", "bestRating" => "5"]
        ],
        [
            "@type" => "Review",
            "author" => ["@type" => "Person", "name" => "Michael Rodriguez"],
            "datePublished" => "2025-12-20",
            "reviewBody" => "Great service from consultation to installation. The team was punctual, professional, and the quartz countertops exceeded our expectations. Highly recommend!",
            "reviewRating" => ["@type" => "Rating", "ratingValue" => "5", "bestRating" => "5"]
        ],
        [
            "@type" => "Review",
            "author" => ["@type" => "Person", "name" => "Jennifer Williams"],
            "datePublished" => "2026-01-10",
            "reviewBody" => "Beautiful marble countertops for our master bathroom. The edge profile they recommended was perfect. Fair pricing and fast turnaround. Will use again!",
            "reviewRating" => ["@type" => "Rating", "ratingValue" => "5", "bestRating" => "5"]
        ]
    ]
];

echo '<script type="application/ld+json">' .
    json_encode($reviewSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) .
    '</script>' . PHP_EOL;

// ═══════════════════════════════════════
//  Service Schema
// ═══════════════════════════════════════
$serviceSchema = [
    "@context" => "https://schema.org",
    "@type" => "Service",
    "serviceType" => "Countertop Fabrication & Installation",
    "provider" => ["@id" => "$DOMAIN/#localbusiness"],
    "areaServed" => [
        [
            "@type" => "City",
            "name" => "Carrollton",
            "containedInPlace" => ["@type" => "State", "name" => "Texas"]
        ],
        [
            "@type" => "City",
            "name" => "Dallas"
        ],
        [
            "@type" => "City",
            "name" => "Plano"
        ],
        [
            "@type" => "City",
            "name" => "Frisco"
        ],
        [
            "@type" => "City",
            "name" => "McKinney"
        ],
        [
            "@type" => "City",
            "name" => "Richardson"
        ],
        [
            "@type" => "City",
            "name" => "Garland"
        ],
        [
            "@type" => "City",
            "name" => "Arlington"
        ],
        [
            "@type" => "City",
            "name" => "Irving"
        ],
        [
            "@type" => "City",
            "name" => "Fort Worth"
        ]
    ],
    "hasOfferCatalog" => [
        "@type" => "OfferCatalog",
        "name" => "Countertop Services",
        "itemListElement" => [
            [
                "@type" => "Offer",
                "itemOffered" => [
                    "@type" => "Service",
                    "name" => "Granite Countertop Fabrication & Installation",
                    "description" => "Custom granite countertop fabrication with CNC precision cutting and professional installation."
                ]
            ],
            [
                "@type" => "Offer",
                "itemOffered" => [
                    "@type" => "Service",
                    "name" => "Quartz Countertop Fabrication & Installation",
                    "description" => "Engineered quartz countertop fabrication and installation with seamless joints."
                ]
            ],
            [
                "@type" => "Offer",
                "itemOffered" => [
                    "@type" => "Service",
                    "name" => "Marble Countertop Fabrication & Installation",
                    "description" => "Premium marble countertop fabrication, installation and professional sealing."
                ]
            ],
            [
                "@type" => "Offer",
                "itemOffered" => [
                    "@type" => "Service",
                    "name" => "Countertop Repair, Sealing & Maintenance",
                    "description" => "Professional stone countertop repair, chip fixing, re-sealing and polishing services."
                ]
            ],
            [
                "@type" => "Offer",
                "itemOffered" => [
                    "@type" => "Service",
                    "name" => "Free In-Home Measurement & Estimate",
                    "description" => "Complimentary in-home measurement, design consultation and project estimate."
                ]
            ]
        ]
    ]
];

echo '<script type="application/ld+json">' .
    json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) .
    '</script>' . PHP_EOL;