<?php

/**
 * admin/includes/sidebar.php
 * All navigation links for the admin panel.
 */

// متغيرات عامة موجودة
$_cu       = $currentUser ?? [];
$_initials = strtoupper(substr($_cu['name'] ?? 'A', 0, 2));
$_page     = basename($_SERVER['PHP_SELF'], '.php');

function _nav(string $page): string
{
    global $_page;
    return $page === $_page ? 'active' : '';
}

// --- بيانات الادمن خاصة بالـ sidebar فقط، بدون override للمتغيرات الأصلية ---
function getAdminDataSidebar()
{
    if (!empty($_SESSION['user_id'])) {
        try {
            global $pdo;
            $stmt = $pdo->prepare("SELECT id, fullname, username, email, avatar FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }
    return [];
}

// جلب بيانات الادمن للـ sidebar فقط
$adminSidebar = getAdminDataSidebar();

// متغيرات خاصة بالـ sidebar لتجنب أي override
$_cuSidebar       = $adminSidebar;
$_initialsSidebar = strtoupper(substr($_cuSidebar['fullname'] ?? 'A', 0, 2));

?>
<aside class="app-sidebar" id="appSidebar">

    <!-- Brand -->
    <a href="index.php" class="sidebar-brand" style="text-decoration:none;">
        <div class="sidebar-avatar"> <img src="../auth/uploads/<?= htmlspecialchars($adminSidebar['avatar'] ?? 'default-avatar.png') ?>"
                style="border-radius:50%; border:3px solid var(--color-surface); object-fit:cover;"
                alt="Avatar"></div>
        <div>
            <div class="sidebar-brand-name">
                <?php
                if (!empty($adminSidebar['fullname'])) {
                    $words = explode(' ', $adminSidebar['fullname']);
                    $initials = '';
                    foreach ($words as $word) {
                        $initials .= mb_substr($word, 0, 1); // يدعم الحروف العربية كمان
                    }
                    echo htmlspecialchars(strtoupper($initials)); // يحولهم لكابيتال إذا بدك
                } else {
                    echo 'Granite Artists';
                }
                ?>
            </div>
            <div class="sidebar-brand-tag">Admin</div>
        </div>
    </a>

    <!-- Nav -->
    <nav class="sidebar-nav">

        <p class="nav-section-title">Overview</p>
        <ul class="nav-list">
            <li>
                <a href="index.php" class="nav-link <?= _nav('index') ?>">
                    <span class="nav-icon"><i class='bx bxs-dashboard'></i></span>
                    Dashboard
                </a>
            </li>
        </ul>

        <p class="nav-section-title">Fabrication</p>
        <ul class="nav-list">
            <li>
                <a href="orders.php" class="nav-link <?= _nav('orders') ?>">
                    <span class="nav-icon"><i class='bx bx-file'></i></span>
                    Orders
                </a>
            </li>
            <li>
                <a href="order-new.php" class="nav-link <?= _nav('order-new') ?>">
                    <span class="nav-icon"><i class='bx bx-plus-circle'></i></span>
                    New Order
                </a>
            </li>
        </ul>

        <p class="nav-section-title">Catalog</p>
        <ul class="nav-list">
            <li>
                <a href="products.php" class="nav-link <?= _nav('products') ?>">
                    <span class="nav-icon"><i class='bx bx-cube-alt'></i></span>
                    Remnants
                </a>
            </li>
            <li>
                <a href="products-new.php" class="nav-link <?= _nav('products-new') ?>">
                    <span class="nav-icon"><i class='bx bx-image-add'></i></span>
                    Add Remnants
                </a>
            </li>
        </ul>

        <p class="nav-section-title">Customers</p>
        <ul class="nav-list">
            <li>
                <a href="customers.php" class="nav-link <?= _nav('customers') ?>">
                    <span class="nav-icon"><i class='bx bx-group'></i></span>
                    All Customers
                </a>
            </li>
            <li>
                <a href="customers-new.php" class="nav-link <?= _nav('customers-new') ?>">
                    <span class="nav-icon"><i class='bx bx-user-plus'></i></span>
                    Add Customer
                </a>
            </li>
        </ul>

        <p class="nav-section-title">Resources</p>
        <ul class="nav-list">
            <li>
                <a href="training.php" class="nav-link <?= _nav('training') ?>">
                    <span class="nav-icon"><i class='bx bx-video'></i></span>
                    Video Training
                </a>
            </li>
            <li>
                <a href="priceList.php" class="nav-link <?= _nav('priceList') ?>">
                    <span class="nav-icon"><i class='bx bx-file'></i></span>
                    Price Lists
                </a>
            </li>
        </ul>

        <p class="nav-section-title">Support</p>
        <ul class="nav-list">
            <li>
                <a href="https://mail.google.com/mail/?view=cm&to=ahmad@webkoit.com" target="_blank" class="nav-link">
                    <span class="nav-icon"><i class='bx bx-support'></i></span>
                    Help Center
                </a>
            </li>
        </ul>

    </nav>

    <!-- User footer -->
    <div class="sidebar-footer">
        <div class="dropdown dropup w-100">
            <button class="sidebar-user-btn dropdown-toggle w-100"
                data-bs-toggle="dropdown" type="button" style="border:none;">
                <div class="sidebar-avatar"> <img src="../auth/uploads/<?= htmlspecialchars($adminSidebar['avatar'] ?? 'default-avatar.png') ?>"
                        style="border-radius:50%; border:3px solid var(--color-surface); object-fit:cover;"
                        alt="Avatar"></div>
                <div style="flex:1;min-width:0;">
                    <div class="sidebar-user-name"><?= htmlspecialchars($_cu['name'] ?? 'Admin') ?></div>
                    <div class="sidebar-user-meta">Administrator</div>
                </div>
            </button>
            <ul class="dropdown-menu" style="min-width:200px;margin-bottom:4px;">
                <li>
                    <div class="dropdown-header">
                        <?php
                        if (!empty($adminSidebar['fullname'])) {
                            $words = explode(' ', $adminSidebar['fullname']);
                            $initials = '';
                            foreach ($words as $word) {
                                $initials .= mb_substr($word, 0, 1); // يدعم العربية والإنجليزية
                            }
                            echo htmlspecialchars(strtoupper($initials)); // حروف كبيرة
                        }
                        ?>
                    </div>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="profile.php"><i class='bx bx-user'></i> My Profile</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="../auth/logout.php"><i class='bx bx-log-out'></i> Sign Out</a></li>
            </ul>
        </div>
    </div>

</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>