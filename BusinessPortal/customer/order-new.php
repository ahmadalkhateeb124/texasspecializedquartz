<?php

/**
 * customer/order-new.php — New Fabrication Order (Customer)
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireCustomer();

$currentUser = currentUser();
$pageTitle   = 'New Order';
$breadcrumb  = [['label' => 'My Orders', 'url' => 'orders.php'], ['label' => 'New Order']];
$extraCss    = '<link rel="stylesheet" href="../assets/css/shared/order-form.css">';

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">New Fabrication Order</h1>
                    <p class="page-subtitle">Fill out the form and we'll process your order right away</p>
                </div>
                <div class="page-actions">
                    <a href="orders" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> My Orders
                    </a>
                </div>
            </div>

            <form method="POST" id="orderForm" action="../auth/AddFabricationOrders.php"
                enctype="multipart/form-data" novalidate>

                <div class="row g-4">

                    <div class="col-lg-8">
                        <?php include __DIR__ . '/../includes/partials/orders/form/customer.php'; ?>
                        <?php include __DIR__ . '/../includes/partials/orders/form/sales-rep.php'; ?>
                        <?php include __DIR__ . '/../includes/partials/orders/form/job-areas.php'; ?>

                        <div id="jobSectionsContainer"></div>

                        <?php include __DIR__ . '/../includes/partials/orders/form/notes-attachment.php'; ?>
                    </div>

                    <div class="col-lg-4">
                        <?php include __DIR__ . '/../includes/partials/orders/form/submit-sidebar.php'; ?>
                    </div>

                </div>
            </form>

            <?php include __DIR__ . '/../includes/partials/orders/form/job-section-template.php'; ?>

        </main>

        <script src="../assets/js/shared/order-new.js"></script>

        <?php include __DIR__ . '/includes/footer.php'; ?>
