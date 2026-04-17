<?php

/**
 * customer/includes/sidebar.php
 * Requires: $currentUser, $customerMeta (optional: company_name, company_id)
 */
$_cu       = $currentUser ?? [];
$_initials = strtoupper(substr($_cu['name'] ?? 'C', 0, 2));
$_page     = basename($_SERVER['PHP_SELF'], '.php');
$_company  = $customerMeta['company_name'] ?? '';

function _custSidebarActive(string $p): string
{
    global $_page;
    return $p === $_page ? 'active' : '';
}
?>
<?php
$adminAvatar = 'default-avatar.png';
$adminData = [];

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([2]);
    $adminData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($adminData) {
        $filePath = __DIR__ . '/../../auth/uploads/' . $adminData['avatar'];

        if (!empty($adminData['avatar']) && file_exists($filePath)) {
            $adminAvatar = $adminData['avatar'];
        }
    }
} catch (PDOException $e) {
    // echo $e->getMessage();
}
?>
<aside class="app-sidebar" id="appSidebar">

    <!-- ── Brand ────────────────────────────────── -->
    <a href="index.php" class="sidebar-brand" style="text-decoration:none;">
        <div class="sidebar-brand-logo">
            <img src="../auth/uploads/<?= htmlspecialchars($adminAvatar) ?>"
                alt="Avatar"
                style="width:100%; height:100%; object-fit:cover; border-radius:16px;">
        </div>
        <div>
            <div class="sidebar-brand-name">TSQ&G </div>
            <span class="sidebar-brand-tag">Portal</span>
        </div>
    </a>


    <!-- ── Navigation ───────────────────────────── -->
    <nav class="sidebar-nav">

        <div class="nav-label">Menu</div>
        <ul class="nav-list">
            <li>
                <a href="index.php" class="nav-link <?= _custSidebarActive('index') ?>">
                    <span class="nav-icon"><i class='bx bxs-dashboard'></i></span>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="orders.php" class="nav-link <?= _custSidebarActive('orders') ?>">
                    <span class="nav-icon"><i class='bx bx-file'></i></span>
                    My Orders
                </a>
            </li>
            <li>
                <a href="order-new.php" class="nav-link <?= _custSidebarActive('order-new') ?>">
                    <span class="nav-icon"><i class='bx bx-plus-circle'></i></span>
                    New Order
                </a>
            </li>
            <li>
                <a href="profile.php" class="nav-link <?= _custSidebarActive('profile') ?>">
                    <span class="nav-icon"><i class='bx bx-user'></i></span>
                    My Profile
                </a>
            </li>
        </ul>

        <div class="nav-label" style="margin-top:8px;">Resources</div>
        <ul class="nav-list">
            <li>
                <a href="training.php" class="nav-link <?= _custSidebarActive('training') ?>">
                    <span class="nav-icon"><i class='bx bx-video'></i></span>
                    Video Training
                </a>
            </li>
            <li>
                <a href="priceList.php" class="nav-link <?= _custSidebarActive('priceList') ?>">
                    <span class="nav-icon"><i class='bx bx-file'></i></span>
                    Price Lists
                </a>
            </li>
        </ul>

        <div class="nav-label" style="margin-top:8px;">Support</div>
        <ul class="nav-list">
            <li>
                <a href="https://mail.google.com/mail/?view=cm&to=<?php echo urlencode(!empty($adminData['email']) ? $adminData['email'] : 'cs@webkoit.com'); ?>" target="_blank" class="nav-link">
                    <span class="nav-icon"><i class='bx bx-envelope'></i></span>
                    Contact Us
                </a>
            </li>
        </ul>

    </nav>

    <!-- ── User footer ───────────────────────────── -->
    <div class="sidebar-footer">
        <div class="dropdown dropup">
            <button class="sidebar-user-btn "
                aria-expanded="false" type="button">
                <div class="sidebar-avatar"><?= htmlspecialchars($_initials) ?></div>
                <div style="flex:1;min-width:0;">
                    <div class="sidebar-user-name"><?= htmlspecialchars($_cu['name'] ?? 'Customer') ?></div>
                    <div class="sidebar-user-role">Customer</div>
                </div>
            </button>

        </div>
    </div>

</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>