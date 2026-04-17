<?php
/**
 * customer/includes/header.php
 */
$_cu         = $currentUser ?? [];
$_initials   = strtoupper(substr($_cu['name'] ?? 'C', 0, 2));
$_breadcrumb = $breadcrumb  ?? [];
?>

<header class="topbar">

    <!-- Mobile toggle -->
    <button class="topbar-toggle" id="sidebarToggle" title="Toggle sidebar">
        <i class='bx bx-menu'></i>
    </button>

    <!-- Breadcrumb -->
    <?php if (!empty($_breadcrumb)): ?>
    <nav class="topbar-breadcrumb d-none d-md-flex" aria-label="breadcrumb">
        <a href="index.php" class="bc-link">
            <i class='bx bxs-home' style="font-size:15px;"></i>
        </a>
        <?php foreach ($_breadcrumb as $_bc): ?>
            <span class="bc-sep"><i class='bx bx-chevron-right'></i></span>
            <?php if (!empty($_bc['url'])): ?>
                <a href="<?= htmlspecialchars($_bc['url']) ?>" class="bc-link">
                    <?= htmlspecialchars($_bc['label']) ?>
                </a>
            <?php else: ?>
                <span class="bc-current"><?= htmlspecialchars($_bc['label']) ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>
    <?php endif; ?>

    <div class="topbar-right">

        <!-- Quick action: New order -->
        <a href="order-new.php" class="topbar-icon-btn d-none d-sm-flex" title="New Order">
            <i class='bx bx-plus'></i>
        </a>

        <div class="topbar-divider"></div>

        <!-- User dropdown -->
        <div class="dropdown">
            <div class="topbar-user dropdown-toggle" data-bs-toggle="dropdown"
                 aria-expanded="false" role="button">
                <div class="topbar-user-avatar"><?= htmlspecialchars($_initials) ?></div>
                <div class="d-none d-sm-block">
                    <div class="topbar-user-name"><?= htmlspecialchars($_cu['name'] ?? 'Customer') ?></div>
                    <div class="topbar-user-role">Customer</div>
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <div class="px-3 py-2">
                        <div style="font-size:13px;font-weight:600;"><?= htmlspecialchars($_cu['name'] ?? '') ?></div>
                        <div style="font-size:11px;color:var(--muted);"><?= htmlspecialchars($_cu['email'] ?? '') ?></div>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="profile.php">
                        <i class='bx bx-user'></i> My Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="orders.php">
                        <i class='bx bx-file'></i> My Orders
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="../auth/logout.php">
                        <i class='bx bx-log-out'></i> Sign Out
                    </a>
                </li>
            </ul>
        </div>

    </div>
</header>
