<?php
include './BusinessPortal/partials/conn.php';

// جلب المنتجات
$stmt = $pdo->prepare("SELECT * FROM products ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<header class="page-header" style="background-image: url('images/Granit-Img/cc.png');" data-stellar-background-ratio="1.15">
    <div class="container ee">
        <h1>Luxury Stone <strong>Remnants</strong> – Premium Materials at Exceptional Value</h1>
        <p class="page-subtitle">Shop affordable granite and quartz remnants – premium quality at discounted prices</p>
    </div>
</header>

<section class="about-content py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2><span>Affordable </span><strong>Granite Remnants in Texas</strong></h2>
            </div>
            <div class="col-12">
                <p>Discover top-quality <strong>granite, quartz,</strong> and <strong>marble remnants</strong> perfect for kitchen and bathroom projects—at a fraction of the cost.</p>
                <p>Our <strong>remnant pieces</strong> are ideal for vanities, tabletops, fireplace surrounds, and more. Get premium materials with big savings!</p>
            </div>
            <div class="col-12">
                <h3>Why Choose Remnants?</h3>
                <ul>
                    <li><strong>Budget-Friendly:</strong> Save up to 70% vs. full slabs</li>
                    <li><strong>Eco-Friendly:</strong> Repurpose high-end stone and reduce waste</li>
                    <li><strong>Perfect for Small Projects:</strong> Kitchens, bathrooms, shelves, and more</li>
                </ul>
            </div>
            <div class="col-12">
                <p>Visit our Texas showroom or <strong>call us at
                        <a style="color:#767676;" href="tel:+14698140555">(469) 814-0555</a></strong> to find the perfect granite remnant today.
                </p>
            </div>
        </div>
    </div>
</section>


<section class="sales-offices py-5">
    <div class="container">
        <?php if (!empty($result)): ?>
            <div class="row g-4">
                <?php foreach ($result as $row):
                    $imageSrc = !empty($row['image'])
                        ? './BusinessPortal/assets/products/' . htmlspecialchars($row['image'])
                        : 'images/faces/face1.jpg';
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="product-card-modern">
                            <div class="product-image-modern">
                                <img src="<?= $imageSrc; ?>" alt="<?= htmlspecialchars($row['title']); ?>">

                            </div>
                            <div class="product-body-modern">
                                <h5 class="product-title-modern"><?= htmlspecialchars($row['title']); ?></h5>
                                <div class="product-info-modern">
                                    <span>Qty: <?= htmlspecialchars($row['quantity']); ?></span>
                                    <span>Color: <?= htmlspecialchars($row['color_name']); ?></span>
                                    <span>Size: <?= htmlspecialchars($row['size']); ?></span>
                                    <span>ID Number: <?= htmlspecialchars($row['id']); ?></span>
                                </div>

                                <?php
                                $status = htmlspecialchars($row['availability']);
                                $statusText = $status; // النص النهائي ليظهر
                                $bg_color = '#f0f0f0';  // خلفية افتراضية فاتحة
                                $text_color = '#555';    // نص افتراضي

                                if ($status === 'Reserved' && !empty($row['status_updated_at'])) {
                                    $now = new DateTime();
                                    $holdDate = new DateTime($row['status_updated_at']);
                                    $daysLeft = max(0, 5 - $now->diff($holdDate)->days);
                                    $statusText = $daysLeft > 0 ? "Hold ($daysLeft day left)" : "New";

                                    // ألوان خاصة بالـHold وNew
                                    $bg_color = $daysLeft > 0 ? 'rgba(255, 152, 0, 0.1)' : 'rgba(40, 167, 69, 0.1)'; // برتقالي فاتح / أخضر فاتح
                                    $text_color = $daysLeft > 0 ? '#ff9800' : '#28a745';
                                } else {
                                    switch ($status) {
                                        case 'Available in store':
                                            $bg_color = 'rgba(40, 167, 69, 0.1)'; // أخضر فاتح
                                            $text_color = '#28a745';
                                            break;
                                        case 'Reserved':
                                            $bg_color = 'rgba(255, 152, 0, 0.1)'; // برتقالي فاتح
                                            $text_color = '#ff9800';
                                            break;
                                        case 'Sold Out':
                                            $bg_color = 'rgba(220, 53, 69, 0.1)'; // أحمر فاتح
                                            $text_color = '#dc3545';
                                            break;
                                        default:
                                            $bg_color = 'rgba(128, 128, 128, 0.1)'; // رمادي فاتح
                                            $text_color = '#555';
                                    }
                                }
                                ?>

                                <div class="product-status-modern" style="background-color: <?= $bg_color ?>; color: <?= $text_color ?>; padding: 5px 10px; border-radius: 5px; display: inline-block; font-size: 12px ">
                                    <?= $statusText ?>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No products found.</p>
        <?php endif; ?>
    </div>
</section>

<style>
    /* Container */
    .sales-offices {
        padding-top: 60px;
        padding-bottom: 60px;
    }

    /* Product Card Modern */
    .product-card-modern {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.08);
        transition: transform 0.4s ease, box-shadow 0.4s ease;
        position: relative;
        margin-bottom: 30px;
    }

    .product-card-modern:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 30px rgba(0, 0, 0, 0.15);
    }

    /* Product Image */
    .product-image-modern {
        position: relative;
        overflow: hidden;
    }

    .product-image-modern img {
        width: 100%;
        height: 230px;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .product-card-modern:hover .product-image-modern img {
        transform: scale(1.05);
    }

    /* Overlay Button */
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
        opacity: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.3s ease;
    }

    .product-card-modern:hover .overlay {
        opacity: 1;
    }

    .btn-view {
        background-color: #ff7f50;
        color: #fff;
        padding: 8px 18px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: background 0.3s ease;
    }

    .btn-view:hover {
        background-color: #ff5722;
    }

    /* Product Body */
    .product-body-modern {
        padding: 15px 20px;
    }

    .product-title-modern {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: #333;
    }

    .product-info-modern {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 0.95rem;
        color: #555;
        margin-bottom: 10px;
    }

    .product-info-modern span {
        background: #f3f3f3;
        padding: 4px 8px;
        border-radius: 8px;
    }



    /* Responsive adjustments */
    @media (max-width: 992px) {
        .product-image-modern img {
            height: 200px;
        }
    }

    @media (max-width: 576px) {
        .product-image-modern img {
            height: 180px;
        }
    }
</style>