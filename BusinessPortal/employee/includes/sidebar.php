<?php

/**
 * employee/includes/sidebar.php
 * Top brand bar + horizontal tabs for the employee portal.
 */

$_cu   = $currentUser ?? [];
$_page = basename($_SERVER['PHP_SELF'], '.php');

if (!function_exists('_nav')) {
    function _nav(string $page): string
    {
        global $_page;
        return $page === $_page ? 'active' : '';
    }
}

$initials = strtoupper(substr($_cu['name'] ?? 'E', 0, 2));

$employeeTabs = [
    'index'    => ['bxs-dashboard', 'Dashboard'],
    'calendar' => ['bx-calendar',   'Calendar'],
    'profile'  => ['bx-user',       'Profile'],
];

$tabAliases = ['index' => ['order-view']];
$activeTab  = $_page;
foreach ($tabAliases as $tab => $aliases) {
    if (in_array($_page, $aliases, true)) {
        $activeTab = $tab;
        break;
    }
}
?>

<header class="topbar">
    <div class="topbar-inner">
        <a href="index" class="topbar-brand">
            <img src="/images/Granit-Img/logo.png" alt="">
            <span class="topbar-brand-name">Texas Specialized Quartz &amp; Granite</span>
        </a>

        <div class="topbar-right">
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

        <div class="dropdown">
            <div class="topbar-user dropdown-toggle" data-bs-toggle="dropdown" role="button">
                <div class="topbar-user-avatar"><?= htmlspecialchars($initials) ?></div>
                <div class="d-none d-sm-block">
                    <div class="topbar-user-name"><?= htmlspecialchars($_cu['name'] ?? 'Employee') ?></div>
                    <div class="topbar-user-role"><?= htmlspecialchars($_cu['designation'] ?: 'Employee') ?></div>
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <div class="px-3 py-2">
                        <div style="font-size:13px;font-weight:600;"><?= htmlspecialchars($_cu['name'] ?? 'Employee') ?></div>
                        <div style="font-size:11px;color:var(--text-sub);"><?= htmlspecialchars($_cu['email'] ?? '') ?></div>
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

<nav class="tabs-nav" aria-label="Sections">
    <div class="tabs-nav-inner">
        <?php foreach ($employeeTabs as $page => [$icon, $label]): ?>
            <a href="<?= $page ?>"
               class="tab-link <?= $activeTab === $page ? 'active' : '' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>
</nav>

<!-- Bottom tab bar (mobile only, app-like) -->
<nav class="bottombar" aria-label="Primary">
    <?php foreach ($employeeTabs as $page => [$icon, $label]): ?>
        <a href="<?= $page ?>" class="bn-link <?= $activeTab === $page ? 'active' : '' ?>">
            <span class="bn-icon-wrap"><i class='bx <?= $icon ?>'></i></span>
            <span><?= $label ?></span>
        </a>
    <?php endforeach; ?>
</nav>
