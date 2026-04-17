<?php
include './BusinessPortal/partials/conn.php';

$stmt = $pdo->prepare("SELECT * FROM products ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$DOMAIN = 'https://texasspecializedquartz.com';
?>
<style>
    .sales-offices{padding-top:60px;padding-bottom:60px}
    .product-card-modern{background:#fff;border-radius:15px;overflow:hidden;box-shadow:0 5px 10px rgba(0,0,0,.08);transition:transform .4s ease,box-shadow .4s ease;position:relative;margin-bottom:30px}
    .product-card-modern:hover{transform:translateY(-10px);box-shadow:0 20px 30px rgba(0,0,0,.15)}
    .product-image-modern{position:relative;overflow:hidden}
    .product-image-modern img{width:100%;height:230px;object-fit:cover;transition:transform .4s ease}
    .product-card-modern:hover .product-image-modern img{transform:scale(1.05)}
    .overlay{position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.3);opacity:0;display:flex;align-items:center;justify-content:center;transition:opacity .3s ease}
    .product-card-modern:hover .overlay{opacity:1}
    .btn-view{background-color:#ff7f50;color:#fff;padding:8px 18px;border-radius:25px;text-decoration:none;font-weight:600;transition:background .3s ease}
    .btn-view:hover{background-color:#ff5722}
    .product-body-modern{padding:15px 20px}
    .product-title-modern{font-size:1.25rem;font-weight:700;margin-bottom:10px;color:#333}
    .product-info-modern{display:flex;flex-wrap:wrap;gap:10px;font-size:.95rem;color:#555;margin-bottom:10px}
    .product-info-modern span{background:#f3f3f3;padding:4px 8px;border-radius:8px}
    @media(max-width:992px){.product-image-modern img{height:200px}}
    @media(max-width:576px){.product-image-modern img{height:180px}}
    .hero-slide{opacity:1!important}
    header.slider,.slider-container,.hero-slider{width:100%;max-width:100%;margin:0;padding:0}
    .hero-slider{position:relative;display:grid;align-items:center;min-height:calc(80vh - 80px);background:url('/images/Granit-Img/Ba-home2.png') center/cover no-repeat}
    .hero-slider::before{content:'';position:absolute;inset:0;background:rgba(0,0,0,.34);backdrop-filter:blur(1.5px);-webkit-backdrop-filter:blur(1.5px)}
    .hero-slider .container{position:relative;z-index:1}
    .hero-slider .hero-copy{color:#f1f1f1;font-weight:500;letter-spacing:-.3px;line-height:1.6;margin-top:120px!important}
    .hero-slider p.lead-text{text-transform:uppercase;letter-spacing:.18em;font-size:.8rem;color:#f1f1f1;margin-bottom:16px}
    .hero-slider h1{font-size:clamp(2.4rem,4vw,3.4rem);line-height:1.05;margin-bottom:18px;font-weight:800}
    .hero-slider h2{font-size:1rem;color:#f1f1f1;line-height:1.6;margin-bottom:24px;max-width:620px}
    .hero-slider .hero-actions{display:flex;flex-wrap:wrap;gap:12px;margin-bottom:28px}
    .hero-slider .hero-actions a{display:inline-flex;align-items:center;gap:10px;padding:12px 26px;border-radius:999px;font-weight:700;font-size:.95rem;text-decoration:none;transition:transform .25s ease,background .25s ease,color .25s ease}
    .hero-slider .hero-actions .btn-primary{background:#111;color:#fff}
    .hero-slider .hero-actions .btn-secondary{background:rgba(255,255,255,.92);color:#111;border:1px solid rgba(17,17,17,.12)}
    .hero-slider .hero-actions a:hover{transform:translateY(-2px)}
    .hero-slider .hero-figure{display:flex;justify-content:flex-end;margin-top:24px}
    .hero-slider .hero-figure img{width:100%;max-width:460px;border-radius:28px;box-shadow:0 28px 70px rgba(15,15,15,.14)}
    @media(max-width:992px){.hero-slider{min-height:auto;padding:70px 0}.hero-slider .hero-figure{justify-content:center}.hero-slider .hero-image{display:block}}
</style>

<!-- ═══════════════════════════════════ -->
<!--  HERO SECTION                      -->
<!-- ═══════════════════════════════════ -->
<header class="slider">
    <div class="slider-container">
        <div class="hero-slider">
            <div class="container">
                <div class="hero-copy container mt-5">
                    <p class="lead-text">Luxury countertops, engineered for life</p>
                    <h1>Premium Granite &amp; Quartz Countertops in Texas — Custom Fabrication &amp; Installation</h1>
                    <h2>Expert stone fabrication, premium materials, and fast professional installation for stunning kitchens and bathrooms across the DFW metroplex.</h2>
                    <div class="hero-actions">
                        <a href="<?= createLink($base_url, 'contact') ?>" class="btn-primary" aria-label="Schedule a free countertop consultation">Get a Free Estimate</a>
                        <a href="<?= createLink($base_url, 'contact') ?>" class="btn-secondary" aria-label="Contact Texas Specialized Quartz team">Contact Our Team</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ═══════════════════════════════════ -->
<!--  INTRO / ABOUT SECTION             -->
<!-- ═══════════════════════════════════ -->
<section class="intro mt-5" aria-label="Company Introduction">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <figure>
                    <div class="pattern-bg" data-stellar-ratio="1.07"></div>
                    <div class="holder" data-stellar-ratio="1.10">
                        <img src="images/Granit-Img/fadeInUp1.png"
                            alt="Professional granite countertop fabrication and installation at Texas Specialized Quartz workshop"
                            width="600" height="400"
                            loading="lazy">
                    </div>
                </figure>
            </div>

            <div class="col-lg-6 wow fadeInUp mt-4">
                <div class="content">
                    <h2>
                        <strong>Texas Specialized Quartz &amp; Granite</strong> — Premium Countertop Specialists You Can Trust
                    </h2>
                    <h3>High-Quality Granite, Quartz &amp; Marble Countertops for Kitchens &amp; Bathrooms</h3>
                    <p class="mt-3">
                        Looking to upgrade your home? Premium <strong>granite, quartz, and marble countertops</strong> can instantly elevate the aesthetic appeal,
                        durability, and property value of any kitchen or bathroom space. At <strong>Texas Specialized Quartz &amp; Granite</strong>, we provide expertly crafted custom
                        countertops designed to perfectly match your design preferences, spatial layout, and lifestyle requirements.
                    </p>
                    <p class="mt-3">
                        We specialize in precision fabrication and professional installation of <strong>custom stone countertops</strong> for residential and
                        commercial projects. Our premium material selection includes high-grade <strong>granite, marble, quartz, and natural stone surfaces</strong>,
                        ensuring exceptional long-lasting performance, stunning visual aesthetics, and superior resistance to heat exposure, surface scratches,
                        and everyday stains.
                    </p>
                    <p class="mt-3">
                        From contemporary open-concept kitchen designs to elegant bathroom vanity installations, our experienced team delivers exceptional
                        craftsmanship with personalized service. Whether you're undertaking a complete home remodeling project, new construction,
                        or countertop replacement, Texas Specialized Quartz &amp; Granite is the trusted choice for homeowners and businesses seeking
                        reliable installation, beautiful design, and premium quality materials that stand the test of time.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ -->
<!--  MATERIALS WE WORK WITH            -->
<!-- ═══════════════════════════════════ -->
<section class="logos" aria-label="Countertop materials we specialize in">
    <div class="container">
        <div class="section-heading text-center mb-4">
            <h2>Materials We Work With</h2>
            <p>Premium natural and engineered stone surfaces for every project</p>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 col-6 wow fadeInUp mb-3" data-wow-delay="0s">
                <figure>
                    <img src="images/Granit-Img/logo01.png" alt="Natural granite stone slab sample" width="200" height="200" loading="lazy">
                    <h6>Granite</h6>
                </figure>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.05s">
                <figure>
                    <img src="images/Granit-Img/logo01 (1).png" alt="Italian marble stone slab sample" width="200" height="200" loading="lazy">
                    <h6>Marble</h6>
                </figure>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-6 wow fadeInUp mb-3" data-wow-delay="0.10s">
                <figure>
                    <img src="images/Granit-Img/logo01 (2).png" alt="Natural quartzite stone slab sample" width="200" height="200" loading="lazy">
                    <h6>Quartzite</h6>
                </figure>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.15s">
                <figure>
                    <img src="images/Granit-Img/logo01 (3).png" alt="Engineered quartz countertop surface sample" width="200" height="200" loading="lazy">
                    <h6>Engineered Quartz</h6>
                </figure>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ -->
<!--  ABOUT US SECTION                  -->
<!-- ═══════════════════════════════════ -->
<section class="property-calculator" aria-label="About our company">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <figure>
                    <div class="pattern-bg" data-stellar-ratio="1.03"></div>
                    <div class="holder" data-stellar-ratio="1.07">
                        <img src="images/Granit-Img/about.png" alt="Texas Specialized Quartz showroom with granite and quartz slab displays" width="600" height="400" loading="lazy">
                    </div>
                </figure>
            </div>
            <div class="col-lg-6 wow fadeInUp">
                <div class="content mb-5">
                    <h2>About Texas Specialized Quartz &amp; Granite</h2>
                    <p>
                        Texas Specialized Quartz &amp; Granite has been providing <strong>custom kitchen countertops</strong>, <strong>bathroom countertops</strong>, and <strong>custom vanities</strong> for over a decade.
                        We pride ourselves on offering top-of-the-line <strong>countertop options</strong> with a wide selection of premium materials including <strong>granite</strong>, <strong>quartz</strong>, and <strong>marble</strong>.
                        Our commitment to quality craftsmanship and exceptional customer service ensures every project is completed to the highest standards.
                    </p>
                    <ul >
                        <li style="list-style:none" ><img class="logo-about" src="images/Granit-Img/logo-gg.jpg" alt="Texas Specialized Quartz company logo" width="80" height="80" loading="lazy">
                           <img class="logo-about" src="images/Granit-Img/logo-gg.jpg" alt="Texas Specialized Quartz quality seal" width="80" height="80" loading="lazy"></li>
                   
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ -->
<!--  WHAT WE DO                        -->
<!-- ═══════════════════════════════════ -->
<section class="recent-gallery" aria-label="Our countertop services">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 wow fadeInUp">
                <b>03</b>
                <h2>What We Do</h2>
                <p>
                    <strong>Texas Specialized Quartz &amp; Granite</strong> is a leading custom countertop company specializing in designing, fabricating, and installing premium natural stone countertops, including granite, marble, and quartz.
                    We deliver high-quality, durable, and beautiful countertop solutions tailored to homeowners, builders, and designers seeking custom kitchen countertops, bathroom countertops, and commercial surfaces.
                    Our expert team ensures precision and craftsmanship for every project, transforming spaces with elegant and functional stone surfaces.
                </p>
                <a href="<?= createLink($base_url, 'about') ?>" class="link" aria-label="Learn more about Texas Specialized Quartz">More About <i class="fas fa-caret-right"></i></a>
            </div>
            <div class="col-lg-7">
                <div class="row inner">
                    <div class="col-md-4 wow fadeInUp" data-wow-delay="0s">
                        <figure data-stellar-ratio="1.07">
                            <a href="images/Granit-Img/Item1.png" data-fancybox="services" aria-label="View granite kitchen countertop installation project">
                                <img src="images/Granit-Img/Item1.png" alt="Custom granite kitchen countertop installation in Dallas TX" width="300" height="400" loading="lazy">
                            </a>
                        </figure>
                    </div>
                    <div class="col-md-4 wow fadeInUp" data-wow-delay="0.05s">
                        <figure data-stellar-ratio="1.15">
                            <a href="images/Granit-Img/Item1 (1).png" data-fancybox="services" aria-label="View quartz bathroom vanity project">
                                <img src="images/Granit-Img/Item1 (1).png" alt="Quartz bathroom vanity countertop with undermount sink" width="300" height="400" loading="lazy">
                            </a>
                        </figure>
                    </div>
                    <div class="col-md-4 wow fadeInUp" data-wow-delay="0.10s">
                        <figure data-stellar-ratio="1.04">
                            <a href="images/Granit-Img/Item1 (2).png" data-fancybox="services" aria-label="View marble countertop fabrication project">
                                <img src="images/Granit-Img/Item1 (2).png" alt="Marble countertop CNC precision cutting and fabrication" width="300" height="400" loading="lazy">
                            </a>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ -->
<!--  OUR WORK GALLERY                  -->
<!-- ═══════════════════════════════════ -->
<section class="recent-gallery" aria-label="Countertop project gallery">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 wow fadeInUp">
                <b>02</b>
                <h2>Explore Our Custom Countertop Work</h2>
                <p>
                    Experience expert craftsmanship and premium quality granite, marble, and quartz countertops delivered by <strong>Texas Specialized Quartz &amp; Granite</strong>.
                    Our custom countertops combine durability and elegance, perfect for kitchens, bathrooms, and commercial spaces.
                </p>
                <a href="<?= createLink($base_url, 'photogallery') ?>" class="link" aria-label="View full countertop project gallery">See All Work <i class="fas fa-caret-right"></i></a>
            </div>
            <div class="col-lg-7">
                <div class="row inner">
                    <div class="col-md-4 wow fadeInUp" data-wow-delay="0s">
                        <figure data-stellar-ratio="1.07">
                            <a href="images/Granit-Img/Item2.png" data-fancybox="gallery" aria-label="View kitchen island granite countertop">
                                <img src="images/Granit-Img/Item2.png" alt="White granite kitchen island countertop with waterfall edge" width="300" height="400" loading="lazy">
                            </a>
                        </figure>
                    </div>
                    <div class="col-md-4 wow fadeInUp" data-wow-delay="0.05s">
                        <figure data-stellar-ratio="1.15">
                            <a href="images/Granit-Img/Item2 (1).png" data-fancybox="gallery" aria-label="View quartz kitchen countertop project">
                                <img src="images/Granit-Img/Item2 (1).png" alt="Modern quartz kitchen countertop with full backsplash" width="300" height="400" loading="lazy">
                            </a>
                        </figure>
                    </div>
                    <div class="col-md-4 wow fadeInUp" data-wow-delay="0.10s">
                        <figure data-stellar-ratio="1.04">
                            <a href="images/Granit-Img/Item2 (2).png" data-fancybox="gallery" aria-label="View bathroom marble vanity project">
                                <img src="images/Granit-Img/Item2 (2).png" alt="Elegant marble bathroom vanity with polished edge profile" width="300" height="400" loading="lazy">
                            </a>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ -->
<!--  VENDORS / PARTNERS                -->
<!-- ═══════════════════════════════════ -->
<section class="property-customization" aria-label="Our stone material vendors and partners">
    <div class="container">
        <div class="row">
            <div class="col-12 wow fadeInUp">
                <b>04</b>
                <h2>Our Trusted Vendors</h2>
                <h3>Premium stone suppliers we partner with for the highest quality materials</h3>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0s">
                <figure data-toggle="tooltip" data-placement="top" title="West Quartz">
                    <img src="images/Granit-Img/client.png" alt="West Quartz stone supplier logo" width="50" height="80" loading="lazy">
                    <figcaption>West Quartz</figcaption>
                </figure>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.05s">
                <figure data-toggle="tooltip" data-placement="top" title="VicoStone">
                    <img src="images/Granit-Img/client (1).png" alt="VicoStone engineered quartz manufacturer logo" width="50" height="80" loading="lazy">
                    <figcaption>VicoStone</figcaption>
                </figure>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.10s">
                <figure data-toggle="tooltip" data-placement="top" title="Silestone">
                    <img src="images/Granit-Img/client (2).png" alt="Silestone quartz surfaces brand logo" width="50" height="80" loading="lazy">
                    <figcaption>Silestone</figcaption>
                </figure>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.15s">
                <figure data-toggle="tooltip" data-placement="top" title="MSI Surfaces">
                    <img src="images/Granit-Img/client (3).png" alt="MSI Surfaces natural stone distributor logo" width="50" height="80" loading="lazy">
                    <figcaption>MSI Surfaces</figcaption>
                </figure>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.20s">
                <figure data-toggle="tooltip" data-placement="top" title="Metro Quartz">
                    <img src="images/Granit-Img/client (4).png" alt="Metro Quartz countertop supplier logo" width="50" height="80" loading="lazy">
                    <figcaption>Metro Quartz</figcaption>
                </figure>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.25s">
                <figure data-toggle="tooltip" data-placement="top" title="KLZ Stone">
                    <img src="images/Granit-Img/client (5).png" alt="KLZ Stone natural stone supplier logo" width="50" height="80" loading="lazy">
                    <figcaption>KLZ Stone</figcaption>
                </figure>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.30s">
                <figure data-toggle="tooltip" data-placement="top" title="Everest Stone">
                    <img src="images/Granit-Img/client (6).png" alt="Everest Stone premium granite supplier logo" width="50" height="80" loading="lazy">
                    <figcaption>Everest Stone</figcaption>
                </figure>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ -->
<!--  GRANITE REMNANTS (Dynamic)        -->
<!-- ═══════════════════════════════════ -->
<?php
$batchSize = 3;
$secondsPerBatch = 3 * 60 * 60;
$now = time();
$startOfDay = strtotime("today");
$elapsed = $now - $startOfDay;
$periodIndex = floor($elapsed / $secondsPerBatch);
$totalProducts = count($result);
$totalBatches = ceil($totalProducts / $batchSize);
$currentBatch = $periodIndex % $totalBatches;
$productsToShow = array_slice($result, $currentBatch * $batchSize, $batchSize);
?>

<section class="sales-offices" aria-label="Granite remnants available in store">
    <div class="container">
        <div class="text-center mb-4">
            <h2>Granite Remnants Available in Texas</h2>
            <p class="lead">
                High-quality <strong>granite, quartz, and marble remnant slabs</strong> at up to 60% off — perfect for bathroom vanities, bar tops, and small kitchen projects
                in <strong>Carrollton, Plano, Frisco, McKinney, and the greater Dallas-Fort Worth area</strong>.
            </p>
            <a href="<?= createLink($base_url, 'Remnants') ?>" class="link" aria-label="View all granite remnants in stock">View All Remnants <i class="fas fa-caret-right"></i></a>
        </div>

        <?php if (!empty($productsToShow)): ?>
        <div class="row g-4">
            <?php foreach ($productsToShow as $row):
                $imageSrc = !empty($row['image'])
                    ? './BusinessPortal/assets/products/' . htmlspecialchars($row['image'])
                    : 'images/faces/face1.jpg';
                $productTitle = htmlspecialchars($row['title']);
                $productColor = htmlspecialchars($row['color_name']);
            ?>
            <div class="col-lg-4 col-md-6">
                <article class="product-card-modern" itemscope itemtype="https://schema.org/Product">
                    <div class="product-image-modern">
                        <img src="<?= $imageSrc; ?>" alt="<?= $productTitle ?> granite remnant slab in <?= $productColor ?> color" width="400" height="230" loading="lazy" itemprop="image">
                    </div>
                    <div class="product-body-modern">
                        <h3 class="product-title-modern" itemprop="name"><?= $productTitle; ?></h3>
                        <div class="product-info-modern">
                            <span>Qty: <?= htmlspecialchars($row['quantity']); ?></span>
                            <span>Color: <?= $productColor; ?></span>
                            <span>Size: <?= htmlspecialchars($row['size']); ?></span>
                            <span>ID: <?= htmlspecialchars($row['id']); ?></span>
                        </div>

                        <?php
                        $status = htmlspecialchars($row['availability']);
                        $statusText = $status;
                        $bg_color = '#f0f0f0';
                        $text_color = '#555';

                        if ($status === 'Reserved' && !empty($row['status_updated_at'])) {
                            $nowDT = new DateTime();
                            $holdDate = new DateTime($row['status_updated_at']);
                            $daysLeft = max(0, 5 - $nowDT->diff($holdDate)->days);
                            $statusText = $daysLeft > 0 ? "Hold ($daysLeft day left)" : "New";
                            $bg_color = $daysLeft > 0 ? 'rgba(255, 152, 0, 0.1)' : 'rgba(40, 167, 69, 0.1)';
                            $text_color = $daysLeft > 0 ? '#ff9800' : '#28a745';
                        } else {
                            switch ($status) {
                                case 'Available in store':
                                    $bg_color = 'rgba(40, 167, 69, 0.1)';
                                    $text_color = '#28a745';
                                    break;
                                case 'Reserved':
                                    $bg_color = 'rgba(255, 152, 0, 0.1)';
                                    $text_color = '#ff9800';
                                    break;
                                case 'Sold Out':
                                    $bg_color = 'rgba(220, 53, 69, 0.1)';
                                    $text_color = '#dc3545';
                                    break;
                                default:
                                    $bg_color = 'rgba(128, 128, 128, 0.1)';
                                    $text_color = '#555';
                            }
                        }
                        ?>
                        <div class="product-status-modern" style="background-color:<?= $bg_color ?>;color:<?= $text_color ?>;padding:5px 10px;border-radius:5px;display:inline-block;font-size:12px" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                            <meta itemprop="availability" content="<?= $status === 'Available in store' ? 'https://schema.org/InStock' : ($status === 'Sold Out' ? 'https://schema.org/OutOfStock' : 'https://schema.org/LimitedAvailability') ?>">
                            <?= $statusText ?>
                        </div>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-center">No remnants currently available. Check back soon or <a href="<?= createLink($base_url, 'contact') ?>">contact us</a> for special orders.</p>
        <?php endif; ?>
    </div>
</section>

<!-- ═══════════════════════════════════ -->
<!--  CERTIFICATES                      -->
<!-- ═══════════════════════════════════ -->
<section class="certificates" aria-label="Industry certifications and quality awards">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 col-md-12 wow fadeInUp">
                <b>05</b>
                <h2>Industry Certifications</h2>
                <p>Premium quality granite fabrication backed by industry-recognized certifications and standards</p>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.05s">
                <figure>
                    <a href="images/Granit-Img/arown1.png" data-fancybox="certs" aria-label="View stone fabrication certification">
                        <img src="images/Granit-Img/arown1.png" alt="Stone fabrication industry certification badge" width="200" height="200" loading="lazy">
                    </a>
                </figure>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.15s">
                <figure>
                    <a href="images/Granit-Img/arown3.png" data-fancybox="certs" aria-label="View quality assurance certificate">
                        <img src="images/Granit-Img/arown3.png" alt="Countertop quality assurance certification" width="200" height="200" loading="lazy">
                    </a>
                </figure>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.10s">
                <figure>
                    <a href="images/Granit-Img/arown2.png" data-fancybox="certs" aria-label="View professional installer certification">
                        <img src="images/Granit-Img/arown2.png" alt="Professional countertop installer certification" width="200" height="200" loading="lazy">
                    </a>
                </figure>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.20s">
                <figure>
                    <a href="images/Granit-Img/arown4.png" data-fancybox="certs" aria-label="View safety compliance award">
                        <img src="images/Granit-Img/arown4.png" alt="Workplace safety compliance award for stone fabrication" width="200" height="200" loading="lazy">
                    </a>
                </figure>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ -->
<!--  BLOG PREVIEW                      -->
<!-- ═══════════════════════════════════ -->
<section aria-label="Latest blog articles about countertops">
    <div class="container">
        <div class="text-center py-4">
            <h2>Latest From Our Blog</h2>
            <p class="lead">
                Expert tips, design ideas, and the latest trends in <strong>granite, quartz, and marble countertops</strong>
                for homeowners across Plano, Frisco, McKinney, and the Dallas-Fort Worth area.
            </p>
        </div>

        <div class="row">
            <div class="col-md-6 col-12 d-flex mb-3">
                <article class="card border-0 h-100 w-100 d-flex flex-column" itemscope itemtype="https://schema.org/BlogPosting">
                    <img src="images/Granit-Img/BLOG28.png" class="card-img-top img-fluid flex-shrink-0" style="height:200px;object-fit:cover;"
                        alt="Granite and marble countertop pricing guide for Texas homeowners 2025" width="600" height="200" loading="lazy" itemprop="image">
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title" style="font-size:1.15rem;" itemprop="headline">
                            <a href="newsdetail/granite-marble-prices-2025-how-choose-according-budget">Granite and Marble Prices 2025: How to Choose According to Your Budget</a>
                        </h3>
                        <p class="card-text" itemprop="description">
                            Planning to renovate your kitchen or bathroom with granite or marble? Know the current prices and available options to make a smart budget decision.
                        </p>
                        <div class="mt-auto">
                            <time datetime="2025-09-08" itemprop="datePublished" class="text-body-secondary" style="font-size:13px;">September 8, 2025</time>
                            <div>
                                <a href="newsdetail/granite-marble-prices-2025-how-choose-according-budget" class="link" aria-label="Read full article about granite and marble prices 2025">Read More <i class="fas fa-caret-right"></i></a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <div class="col-md-6 col-12 d-flex mb-3">
                <article class="card border-0 h-100 w-100 d-flex flex-column" itemscope itemtype="https://schema.org/BlogPosting">
                    <img src="images/Granit-Img/BLOG27.jpg" class="card-img-top img-fluid flex-shrink-0" style="height:200px;object-fit:cover;"
                        alt="Affordable granite remnant slabs available in Texas for small projects" width="600" height="200" loading="lazy" itemprop="image">
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title" style="font-size:1.15rem;" itemprop="headline">
                            <a href="newsdetail/save-big-affordable-granite-remnants-texas">Save Big with Affordable Granite Remnants in Texas</a>
                        </h3>
                        <p class="card-text" itemprop="description">
                            Beautiful granite countertops without the high price tag. Remnants come at a fraction of the cost with the same durability and natural beauty.
                        </p>
                        <div class="mt-auto">
                            <time datetime="2025-08-31" itemprop="datePublished" class="text-body-secondary" style="font-size:13px;">August 31, 2025</time>
                            <div>
                                <a href="newsdetail/save-big-affordable-granite-remnants-texas" class="link" aria-label="Read full article about affordable granite remnants in Texas">Read More <i class="fas fa-caret-right"></i></a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>

        <div class="text-center mt-3 mb-4">
            <a href="<?= createLink($base_url, 'blog') ?>" class="link" aria-label="Browse all countertop blog articles">View All Articles <i class="fas fa-caret-right"></i></a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ -->
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
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
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
            "itemListElement": [
                {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Granite Countertop Fabrication & Installation"}},
                {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Quartz Countertop Fabrication & Installation"}},
                {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Marble Countertop Fabrication & Installation"}},
                {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Countertop Repair & Maintenance"}},
                {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Free In-Home Estimate & Consultation"}}
            ]
        }
    }
}
</script>