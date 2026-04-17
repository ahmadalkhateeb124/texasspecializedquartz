<?php
$current_page = basename($_SERVER['REQUEST_URI']); // استخراج اسم الصفحة الحالية
require('header-ini.php');
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#282828" />
    <meta name="google-site-verification" content="ciaB6dQnA93KBhXoYEBlFX5R0ODHyobOopZtgTFI_OA" />

    <title><?php echo $PageTitle; ?></title>
    <meta name="description" content="<?php echo $escapedDescription; ?>">
    <meta name="keywords" content="<?php echo $KeyWords; ?>">
    <meta name="author" content="Texas Specialized Quartz & Granite">
    <!-- Canonical -->
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>" />
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $PageTitle; ?>">
    <meta property="og:description" content="<?php echo $escapedDescription; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $canonicalUrl; ?>">
    <meta property="og:image" content="https://texasspecializedquartz.com/images/Granit-Img/Ba-home2.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/png">    <!-- Robots -->
    <meta http-equiv="Cache-Control" content="public, max-age=604800, immutable">
    <meta name="robots" content="index, follow">
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="https://texasspecializedquartz.com/images/Granit-Img/Ba-home2.png">
    <meta name="twitter:site" content="@graniteartists">
    <meta name="twitter:creator" content="@graniteartists">
    <meta name="twitter:title" content="<?php echo $PageTitle; ?>">
    <meta name="twitter:description" content="<?php echo $escapedDescription; ?>">
    <!-- Favicons -->
    <link href="images/Granit-Img/logo-gg.jpg" rel="apple-touch-icon" sizes="144x144">
    <link href="images/Granit-Img/logo-gg.jpg" rel="apple-touch-icon" sizes="114x114">
    <link href="images/Granit-Img/logo-gg.jpg" rel="apple-touch-icon" sizes="72x72">
    <link href="images/Granit-Img/logo-gg.jpg" rel="apple-touch-icon">
    <link href="images/Granit-Img/logo-gg.jpg" rel="shortcut icon">
    <!-- Critical CSS -->
    <link rel="stylesheet" href="<?= $base_url ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>css/style.css">
    <link rel="stylesheet" href="<?= $base_url ?>css/megamenu.css">
    <!-- Non-critical CSS async -->
    <link rel="preload" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="<?= $base_url ?>css/fontawesome.min.css" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="<?= $base_url ?>css/animate.min.css" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="<?= $base_url ?>css/fancybox.min.css" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="<?= $base_url ?>css/odometer.min.css" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="<?= $base_url ?>css/swiper.min.css" as="style" onload="this.rel='stylesheet'">
    <!-- Fallback for no-JS -->
    <noscript>
        <link rel="stylesheet" href="<?= $base_url ?>css/fontawesome.min.css">
        <link rel="stylesheet" href="<?= $base_url ?>css/animate.min.css">
        <link rel="stylesheet" href="<?= $base_url ?>css/fancybox.min.css">
        <link rel="stylesheet" href="<?= $base_url ?>css/odometer.min.css">
        <link rel="stylesheet" href="<?= $base_url ?>css/swiper.min.css">
    </noscript>
    
   
    <?php include 'Profuct.php'; ?>
    <?php include 'Json.php'; ?>
    <?php include 'JSON-LD.php'; ?>
    <?php include 'Product.php'; ?>
  <meta name="google-site-verification" content="JQLG-pg9KyUIQwwq6zVOPs34wtv-V-WzGF2vZ0_b9fA" />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8C27Y2TZ8H"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-8C27Y2TZ8H');
    </script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-18086537849">
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'AW-18086537849');
    </script>
</head>

<body>

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
    <div class="side-navigation">

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
                        <li><a href="<?= createLink($base_url, 'Reviews') ?>">Reviews</a></li>

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
            <figure> <img src="/images/Granit-Img/logo.png" alt="Logo" loading="lazy"> </figure>
            <p>Enhancing the quality of your living spaces with premium granite solutions, Texas Specialized Quartz & Granite TX is your go-to source for luxury countertops and exceptional craftsmanship</p>
            <ul class="gallery">
                <li><a href="images/Granit-Img/countertop.png" data-fancybox><img src="images/Granit-Img/countertop.png" alt="Image" loading="lazy"></a></li>
                <li><a href="images/Granit-Img/countertop2.png" data-fancybox><img src="images/Granit-Img/countertop2.png" alt="Image" loading="lazy"></a></li>
                <li><a href="images/Granit-Img/countertop3.png" data-fancybox><img src="images/Granit-Img/countertop3.png" alt="Image" loading="lazy"></a></li>
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
    <nav class="navbar ">

        <div class="container">
            <div class="upper-side d-lg-none ">
                <div class="logo logo-center ">
                    <a href="<?= createLink($base_url, 'Home') ?>">
                        <img src="/images/Granit-Img/logo.png" alt="Logo" loading="lazy">
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
                            <img src="/images/Granit-Img/logo.png" alt="Logo" loading="lazy">
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
                            <li><a href="<?= createLink($base_url, 'Reviews') ?>">Reviews</a></li>
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