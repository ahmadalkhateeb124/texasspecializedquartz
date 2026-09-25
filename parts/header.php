<?php
$current_page = basename($_SERVER['REQUEST_URI']); // استخراج اسم الصفحة الحالية
require('header-ini.php');
?>
<!doctype html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#282828" />
    <?php $gsv = setting('seo_google_verification'); if ($gsv): ?>
    <meta name="google-site-verification" content="<?= htmlspecialchars($gsv) ?>" />
    <?php endif; ?>

    <title><?php echo $PageTitle; ?></title>
    <meta name="description" content="<?php echo $escapedDescription; ?>">
    <meta name="keywords" content="<?php echo $KeyWords; ?>">
    <meta name="author" content="<?= htmlspecialchars(setting('business_name', 'Texas Specialized Quartz & Granite')) ?>">
    <!-- Geo + Local SEO -->
    <meta name="geo.region"    content="US-TX">
    <meta name="geo.placename" content="Carrollton, Texas">
    <meta name="geo.position"  content="32.9537;-96.8903">
    <meta name="ICBM"          content="32.9537, -96.8903">
    <!-- Canonical -->
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>" />
    <!-- Open Graph -->
    <?php $ogImage = setting('seo_og_image', 'https://texasspecializedquartz.com/images/Granit-Img/Ba-home2.png'); ?>
    <meta property="og:title" content="<?php echo $PageTitle; ?>">
    <meta property="og:description" content="<?php echo $escapedDescription; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $canonicalUrl; ?>">
    <meta property="og:image" content="<?= htmlspecialchars($ogImage) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <!-- Robots -->
    <meta http-equiv="Cache-Control" content="public, max-age=604800, immutable">
    <?php $_noindexPages = ['lp', 'thank-you', 'B', 'M']; ?>
    <meta name="robots" content="<?= in_array($requestUrl ?? '', $_noindexPages) ? 'noindex, nofollow' : 'index, follow' ?>">
    <!-- Twitter -->
    <?php $tw = setting('social_twitter_handle', '@graniteartists'); ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="<?= htmlspecialchars($ogImage) ?>">
    <meta name="twitter:site" content="<?= htmlspecialchars($tw) ?>">
    <meta name="twitter:creator" content="<?= htmlspecialchars($tw) ?>">
    <meta name="twitter:title" content="<?php echo $PageTitle; ?>">
    <meta name="twitter:description" content="<?php echo $escapedDescription; ?>">
    <!-- Favicons -->
    <link href="<?= $base_url ?>images/Granit-Img/logo-gg.jpg" rel="apple-touch-icon" sizes="144x144">
    <link href="<?= $base_url ?>images/Granit-Img/logo-gg.jpg" rel="apple-touch-icon" sizes="114x114">
    <link href="<?= $base_url ?>images/Granit-Img/logo-gg.jpg" rel="apple-touch-icon" sizes="72x72">
    <link href="<?= $base_url ?>images/Granit-Img/logo-gg.jpg" rel="apple-touch-icon">
    <link href="<?= $base_url ?>images/Granit-Img/logo-gg.jpg" rel="shortcut icon">
    <link rel="preload" as="video" href="/images/Granit-Img/hero.mp4" type="video/mp4">
    <!-- Critical CSS -->
    <link rel="stylesheet" href="<?= $base_url ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>css/theme-2026.css">
    <?php if (($_GET['url'] ?? '') === 'newsdetail'): ?>
    <link rel="stylesheet" href="<?= $base_url ?>css/newsdetail.css">
    <?php endif; ?>
    <!-- Non-critical CSS async -->
    <link rel="preload" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="<?= $base_url ?>css/fontawesome.min.css" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="<?= $base_url ?>css/fancybox.min.css" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="<?= $base_url ?>css/odometer.min.css" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="<?= $base_url ?>css/swiper.min.css" as="style" onload="this.rel='stylesheet'">
    <!-- Fallback for no-JS -->
    <noscript>
        <link rel="stylesheet" href="<?= $base_url ?>css/fontawesome.min.css">
        <link rel="stylesheet" href="<?= $base_url ?>css/fancybox.min.css">
        <link rel="stylesheet" href="<?= $base_url ?>css/odometer.min.css">
        <link rel="stylesheet" href="<?= $base_url ?>css/swiper.min.css">
    </noscript>
    
   
    <?php include 'Json.php'; ?>
    <?php include 'JSON-LD.php'; ?>
    <?php include 'Product.php'; ?>
    <?php include 'schema-organization.php'; ?>
    <?php include 'schema-localbusiness.php'; ?>
    <?php include 'schema-breadcrumbs.php'; ?>
    <?php if (basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) === 'faq'): include 'schema-faq.php'; endif; ?>
    <?php if (basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) === 'Inventory'): include 'schema-inventory.php'; endif; ?>
    <?php
    $ga4   = setting('tracking_ga4_id');
    $gads  = setting('tracking_google_ads_id');
    $gtm   = setting('tracking_gtm_id');
    $metaP = setting('tracking_meta_pixel');
    $ttP   = setting('tracking_tiktok_pixel');
    ?>

    <?php if ($gtm): ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?= htmlspecialchars($gtm) ?>');</script>
    <?php endif; ?>

    <?php if ($ga4 || $gads): ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($ga4 ?: $gads) ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        <?php if ($ga4): ?>gtag('config', '<?= htmlspecialchars($ga4) ?>');<?php endif; ?>
        <?php if ($gads): ?>gtag('config', '<?= htmlspecialchars($gads) ?>');<?php endif; ?>
    </script>
    <?php endif; ?>

    <?php if ($metaP): ?>
    <!-- Meta Pixel -->
    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '<?= htmlspecialchars($metaP) ?>');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" alt="" src="https://www.facebook.com/tr?id=<?= htmlspecialchars($metaP) ?>&ev=PageView&noscript=1"/></noscript>
    <?php endif; ?>

    <?php if ($ttP): ?>
    <!-- TikTok Pixel -->
    <script>
    !function (w, d, t) {w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
      ttq.load('<?= htmlspecialchars($ttP) ?>');
      ttq.page();
    }(window, document, 'ttq');
    </script>
    <?php endif; ?>

</head>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5HGBN8ML');</script>
<!-- End Google Tag Manager -->
<?php $txIsHome = in_array(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), ['', 'Home', 'index.php'], true); ?>
<body class="tx-theme <?= $txIsHome ? 'tx-home' : '' ?>">

    <div class="contact-buttons">
     <a href="mailto:Cs@TexasSpecializedQuartz.com" 
   class="contact-btn email-btn" 
   aria-label="Send email to Texas Specialized Quartz & Granite">
    <i class='bx bx-envelope'></i>
</a>

        <a href="tel:+14698140555" class="contact-btn phone-btn" aria-label="Call Texas Specialized Quartz & Granite">
            <i class='bx bx-phone'></i>
        </a>

        <a href="https://share.google/DPZ6xdT7uYvhfF6q5"
            target="_blank" rel="noopener" class="contact-btn location-btn" aria-label="View Texas Specialized Quartz & Granite location on Google Maps">
            <i class='bx bx-map'></i>
        </a>
    </div>
    <!-- end prelaoder -->
    <div class="transition-overlay">
        <div class="layer"></div>
    </div>
    <!-- end transition-overlay -->
    <!-- ═══ New minimal-luxury navbar ═══ -->
    <nav class="tx-nav" aria-label="Primary">
        <div class="tx-nav-left">
            <a href="<?= createLink($base_url, 'Home') ?>" class="tx-link">Home</a>
            <a href="<?= createLink($base_url, 'Inventory') ?>" class="tx-link">Inventory</a>
            <a href="<?= createLink($base_url, 'Sinks') ?>" class="tx-link">Sinks</a>
         <a href="<?= createLink($base_url, 'EdgeType') ?>" class="tx-link">Edge Types</a>
            <div class="tx-drop">
                <a href="#" class="tx-link tx-drop-toggle">Countertops</a>
                <ul class="tx-drop-menu">
                    <li><a href="<?= createLink($base_url, 'CountertopMaterials') ?>">Granite Countertops</a></li>
                    <li><a href="<?= createLink($base_url, 'KitchenCountertops') ?>">Kitchen Countertops</a></li>
                    <li><a href="<?= createLink($base_url, 'Countertops') ?>">All Countertops</a></li>
                    
                </ul>
            </div>
            <div class="tx-drop">
                <a href="#" class="tx-link tx-drop-toggle">Visualizer</a>
                <ul class="tx-drop-menu">
                    <li><a href="<?= createLink($base_url, 'kitchenVisualizer') ?>">Kitchen Visualizer</a></li>
                    <li><a href="<?= createLink($base_url, 'EdgeVisualizer') ?>">Edge Visualizer</a></li>
                    <li><a href="<?= createLink($base_url, 'QuartzSlabs') ?>">Quartz Slabs</a></li>
                </ul>
            </div>
        </div>

        <a href="<?= createLink($base_url, 'Home') ?>" class="tx-nav-brand">
            Texas Specialized
            <span>Quartz &amp; Granite</span>
        </a>

        <div class="tx-nav-right">
            <a href="<?= createLink($base_url, 'Remnants') ?>" class="tx-link">Remnants</a>
            
                      <a href="<?= createLink($base_url, 'photogallery') ?>"  class="tx-link" > Project</a>

            <div class="tx-drop">
                <a href="#" class="tx-link tx-drop-toggle">Company</a>
                <ul class="tx-drop-menu">
                    <li><a href="<?= createLink($base_url, 'Services') ?>">Services</a></li>
                    <li><a href="<?= createLink($base_url, 'InstallationServices') ?>">Installation</a></li>
                     <li><a href="<?= createLink($base_url, 'about') ?>">About</a></li>
                    <li><a href="<?= createLink($base_url, 'faq') ?>">FAQ</a></li>
                    <li><a href="<?= createLink($base_url, 'certificates') ?>">Certificates</a></li>
                </ul>
            </div>
            <a href="<?= createLink($base_url, 'blog') ?>" class="tx-link">Blog</a>
            <a href="<?= createLink($base_url, 'BusinessPortal') ?>" class="tx-link">Login</a>
            <a href="<?= createLink($base_url, 'contact') ?>" class="tx-nav-cta">Free Quote</a>
            <button class="tx-hamburger" aria-label="Open menu" type="button">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- Mobile slide-in menu -->
    <aside class="tx-mobile" aria-hidden="true">
        <button class="tx-mobile-close" aria-label="Close menu" type="button">&times;</button>
        <ul>
            <li><a href="<?= createLink($base_url, 'Home') ?>">Home</a></li>
            <li><a href="<?= createLink($base_url, 'Inventory') ?>">Inventory</a></li>
            <li><a href="<?= createLink($base_url, 'Sinks') ?>">Sinks</a></li>
              <li><a href="<?= createLink($base_url, 'EdgeType') ?>">Edge Types</a></li>
            <li class="has-sub">
                <a href="#">Countertops</a>
                <ul class="submenu">
                    <li><a href="<?= createLink($base_url, 'CountertopMaterials') ?>">Granite Countertops</a></li>
                    <li><a href="<?= createLink($base_url, 'KitchenCountertops') ?>">Kitchen Countertops</a></li>
                    <li><a href="<?= createLink($base_url, 'Countertops') ?>">All Countertops</a></li>
                  
                </ul>
            </li>
            <li class="has-sub">
                <a href="#">Visualizer</a>
                <ul class="submenu">
                    <li><a href="<?= createLink($base_url, 'kitchenVisualizer') ?>">Kitchen Visualizer</a></li>
                    <li><a href="<?= createLink($base_url, 'EdgeVisualizer') ?>">Edge Visualizer</a></li>
                    <li><a href="<?= createLink($base_url, 'QuartzSlabs') ?>">Quartz Slabs</a></li>
                </ul>
            </li>
            <li><a href="<?= createLink($base_url, 'Remnants') ?>">Remnants</a></li>
                  <li><a href="<?= createLink($base_url, 'photogallery') ?>">Inspiration Center</a></li>
            <li class="has-sub">
                <a href="#">Company</a>
                <ul class="submenu">
                    <li><a href="<?= createLink($base_url, 'Services') ?>">Services</a></li>
                    <li><a href="<?= createLink($base_url, 'InstallationServices') ?>">Installation</a></li>
             
                     <li><a href="<?= createLink($base_url, 'about') ?>">About</a></li>
                    <li><a href="<?= createLink($base_url, 'faq') ?>">FAQ</a></li>
                    <li><a href="<?= createLink($base_url, 'certificates') ?>">Certificates</a></li>
                </ul>
            </li>
            <li><a href="<?= createLink($base_url, 'blog') ?>">Blog</a></li>
            <li><a href="<?= createLink($base_url, 'BusinessPortal') ?>">Login</a></li>
            <li><a href="<?= createLink($base_url, 'contact') ?>">Free Quote</a></li>
        </ul>
        <div class="tx-mobile-footer">
            <a href="tel:<?= htmlspecialchars(preg_replace('/[^+\d]/', '', setting('business_phone', '+14698140555'))) ?>">
                <i class='bx bx-phone'></i> <?= htmlspecialchars(setting('business_phone', '(469) 814-0555')) ?>
            </a>
            <a href="mailto:<?= htmlspecialchars(setting('business_email', 'Cs@TexasSpecializedQuartz.com')) ?>">
                <i class='bx bx-envelope'></i> <?= htmlspecialchars(setting('business_email', 'Cs@TexasSpecializedQuartz.com')) ?>
            </a>
            <a href="<?= createLink($base_url, 'BusinessPortal') ?>"><i class='bx bx-log-in'></i> Customer Login</a>
        </div>
    </aside>

    <script>
    // Apply data-background as background-image on page-header (replaces stellar.js)
    document.querySelectorAll('[data-background]').forEach(el => {
        el.style.backgroundImage = `url('${el.dataset.background}')`;
    });
    (function () {
        const nav = document.querySelector('.tx-nav');
        const toggle = () => nav.classList.toggle('is-scrolled', window.scrollY > 40);
        window.addEventListener('scroll', toggle, { passive: true }); toggle();

        const m = document.querySelector('.tx-mobile');
        document.querySelector('.tx-hamburger')?.addEventListener('click', () => m.classList.add('is-open'));
        m?.querySelector('.tx-mobile-close')?.addEventListener('click', () => m.classList.remove('is-open'));

        // Collapsible submenus (mobile)
        document.querySelectorAll('.tx-mobile .has-sub > a').forEach(a => {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                const li = a.parentElement;
                const wasOpen = li.classList.contains('is-open');
                li.parentElement.querySelectorAll(':scope > .has-sub.is-open').forEach(el => el.classList.remove('is-open'));
                if (!wasOpen) li.classList.add('is-open');
            });
        });
    })();
    </script>

    <div class="side-navigation" style="display:none;">

        <div class="menu">
            <ul>
                <li><a href="<?= createLink($base_url, 'Home') ?>">Home</a></li>
                  <li class="menu-item">
                    <a href="<?= createLink($base_url, 'Inventory') ?>">Inventory </a>
                </li>
                <li><a href="<?= createLink($base_url, 'Sinks') ?>">Sinks</a></li>
                <li><a href="<?= createLink($base_url, 'EdgeType') ?>">EdgeType</a></li>

                <li class="menu-item">
                    <a href="#">Countertops <i class='bx bx-down-arrow-alt'></i></a>
                    <ul class="submenu">
                        <li><a href="<?= createLink($base_url, 'CountertopMaterials') ?>">Granite Countertop</a></li>
                        <li><a href="<?= createLink($base_url, 'KitchenCountertops') ?>">Kitchen Countertops</a></li>
                        <li>
                            <a href="<?= createLink($base_url, 'InstallationServices') ?>">Installation Services</a>
                        </li>

                    </ul>
                </li>
                <li class="menu-item">
                    <a href="#">Page <i class='bx bx-down-arrow-alt'></i></a>
                    <ul class="submenu">
                        <li><a href="<?= createLink($base_url, 'Services') ?>">Services</a></li>
                        <li><a href="<?= createLink($base_url, 'about') ?>">About Us</a></li>
                        <li><a href="<?= createLink($base_url, 'faq') ?>">FAQ</a></li>
                        <li><a href="<?= createLink($base_url, 'photogallery') ?>">Photo-Gallery</a></li>
                        <li><a href="<?= createLink($base_url, 'certificates') ?>">Certificates</a></li>

                    </ul>
                </li>
              
                <li class="menu-item">
                    <a href="#">Visualizer <i class='bx bx-down-arrow-alt'></i></a>
                    <ul class="submenu">
                        <li><a href="<?= createLink($base_url, 'kitchenVisualizer') ?>">Kitchen Visualizer</a></li>
                        <li><a href="<?= createLink($base_url, 'EdgeVisualizer') ?>">Edge Visualizer</a></li>
                        <li><a href="<?= createLink($base_url, 'QuartzSlabs') ?>">Quartz Slabs Visualizer</a></li>
                    </ul>
                </li>


                <li><a href="<?= createLink($base_url, 'Remnants') ?>">Remnants</a></li>
                <li><a href="<?= createLink($base_url, 'blog') ?>">Blog</a></li>
                <li><a href="<?= createLink($base_url, 'contact') ?>">Contact</a></li>
                <li><a href="<?= createLink($base_url, 'BusinessPortal') ?>">login</a></li>
            </ul>
        </div>


        <!-- end menu -->
        <div class="side-content">
            <figure> <img src="<?= $base_url ?>images/Granit-Img/logo.png" alt="Logo" loading="lazy"> </figure>
            <p>Enhancing the quality of your living spaces with premium granite solutions, Texas Specialized Quartz & Granite TX is your go-to source for luxury countertops and exceptional craftsmanship</p>
            <ul class="gallery">
                <li><a href="images/Granit-Img/countertop.png" data-fancybox><img src="images/Granit-Img/countertop.png" alt="Granite countertop installation in Texas kitchen" loading="lazy"></a></li>
                <li><a href="images/Granit-Img/countertop2.png" data-fancybox><img src="images/Granit-Img/countertop2.png" alt="Quartz countertop showcase by Texas Specialized Quartz" loading="lazy"></a></li>
                <li><a href="images/Granit-Img/countertop3.png" data-fancybox><img src="images/Granit-Img/countertop3.png" alt="Marble countertop with custom edge profile" loading="lazy"></a></li>
            </ul>
            <address>
                1225 W College Ave #616, Carrollton, TX 75006
            </address>
            <p><a style="color: aliceblue !important ;" href="tel:+1 469-814-0555">(469) 814-0555</a> </p>
            <p><a href="mailto:Cs@TexasSpecializedQuartz.com">Cs@TexasSpecializedQuartz.com</a></p>
            <ul class="social-media">
                <li><a href="https://www.facebook.com/GraniteArtists" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="https://www.instagram.com/graniteartiststx/" target="_blank"><i class="fab fa-instagram"></i></a></li>
                <li><a href="https://x.com/graniteartists" target="_blank"><i class="fab fa-twitter"></i></a></li>
                <li><a href="https://www.youtube.com/@Granite_Artists" target="_blank"><i class="fab fa-youtube"></i></a></li>
                <li><a href="https://g.co/kgs/CX1qFyP" target="_blank"><i class="fab fa-google"></i></a></li>
                <li><a href="https://www.pinterest.com/Granite_Artists/" target="_blank"><i class="fab fa-pinterest"></i></a></li>
            </ul>
            <small>© 2025 Texas Specialized Quartz & Granite | Premium Granite Countertops & Expert Installations</small>
        </div>
        <!-- end side-content -->
    </div>
    <!-- end side-navigation -->
    <nav class="navbar " style="display:none;">

        <div class="container">
            <div class="upper-side d-lg-none ">
                <div class="logo logo-center ">
                    <a href="<?= createLink($base_url, 'Home') ?>">
                        <img src="<?= $base_url ?>images/Granit-Img/logo.png" alt="Logo" loading="lazy">
                    </a>
                </div>
                <!-- end logo -->
                <div class="phone-email">
                </div>
                <!-- end language -->
                <div class="hamburger "> <span></span> <span></span> <span></span><span></span> </div>
                <!-- end hamburger -->
            </div>
            <div class="menu">
                <ul>
                    <li><a href="<?= createLink($base_url, 'Home') ?>">Home</a></li>
                      <li class="menu-item">
                    <a href="<?= createLink($base_url, 'Inventory') ?>">Inventory </a>

                </li>
                    <li><a href="<?= createLink($base_url, 'Sinks') ?>">Sinks</a></li>
                    <li><a href="<?= createLink($base_url, 'EdgeType') ?>">EdgeType</a></li>

                    <li class="menu-item">
                        <a href="#">Countertops <i class='bx bx-down-arrow-alt'></i></a>
                        <ul class="submenu">
                            <li><a href="<?= createLink($base_url, 'CountertopMaterials') ?>">Granite Countertop</a></li>
                            <li><a href="<?= createLink($base_url, 'KitchenCountertops') ?>">Kitchen Countertops</a></li>
                            <li>
                                <a href="<?= createLink($base_url, 'InstallationServices') ?>">Installation Services</a>
                            </li>


                        </ul>
                    </li>
               
              

                    <div class="logo-center ">
                        <a href="<?= createLink($base_url, 'Home') ?>">
                            <img src="<?= $base_url ?>images/Granit-Img/logo.png" alt="Logo" loading="lazy">
                        </a>
                    </div>

                    <li class="menu-item">
                        <a href="#">Visualizer <i class='bx bx-down-arrow-alt'></i></a>
                        <ul class="submenu">
                            <li><a href="<?= createLink($base_url, 'kitchenVisualizer') ?>">Kitchen Visualizer</a></li>
                            <li><a href="<?= createLink($base_url, 'EdgeVisualizer') ?>">Edge Visualizer</a></li>
                            <li><a href="<?= createLink($base_url, 'QuartzSlabs') ?>">Quartz Visualizer</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">Page <i class='bx bx-down-arrow-alt'></i></a>
                        <ul class="submenu">

                            <li><a href="<?= createLink($base_url, 'Services') ?>">Services</a></li>
                            <li><a href="<?= createLink($base_url, 'about') ?>">About Us</a></li>
                            <li><a href="<?= createLink($base_url, 'faq') ?>">FAQ</a></li>
                            <li><a href="<?= createLink($base_url, 'photogallery') ?>">Photo-Gallery</a></li>
                            <li><a href="<?= createLink($base_url, 'certificates') ?>">Certificates</a></li>
                        </ul>
                    </li>

                    <li><a href="<?= createLink($base_url, 'Remnants') ?>">Remnants</a></li>
                    <li><a href="<?= createLink($base_url, 'blog') ?>">Blog</a></li>
                    <li><a href="<?= createLink($base_url, 'contact') ?>">Contact</a></li>
                    <li><a href="<?= createLink($base_url, 'BusinessPortal') ?>">login</a></li>
                </ul>
            </div>



            <!-- end menu -->
        </div>
        <!-- end container -->
    </nav>
    <!-- end navbar -->

    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (!navbar) return;
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>


    <style>
        .logo-center {
            width: 90px;
            height: 90px;
            background-color: #f6f6f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-center img {
            width: 100%;

        }
    </style>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.email-btn')?.addEventListener('click', function() {
                gtag('event', 'email_click', {
                    event_category: 'Contact',
                    event_label: 'Email Click'
                });
            });

            document.querySelector('.phone-btn')?.addEventListener('click', function() {
                gtag('event', 'phone_click', {
                    event_category: 'Contact',
                    event_label: 'Phone Click'
                });
            });

            document.querySelector('.location-btn')?.addEventListener('click', function() {
                gtag('event', 'location_click', {
                    event_category: 'Contact',
                    event_label: 'Location Click'
                });
            });
        });
    </script>
    <script>
        window.addEventListener('load', () => {
            const preloader = document.querySelector('.preloader');
            preloader.classList.add('hidden');

   
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 1000); 
        });
    </script>