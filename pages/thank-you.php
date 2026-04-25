<?php
/**
 * pages/thank-you.php — Confirmation page shown after a successful contact submit.
 * Doubles as the GA4 / Google Ads / Meta conversion landing.
 *
 * The contact form's JS redirects here on success (see parts/countertops/script.php
 * and the inline `validateAndSend()` functions in the contact partials).
 */

/* Don't index this page — it's a private confirmation, not landing content. */
if (!headers_sent()) {
    header('X-Robots-Tag: noindex, nofollow', true);
}

$base    = $base_url ?? '/';
$ga4Id   = setting('tracking_ga4_id');
$gAdsId  = setting('tracking_google_ads_id');
$gAdsLbl = setting('tracking_google_ads_inquiry_label'); // optional conversion label
$metaPx  = setting('tracking_meta_pixel');
$tikTok  = setting('tracking_tiktok_pixel');
?>

<header class="page-header" data-background="images/Granit-Img/ccccc.png">
    <div class="container ee">
        <h1>Thank you</h1>
        <p class="page-subtitle">Your message is on its way to our team — we'll be in touch shortly.</p>
    </div>
</header>

<section class="ty" aria-label="Form submitted successfully">
    <div class="ty-container">

        <div class="ty-card">
            <div class="ty-check" aria-hidden="true">
                <svg viewBox="0 0 52 52" width="64" height="64">
                    <circle class="ty-check-circle" cx="26" cy="26" r="24" fill="none" stroke="currentColor" stroke-width="2"/>
                    <path class="ty-check-mark" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 27l8 8 16-18"/>
                </svg>
            </div>
            <h2 class="ty-title">We got your message.</h2>
            <p class="ty-sub">
                Our team will review your inquiry and reach out
                <strong>within one business day</strong>
                with material ideas, pricing, and a timeline.
            </p>

            <div class="ty-meta">
                <div class="ty-meta-item">
                    <i class="far fa-clock"></i>
                    <div>
                        <strong>Hours</strong>
                        <small>Mon–Fri 9am–6pm · Sat 9am–3pm</small>
                    </div>
                </div>
                <div class="ty-meta-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <strong>Need it sooner?</strong>
                        <small><a href="tel:+14698140555">(469) 814-0555</a></small>
                    </div>
                </div>
                <div class="ty-meta-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong>Visit our showroom</strong>
                        <small>1225 W College Ave #616, Carrollton, TX</small>
                    </div>
                </div>
            </div>

            <div class="ty-cta-row">
                <a class="ty-btn ty-btn-primary" href="<?= htmlspecialchars($base) ?>Inventory">
                    <i class="fas fa-arrow-right"></i> Browse our inventory
                </a>
                <a class="ty-btn ty-btn-ghost" href="<?= htmlspecialchars($base) ?>Home">
                    <i class="fas fa-home"></i> Back to home
                </a>
            </div>
        </div>

        <div class="ty-next">
            <h3>While you wait — explore</h3>
            <div class="ty-next-grid">
                <a href="<?= htmlspecialchars($base) ?>kitchenVisualizer" class="ty-next-card">
                    <div class="ty-next-icon"><i class="fas fa-palette"></i></div>
                    <div>
                        <strong>Kitchen Visualizer</strong>
                        <small>See stones rendered in real kitchens</small>
                    </div>
                </a>
                <a href="<?= htmlspecialchars($base) ?>blog" class="ty-next-card">
                    <div class="ty-next-icon"><i class="fas fa-book-open"></i></div>
                    <div>
                        <strong>Design Journal</strong>
                        <small>Care guides, trends, and project tips</small>
                    </div>
                </a>
                <a href="<?= htmlspecialchars($base) ?>EdgeType" class="ty-next-card">
                    <div class="ty-next-icon"><i class="fas fa-ruler-combined"></i></div>
                    <div>
                        <strong>Edge Profiles</strong>
                        <small>Pick the perfect finish for your countertop</small>
                    </div>
                </a>
            </div>
        </div>

    </div>
</section>

<?php /* ── Conversion tracking — fires once per page view ── */ ?>
<script>
(function () {
    var transactionId = 'inquiry-' + Date.now();

    <?php if ($ga4Id): ?>
    if (typeof gtag === 'function') {
        gtag('event', 'generate_lead', {
            'event_category': 'engagement',
            'event_label': 'Contact form',
            'value': 1,
            'transaction_id': transactionId
        });
    }
    <?php endif; ?>

    <?php if ($gAdsId && $gAdsLbl): ?>
    if (typeof gtag === 'function') {
        gtag('event', 'conversion', {
            'send_to': '<?= htmlspecialchars($gAdsId) ?>/<?= htmlspecialchars($gAdsLbl) ?>',
            'transaction_id': transactionId
        });
    }
    <?php endif; ?>

    <?php if ($metaPx): ?>
    if (typeof fbq === 'function') {
        fbq('track', 'Lead', { content_name: 'Contact form', value: 1, currency: 'USD' });
    }
    <?php endif; ?>

    <?php if ($tikTok): ?>
    if (typeof ttq === 'object' && ttq.track) {
        ttq.track('SubmitForm', { content_name: 'Contact form' });
    }
    <?php endif; ?>
})();
</script>
