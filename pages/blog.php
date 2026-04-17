<style>
    .btn-primary{
        background: #7a7a7a;
        border: 1px solid #7a7a7a;
    }
     .btn-primary:hover{
            background: #7a7a7a;
        border: 1px solid #7a7a7a;
     }
</style>
<?php
// بيانات المقالات
$posts = [
    [
        'title' => 'Get a Free Granite & Quartz Countertop Estimate in Dallas, Plano & Frisco (2026 Guide)',
        'slug' => 'free-granite-quartz-countertop-estimate-dallas-plano-frisco-2026',
        'id' => 34,
        'image' => 'images/Granit-Img/BLOG122.jpg',
        'alt' => 'Free Countertop Estimate Dallas Plano Frisco Texas Granite Quartz Quote 2026',
        'date' => 'Apr 16, 2026',
        'datetime' => '2026-04-20',
        'intro' => 'Looking for a free granite or quartz countertop estimate in Dallas, Plano, or Frisco? This 2026 guide explains how to get accurate quotes, what affects pricing, and how to avoid hidden costs when choosing a countertop company in Texas.',
        'tags' => [
            'free countertop estimate dallas',
            'granite quote plano tx',
            'quartz countertop estimate frisco',
            'countertop pricing texas',
            'get countertop quote near me',
            'countertop estimate cost texas'
        ]
    ],
    [
        'title' => 'Outdoor Granite & Quartz Countertops – Dallas, Plano & Frisco Guide',
        'slug' => 'outdoor-granite-quartz-countertops-dallas-plano-frisco-texas-2026',
        'id' => 33,
        'image' => 'images/Granit-Img/BLOG121.jpg',
        'alt' => 'Outdoor Granite Quartz Countertops Kitchen Dallas Plano Frisco Texas 2026',
        'date' => 'Apr 01, 2026',
        'datetime' => '2026-04-01',
        'intro' => 'Planning an outdoor kitchen in Dallas, Plano, or Frisco? Discover the best granite and quartz countertops for outdoor spaces in Texas. This 2026 guide covers heat-resistant materials, durability, pricing, and how to choose the right countertop fabricator for your backyard kitchen project.',
        'tags' => [
            'outdoor granite countertops dallas',
            'backyard kitchen countertops texas',
            'granite vs quartz outdoor kitchen',
            'outdoor kitchen installation dallas tx',
            'heat resistant countertops texas',
            'custom stone countertops plano frisco',
        ]
    ],
    [
        'title' => 'Best Granite Countertops Near Dallas, Plano & Frisco (2026 Local Guide)',
        'slug' => 'granite-countertops-near-me-texas-find-best-local-fabricator-2026',
        'id' => 32,
        'image' => 'images/Granit-Img/BLOG120.webp',
        'alt' => 'Granite Countertops Near Dallas Plano Frisco Texas 2026',
        'date' => 'Mar 14, 2026',
        'datetime' => '2026-03-14',
        'intro' => 'Looking for granite countertops near Dallas, Plano, or Frisco? In this 2026 local guide, we explain how Texas homeowners can find reliable granite fabricators, compare installation prices, evaluate stone quality, and choose the best countertop company for their kitchen renovation project.',
        'tags' => ['granite countertops near dallas', 'granite countertops plano tx', 'granite near frisco texas', 'granite fabricators texas', 'local granite companies dallas']
    ],
    [
        'title' => 'How Much Do Granite Countertops Cost in Texas 2026? Price Per Sq Ft Guide',
        'slug' => 'granite-countertops-cost-texas-2026-price-per-square-foot',
        'id' => 31,
        'image' => 'images/Granit-Img/BLOG119.webp',
        'alt' => 'Granite Countertops Cost Texas 2026 Price Per Square Foot Installation Guide',
        'date' => 'Mar 3, 2026',
        'datetime' => '2026-03-03',
        'intro' => 'Wondering how much granite countertops cost in Texas in 2026? This complete pricing guide breaks down the average cost per square foot, fabrication fees, installation charges, edge profiles, and hidden expenses homeowners should know before remodeling.',
        'tags' => ['granite countertops cost Texas', 'granite price per square foot', 'granite installation cost 2026', 'kitchen remodel cost Texas', 'affordable granite Texas']
    ],
    [
        'title' => 'Granite Countertop Installation Cost 2026: Full Breakdown for Texas Homes',
        'slug' => 'granite-countertop-installation-cost-2026-full-breakdown-texas-homes',
        'id' => 30,
        'image' => 'images/Granit-Img/BLOG118.webp',
        'alt' => 'Granite Countertop Installation Cost Texas 2026 Breakdown',
        'date' => 'Feb 19, 2026',
        'datetime' => '2026-02-19',
        'intro' => 'Planning a granite countertop installation and need to understand all costs involved? This 2026 guide provides a complete breakdown of installation expenses for Texas homeowners, including labor charges, template fees, sink cutouts, edge work, sealing costs, and hidden fees.',
        'tags' => ['granite installation cost', 'countertop installation price', 'labor cost granite', 'Texas installation fees', 'kitchen remodel budget']
    ],
    [
        'title' => 'Granite Countertop Near Me: How to Find the Best Local Fabricator in Texas (2026 Guide)',
        'slug' => 'granite-countertop-near-me-find-best-local-fabricator-texas-2026-guide',
        'id' => 29,
        'image' => 'images/Granit-Img/BLOG114.webp',
        'alt' => 'Find Best Local Granite Countertop Fabricator Near Me Texas 2026',
        'date' => 'Feb 9, 2026',
        'datetime' => '2026-02-09',
        'intro' => 'Searching "granite countertop near me" is just the first step. In 2026, Texas homeowners need a strategic approach to identify reputable fabricators who deliver precision craftsmanship, transparent pricing, and reliable installation.',
        'tags' => ['granite countertop near me', 'local granite fabricator Texas', 'find granite installer', 'countertop contractors near me', 'Texas kitchen remodel']
    ],
    [
        'title' => 'Texas Granite Countertops 2026: Beyond Aesthetics to Smart Home Investment',
        'slug' => 'texas-granite-countertops-2026-beyond-aesthetics-smart-home-investment',
        'id' => 28,
        'image' => 'images/Granit-Img/BLOG113.webp',
        'alt' => 'Smart Investment Granite Countertops Texas 2026 ROI Home Value',
        'date' => 'Jan 28, 2026',
        'datetime' => '2026-01-28',
        'intro' => 'Modern granite selection has evolved into a calculated investment decision that impacts property valuation, energy efficiency, and long-term maintenance costs. Explore how intelligent granite choices contribute to tangible financial returns and sustainable living.',
        'tags' => ['granite ROI Texas', 'smart countertop investment', 'property value granite', 'sustainable stone surfaces', 'home improvement ROI']
    ],
    [
        'title' => 'Engineered Granite vs Natural Granite: Which is Better for Texas Kitchens in 2026?',
        'slug' => 'engineered-granite-vs-natural-granite-better-texas-kitchens-2026',
        'id' => 27,
        'image' => 'images/Granit-Img/BLOG112.webp',
        'alt' => 'Engineered vs Natural Granite Comparison for Texas Kitchens 2026',
        'date' => 'Jan 8, 2026',
        'datetime' => '2026-01-08',
        'intro' => 'A new market has emerged for engineered granite that challenges natural stone in beauty and durability. This guide reveals the real differences in cost, environmental impact, design options, and maintenance ease for Texas homeowners.',
        'tags' => ['engineered granite', 'natural granite', 'reconstructed granite', 'eco-friendly kitchens', 'Texas countertop comparison']
    ],
    [
        'title' => 'Granite vs Quartz Countertops in Texas: Best Choice for Your Kitchen in 2026',
        'slug' => 'granite-vs-quartz-countertops-texas-best-choice-kitchen-2026',
        'id' => 26,
        'image' => 'images/Granit-Img/BLOG111.webp',
        'alt' => 'Granite vs Quartz Countertops Comparison Texas Kitchen 2026',
        'date' => 'Dec 28, 2025',
        'datetime' => '2025-12-28',
        'intro' => 'Confused between granite and quartz countertops for your Texas kitchen? This guide compares cost, maintenance, style options, and long-term value to help homeowners make the smartest choice for their remodel.',
        'tags' => ['granite vs quartz', 'countertop comparison', 'texas kitchen countertops', 'durable countertops', 'countertop material guide']
    ],
    [
        'title' => 'How Much Do Granite Countertops Cost in Texas? (2025 Price Per Square Foot)',
        'slug' => 'how-much-granite-countertops-cost-texas-2025-price-per-square-foot',
        'id' => 25,
        'image' => 'images/Granit-Img/BLOG110.jpg',
        'alt' => 'Granite Countertop Cost Per Square Foot Texas 2025 Price Guide',
        'date' => 'Dec 20, 2025',
        'datetime' => '2025-12-20',
        'intro' => 'Prices vary depending on stone quality, thickness, edge profiles, and installation complexity. We break down the average cost per square foot and smart tips to help homeowners choose high-quality granite while staying within budget.',
        'tags' => ['granite countertop cost texas', 'granite price per square foot', 'granite installation cost', 'affordable granite texas']
    ],
    [
        'title' => 'Essential Countertop Buying Tips for 2025: What Homeowners Should Know Before Upgrading',
        'slug' => 'essential-countertop-buying-tips-2025-homeowners-know-before-upgrading',
        'id' => 24,
        'image' => 'images/Granit-Img/BLOG109.webp',
        'alt' => 'Countertop Buying Tips 2025 for Texas Homeowners',
        'date' => 'Dec 8, 2025',
        'datetime' => '2025-12-08',
        'intro' => 'Before choosing granite, quartz, or marble, make sure you understand the key factors that affect durability, price, installation quality, and long-term value. This guide covers the most important tips to avoid costly regrets.',
        'tags' => ['countertop buying tips', 'countertop guide 2025', 'granite vs quartz tips', 'texas kitchen remodel', 'homeowner guide']
    ],
    [
        'title' => 'How to Choose the Perfect Countertop Fabricator in Texas (2025 Guide)',
        'slug' => 'how-choose-perfect-countertop-fabricator-texas-2025-guide',
        'id' => 23,
        'image' => 'images/Granit-Img/BLOG108.jpg',
        'alt' => 'Best Countertop Fabricator Selection Guide Texas 2025',
        'date' => 'Dec 1, 2025',
        'datetime' => '2025-12-01',
        'intro' => 'The quality of fabrication and installation determines how durable and beautiful your countertops will look. This guide walks you through certifications, portfolio quality, pricing transparency, and customer reviews.',
        'tags' => ['countertop fabricator texas', 'hire granite installer', 'quartz fabricator texas', 'best countertop companies texas']
    ],
    [
        'title' => 'Top 10 Mistakes to Avoid When Choosing Granite Countertops in 2025',
        'slug' => 'top-10-mistakes-avoid-when-choosing-granite-countertops-2025',
        'id' => 22,
        'image' => 'images/Granit-Img/BLOG107.jpg',
        'alt' => 'Granite Countertop Buying Mistakes to Avoid 2025',
        'date' => 'Nov 22, 2025',
        'datetime' => '2025-11-22',
        'intro' => 'Many homeowners make simple mistakes that cost them money, quality, and long-term satisfaction. Learn the most common granite buying mistakes and how to avoid them for a confident purchase.',
        'tags' => ['granite mistakes', 'avoid granite buying mistakes', 'granite countertop guide', 'kitchen remodel tips 2025']
    ],
    [
        'title' => 'Best Granite Colors for Modern Kitchens in 2025',
        'slug' => 'best-granite-colors-modern-kitchens-2025',
        'id' => 20,
        'image' => 'images/Granit-Img/BLOG104.jpg',
        'alt' => 'Best Granite Colors for Modern Kitchens 2025 Trending Shades',
        'date' => 'Nov 16, 2025',
        'datetime' => '2025-11-16',
        'intro' => 'From elegant white tones to bold dark shades, this guide covers the most trending and durable granite colors that enhance any contemporary kitchen design with expert tips on choosing the right shade.',
        'tags' => ['granite colors 2025', 'modern kitchen granite', 'trending granite', 'white granite', 'black granite']
    ],
    [
        'title' => 'Granite Maintenance Tips: Keep Your Countertops Looking New for Years',
        'slug' => 'granite-maintenance-tips-keep-countertops-looking-new-years',
        'id' => 19,
        'image' => 'images/Granit-Img/BLOG103.jpg',
        'alt' => 'Granite Countertop Maintenance and Cleaning Tips Guide',
        'date' => 'Nov 7, 2025',
        'datetime' => '2025-11-07',
        'intro' => 'Expert tips on daily cleaning, sealing schedules, stain prevention, and care routines to preserve your granite\'s natural shine and durability for years to come.',
        'tags' => ['granite maintenance', 'granite cleaning', 'countertop care', 'sealing granite', 'granite polish']
    ],
    [
        'title' => 'Hidden Benefits of Granite Countertops You Never Knew',
        'slug' => 'hidden-benefits-granite-countertops-you-never-knew',
        'id' => 18,
        'image' => 'images/Granit-Img/BLOG102.jpg',
        'alt' => 'Hidden Benefits of Granite Countertops Durability and Value',
        'date' => 'Oct 25, 2025',
        'datetime' => '2025-10-25',
        'intro' => 'Granite is more than just beautiful. Discover surprising benefits including antibacterial properties, heat resistance, increased home value, and eco-friendliness that make granite a smart investment.',
        'tags' => ['granite benefits', 'durable countertops', 'kitchen investment', 'heat resistant surfaces']
    ],
    [
        'title' => 'Countertop Edge Profiles: A Complete Guide with Pros and Cons',
        'slug' => 'countertop-edge-profiles-complete-guide-pros-cons',
        'id' => 17,
        'image' => 'images/Granit-Img/BLOG33.jpg',
        'alt' => 'Countertop Edge Profiles Guide Bullnose Ogee Waterfall 2025',
        'date' => 'Oct 14, 2025',
        'datetime' => '2025-10-14',
        'intro' => 'From classic eased edges to elegant ogee and modern waterfall styles, each profile affects both the look and functionality of your surface. This guide covers the most popular edge types with pros, cons, and cost comparisons.',
        'tags' => ['countertop edge profiles', 'granite edges', 'ogee edge', 'waterfall countertop', 'edge types guide']
    ],
    [
        'title' => 'Outdoor Kitchen Countertops: Top Weather-Resistant Materials',
        'slug' => 'outdoor-kitchen-countertops-top-weather-resistant-materials',
        'id' => 16,
        'image' => 'images/Granit-Img/BLOG32.jpg',
        'alt' => 'Outdoor Kitchen Countertops Weather Resistant Materials Texas',
        'date' => 'Oct 4, 2025',
        'datetime' => '2025-10-04',
        'intro' => 'Discover the best weather-resistant countertop materials for Texas outdoor kitchens including granite, concrete, and custom quartz — plus expert tips to keep them in great shape year-round.',
        'tags' => ['outdoor kitchen countertops', 'weather resistant countertops', 'granite outdoor', 'patio kitchen design']
    ],
    [
        'title' => 'Top Stain and Scratch Resistant Countertops in 2025',
        'slug' => 'top-stain-scratch-resistant-countertops-2025',
        'id' => 15,
        'image' => 'images/Granit-Img/BLOG31.jpg',
        'alt' => 'Best Stain and Scratch Resistant Countertops Comparison 2025',
        'date' => 'Sep 29, 2025',
        'datetime' => '2025-09-29',
        'intro' => 'Which countertop materials handle daily wear and tear best? Compare granite, quartz, quartzite, and porcelain for stain resistance and scratch durability in busy Texas kitchens.',
        'tags' => ['stain resistant countertops', 'scratch resistant countertops', 'granite durability', 'best countertops 2025']
    ],
    [
        'title' => 'Granite Finishes Explained: Polished vs Honed vs Leathered',
        'slug' => 'granite-finishes-explained-polished-vs-honed-vs-leathered',
        'id' => 14,
        'image' => 'images/Granit-Img/BLOG30.png',
        'alt' => 'Polished vs Honed vs Leathered Granite Finish Comparison',
        'date' => 'Sep 23, 2025',
        'datetime' => '2025-09-23',
        'intro' => 'Each granite finish offers unique benefits for appearance, maintenance, and durability. Explore polished, honed, and leathered finishes to find the perfect style for your kitchen or bathroom.',
        'tags' => ['polished granite', 'honed granite', 'leathered granite', 'granite finishes', 'granite surface types']
    ],
    [
        'title' => 'Mixing Materials: Combining Granite, Quartz, and Wood in Modern Kitchens',
        'slug' => 'mixing-materials-combining-granite-quartz-wood-modern-kitchens',
        'id' => 13,
        'image' => 'images/Granit-Img/BLOG29.webp',
        'alt' => 'Mixing Granite Quartz and Wood Countertops in Modern Kitchen Design',
        'date' => 'Sep 17, 2025',
        'datetime' => '2025-09-17',
        'intro' => 'Modern kitchens are embracing mixed materials to balance durability, elegance, and warmth. Discover why combining granite, quartz, and wood creates a unique design that enhances functionality.',
        'tags' => ['granite and quartz mix', 'wood kitchen countertops', 'mixed materials kitchen', 'modern kitchen design']
    ],
    [
        'title' => 'Granite and Marble Prices 2025: How to Choose According to Your Budget',
        'slug' => 'granite-marble-prices-2025-how-choose-according-budget',
        'id' => 12,
        'image' => 'images/Granit-Img/BLOG28.png',
        'alt' => 'Granite and Marble Countertop Prices 2025 Budget Guide',
        'date' => 'Sep 8, 2025',
        'datetime' => '2025-09-08',
        'intro' => 'A complete guide to current granite and marble prices with practical tips to choose the right material according to your budget and get the best value for your money.',
        'tags' => ['marble prices 2025', 'granite prices 2025', 'affordable countertops', 'countertop budget guide', 'granite vs marble cost']
    ],
    [
        'title' => 'Save Big with Affordable Granite Remnants in Texas',
        'slug' => 'save-big-affordable-granite-remnants-texas',
        'id' => 11,
        'image' => 'images/Granit-Img/BLOG27.jpg',
        'alt' => 'Affordable Granite Remnants for Sale in Texas Save Money',
        'date' => 'Aug 31, 2025',
        'datetime' => '2025-08-31',
        'intro' => 'Granite remnants are leftover pieces from larger projects that come at a fraction of the cost while offering the same durability and natural beauty. Learn how to use them for kitchens, bathrooms, and small projects.',
        'tags' => ['granite remnants', 'affordable granite countertops', 'cheap granite texas', 'discount countertops']
    ],
    [
        'title' => 'Black Granite Countertops: Luxury, Durability and Bold Kitchen Design',
        'slug' => 'black-granite-countertops',
        'id' => 10,
        'image' => 'images/Granit-Img/BLOG26.jpg',
        'alt' => 'Black Granite Countertops Absolute Black Galaxy Impala Kitchen',
        'date' => 'Jul 22, 2025',
        'datetime' => '2025-07-22',
        'intro' => 'Black granite countertops bring luxury and bold character to any space. Explore popular types like Absolute Black, Black Galaxy, and Impala Black with design ideas for your next kitchen upgrade.',
        'tags' => ['black granite countertops', 'absolute black granite', 'black galaxy granite', 'luxury granite']
    ],
    [
        'title' => 'White Granite Countertops: Timeless Elegance and Lasting Durability',
        'slug' => 'white-granite-countertops-timeless-elegance-lasting-durability',
        'id' => 9,
        'image' => 'images/Granit-Img/BLOG25.jpg',
        'alt' => 'White Granite Countertops Alaska White Kashmir White Kitchen',
        'date' => 'Jul 19, 2025',
        'datetime' => '2025-07-19',
        'intro' => 'White granite countertops combine natural beauty with durability and give your space a clean, modern feel. Explore top white granite options, design ideas, and tips for choosing the right style.',
        'tags' => ['white granite countertops', 'granite colors', 'countertop design', 'natural stone countertops']
    ],
    [
        'title' => 'The 2025 Ultimate Kitchen Countertop Color Guide: Trending Shades You\'ll Love',
        'slug' => '2025-ultimate-kitchen-countertop-color-guide-trending-shades',
        'id' => 7,
        'image' => 'images/Granit-Img/BLOG23.png',
        'alt' => 'Kitchen Countertop Color Trends 2025 Trending Shades Guide',
        'date' => 'Aug 6, 2025',
        'datetime' => '2025-08-06',
        'intro' => 'Explore the hottest countertop colors for 2025 — from bold marbles to soft earthy tones — and see which shades will transform your kitchen into a modern masterpiece.',
        'tags' => ['kitchen countertop colors 2025', 'countertop color trends', 'modern kitchen design', 'trending countertops']
    ],
    [
        'title' => 'Top 5 Modern Countertop Trends You\'ll See Everywhere in 2025',
        'slug' => 'top-5-modern-countertop-trends-youll-see-everywhere-2025',
        'id' => 6,
        'image' => 'images/Granit-Img/BLOG22.png',
        'alt' => 'Top 5 Countertop Design Trends 2025 Modern Kitchen',
        'date' => 'Aug 6, 2025',
        'datetime' => '2025-08-06',
        'intro' => 'Discover the hottest countertop trends for 2025 — from bold veining to sustainable surfaces — and get inspired to transform your space with modern elegance.',
        'tags' => ['countertop trends', 'kitchen design 2025', 'modern surfaces', 'sustainable countertops']
    ],
    [
        'title' => 'Granite vs Quartz: Choosing the Perfect Countertop for Your Home',
        'slug' => 'granite-vs-quartz-choosing-perfect-countertop',
        'id' => 5,
        'image' => 'images/Granit-Img/BLOG20.png',
        'alt' => 'Granite vs Quartz Countertop Comparison Guide Pros Cons',
        'date' => 'Aug 5, 2025',
        'datetime' => '2025-08-05',
        'intro' => 'A complete guide comparing granite and quartz countertops. Learn the pros, cons, and care tips to help you select the perfect surface for your kitchen or bathroom.',
        'tags' => ['granite vs quartz', 'countertops comparison', 'kitchen remodel', 'countertop guide']
    ],
    [
        'title' => 'Transform Your Home with Premium Granite Countertops: Stylish, Durable and Custom-Made',
        'slug' => 'transform-home-premium-granite-countertops-stylish-durable-custom-made',
        'id' => 1,
        'image' => 'images/Granit-Img/BLOG19.png',
        'alt' => 'Premium Custom Granite Countertops for Kitchen and Bathroom',
        'date' => 'Aug 3, 2025',
        'datetime' => '2025-08-03',
        'intro' => 'Upgrade your kitchen or bathroom with premium granite countertops — expertly crafted for unmatched durability, timeless beauty, and a perfect custom fit for your home.',
        'tags' => ['granite countertops', 'custom granite installation', 'premium countertops', 'natural stone']
    ],
    [
        'title' => 'Transform Your Home with Stunning and Durable Granite Countertops',
        'slug' => 'transform-home-stunning-durable-granite-countertops',
        'id' => 2,
        'image' => 'images/Granit-Img/GraniteCountertops.jpg',
        'alt' => 'Stunning Durable Granite Countertops Installation Texas',
        'date' => 'Aug 3, 2025',
        'datetime' => '2025-08-03',
        'intro' => 'Stunning granite countertops that combine timeless beauty, unmatched durability, and easy maintenance. Premium natural stone surfaces that add elegance and lasting performance to your home.',
        'tags' => ['granite countertops', 'natural stone countertops', 'granite installation', 'custom countertops']
    ],
    [
        'title' => 'Custom Bathroom Countertops in Murphy and Garland, TX',
        'slug' => 'custom-bathroom-countertops-murphy-garland-tx',
        'id' => 3,
        'image' => 'images/Granit-Img/BLOG.png',
        'alt' => 'Custom Granite Bathroom Countertops Murphy Garland Texas',
        'date' => 'Aug 3, 2025',
        'datetime' => '2025-08-03',
        'intro' => 'Granite bathroom countertops offering moisture resistance and elegant design, perfect for enhancing homes in Murphy, Garland, and surrounding Texas areas.',
        'tags' => ['bathroom countertops', 'granite Murphy TX', 'granite Garland TX', 'moisture resistant granite']
    ],
    [
        'title' => 'Upgrade Your Kitchen with Durable Quartz Countertops',
        'slug' => 'granite-kitchen-countertops-durable-stylish-custom-made-solutions',
        'id' => 4,
        'image' => 'images/Granit-Img/BLOG (1).png',
        'alt' => 'Durable Quartz Kitchen Countertops Custom Installation',
        'date' => 'Jul 6, 2025',
        'datetime' => '2025-07-06',
        'intro' => 'Quartz countertops combine durability, style, and low maintenance, making them an excellent choice for any modern kitchen upgrade.',
        'tags' => ['quartz countertops', 'kitchen countertops', 'durable quartz', 'custom countertops']
    ]
];

// Pagination
$limit = 6;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$start = ($page - 1) * $limit;
$totalPosts = count($posts);
$totalPages = ceil($totalPosts / $limit);
$page = min($page, $totalPages);
$currentPosts = array_slice($posts, $start, $limit);
?>

<header class="page-header" data-background="images/Granit-Img/ccccc.png" data-stellar-background-ratio="1.15">
    <div class="container ee">
        <h1>Countertop Insights — Design Trends, Care Tips &amp; Industry Excellence</h1>
        <p class="page-subtitle">Expert articles on granite countertops, quartz surfaces, design ideas, and maintenance tips from Texas stone professionals</p>
    </div>
</header>



<section class="blog" aria-label="Blog Articles">
    <div class="container">
        <div class="row">

            <!-- ═══ Main Content ═══ -->
            <div class="col-lg-8">

                <?php if ($totalPosts > 0): ?>
                <p class="text-muted mb-4" style="font-size:14px;">
                    Showing <?= $start + 1 ?>–<?= min($start + $limit, $totalPosts) ?> of <?= $totalPosts ?> articles
                </p>
                <?php endif; ?>

                <?php foreach ($currentPosts as $index => $post): ?>
                <article class="post" itemscope itemtype="https://schema.org/BlogPosting">
                    <meta itemprop="author" content="Texas Specialized Quartz &amp; Granite">
                    <meta itemprop="publisher" content="Texas Specialized Quartz &amp; Granite">
                    <meta itemprop="mainEntityOfPage" content="newsdetail/<?= $post['slug'] ?>">

                    <figure class="post-image">
                        <?php if ($index === 0 && $page === 1): ?>
                        <img src="<?= $post['image'] ?>" alt="<?= htmlspecialchars($post['alt'], ENT_QUOTES) ?>" width="800" height="450" itemprop="image" fetchpriority="high">
                        <?php else: ?>
                        <img src="<?= $post['image'] ?>" alt="<?= htmlspecialchars($post['alt'], ENT_QUOTES) ?>" width="800" height="450" loading="lazy" itemprop="image">
                        <?php endif; ?>
                    </figure>

                    <div class="post-content">
                        <h2 class="post-title" itemprop="headline">
                            <a href="newsdetail/<?= $post['slug'] ?>"><?= htmlspecialchars(strip_tags($post['title']), ENT_QUOTES) ?></a>
                        </h2>

                        <div class="post-author">
                            <time datetime="<?= $post['datetime'] ?>" itemprop="datePublished" style="font-size:13px;color:#6b7280;">
                                Published on: <?= $post['date'] ?>
                            </time>
                        </div>

                        <p class="post-intro" itemprop="description"><?= htmlspecialchars($post['intro'], ENT_QUOTES) ?></p>

                        <div class="post-tags" aria-label="Article tags" style="margin-top:10px;">
                            <?php foreach ($post['tags'] as $tag): ?>
                            <span class="badge" style="background:#f3f4f6;color:#4b5563;font-weight:500;font-size:12px;padding:4px 10px;margin:2px 2px;border-radius:4px;display:inline-block;"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>

                        <a href="newsdetail/<?= $post['slug'] ?>" class="btn btn-primary mt-3" aria-label="Read full article: <?= htmlspecialchars(strip_tags($post['title']), ENT_QUOTES) ?>">Read More</a>
                    </div>
                </article>
                <?php endforeach; ?>

                <!-- ═══ Pagination ═══ -->
                <?php if ($totalPages > 1): ?>
                <nav aria-label="Blog pagination " class="mt-4 mb-5">
                    <ul class="pagination justify-content-center container" style="list-style:none;padding:0;display:flex;gap:6px;">
                        <?php if ($page > 1): ?>
                        <li><a href="?page=<?= $page - 1 ?>" rel="prev" class="mx-1" aria-label="Previous page" style="padding:6px 14px;border:1px solid #ddd;border-radius:6px;text-decoration:none;color:#333;"> Prev</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i == $page): ?>
                            <li><strong class="mx-1" aria-current="page" style="padding:6px 14px;background:#333;color:#fff;border-radius:6px;display:inline-block;"><?= $i ?></strong></li>
                            <?php else: ?>
                            <li><a href="?page=<?= $i ?>" class="mx-1" style="padding:6px 14px;border:1px solid #ddd;border-radius:6px;text-decoration:none;color:#333;"><?= $i ?></a></li>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                        <li><a href="?page=<?= $page + 1 ?>" rel="next" class="mx-1" aria-label="Next page" style="padding:6px 14px;border:1px solid #ddd;border-radius:6px;text-decoration:none;color:#333;">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>

            </div>

            <!-- ═══ Sidebar ═══ -->
            <div class="col-lg-4">
                <aside class="sidebar" aria-label="Blog sidebar">

                    <div class="widget">
                        <h3 class="title" style="font-size:18px;">Popular Stone Types</h3>
                        <ul class="tags" style="list-style:none;padding:0;">
                            <li><a href="NaturalSlabs" title="View Black Galaxy Granite Slabs">Black Galaxy Granite</a></li>
                            <li><a href="NaturalSlabs" title="View Red Indian Granite Slabs">Red Indian Granite</a></li>
                            <li><a href="NaturalSlabs" title="View Rosa Pink Granite Slabs">Rosa Pink Granite</a></li>
                            <li><a href="QuartzSlabs" title="View Pure White Quartz Slabs">Pure White Quartz</a></li>
                            <li><a href="QuartzSlabs" title="View Grey Quartz Slabs">Grey Quartz</a></li>
                            <li><a href="QuartzSlabs" title="View Calacatta Quartz Slabs">Calacatta Quartz</a></li>
                            <li><a href="NaturalSlabs" title="View Carrara Marble Slabs">Carrara Marble</a></li>
                            <li><a href="NaturalSlabs" title="View Crema Marfil Marble Slabs">Crema Marfil Marble</a></li>
                            <li><a href="NaturalSlabs" title="View Emperador Marble Slabs">Emperador Marble</a></li>
                        </ul>
                    </div>

                    <div class="widget">
                        <h3 class="title" style="font-size:18px;">Recent Projects</h3>
                        <ul class="side-gallery" style="list-style:none;padding:0;">
                            <li><a href="images/Granit-Img/countertop.png" data-fancybox="projects" aria-label="View granite countertop project 1"><img src="images/Granit-Img/countertop.png" alt="White granite kitchen countertop installation in Texas" width="150" height="150" loading="lazy"></a></li>
                            <li><a href="images/Granit-Img/countertop (3).png" data-fancybox="projects" aria-label="View granite countertop project 2"><img src="images/Granit-Img/countertop (3).png" alt="Modern quartz countertop with waterfall edge" width="150" height="150" loading="lazy"></a></li>
                            <li><a href="images/Granit-Img/countertop2.png" data-fancybox="projects" aria-label="View granite countertop project 3"><img src="images/Granit-Img/countertop2.png" alt="Custom granite island countertop fabrication" width="150" height="150" loading="lazy"></a></li>
                            <li><a href="images/Granit-Img/countertop (4).png" data-fancybox="projects" aria-label="View granite countertop project 4"><img src="images/Granit-Img/countertop (4).png" alt="Dark granite bathroom vanity countertop" width="150" height="150" loading="lazy"></a></li>
                            <li><a href="images/Granit-Img/countertop (5).png" data-fancybox="projects" aria-label="View granite countertop project 5"><img src="images/Granit-Img/countertop (5).png" alt="Marble countertop with ogee edge profile" width="150" height="150" loading="lazy"></a></li>
                            <li><a href="images/Granit-Img/countertop3.png" data-fancybox="projects" aria-label="View granite countertop project 6"><img src="images/Granit-Img/countertop3.png" alt="Quartz kitchen countertop professional installation" width="150" height="150" loading="lazy"></a></li>
                        </ul>
                    </div>

                    <div class="widget">
                        <h3 class="title" style="font-size:18px;">Need Help Choosing?</h3>
                        <p style="font-size:14px;color:#555;line-height:1.6;">Not sure which countertop material is right for you? Our experts are here to help with free consultations and estimates.</p>
                        <a href="contact" class="btn btn-primary" style="width:100%;text-align:center;margin-top:10px;">Get Free Quote</a>
                    </div>

                </aside>
            </div>

        </div>
    </div>
</section>