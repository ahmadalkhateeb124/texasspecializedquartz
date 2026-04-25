<?php
$phone = setting('business_phone', '(469) 814-0555');
$email = setting('business_email', 'Cs@TexasSpecializedQuartz.com');
$addr  = setting('business_address', '1225 W College Ave #616');
$city  = setting('business_city', 'Carrollton');
$state = setting('business_state', 'TX');
$zip   = setting('business_zip', '75006');
$hours = setting('business_hours', 'Mon-Fri: 9:00 AM - 6:00 PM | Sat: 9:00 AM - 3:00 PM');
$bizName = setting('business_name', 'Texas Specialized Quartz & Granite');
$tagline = setting('business_tagline', 'Premium Granite Countertops');
?>
<footer class="tx-footer">
    <div class="tx-footer-inner">

        <!-- Brand column -->
        <div class="tx-footer-col tx-footer-brand">
            <a href="<?= createLink($base_url, 'Home') ?>" class="tx-footer-logo">
                <img src="<?= $base_url ?>images/Granit-Img/logo.png" alt="<?= htmlspecialchars($bizName) ?> Logo">
            </a>
            <p class="tx-footer-about">
                Premium granite, quartz &amp; marble fabrication and installation — crafted for Texas homes with uncompromising quality since 2005.
            </p>
            <ul class="tx-footer-social">
                <?php if ($fb = setting('social_facebook')): ?>
                    <li><a href="<?= htmlspecialchars($fb) ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                <?php endif; ?>
                <?php if ($ig = setting('social_instagram')): ?>
                    <li><a href="<?= htmlspecialchars($ig) ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
                <?php endif; ?>
                <?php if ($yt = setting('social_youtube')): ?>
                    <li><a href="<?= htmlspecialchars($yt) ?>" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a></li>
                <?php endif; ?>
                <?php if ($tw = setting('social_twitter')): ?>
                    <li><a href="<?= htmlspecialchars($tw) ?>" target="_blank" rel="noopener" aria-label="Twitter"><i class="fab fa-twitter"></i></a></li>
                <?php endif; ?>
                <?php if ($li = setting('social_linkedin')): ?>
                    <li><a href="<?= htmlspecialchars($li) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a></li>
                <?php endif; ?>
                <?php if ($tk = setting('social_tiktok')): ?>
                    <li><a href="<?= htmlspecialchars($tk) ?>" target="_blank" rel="noopener" aria-label="TikTok"><i class="fab fa-tiktok"></i></a></li>
                <?php endif; ?>
            </ul>
        </div>

   

        <!-- Materials -->
        <div class="tx-footer-col">
            <h5>Materials</h5>
            <ul>
                <li><a href="<?= createLink($base_url, 'CountertopMaterials') ?>">Granite Countertops</a></li>
                <li><a href="<?= createLink($base_url, 'Countertops') ?>">Quartz Countertops</a></li>
                <li><a href="<?= createLink($base_url, 'Countertops') ?>">Marble Countertops</a></li>
                <li><a href="<?= createLink($base_url, 'KitchenCountertops') ?>">Kitchen Countertops</a></li>
                <li><a href="<?= createLink($base_url, 'EdgeType') ?>">Edge Types</a></li>
                <li><a href="<?= createLink($base_url, 'Sinks') ?>">Sinks</a></li>
                <li><a href="<?= createLink($base_url, 'InstallationServices') ?>">Installation</a></li>
            </ul>
        </div>

        <!-- Service Areas (Local SEO) -->
        <div class="tx-footer-col">
            <h5>Service Areas</h5>
            <ul>
                <?php
                $footerCities = require __DIR__ . '/locations-data.php';
                $footerPrimary = array_filter($footerCities, fn($c) => ($c['tier'] ?? '') === 'primary');
                foreach ($footerPrimary as $slug => $c):
                ?>
                    <li><a href="<?= htmlspecialchars($base_url . 'locations/' . $slug) ?>">Countertops <?= htmlspecialchars($c['name']) ?>, TX</a></li>
                <?php endforeach; ?>
                <li><a href="<?= htmlspecialchars($base_url) ?>service-areas"><strong class="text-light" >View all Texas cities →</strong></a></li>
            </ul>
        </div>

        <!-- Contact -->
        <div class="tx-footer-col tx-footer-contact">
            <h5>Get in Touch</h5>
            <ul class="tx-footer-contact-list">
                <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <span>
                        <?= htmlspecialchars($addr) ?>,<br>
                        <?= htmlspecialchars($city) ?>, <?= htmlspecialchars($state) ?> <?= htmlspecialchars($zip) ?>
                    </span>
                </li>
                <li>
                    <i class="fas fa-phone"></i>
                    <a href="tel:<?= htmlspecialchars(preg_replace('/[^+\d]/', '', $phone)) ?>"><?= htmlspecialchars($phone) ?></a>
                </li>
                <li>
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a>
                </li>
                <li>
                    <i class="far fa-clock"></i>
                    <span><?= htmlspecialchars($hours) ?></span>
                </li>
            </ul>
        </div>

    </div>

    <div class="tx-footer-bottom">
        <div class="tx-footer-bottom-inner">
            <span class="tx-copy">
                © <?= date('Y') ?> <?= htmlspecialchars($bizName) ?>. All rights reserved.
            </span>
            <span class="tx-tagline"><?= htmlspecialchars($tagline) ?></span>
            <span class="tx-credit">Developed by <a href="https://webkoit.com/" target="_blank" rel="noopener">Webkoit</a></span>
        </div>
    </div>
</footer>
<!-- end footer -->

<!-- JS FILES -->
<script src="<?= $base_url ?>js/jquery.min.js"></script>
<script src="<?= $base_url ?>js/popper.min.js"></script>
<script src="<?= $base_url ?>js/bootstrap.min.js"></script>
<script src="<?= $base_url ?>js/swiper.min.js"></script>
<script src="<?= $base_url ?>js/fancybox.min.js"></script>
<script src="<?= $base_url ?>js/odometer.min.js"></script>
<script src="<?= $base_url ?>js/isotope.min.js"></script>
<script src="<?= $base_url ?>js/scripts.js"></script>
</body>

</html>