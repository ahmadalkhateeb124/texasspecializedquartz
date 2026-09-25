<?php

/**
 * customer/includes/sidebar.php
 * Top brand bar + horizontal tabs for the customer portal.
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

$initials = strtoupper(substr($_cu['name'] ?? 'C', 0, 2));

$customerTabs = [
    'index'     => ['bxs-dashboard', 'Dashboard'],
    'orders'    => ['bx-file',       'My Orders'],
    'order-new' => ['bx-plus-circle','New Order'],
    'priceList' => ['bx-file',       'Price Lists'],
    'training'  => ['bx-video',      'Training'],
    'profile'   => ['bx-user',       'Profile'],
];

$tabAliases = [
    'orders' => ['order-view'],
];

$activeTab = $_page;
foreach ($tabAliases as $tab => $aliases) {
    if (in_array($_page, $aliases, true)) {
        $activeTab = $tab;
        break;
    }
}

/* Bottom nav: 3 primary destinations directly, the rest under "More". */
$bottomPrimary = ['index', 'orders', 'order-new'];
$bottomMore    = ['priceList' => 'Catalog', 'training' => 'Catalog', 'profile' => 'Account'];
?>

<header class="topbar">
    <div class="topbar-inner">
        <a href="index" class="topbar-brand">
            <img src="/images/Granit-Img/logo.png" alt="">
            <span class="topbar-brand-name">Texas Specialized Quartz &amp; Granite</span>
        </a>

        <div class="topbar-right">
        <!-- Install PWA button -->
        <button id="pwaInstallBtn" class="topbar-icon-btn pwa-install-btn" title="Install desktop app" type="button" style="display:none;">
            <i class='bx bx-download'></i>
            <span class="pwa-install-label">Install App</span>
        </button>

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
                    <div class="topbar-user-name"><?= htmlspecialchars($_cu['name'] ?? 'Customer') ?></div>
                    <div class="topbar-user-role">Customer</div>
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <div class="px-3 py-2">
                        <div style="font-size:13px;font-weight:600;"><?= htmlspecialchars($_cu['name'] ?? 'Customer') ?></div>
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
        <?php foreach ($customerTabs as $page => [$icon, $label]): ?>
            <a href="<?= $page ?>"
               class="tab-link <?= $activeTab === $page ? 'active' : '' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>
</nav>

<!-- Bottom tab bar (mobile only, app-like) -->
<nav class="bottombar" aria-label="Primary">
    <?php foreach ($bottomPrimary as $page):
        [$icon, $label] = $customerTabs[$page]; ?>
        <a href="<?= $page ?>" class="bn-link <?= $activeTab === $page ? 'active' : '' ?>">
            <span class="bn-icon-wrap"><i class='bx <?= $icon ?>'></i></span>
            <span><?= $label ?></span>
        </a>
    <?php endforeach; ?>
    <button type="button" class="bn-link" id="moreSheetBtn">
        <span class="bn-icon-wrap"><i class='bx bx-grid-alt'></i></span>
        <span>More</span>
    </button>
</nav>

<!-- More sheet (mobile only) -->
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
                        [$icon, $label] = $customerTabs[$page]; ?>
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
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker
                .register('/BusinessPortal/sw.js', { scope: '/BusinessPortal/' })
                .catch(function (err) { console.warn('SW register failed:', err); });
        });
    }

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

    if (window.matchMedia('(display-mode: standalone)').matches && installBtn) {
        installBtn.style.display = 'none';
    }
})();
</script>
