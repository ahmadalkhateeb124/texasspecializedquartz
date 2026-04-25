<link rel="stylesheet" href="<?= $base_url ?>css/inventory.css">
</head>
<body>

<header class="page-header" data-background="images/Granit-Img/cccc.png" data-stellar-background-ratio="1.15">
  <div class="container ee">
    <h1>Elegance in Stone – <strong>Marble</strong>, <strong>Granite</strong> & <strong>Quartzite</strong> Perfection</h1>
  </div>
</header>

<section class="about-content">
  <div class="container">

    <!-- HERO TEXT -->
    <div class="stone-hero">
      <h2>Premium Natural Stone Slabs —<br><em>Marble</em>, <em>Granite</em> & <em>Quartzite</em></h2>
      <p>At <strong>Texas Specialized Quartz & Granite</strong>, we specialize in supplying a broad range of natural stone slabs and engineered stone surfaces for kitchen countertops, bathroom vanities, wall cladding, flooring, and commercial interiors.</p>
      <p>Our stain-resistant, scratch-resistant, and heat-tolerant surfaces make them a top choice for homeowners, designers, and contractors. With access to over <strong>+10 premium stone slabs</strong>, our experts help you find the perfect surface for your project.</p>
      <div class="phone-link">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
          <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a2 2 0 012-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L9.91 15a16 16 0 006 6l.61-.61a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
        </svg>
        Schedule a consultation: <a href="tel:+14698140555">(469) 814-0555</a>
      </div>
    </div>

    <!-- FEATURES STRIP -->
    <div class="features-strip mt-3">
      <div class="feat-item">
        <div class="feat-label">Material types</div>
        <div class="feat-val">Granite · Marble · Quartzite</div>
      </div>
      <div class="feat-item">
        <div class="feat-label">Inventory</div>
        <div class="feat-val">premium slabs</div>
      </div>
      <div class="feat-item">
        <div class="feat-label">Applications</div>
        <div class="feat-val">Residential & Commercial</div>
      </div>
    </div>

    <!-- ══════════ GRANITE ══════════ -->
    <div class="stone-section-head">
      <h2>All Inventory</h2>
      <span class="slab-count">+10 slabs available</span>
    </div>
    <div class="stone-divider"></div>
 <div class="row g-4">
<?php
require_once __DIR__ . '/../BusinessPortal/src/bootstrap.php';
$slabs = (new InventorySlabRepository($pdo))->allActive();
$imgFallback = 'onerror="this.parentElement.innerHTML=\'<div class=\\\'img-placeholder\\\'><svg width=\\\'36\\\' height=\\\'36\\\' viewBox=\\\'0 0 24 24\\\' fill=\\\'none\\\' stroke=\\\'#bbb\\\' stroke-width=\\\'1\\\'><rect x=\\\'3\\\' y=\\\'3\\\' width=\\\'18\\\' height=\\\'18\\\' rx=\\\'2\\\'/><circle cx=\\\'8.5\\\' cy=\\\'8.5\\\' r=\\\'1.5\\\'/><path d=\\\'M21 15l-5-5L5 21\\\'/></svg></div>\'"';
?>
<?php foreach ($slabs as $slab): ?>
      <div class="col-lg-4 col-md-6 mb-3">
        <div class="stone-card">
          <div class="img-wrap">
            <img src="<?= htmlspecialchars($slab['image']) ?>"
                 alt="<?= htmlspecialchars($slab['name']) ?>" loading="lazy"
                 <?= $imgFallback ?>>
            <span class="qty-badge">Availability <?= (int)$slab['quantity'] ?> slabs</span>
          </div>
          <div class="stone-info">
            <h4><?= htmlspecialchars($slab['name']) ?></h4>
            <div class="stone-meta">
              <span class="tag"><?= htmlspecialchars($slab['name']) ?></span>
              <span class="tag size"><?= htmlspecialchars($slab['size']) ?></span>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    </div>
  </div>
</section>

</body>
</html>
