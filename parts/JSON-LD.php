<?php
// ═══════════════════════════════════════════════════════
//  Blog Articles Schema (JSON-LD) — texasspecializedquartz.com
//  URLs use slugs matching sitemap.xml
// ═══════════════════════════════════════════════════════

$DOMAIN = 'https://texasspecializedquartz.com';
$PUBLISHER_LOGO = "$DOMAIN/images/Granit-Img/logo-gg.jpg";
$PUBLISHER_NAME = 'Texas Specialized Quartz & Granite';

$currentUrl = $_SERVER['REQUEST_URI'];

if (strpos($currentUrl, 'blog') !== false || strpos($currentUrl, 'newsdetail') !== false) {

    $articles = [
        // ═══ 2026 Articles ═══
        [
            'title' => 'Find the Best Granite Countertop Fabricator Near You in Texas (2026)',
            'image' => "$DOMAIN/images/Granit-Img/BLOG19.png",
            'datePublished' => '2026-03-14T10:00:00-06:00',
            'dateModified' => '2026-03-14T10:00:00-06:00',
            'description' => 'Looking for granite countertops near you in Texas? Learn how to find the best local fabricator with expert tips on quality, pricing, and what to look for in 2026.',
            'slug' => 'granite-countertops-near-me-texas-find-best-local-fabricator-2026',
            'keywords' => 'granite countertops near me, granite fabricator Texas, local granite installer, countertop company near me, best granite fabricator 2026, Texas countertop shops'
        ],
        [
            'title' => 'Granite Countertops Cost in Texas 2026: Price Per Square Foot Breakdown',
            'image' => "$DOMAIN/images/Granit-Img/BLOG26.jpg",
            'datePublished' => '2026-02-28T10:00:00-06:00',
            'dateModified' => '2026-02-28T10:00:00-06:00',
            'description' => 'How much do granite countertops cost in Texas in 2026? Complete price per square foot breakdown including fabrication, installation, and material grades.',
            'slug' => 'granite-countertops-cost-texas-2026-price-per-square-foot',
            'keywords' => 'granite countertops cost Texas, granite price per square foot, countertop prices 2026, affordable granite Texas, granite installation cost, countertop budget guide'
        ],
        [
            'title' => 'Granite Countertop Installation Cost 2026: Full Breakdown for Texas Homes',
            'image' => "$DOMAIN/images/Granit-Img/BLOG24.png",
            'datePublished' => '2026-02-19T10:00:00-06:00',
            'dateModified' => '2026-02-19T10:00:00-06:00',
            'description' => 'Full cost breakdown for granite countertop installation in Texas homes in 2026. Learn about material costs, labor, edge profiles, and how to save money on your project.',
            'slug' => 'granite-countertop-installation-cost-2026-full-breakdown-texas-homes',
            'keywords' => 'granite installation cost, countertop installation price Texas, granite labor cost, countertop cost breakdown, kitchen renovation cost 2026, granite project budget'
        ],
        [
            'title' => 'How to Find the Best Local Countertop Fabricator in Texas (2026 Guide)',
            'image' => "$DOMAIN/images/Granit-Img/BLOG22.png",
            'datePublished' => '2026-02-09T10:00:00-06:00',
            'dateModified' => '2026-02-09T10:00:00-06:00',
            'description' => 'Step-by-step guide to finding the best countertop fabricator near you in Texas. What to look for, questions to ask, and red flags to avoid in 2026.',
            'slug' => 'granite-countertop-near-me-find-best-local-fabricator-texas-2026-guide',
            'keywords' => 'countertop fabricator near me, best granite company Texas, how to choose fabricator, countertop installer guide, granite shop near me, Texas stone fabricator'
        ],
        [
            'title' => 'Texas Granite Countertops 2026: A Smart Home Investment Beyond Aesthetics',
            'image' => "$DOMAIN/images/Granit-Img/BLOG23.png",
            'datePublished' => '2026-01-28T10:00:00-06:00',
            'dateModified' => '2026-01-28T10:00:00-06:00',
            'description' => 'Why granite countertops are more than just beautiful surfaces. Discover how they increase home value, reduce maintenance costs, and provide lasting ROI for Texas homeowners.',
            'slug' => 'texas-granite-countertops-2026-beyond-aesthetics-smart-home-investment',
            'keywords' => 'granite countertops investment, home value granite, ROI countertops, Texas home improvement, granite benefits, smart home upgrades 2026'
        ],
        [
            'title' => 'Engineered Granite vs Natural Granite: Which Is Better for Texas Kitchens?',
            'image' => "$DOMAIN/images/Granit-Img/BLOG20.png",
            'datePublished' => '2026-01-08T10:00:00-06:00',
            'dateModified' => '2026-01-08T10:00:00-06:00',
            'description' => 'Compare engineered granite vs natural granite for Texas kitchens. Durability, cost, maintenance, and appearance differences explained to help you make the right choice.',
            'slug' => 'engineered-granite-vs-natural-granite-better-texas-kitchens-2026',
            'keywords' => 'engineered granite vs natural granite, granite comparison, Texas kitchen countertops, natural stone vs engineered, granite durability, kitchen material guide'
        ],

        // ═══ 2025 Articles (Dec–Oct) ═══
        [
            'title' => 'Granite vs Quartz Countertops in Texas: Best Choice for Your Kitchen in 2026',
            'image' => "$DOMAIN/images/Granit-Img/BLOG20.png",
            'datePublished' => '2025-12-28T10:00:00-06:00',
            'dateModified' => '2025-12-28T10:00:00-06:00',
            'description' => 'Granite vs quartz countertops — which is the better choice for Texas kitchens? Compare cost, durability, maintenance, heat resistance, and style to make an informed decision.',
            'slug' => 'granite-vs-quartz-countertops-texas-best-choice-kitchen-2026',
            'keywords' => 'granite vs quartz Texas, countertop comparison, best kitchen countertop, quartz vs granite cost, heat resistant countertops, Texas kitchen renovation'
        ],
        [
            'title' => 'How Much Do Granite Countertops Cost in Texas? 2025 Price Guide',
            'image' => "$DOMAIN/images/Granit-Img/BLOG26.jpg",
            'datePublished' => '2025-12-20T10:00:00-06:00',
            'dateModified' => '2025-12-20T10:00:00-06:00',
            'description' => 'Complete 2025 pricing guide for granite countertops in Texas. Average costs per square foot, factors that affect price, and tips to get the best deal.',
            'slug' => 'how-much-granite-countertops-cost-texas-2025-price-per-square-foot',
            'keywords' => 'granite countertops cost, Texas granite prices, price per square foot, countertop pricing guide, affordable granite, budget countertops Texas'
        ],
        [
            'title' => 'Essential Countertop Buying Tips Every Homeowner Should Know (2025)',
            'image' => "$DOMAIN/images/Granit-Img/BLOG24.png",
            'datePublished' => '2025-12-08T10:00:00-06:00',
            'dateModified' => '2025-12-08T10:00:00-06:00',
            'description' => 'Before upgrading your countertops, read these essential buying tips. Material selection, measurement mistakes, hidden costs, and contractor vetting advice for 2025.',
            'slug' => 'essential-countertop-buying-tips-2025-homeowners-know-before-upgrading',
            'keywords' => 'countertop buying tips, kitchen renovation advice, granite buying guide, countertop mistakes, how to buy countertops, homeowner tips 2025'
        ],
        [
            'title' => 'How to Choose the Perfect Countertop Fabricator in Texas (2025 Guide)',
            'image' => "$DOMAIN/images/Granit-Img/BLOG22.png",
            'datePublished' => '2025-12-01T10:00:00-06:00',
            'dateModified' => '2025-12-01T10:00:00-06:00',
            'description' => 'Find the perfect countertop fabricator in Texas with this comprehensive guide. Learn what certifications to look for, questions to ask, and how to compare quotes.',
            'slug' => 'how-choose-perfect-countertop-fabricator-texas-2025-guide',
            'keywords' => 'countertop fabricator Texas, choose granite installer, fabricator comparison, countertop contractor tips, Texas stone companies, granite fabrication guide'
        ],
        [
            'title' => 'Top 10 Mistakes to Avoid When Choosing Granite Countertops (2025)',
            'image' => "$DOMAIN/images/Granit-Img/BLOG23.png",
            'datePublished' => '2025-11-22T10:00:00-06:00',
            'dateModified' => '2025-11-22T10:00:00-06:00',
            'description' => 'Avoid costly countertop mistakes with our expert guide. From choosing the wrong material grade to skipping sealing — learn the top 10 granite countertop pitfalls.',
            'slug' => 'top-10-mistakes-avoid-when-choosing-granite-countertops-2025',
            'keywords' => 'granite countertop mistakes, countertop buying errors, avoid bad granite, kitchen renovation mistakes, granite selection tips, countertop pitfalls'
        ],
        [
            'title' => 'Best Granite Colors for Modern Kitchens in 2025',
            'image' => "$DOMAIN/images/Granit-Img/BLOG23.png",
            'datePublished' => '2025-10-28T10:00:00-06:00',
            'dateModified' => '2025-10-28T10:00:00-06:00',
            'description' => 'Discover the most popular granite colors trending in modern kitchens for 2025. From warm whites to bold blacks and exotic veining patterns.',
            'slug' => 'best-granite-colors-modern-kitchens-2025',
            'keywords' => 'granite colors 2025, modern kitchen granite, trending granite colors, white granite, black granite, exotic granite, kitchen design colors'
        ],
        [
            'title' => 'Granite Maintenance Tips: Keep Your Countertops Looking New for Years',
            'image' => "$DOMAIN/images/Granit-Img/BLOG24.png",
            'datePublished' => '2025-10-25T10:00:00-06:00',
            'dateModified' => '2025-10-25T10:00:00-06:00',
            'description' => 'Expert granite maintenance tips to keep your countertops looking brand new. Learn about sealing schedules, daily cleaning, stain removal, and scratch prevention.',
            'slug' => 'granite-maintenance-tips-keep-countertops-looking-new-years',
            'keywords' => 'granite maintenance, countertop care tips, how to seal granite, granite cleaning, stain removal granite, granite countertop care guide'
        ],
        [
            'title' => 'Hidden Benefits of Granite Countertops You Never Knew',
            'image' => "$DOMAIN/images/Granit-Img/BLOG22.png",
            'datePublished' => '2025-10-14T10:00:00-06:00',
            'dateModified' => '2025-10-14T10:00:00-06:00',
            'description' => 'Beyond beauty — discover the hidden benefits of granite countertops including antibacterial properties, heat resistance, increased home value, and eco-friendliness.',
            'slug' => 'hidden-benefits-granite-countertops-you-never-knew',
            'keywords' => 'granite benefits, granite countertop advantages, why choose granite, granite home value, antibacterial countertops, eco friendly stone'
        ],
        [
            'title' => 'Countertop Edge Profiles: Complete Guide with Pros & Cons',
            'image' => "$DOMAIN/images/Granit-Img/BLOG20.png",
            'datePublished' => '2025-10-14T09:00:00-06:00',
            'dateModified' => '2025-10-14T09:00:00-06:00',
            'description' => 'Complete guide to countertop edge profiles with photos, pros, cons, and cost comparisons. Bullnose, ogee, beveled, waterfall, and 20+ more edge styles explained.',
            'slug' => 'countertop-edge-profiles-complete-guide-pros-cons',
            'keywords' => 'countertop edge profiles, granite edges guide, bullnose edge, ogee edge, waterfall edge, edge profile comparison, countertop edge cost'
        ],

        // ═══ 2025 Articles (Aug–Jul) ═══
        [
            'title' => 'Outdoor Kitchen Countertops: Top Weather-Resistant Materials',
            'image' => "$DOMAIN/images/Granit-Img/BLOG19.png",
            'datePublished' => '2025-08-31T10:00:00-06:00',
            'dateModified' => '2025-08-31T10:00:00-06:00',
            'description' => 'Best materials for outdoor kitchen countertops in Texas heat. Compare granite, quartzite, concrete, and porcelain for weather resistance, UV stability, and durability.',
            'slug' => 'outdoor-kitchen-countertops-top-weather-resistant-materials',
            'keywords' => 'outdoor kitchen countertops, weather resistant countertops, outdoor granite, Texas outdoor kitchen, heat resistant stone, UV stable countertops'
        ],
        [
            'title' => 'Top Stain & Scratch Resistant Countertops for 2025',
            'image' => "$DOMAIN/images/Granit-Img/BLOG24.png",
            'datePublished' => '2025-08-31T09:00:00-06:00',
            'dateModified' => '2025-08-31T09:00:00-06:00',
            'description' => 'Which countertop materials are the most stain and scratch resistant? Compare granite, quartz, quartzite, and porcelain for durability in busy Texas kitchens.',
            'slug' => 'top-stain-scratch-resistant-countertops-2025',
            'keywords' => 'scratch resistant countertops, stain proof countertops, durable kitchen surfaces, best countertop material, granite durability, quartz scratch resistance'
        ],
        [
            'title' => 'Granite Finishes Explained: Polished vs Honed vs Leathered',
            'image' => "$DOMAIN/images/Granit-Img/BLOG22.png",
            'datePublished' => '2025-08-31T08:00:00-06:00',
            'dateModified' => '2025-08-31T08:00:00-06:00',
            'description' => 'Understand the differences between polished, honed, and leathered granite finishes. Appearance, maintenance, slip resistance, and which finish is best for your space.',
            'slug' => 'granite-finishes-explained-polished-vs-honed-vs-leathered',
            'keywords' => 'granite finishes, polished granite, honed granite, leathered granite, granite finish comparison, matte granite, textured granite surface'
        ],
        [
            'title' => 'Mixing Materials: Combining Granite, Quartz & Wood in Modern Kitchens',
            'image' => "$DOMAIN/images/Granit-Img/BLOG23.png",
            'datePublished' => '2025-08-31T07:00:00-06:00',
            'dateModified' => '2025-08-31T07:00:00-06:00',
            'description' => 'How to mix granite, quartz, and wood countertops in a modern kitchen design. Expert tips for combining materials, matching colors, and creating visual contrast.',
            'slug' => 'mixing-materials-combining-granite-quartz-wood-modern-kitchens',
            'keywords' => 'mixing countertop materials, granite and wood kitchen, two tone countertops, modern kitchen design, mixed materials kitchen, granite quartz combination'
        ],
        [
            'title' => 'Granite & Marble Prices 2025: How to Choose According to Your Budget',
            'image' => "$DOMAIN/images/Granit-Img/BLOG20.png",
            'datePublished' => '2025-08-31T06:00:00-06:00',
            'dateModified' => '2025-08-31T06:00:00-06:00',
            'description' => 'Compare granite and marble countertop prices for 2025. Budget-friendly options, premium selections, and how to get the most value for your countertop investment.',
            'slug' => 'granite-marble-prices-2025-how-choose-according-budget',
            'keywords' => 'granite prices 2025, marble countertop cost, budget countertops, affordable granite, premium marble, countertop price comparison'
        ],
        [
            'title' => 'Save Big: Affordable Granite Remnants in Texas',
            'image' => "$DOMAIN/images/Granit-Img/BLOG26.jpg",
            'datePublished' => '2025-08-22T10:00:00-06:00',
            'dateModified' => '2025-08-22T10:00:00-06:00',
            'description' => 'Save up to 60% on countertops with granite remnant slabs in Texas. Perfect for bathroom vanities, bar tops, and small kitchen projects. Updated inventory weekly.',
            'slug' => 'save-big-affordable-granite-remnants-texas',
            'keywords' => 'granite remnants Texas, cheap granite slabs, discount countertops, remnant deals, affordable granite, small project granite, budget stone Texas'
        ],
        [
            'title' => 'Black Granite Countertops: Luxury, Durability & Bold Kitchen Design',
            'image' => "$DOMAIN/images/Granit-Img/BLOG26.jpg",
            'datePublished' => '2025-08-15T10:00:00-06:00',
            'dateModified' => '2025-08-15T10:00:00-06:00',
            'description' => 'Black granite countertops bring luxury and bold character to any kitchen. Explore popular types like Absolute Black, Black Galaxy, and Impala Black with design ideas.',
            'slug' => 'black-granite-countertops',
            'keywords' => 'black granite countertops, absolute black granite, black galaxy granite, impala black granite, dark kitchen countertops, luxury granite, modern kitchen design'
        ],
        [
            'title' => 'Transform Your Home with Premium Granite Countertops — Stylish, Durable & Custom-Made',
            'image' => "$DOMAIN/images/Granit-Img/BLOG19.png",
            'datePublished' => '2025-08-03T10:00:00-06:00',
            'dateModified' => '2025-08-03T10:00:00-06:00',
            'description' => 'Discover durable and elegant granite countertops custom-made for your kitchen or bathroom. Premium quality surfaces designed to elevate your home aesthetic and functionality.',
            'slug' => 'transform-home-premium-granite-countertops-stylish-durable-custom-made',
            'keywords' => 'premium granite countertops, custom granite kitchen, durable countertops, granite fabrication Texas, stylish kitchen surfaces, custom stone countertops'
        ],
        [
            'title' => 'Transform Your Home with Stunning & Durable Granite Countertops',
            'image' => "$DOMAIN/images/Granit-Img/GraniteCountertops.jpg",
            'datePublished' => '2025-08-03T09:00:00-06:00',
            'dateModified' => '2025-08-03T09:00:00-06:00',
            'description' => 'Upgrade your home with high-quality granite countertops that blend style, strength, and value. Ideal for kitchens and bathrooms, custom-built to last.',
            'slug' => 'transform-home-stunning-durable-granite-countertops',
            'keywords' => 'granite countertops, durable kitchen surfaces, home upgrade granite, granite installation, custom countertops, natural stone kitchen'
        ],
        [
            'title' => 'Custom Bathroom Countertops in Murphy & Garland, TX',
            'image' => "$DOMAIN/images/Granit-Img/BLOG.png",
            'datePublished' => '2025-08-03T08:00:00-06:00',
            'dateModified' => '2025-08-03T08:00:00-06:00',
            'description' => 'Custom granite bathroom countertops designed for durability and moisture resistance. Expert installation for homes in Murphy, Garland, and surrounding TX areas.',
            'slug' => 'custom-bathroom-countertops-murphy-garland-tx',
            'keywords' => 'bathroom countertops Murphy TX, granite bathroom Garland TX, custom bathroom countertops, moisture resistant granite, bathroom renovation Texas'
        ],
        [
            'title' => 'Granite Kitchen Countertops: Durable, Stylish & Custom-Made Solutions',
            'image' => "$DOMAIN/images/Granit-Img/BLOG (1).png",
            'datePublished' => '2025-07-06T10:00:00-06:00',
            'dateModified' => '2025-07-06T10:00:00-06:00',
            'description' => 'Upgrade your kitchen with premium custom granite countertops built for style, durability, and lasting value. Expert fabrication and installation in Texas.',
            'slug' => 'granite-kitchen-countertops-durable-stylish-custom-made-solutions',
            'keywords' => 'granite kitchen countertops, custom granite fabrication, durable kitchen surfaces, kitchen remodeling Texas, natural stone countertops, granite installation'
        ],
        [
            'title' => 'Granite vs Quartz: Choosing the Perfect Countertop for Your Home',
            'image' => "$DOMAIN/images/Granit-Img/BLOG20.png",
            'datePublished' => '2025-07-03T10:00:00-06:00',
            'dateModified' => '2025-07-03T10:00:00-06:00',
            'description' => 'Compare granite vs quartz countertops in this detailed guide. Durability, appearance, maintenance, cost, and which surface suits your lifestyle best.',
            'slug' => 'granite-vs-quartz-choosing-perfect-countertop',
            'keywords' => 'granite vs quartz, countertop comparison, quartz countertops, granite countertops, engineered stone, natural stone, kitchen countertop guide'
        ],
        [
            'title' => 'Top 5 Modern Countertop Trends You\'ll See Everywhere in 2025',
            'image' => "$DOMAIN/images/Granit-Img/BLOG22.png",
            'datePublished' => '2025-07-03T09:00:00-06:00',
            'dateModified' => '2025-07-03T09:00:00-06:00',
            'description' => 'Discover the latest countertop trends for 2025 — bold quartz, eco materials, matte finishes, waterfall edges, and more. Design inspiration for your next project.',
            'slug' => 'top-5-modern-countertop-trends-youll-see-everywhere-2025',
            'keywords' => 'countertop trends 2025, modern countertops, kitchen design trends, waterfall edge, matte countertops, eco friendly countertops, quartz trends'
        ],
        [
            'title' => 'The 2025 Ultimate Kitchen Countertop Color Guide: Trending Shades',
            'image' => "$DOMAIN/images/Granit-Img/BLOG23.png",
            'datePublished' => '2025-07-03T08:00:00-06:00',
            'dateModified' => '2025-07-03T08:00:00-06:00',
            'description' => 'Explore trending kitchen countertop colors for 2025 — warm neutrals, bold blacks, soft pastels, and two-tone combinations. Your ultimate color selection guide.',
            'slug' => '2025-ultimate-kitchen-countertop-color-guide-trending-shades',
            'keywords' => 'countertop colors 2025, kitchen color trends, trending countertop shades, white countertops, black countertops, two tone kitchen, modern kitchen colors'
        ],
        [
            'title' => 'White Granite Countertops: Timeless Elegance & Lasting Durability',
            'image' => "$DOMAIN/images/Granit-Img/BLOG24.png",
            'datePublished' => '2025-07-03T07:00:00-06:00',
            'dateModified' => '2025-07-03T07:00:00-06:00',
            'description' => 'White granite countertops offer timeless elegance for any kitchen. Explore popular varieties, maintenance tips, and design ideas for a bright, luxurious space.',
            'slug' => 'white-granite-countertops-timeless-elegance-lasting-durability',
            'keywords' => 'white granite countertops, white kitchen countertops, Alaska White granite, Kashmir White granite, bright kitchen design, elegant countertops, white stone surfaces'
        ],
    ];


    // ═══════════════════════════════════════
    //  Determine which article to render
    // ═══════════════════════════════════════

    // Check if we're on a specific article page (by slug in URL)
    $currentSlug = null;
    if (preg_match('/newsdetail\/([a-z0-9\-]+)/i', $currentUrl, $matches)) {
        $currentSlug = $matches[1];
    }

    if ($currentSlug) {
        // ── Single article page: only output that article's schema ──
        foreach ($articles as $article) {
            if ($article['slug'] === $currentSlug) {
                $jsonLd = [
                    "@context" => "https://schema.org",
                    "@type" => "BlogPosting",
                    "mainEntityOfPage" => [
                        "@type" => "WebPage",
                        "@id" => "$DOMAIN/newsdetail/{$article['slug']}"
                    ],
                    "headline" => strip_tags($article['title']),
                    "image" => [
                        "@type" => "ImageObject",
                        "url" => $article['image'],
                        "width" => 1200,
                        "height" => 630
                    ],
                    "author" => [
                        "@type" => "Organization",
                        "name" => $PUBLISHER_NAME,
                        "@id" => "$DOMAIN/#organization"
                    ],
                    "publisher" => [
                        "@type" => "Organization",
                        "name" => $PUBLISHER_NAME,
                        "@id" => "$DOMAIN/#organization",
                        "logo" => [
                            "@type" => "ImageObject",
                            "url" => $PUBLISHER_LOGO,
                            "width" => 300,
                            "height" => 100
                        ]
                    ],
                    "datePublished" => $article['datePublished'],
                    "dateModified" => $article['dateModified'],
                    "description" => $article['description'],
                    "url" => "$DOMAIN/newsdetail/{$article['slug']}",
                    "inLanguage" => "en-US",
                    "keywords" => $article['keywords'],
                    "isPartOf" => [
                        "@type" => "Blog",
                        "@id" => "$DOMAIN/blog",
                        "name" => "Texas Specialized Quartz Blog",
                        "publisher" => ["@id" => "$DOMAIN/#organization"]
                    ]
                ];

                echo '<script type="application/ld+json">' .
                    json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) .
                    '</script>' . PHP_EOL;
                break;
            }
        }
    } else {
        // ── Blog listing page: output Blog schema with all articles ──
        $blogPosts = [];
        foreach ($articles as $index => $article) {
            $blogPosts[] = [
                "@type" => "BlogPosting",
                "position" => $index + 1,
                "headline" => strip_tags($article['title']),
                "url" => "$DOMAIN/newsdetail/{$article['slug']}",
                "datePublished" => $article['datePublished'],
                "image" => $article['image'],
                "description" => $article['description']
            ];
        }

        $blogSchema = [
            "@context" => "https://schema.org",
            "@type" => "Blog",
            "@id" => "$DOMAIN/blog",
            "name" => "Texas Specialized Quartz Blog — Countertop Tips, Trends & Guides",
            "description" => "Expert articles on countertop design trends, maintenance tips, material comparisons & installation guides by Texas stone professionals.",
            "url" => "$DOMAIN/blog",
            "publisher" => [
                "@type" => "Organization",
                "name" => $PUBLISHER_NAME,
                "@id" => "$DOMAIN/#organization",
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => $PUBLISHER_LOGO
                ]
            ],
            "inLanguage" => "en-US",
            "blogPost" => $blogPosts
        ];

        echo '<script type="application/ld+json">' .
            json_encode($blogSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) .
            '</script>' . PHP_EOL;
    }
}