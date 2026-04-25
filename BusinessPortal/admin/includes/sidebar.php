<?php

/**
 * admin/includes/sidebar.php
 * Renders the top brand bar + horizontal tabs (replaces the old left sidebar).
 */

$_cu       = $currentUser ?? [];
$_page     = basename($_SERVER['PHP_SELF'], '.php');

function _nav(string $page): string
{
    global $_page;
    return $page === $_page ? 'active' : '';
}

/* Admin user info for avatar / name in topbar. */
$adminSidebar = [];
try {
    if (!empty($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT id, fullname, username, email, avatar FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $adminSidebar = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }
} catch (PDOException $e) {
    // silent
}
$_initialsSidebar = strtoupper(substr($adminSidebar['fullname'] ?? 'A', 0, 2));

/* Tab definitions — page => [icon, label] */
$adminTabs = [
    'index'      => ['bxs-dashboard', 'Dashboard'],
    'orders'     => ['bx-file',       'Orders'],
    'calendar'   => ['bx-calendar',   'Calendar'],
    'inquiries'  => ['bx-envelope',   'Inbox'],
    'customers'  => ['bx-group',      'Customers'],
    'products'   => ['bx-cube-alt',   'Remnants'],
    'inventory'  => ['bx-cube',       'Inventory'],
    'sinks'      => ['bx-grid-alt',   'Sinks'],
    'gallery'    => ['bx-image',      'Gallery'],
    'blog'       => ['bx-news',       'Blog'],
    'faq'        => ['bx-help-circle','FAQ'],
    'priceList'  => ['bx-file',       'Price Lists'],
    'training'   => ['bx-video',      'Videos'],
    'settings'   => ['bx-cog',        'Settings'],
    'profile'    => ['bx-user',       'Profile'],
];

/* Pages whose tab should stay active while on related sub-pages */
$tabAliases = [
    'orders'    => ['order-new', 'order-edit', 'order-view'],
    'customers' => ['customers-new', 'customers-edit', 'customers-view'],
    'products'  => ['products-new', 'products-edit'],
    'sinks'     => ['sinks-new', 'sinks-edit'],
    'blog'      => ['blog-new', 'blog-edit'],
    'inventory' => ['inventory-new', 'inventory-edit'],
];

$activeTab = $_page;
foreach ($tabAliases as $tab => $aliases) {
    if (in_array($_page, $aliases, true)) {
        $activeTab = $tab;
        break;
    }
}
?>

<!-- ── Top brand bar ───────────────────────────────────────── -->
<header class="topbar">
    <div class="topbar-inner">
        <a href="index" class="topbar-brand">
            <img src="/images/Granit-Img/logo.png" alt="">
            <span class="topbar-brand-name">Texas Specialized Quartz &amp; Granite</span>
        </a>

        <div class="topbar-right">
        <!-- Notifications (placeholder) -->
        <div class="dropdown">
            <button class="topbar-icon-btn" data-bs-toggle="dropdown" title="Notifications">
                <i class='bx bx-bell'></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" style="min-width:280px;">
                <div class="empty-state py-3" style="padding:20px;">
                    <i class='bx bx-bell-off empty-state-icon' style="font-size:28px;"></i>
                    <p class="mb-0 text-muted" style="font-size:12px;">No new notifications</p>
                </div>
            </div>
        </div>

        <div class="topbar-divider"></div>

        <!-- User dropdown -->
        <div class="dropdown">
            <div class="topbar-user dropdown-toggle" data-bs-toggle="dropdown" role="button">
                <div class="topbar-user-avatar">
                    <?php if (!empty($adminSidebar['avatar']) && file_exists(__DIR__ . '/../../auth/uploads/' . $adminSidebar['avatar'])): ?>
                        <img src="../auth/uploads/<?= htmlspecialchars($adminSidebar['avatar']) ?>" alt="">
                    <?php else: ?>
                        <?= htmlspecialchars($_initialsSidebar) ?>
                    <?php endif; ?>
                </div>
                <div class="d-none d-sm-block">
                    <div class="topbar-user-name"><?= htmlspecialchars($adminSidebar['fullname'] ?? 'Admin') ?></div>
                    <div class="topbar-user-role">Administrator</div>
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <div class="px-3 py-2">
                        <div style="font-size:13px;font-weight:600;"><?= htmlspecialchars($adminSidebar['fullname'] ?? 'Admin') ?></div>
                        <div style="font-size:11px;color:var(--text-sub);"><?= htmlspecialchars($adminSidebar['email'] ?? '') ?></div>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="profile"><i class='bx bx-user'></i> My Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="../auth/logout.php"><i class='bx bx-log-out'></i> Sign Out</a></li>
            </ul>
        </div>
        </div>
    </div>
</header>

<!-- ── Horizontal tabs navigation ──────────────────────────── -->
<nav class="tabs-nav">
    <div class="tabs-nav-inner">
        <?php foreach ($adminTabs as $page => [$icon, $label]): ?>
            <a href="<?= $page ?>"
               class="tab-link <?= $activeTab === $page ? 'active' : '' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>
</nav>
