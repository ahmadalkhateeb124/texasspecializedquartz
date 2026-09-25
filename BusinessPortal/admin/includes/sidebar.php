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
    'employees'  => ['bx-id-card',    'Employees'],
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
    'employees' => ['employees-new', 'employees-edit'],
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

/* Bottom nav: 3 most-used destinations directly, everything else grouped under "More". */
$bottomPrimary = ['index', 'orders', 'calendar'];
$bottomMore = [
    'inquiries' => 'Communication',
    'customers' => 'People',
    'employees' => 'People',
    'products'  => 'Catalog',
    'inventory' => 'Catalog',
    'sinks'     => 'Catalog',
    'gallery'   => 'Content',
    'blog'      => 'Content',
    'faq'       => 'Content',
    'priceList' => 'Content',
    'training'  => 'Content',
    'settings'  => 'Account',
    'profile'   => 'Account',
];
?>

<!-- ── Top brand bar ───────────────────────────────────────── -->
<header class="topbar">
    <div class="topbar-inner">
        <a href="index" class="topbar-brand">
            <img src="/images/Granit-Img/logo.png" alt="">
            <span class="topbar-brand-name">Texas Specialized Quartz &amp; Granite</span>
        </a>

        <div class="topbar-right">
        <!-- Install PWA button (visible only when browser supports install) -->
        <button id="pwaInstallBtn" class="topbar-icon-btn pwa-install-btn" title="Install desktop app" type="button" style="display:none;">
            <i class='bx bx-download'></i>
            <span class="pwa-install-label">Install App</span>
        </button>

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

<!-- ── Tabs navigation (horizontal on desktop) ─────────────── -->
<nav class="tabs-nav" aria-label="Sections">
    <div class="tabs-nav-inner">
        <?php foreach ($adminTabs as $page => [$icon, $label]): ?>
            <a href="<?= $page ?>"
               class="tab-link <?= $activeTab === $page ? 'active' : '' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>
</nav>

<!-- ── Bottom tab bar (mobile only, app-like) ──────────────── -->
<nav class="bottombar" aria-label="Primary">
    <?php foreach ($bottomPrimary as $page):
        [$icon, $label] = $adminTabs[$page]; ?>
        <a href="<?= $page ?>" class="bn-link <?= $activeTab === $page ? 'active' : '' ?>">
            <span class="bn-icon-wrap"><i class='bx <?= $icon ?>'></i></span>
            <span><?= $label ?></span>
        </a>
    <?php endforeach; ?>
    <button type="button" class="bn-link <?= isset($bottomMore[$activeTab]) ? 'active' : '' ?>" id="moreSheetBtn">
        <span class="bn-icon-wrap"><i class='bx bx-grid-alt'></i></span>
        <span>More</span>
    </button>
</nav>

<!-- ── More sheet (mobile only) ────────────────────────────── -->
<div class="more-sheet" id="moreSheet">
    <div class="more-sheet-backdrop"></div>
    <div class="more-sheet-panel">
        <div class="more-sheet-handle"></div>
        <div class="more-sheet-head">
            <span class="more-sheet-title">Menu</span>
            <button type="button" class="more-sheet-close" aria-label="Close">&times;</button>
        </div>
        <?php
        $sections = [];
        foreach ($bottomMore as $page => $section) {
            $sections[$section][] = $page;
        }
        foreach ($sections as $section => $pages): ?>
            <div class="more-section">
                <div class="more-section-label"><?= htmlspecialchars($section) ?></div>
                <div class="more-grid">
                    <?php foreach ($pages as $page):
                        [$icon, $label] = $adminTabs[$page]; ?>
                        <a href="<?= $page ?>" class="more-item <?= $activeTab === $page ? 'active' : '' ?>">
                            <span class="more-item-icon"><i class='bx <?= $icon ?>'></i></span>
                            <span><?= $label ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
(function () {
    var openBtn = document.getElementById('moreSheetBtn');
    var sheet   = document.getElementById('moreSheet');
    if (!openBtn || !sheet) return;
    var closeBtn = sheet.querySelector('.more-sheet-close');
    var backdrop = sheet.querySelector('.more-sheet-backdrop');
    function open()  { sheet.classList.add('open'); }
    function close() { sheet.classList.remove('open'); }
    openBtn.addEventListener('click', open);
    if (closeBtn) closeBtn.addEventListener('click', close);
    if (backdrop) backdrop.addEventListener('click', close);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
})();
</script>

<!-- ── PWA: service-worker registration + install prompt ────── -->
<script>
(function () {
    /* 1. Register service worker (required for installability) */
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker
                .register('/BusinessPortal/sw.js', { scope: '/BusinessPortal/' })
                .catch(function (err) { console.warn('SW register failed:', err); });
        });
    }

    /* 2. Custom install button — only visible when the browser supports it */
    var installBtn = document.getElementById('pwaInstallBtn');
    var deferredPrompt = null;

    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferredPrompt = e;
        if (installBtn) installBtn.style.display = 'inline-flex';
    });

    if (installBtn) {
        installBtn.addEventListener('click', async function () {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            try { await deferredPrompt.userChoice; } catch (_) {}
            deferredPrompt = null;
            installBtn.style.display = 'none';
        });
    }

    window.addEventListener('appinstalled', function () {
        if (installBtn) installBtn.style.display = 'none';
        deferredPrompt = null;
    });

    /* 3. Hide install button when already running as installed app */
    if (window.matchMedia('(display-mode: standalone)').matches && installBtn) {
        installBtn.style.display = 'none';
    }
})();
</script>
