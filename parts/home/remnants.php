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

        <div class="text-center mt-5">
            <a href="<?= createLink($base_url, 'Remnants') ?>" class="link" aria-label="View all granite remnants in stock">View All Remnants <i class="fas fa-caret-right"></i></a>
        </div>
    </div>
</section>
