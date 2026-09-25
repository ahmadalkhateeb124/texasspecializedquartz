<?php
require_once __DIR__ . '/../BusinessPortal/src/bootstrap.php';
$result = (new ProductRepository($pdo))->all();

$DOMAIN = 'https://texasspecializedquartz.com';
?>
<link rel="stylesheet" href="<?= $base_url ?>css/home.css">

<?php include __DIR__ . '/../parts/home/hero.php'; ?>
<?php include __DIR__ . '/../parts/home/intro.php'; ?>
<!--<?php include __DIR__ . '/../parts/home/logos.php'; ?>-->
<?php include __DIR__ . '/../parts/home/services.php'; ?>
<?php include __DIR__ . '/../parts/home/about-calculator.php'; ?>

<?php include __DIR__ . '/../parts/home/gallery.php'; ?>
<?php include __DIR__ . '/../parts/home/customization.php'; ?>
<?php include __DIR__ . '/../parts/home/remnants.php'; ?>
<!--<?php include __DIR__ . '/../parts/home/certificates.php'; ?>-->
<?php include __DIR__ . '/../parts/home/articles.php'; ?>
<?php include __DIR__ . '/../parts/home/structured-data.php'; ?>
