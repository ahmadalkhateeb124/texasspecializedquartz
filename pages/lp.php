<?php
/**
 * pages/lp.php — Google Ads landing page.
 *
 * Single conversion goal: capture the contact form.
 * No primary navbar / footer (cleaner = higher Quality Score).
 * Not indexed by search engines (noindex) — paid traffic only.
 */
require_once __DIR__ . '/../BusinessPortal/src/bootstrap.php';

if (!headers_sent()) {
    header('X-Robots-Tag: noindex, follow', true);
}

$pdo = $pdo ?? null;

/* Pull a few real items to populate the showcases */
try {
    $slabs    = $pdo ? $pdo->query("SELECT name, image, material_type FROM inventory_slabs WHERE status='active' AND image<>'' ORDER BY sort_order, id LIMIT 6")->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (Throwable $e) { $slabs = []; }
try {
    $sinks    = $pdo ? $pdo->query("SELECT name, image, category FROM sinks WHERE status='active' AND image<>'' ORDER BY sort_order, id LIMIT 4")->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (Throwable $e) { $sinks = []; }
try {
    $rawGallery = $pdo ? $pdo->query("SELECT image, caption FROM gallery_photos WHERE status='active' ORDER BY sort_order, id LIMIT 18")->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (Throwable $e) { $rawGallery = []; }

/* Resolve gallery image paths (admin-uploaded "gx-*" vs seed) and keep only ones that exist on disk. */
$projectRoot = realpath(__DIR__ . '/..');
$gallery = [];
foreach ($rawGallery as $g) {
    $name    = trim((string)($g['image'] ?? ''));
    if ($name === '') continue;
    $relPath = str_starts_with($name, 'gx-')
        ? 'BusinessPortal/assets/gallery/' . $name
        : 'images/photo/' . $name;
    if (file_exists($projectRoot . '/' . $relPath)) {
        $gallery[] = ['rel' => $relPath, 'caption' => $g['caption'] ?? ''];
        if (count($gallery) >= 9) break;
    }
}

$base = $base_url ?? '/';
?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-8C27Y2TZ8H"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-8C27Y2TZ8H');
</script>
<!-- Tag the body so we can hide nav/footer specifically on this page -->
<script>document.body.classList.add('tx-lp');</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5HGBN8ML');</script>
<!-- End Google Tag Manager -->
<!-- ═══ MINIMAL TOPBAR (just logo + click-to-call) ═══ -->
<header class="lp-topbar">
    <div class="lp-topbar-inner">
        <a href="<?= htmlspecialchars($base) ?>" class="lp-logo">
            <img src="<?= $base ?>images/Granit-Img/logo.png" alt="Texas Specialized Quartz &amp; Granite">
            <span><strong>Texas Specialized</strong> Quartz &amp; Granite</span>
        </a>
        <a href="tel:+14698140555" class="lp-topbar-call">
            <i class="fas fa-phone"></i>
            <span class="lp-topbar-call-label">Call Now</span>
            <strong>(469) 814-0555</strong>
        </a>
    </div>
</header>

<!-- ═══ HERO + LEAD FORM ═══ -->
<section class="lp-hero">
    <!-- Background layers (image + overlay) -->
    <div class="lp-hero-bg" aria-hidden="true"></div>
    <div class="lp-hero-overlay" aria-hidden="true"></div>

    <div class="lp-hero-inner mt-4">

        <div class="lp-hero-text">
            <div class="lp-rating mt-3">
                <span class="lp-stars">★★★★★</span>
                <span class="lp-rating-label">
                    <strong>4.9</strong> &nbsp;·&nbsp; 200+ Texas reviews
                </span>
            </div>

            <h1>
                Premium <span class="lp-h1-accent">Granite, Quartz</span> &amp; Marble Countertops
                <span class="lp-h1-line">Installed in <em>One Week</em></span>
            </h1>
            <p class="lp-sub">
                Hand-selected slabs · CNC fabrication · Lifetime craftsmanship warranty.
                <strong>Free in-home measurement</strong> across DFW.
            </p>

            <ul class="lp-bullets">
                <li><i class="fas fa-check"></i> <span><strong>300+ slabs</strong> in our Carrollton showroom</span></li>
                <li><i class="fas fa-check"></i> <span><strong>Same-week install</strong> — template Mon, install by Friday</span></li>
                <li><i class="fas fa-check"></i> <span><strong>Lifetime warranty</strong> on all craftsmanship</span></li>
                <li><i class="fas fa-check"></i> <span>Serving <strong>Dallas, Plano, Frisco</strong> &amp; all DFW</span></li>
            </ul>

            <!-- Mobile-only CTA pair below bullets -->
            <!--<div class="lp-hero-mobile-cta">-->
            <!--    <a href="#quote" class="lp-mcta lp-mcta-primary">-->
            <!--        <i class="fas fa-arrow-down"></i> Get free quote-->
            <!--    </a>-->
            <!--    <a href="tel:+14698140555" class="lp-mcta lp-mcta-secondary">-->
            <!--        <i class="fas fa-phone"></i> Call now-->
            <!--    </a>-->
            <!--</div>-->

            <div class="lp-trust-mini">
                <div><span class="lp-trust-num">15+</span><span class="lp-trust-lbl">Years experience</span></div>
                <div class="lp-trust-divider"></div>
                <div><span class="lp-trust-num">5,000+</span><span class="lp-trust-lbl">Projects done</span></div>
                <div class="lp-trust-divider"></div>
                <div><span class="lp-trust-num">300+</span><span class="lp-trust-lbl">Slabs in stock</span></div>
            </div>
        </div>

        <!-- LEAD FORM (the conversion goal) -->
        <aside class="lp-form-card" id="quote">
            <div class="lp-form-head">
                <span class="lp-badge">
                    <i class="fas fa-bolt"></i> Free quote · 24-hour reply
                </span>
                <h2>Get your free quote</h2>
                <p>Tell us a bit about your project. We'll respond <strong>within one business day</strong>.</p>
            </div>
            <form method="POST" id="contactForm" name="contactForm" class="lp-form"
                  onsubmit="event.preventDefault(); validateAndSend();">
                <?php include __DIR__ . '/../parts/contact-spam-fields.php'; ?>

                <div class="lp-field">
                    <input type="text" name="name" id="name" placeholder=" " required autocomplete="name">
                    <label for="name">Your name</label>
                </div>

                <div class="lp-field-row">
                    <div class="lp-field">
                        <input type="tel" name="subject" id="subject" placeholder=" " required autocomplete="tel">
                        <label for="subject">Phone</label>
                    </div>
                    <div class="lp-field">
                        <input type="email" name="Email" id="Email" placeholder=" " required autocomplete="email">
                        <label for="Email">Email</label>
                    </div>
                </div>

                <div class="lp-field">
                    <textarea name="message" id="message" placeholder=" " rows="3" required></textarea>
                    <label for="message">Project details (kitchen, bath, sq ft, timeline…)</label>
                </div>

                <button type="submit" class="lp-cta-btn">
                    Get my free quote <i class="fas fa-arrow-right"></i>
                </button>

                <p class="lp-form-note">
                    <i class="fas fa-shield-alt"></i> No spam. No obligation. Your info is private.
                </p>

                <div id="success" class="lp-alert lp-alert-success" style="display:none;">
                    <i class="fas fa-check-circle"></i>
                    <strong>Message sent!</strong>
                    <span id="success-detail">We'll be in touch shortly.</span>
                </div>
                <div id="error" class="lp-alert lp-alert-error" style="display:none;"></div>
            </form>
        </aside>

    </div>
</section>

<!-- ═══ MOBILE STICKY CTA BAR (always visible at bottom) ═══ -->
<!--<div class="lp-mobile-bar" aria-hidden="true">-->
<!--    <a href="tel:+14698140555" class="lp-mb-call">-->
<!--        <i class="fas fa-phone"></i>-->
<!--        <span><strong>Call</strong><br>Now</span>-->
<!--    </a>-->
<!--    <a href="#quote" class="lp-mb-quote">-->
<!--        <i class="fas fa-arrow-right"></i>-->
<!--        <span>Get my free quote</span>-->
<!--    </a>-->
<!--</div>-->

<!-- ═══ TRUST STRIP ═══ -->
<section class="lp-trust">
    <div class="lp-trust-inner">
        <div class="lp-trust-item">
            <div class="lp-trust-icon"><i class="fas fa-medal"></i></div>
            <div>
                <strong>Lifetime warranty</strong>
                <span>On all craftsmanship</span>
            </div>
        </div>
        <div class="lp-trust-item">
            <div class="lp-trust-icon"><i class="fas fa-shipping-fast"></i></div>
            <div>
                <strong>Same-week install</strong>
                <span>Template Monday → install by Friday</span>
            </div>
        </div>
        <div class="lp-trust-item">
            <div class="lp-trust-icon"><i class="fas fa-tape"></i></div>
            <div>
                <strong>Free measurement</strong>
                <span>In-home digital templating</span>
            </div>
        </div>
        <div class="lp-trust-item">
            <div class="lp-trust-icon"><i class="fas fa-home"></i></div>
            <div>
                <strong>Local since 2010</strong>
                <span>Carrollton, TX showroom</span>
            </div>
        </div>
    </div>
</section>

<!-- ═══ PHOTO GALLERY (BIG — the user's priority) ═══ -->
<section class="lp-gallery">
    <div class="lp-gallery-inner">
        <div class="lp-section-head">
            <span class="lp-eyebrow lp-eyebrow-dark">— Real Texas Projects</span>
            <h2>500+ kitchens delivered. <em>Yours could be next.</em></h2>
            <p>Real granite, quartz and marble installs across Dallas, Plano, Frisco and beyond.</p>
        </div>

        <?php
        /* If we don't have at least 6 real gallery photos, fall back to existing
           seed images that we know are on disk. */
        if (count($gallery) < 6) {
            $fallbacks = [
                ['rel' => 'images/Granit-Img/countertop.png',     'caption' => 'White granite kitchen countertops'],
                ['rel' => 'images/Granit-Img/countertop2.png',    'caption' => 'Quartz waterfall island'],
                ['rel' => 'images/Granit-Img/countertop3.png',    'caption' => 'Marble bathroom vanity'],
                ['rel' => 'images/Granit-Img/countertop (3).png', 'caption' => 'Custom granite kitchen'],
                ['rel' => 'images/Granit-Img/countertop (4).png', 'caption' => 'Quartzite kitchen island'],
                ['rel' => 'images/Granit-Img/countertop (5).png', 'caption' => 'Modern marble countertop'],
                ['rel' => 'images/Granit-Img/countertops.png',    'caption' => 'Premium stone countertops'],
                ['rel' => 'images/Granit-Img/countertops (1).png','caption' => 'Granite slab installation'],
                ['rel' => 'images/Granit-Img/countertops (2).png','caption' => 'Modern kitchen remodel'],
                ['rel' => 'images/Granit-Img/countertops (3).png','caption' => 'Texas custom countertops'],
                ['rel' => 'images/Granit-Img/countertopm.png',    'caption' => 'Quartz countertop close-up'],
            ];
            foreach ($fallbacks as $fb) {
                if (count($gallery) >= 9) break;
                if (file_exists($projectRoot . '/' . $fb['rel'])) {
                    $gallery[] = $fb;
                }
            }
        }
        ?>
        <div class="lp-gallery-grid">
            <?php foreach ($gallery as $i => $g):
                $imgSrc = $base . $g['rel'];
                $caption = $g['caption'] ?: 'Texas countertop project';
            ?>
                <a class="lp-gallery-item lp-gallery-item-<?= ($i % 5) + 1 ?>"
                   href="<?= htmlspecialchars($imgSrc) ?>" data-fancybox="gallery"
                   data-caption="<?= htmlspecialchars($caption) ?>">
                    <img src="<?= htmlspecialchars($imgSrc) ?>"
                         alt="<?= htmlspecialchars($caption) ?>"
                         loading="lazy"
                         onerror="this.parentElement.style.display='none';">
                    <span class="lp-gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="lp-gallery-cta">
            <a href="#quote" class="lp-cta-btn lp-cta-inline">
                Start my project — <span>get free quote</span> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- ═══ INVENTORY PREVIEW ═══ -->
<?php if (!empty($slabs)): ?>
<section class="lp-products">
    <div class="lp-products-inner">
        <div class="lp-section-head">
            <span class="lp-eyebrow">— In-Stock Slabs</span>
            <h2>Pick your exact slab in person.</h2>
            <p>300+ premium granite, quartz, marble and quartzite slabs in our Carrollton showroom.</p>
        </div>
        <div class="lp-products-grid">
            <?php foreach ($slabs as $s):
                $img = $base . ltrim($s['image'], '/');
            ?>
                <div class="lp-product-card">
                    <div class="lp-product-img">
                        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($s['name']) ?>" loading="lazy">
                    </div>
                    <div class="lp-product-body">
                        <span class="lp-product-tag"><?= htmlspecialchars($s['material_type']) ?></span>
                        <h4><?= htmlspecialchars($s['name']) ?></h4>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="lp-section-cta">
            <a href="#quote" class="lp-cta-btn lp-cta-inline">
                Reserve your slab <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══ SINKS ═══ -->
<?php if (!empty($sinks)): ?>
<section class="lp-sinks">
    <div class="lp-products-inner">
        <div class="lp-section-head">
            <span class="lp-eyebrow lp-eyebrow-dark">— Pair With a Sink</span>
            <h2>Stainless, composite and ceramic sinks.</h2>
            <p>Precision-cut to match your countertop seamlessly.</p>
        </div>
        <div class="lp-sinks-grid">
            <?php foreach ($sinks as $s):
                $img = $base . ltrim($s['image'], '/');
            ?>
                <div class="lp-sink-card">
                    <div class="lp-sink-img">
                        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($s['name']) ?>" loading="lazy">
                    </div>
                    <h4><?= htmlspecialchars($s['name']) ?></h4>
                    <span><?= htmlspecialchars($s['category']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ═══ EDGE PROFILES ═══ -->
<section class="lp-edges">
    <div class="lp-products-inner">
        <div class="lp-section-head">
            <span class="lp-eyebrow">— Custom Edges</span>
            <h2>25+ edge profiles. Make it yours.</h2>
        </div>
        <div class="lp-edges-grid">
            <?php
            $edges = [
                ['Straight Edge',  'straightedge-ub.png',   'Clean & modern'],
                ['Quarter Bevel',  'quarter-bevel-ub.png',  'Subtle elegance'],
                ['Quarter Radius', 'quarter-radius-ub.png', 'Soft & safe'],
                ['Half Bullnose',  'half-bullnose-ub.png',  'Curved finish'],
                ['Full Bullnose',  'full-bullnose-ub.png',  'Smooth & rounded'],
                ['Ogee',           'ogee-ub.png',           'Classic luxury'],
            ];
            foreach ($edges as [$label, $file, $desc]):
            ?>
                <div class="lp-edge-card">
                    <img src="<?= $base ?>images/Granit-Img/<?= $file ?>"
                         alt="<?= htmlspecialchars($label) ?> edge profile" loading="lazy">
                    <h4><?= htmlspecialchars($label) ?></h4>
                    <span><?= htmlspecialchars($desc) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="lp-edges-note">
            …and 19 more. We'll review every option in your free consultation.
        </p>
    </div>
</section>

<!-- ═══ PROCESS ═══ -->
<section class="lp-process">
    <div class="lp-products-inner">
        <div class="lp-section-head">
            <span class="lp-eyebrow lp-eyebrow-dark">— How It Works</span>
            <h2>From quote to install — under a week.</h2>
        </div>
        <div class="lp-process-grid">
            <div class="lp-process-step">
                <div class="lp-process-num">1</div>
                <h4>Free Quote</h4>
                <p>Submit the form or call. We'll reach out within 24 hours with material ideas and pricing.</p>
            </div>
            <div class="lp-process-step">
                <div class="lp-process-num">2</div>
                <h4>Slab Visit + Template</h4>
                <p>Pick your exact slab at our Carrollton showroom. Our team templates your kitchen with digital precision.</p>
            </div>
            <div class="lp-process-step">
                <div class="lp-process-num">3</div>
                <h4>Fabricate &amp; Install</h4>
                <p>CNC fabrication starts the same week. Most kitchens are installed in 5–7 days from template.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══ FINAL CTA ═══ -->
<section class="lp-final">
    <div class="lp-final-inner">
        <h2>Ready to start your countertop project?</h2>
        <p>Free in-home measurement. Same-week installation. Lifetime craftsmanship warranty.</p>
        <a href="#quote" class="lp-cta-btn lp-cta-large">
            Get my free quote <i class="fas fa-arrow-right"></i>
        </a>
        <div class="lp-final-meta">
            <a href="tel:+14698140555"><i class="fas fa-phone"></i> (469) 814-0555</a>
            <span>·</span>
            <a href="mailto:Cs@TexasSpecializedQuartz.com"><i class="fas fa-envelope"></i> Cs@TexasSpecializedQuartz.com</a>
            <span>·</span>
            <span class="text-light" ><i class="fas fa-map-marker-alt"></i> 2943 Ladybird Ln, Dallas, TX 75220, United States</span>
        </div>
    </div>
</section>

<!-- ═══ FLOATING CONTACT BUTTONS (always visible) ═══ -->
<div class="lp-floats" aria-hidden="false">
    <a href="tel:+14698140555" class="lp-float lp-float-call" aria-label="Call now">
        <i class="fas fa-phone"></i>
        <span>Call</span>
    </a>
    <!--<a href="sms:+14698140555" class="lp-float lp-float-sms" aria-label="Text us">-->
    <!--    <i class="fas fa-sms"></i>-->
    <!--    <span>Text</span>-->
    <!--</a>-->
    <a href="mailto:Cs@TexasSpecializedQuartz.com" class="lp-float lp-float-mail" aria-label="Email">
        <i class="fas fa-envelope"></i>
        <span>Email</span>
    </a>
</div>

<!-- ═══ JS — form handler + smooth scroll ═══ -->
<script>
(function () {
    /* Smooth-scroll for in-page anchors */
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', function (e) {
            const id = this.getAttribute('href').slice(1);
            const el = id ? document.getElementById(id) : null;
            if (el) {
                e.preventDefault();
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                /* Focus first input after scroll */
                setTimeout(() => el.querySelector('input[name="name"]')?.focus(), 600);
            }
        });
    });
})();
function validateAndSend() {
    var form = document.getElementById('contactForm');
    var errEl = document.getElementById('error');
    var sucEl = document.getElementById('success');
    var sucDetail = document.getElementById('success-detail');
    
    errEl.style.display = 'none';
    sucEl.style.display = 'none';
    
    if (!form.checkValidity()) {
        errEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> Please fill in all fields correctly.';
        errEl.style.display = 'block';
        return;
    }
    
    var fd = new FormData(form);
    var btn = form.querySelector('button[type="submit"]');
    var originalLabel = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Sending… <i class="fas fa-spinner fa-spin"></i>';
    
    fetch('<?= htmlspecialchars($base) ?>PHPMail/Inquiry.php', { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                sucDetail.innerHTML = 'We\'ll contact you within <strong>1 business day</strong>.';
                sucEl.style.display = 'block';
                sucEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                form.reset();
                /* Redirect to thank-you after 3 seconds */
                setTimeout(function () {
                    window.location.href = '<?= htmlspecialchars($base) ?>thank-you';
                }, 3000);
                return;
            }
            errEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + (data.message || 'Something went wrong. Please try again.');
            errEl.style.display = 'block';
            errEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        })
        .catch(function () {
            errEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> Network error. Please call <strong>(469) 814-0555</strong>.';
            errEl.style.display = 'block';
        })
        .finally(function () {
            btn.disabled = false;
            btn.innerHTML = originalLabel;
        });
}
</script>
