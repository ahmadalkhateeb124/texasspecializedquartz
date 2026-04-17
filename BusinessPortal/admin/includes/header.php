<?php
// admin/includes/header.php

// دالة تجيب بيانات الادمن الحالي
function getAdminData()
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

$admin = getAdminData();

$_cu       = $admin;
$_initials = strtoupper(substr($_cu['fullname'] ?? 'A', 0, 2));
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

    <!-- Right actions -->
    <div class="topbar-right">

        <!-- Fullscreen toggle -->
        <button class="topbar-icon-btn d-none d-md-flex" id="fullscreenBtn" title="Fullscreen">
            <i class='bx bx-fullscreen' id="fullscreenIcon"></i>
        </button>

        <!-- Notifications (placeholder) -->
        <div class="dropdown">
            <button class="topbar-icon-btn" data-bs-toggle="dropdown" title="Notifications">
                <i class='bx bx-bell'></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" style="min-width:300px;">
                <div class="dropdown-header d-flex align-items-center justify-content-between">
                    <span>Notifications</span>
                    <span class="badge bg-primary rounded-pill">0</span>
                </div>
                <hr class="dropdown-divider">
                <div class="empty-state py-4">
                    <i class='bx bx-bell-off empty-state-icon' style="font-size:32px;"></i>
                    <p class="mb-0 text-muted" style="font-size:12px;">No new notifications</p>
                </div>
            </div>
        </div>

        <div class="topbar-divider"></div>

        <!-- User dropdown -->
        <div class="dropdown">
            <div class="topbar-user dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false" role="button">
                <div class="topbar-user-avatar" style="width:40px;height:40px;">
                    <img src="../auth/uploads/<?= htmlspecialchars($admin['avatar'] ?? 'default-avatar.png') ?>"
                        style="border-radius:50%; border:3px solid var(--color-surface); object-fit:cover;"
                        alt="Avatar">
                </div>
                <div class="d-none d-sm-block">
                    <div class="topbar-user-name"><?= htmlspecialchars($_cu['fullname'] ?? 'Admin') ?></div>
                    <div class="topbar-user-role">Administrator</div>
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <div class="px-3 py-2">
                        <div style="font-size:13px;font-weight:600;"><?= htmlspecialchars($_cu['fullname'] ?? 'Admin') ?></div>
                        <div style="font-size:11px;color:var(--muted);"><?= htmlspecialchars($_cu['email'] ?? '') ?></div>
                    </div>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item" href="profile.php">
                        <i class='bx bx-user'></i> My Profile
                    </a>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item text-danger" href="../auth/logout.php">
                        <i class='bx bx-log-out'></i> Sign Out
                    </a>
                </li>
            </ul>
        </div>

    </div>
</header>