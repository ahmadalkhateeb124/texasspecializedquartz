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

    <?php
    require_once __DIR__ . '/../BusinessPortal/src/bootstrap.php';
    $slabs = (new InventorySlabRepository($pdo))->allActive();
    $materials = array_values(array_unique(array_filter(array_column($slabs, 'material_type'))));
    ?>

    <!-- ══════════ INVENTORY ══════════ -->
    <div class="inv-head">
      <div>
        <span class="inv-eyebrow">In stock now</span>
        <h2>All Inventory</h2>
      </div>
      <span class="inv-count"><?= count($slabs) ?> slabs available</span>
    </div>

    <?php if (count($materials) > 1): ?>
    <div class="inv-filters" role="group" aria-label="Filter by material">
      <button type="button" class="inv-filter is-active" data-filter="all">All</button>
      <?php foreach ($materials as $m): ?>
      <button type="button" class="inv-filter" data-filter="<?= htmlspecialchars($m) ?>"><?= htmlspecialchars(ucfirst($m)) ?></button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="inv-grid">
    <?php foreach ($slabs as $slab):
      $qty = (int)$slab['quantity'];
      $name = htmlspecialchars($slab['name']);
      $img = htmlspecialchars($slab['image']);
      $material = htmlspecialchars($slab['material_type']);
    ?>
      <article class="inv-card" data-material="<?= $material ?>">
        <a class="inv-media" href="<?= $img ?>" data-fancybox="inventory" data-caption="<?= $name ?> · <?= htmlspecialchars($slab['size']) ?>">
          <img src="<?= $img ?>" alt="<?= $name ?>" loading="lazy"
               onerror="this.remove()">
          <span class="inv-material"><?= htmlspecialchars(ucfirst($slab['material_type'])) ?></span>
          <span class="inv-zoom" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35M11 8v6M8 11h6"/></svg>
          </span>
        </a>
        <div class="inv-body">
          <div class="inv-title">
            <h3><?= $name ?></h3>
            <span class="inv-stock<?= $qty <= 5 ? ' is-low' : '' ?>">
              <i></i><?= $qty ?> <?= $qty === 1 ? 'slab' : 'slabs' ?> left
            </span>
          </div>
          <dl class="inv-specs">
            <div><dt>Thickness</dt><dd><?= htmlspecialchars($slab['size']) ?></dd></div>
            <div><dt>Material</dt><dd><?= htmlspecialchars(ucfirst($slab['material_type'])) ?></dd></div>
          </dl>
          <a class="inv-cta" href="<?= $base_url ?>contact">
            Request a quote
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
        </div>
      </article>
    <?php endforeach; ?>
    </div>
  </div>
</section>

<script>
document.querySelectorAll('.inv-filter').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var f = btn.dataset.filter;
    document.querySelectorAll('.inv-filter').forEach(function (b) { b.classList.toggle('is-active', b === btn); });
    document.querySelectorAll('.inv-card').forEach(function (card) {
      card.hidden = f !== 'all' && card.dataset.material !== f;
    });
  });
});
</script>

</body>
</html>
