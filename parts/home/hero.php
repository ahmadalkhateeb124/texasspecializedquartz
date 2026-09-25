<section class="tx-hero" aria-labelledby="tx-hero-heading">
   <video class="tx-hero-bg" role="presentation" aria-hidden="true"
       autoplay muted loop playsinline preload="auto">
    <source src="/images/Granit-Img/hero.mp4" type="video/mp4">
</video>
    <div class="tx-hero-inner">
        <div class="tx-hero-eyebrow">Est. 2005 &middot; North Texas</div>
        <h1 id="tx-hero-heading" class="tx-hero-title">
            Custom Granite &amp; Quartz Countertops <em>in Dallas, TX</em>
        </h1>
        <p class="tx-hero-sub text-light">
            Hand-picked granite, quartz &amp; marble slabs, fabricated and installed by master craftsmen across Dallas &amp; the DFW metroplex. A countertop built for decades, not seasons.
        </p>
        <div class="tx-hero-actions">
            <a href="<?= createLink($base_url, 'contact') ?>" class="tx-btn tx-btn-primary" aria-label="Request a free countertop estimate">
                Get a Free Estimate
            </a>
            <a href="<?= createLink($base_url, 'Inventory') ?>" class="tx-btn tx-btn-ghost" aria-label="Browse our stone slab inventory">
                Browse Inventory <i class='bx bx-right-arrow-alt'></i>
            </a>
        </div>
    </div>
    <a href="#intro" class="tx-hero-scroll" aria-label="Scroll to next section">Scroll</a>
</section>
<style>
.tx-hero {
    position: relative;
    overflow: hidden;
    min-height: 100vh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 120px 20px 80px;
    isolation: isolate;
}

.tx-hero-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(.35);
    z-index: -2;
    pointer-events: none;
    background: none !important;
}

.tx-hero-inner {
    position: relative;
    z-index: 1;
    max-width: 980px;
    width: 100%;
    margin: 0 auto;
    text-align: center;
    color: #fff;
}

.tx-hero-title {
    margin: 0 auto 22px;
}

.tx-hero-sub {
    max-width: 820px;
    margin: 0 auto 32px;
}

.tx-hero-actions {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.tx-hero-scroll {
    position: absolute;
    left: 50%;
    bottom: 28px;
    transform: translateX(-50%);
    z-index: 2;
}

</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var v = document.querySelector('.tx-hero-bg');
  if (!v) return;   // هاد السطر بيمنع الخطأ نهائياً

  v.muted = true;
  v.defaultMuted = true;
  v.playsInline = true;

  function tryPlay() {
    var p = v.play();
    if (p) p.catch(function () {
      ['click','touchstart','scroll','keydown'].forEach(function (evt) {
        document.addEventListener(evt, function h() {
          v.play();
          ['click','touchstart','scroll','keydown'].forEach(function (e2) {
            document.removeEventListener(e2, h);
          });
        }, { once: true, passive: true });
      });
    });
  }

  if (v.readyState >= 2) tryPlay();
  else v.addEventListener('loadeddata', tryPlay, { once: true });
});
</script>