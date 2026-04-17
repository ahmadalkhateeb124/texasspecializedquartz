<?php
$adminAvatar = 'default-avatar.png';
$adminData = [];

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([2]); // غير الرقم حسب الأدمن
    $adminData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($adminData) {
        if (!empty($adminData['avatar']) && file_exists(__DIR__ . '/auth/uploads/' . $adminData['avatar'])) {
            $adminAvatar = $adminData['avatar'];
        }
    }
} catch (PDOException $e) {
   
    // echo $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin — Texas Specialized Quartz & Granite</title>

<!-- SEO -->
<meta name="description" content="Texas Specialized Quartz & Granite offers premium quartz and granite countertops in Texas. Custom designs, fabrication, and professional installation services.">
<meta name="keywords" content="Quartz countertops Texas, Granite countertops Texas, Kitchen countertops, Bathroom countertops, Custom stone, Countertop installation Texas">
<meta name="author" content="Texas Specialized Quartz & Granite">
<meta name="robots" content="index, follow">


<!-- Favicon -->
<link rel="icon" type="image/png" href="https://texasspecializedquartz.com/BusinessPortal/auth/uploads/<?= htmlspecialchars($adminAvatar) ?>"

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Icons -->
<link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<!-- DataTables -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════════════
       GRANITE ARTISTS — LUXURY DESIGN SYSTEM
       Theme: Warm White + Champagne Gold
    ═══════════════════════════════════════════════════ */
        :root {
            --sidebar-w: 248px;
            --header-h: 60px;

            /* ── Surfaces ── */
            --color-surface: #ffffff;
            --color-bg: #f8f6f2;
            --color-bg-subdued: #fdfcfa;

            /* ── Borders ── */
            --color-border: #e8e3dc;
            --color-border-sub: #d5cfc5;

            /* ── Text — warm near-black ── */
            --color-text: #1c1917;
            --color-text-sub: #57534e;
            --color-text-dis: #a8a29e;

            /* ── Primary — Champagne Gold ── */
            --color-primary: #767676;
            --color-primary-h: #767676;
            --color-primary-l: #a4a4a4;

            /* ── Semantic States ── */
            --color-success: #3d6b4f;
            --color-success-l: #dff0e5;
            --color-warning: #b45309;
            --color-warning-l: #fef3c7;
            --color-critical: #b91c1c;
            --color-critical-l: #fee2e2;
            --color-highlight: #64748b;
            --color-highlight-l: #f1f5f9;

            /* ── Sidebar — clean white ── */
            --sidebar-bg: #ffffff;
            --sidebar-surface: #f8f6f2;
            --sidebar-text: #78716c;
            --sidebar-text-h: #1c1917;
            --sidebar-border: #e8e3dc;
            --sidebar-active-bg: rgba(201, 169, 110, .1);
            --sidebar-active-c: #767676;

            /* ── Shadows ── */
            --shadow-xs: 0 1px 2px rgba(200, 200, 200, 0.06);
            --shadow-sm: 0 1px 4px rgba(183, 183, 183, 0.1), 0 0 0 1px rgba(174, 174, 174, 0.08);
            --shadow-md: 0 4px 16px rgba(185, 185, 185, 0.12), 0 0 0 1px rgba(164, 164, 164, .06);
            --shadow-lg: 0 8px 32px rgba(202, 202, 202, 0.14), 0 0 0 1px rgba(190, 190, 190, 0.06);

            /* ── Radius ── */
            --radius-xs: 3px;
            --radius-sm: 6px;
            --radius: 8px;
            --radius-md: 10px;
            --radius-lg: 12px;
            --radius-xl: 16px;

            --t: all .15s ease;
        }

        /* ── Reset ── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Playfair Display', Georgia, serif !important;
            font-size: 14px;
            line-height: 1.5;
            color: var(--color-text);
            background: var(--color-bg);
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: var(--color-primary);
            text-decoration: none;
        }

        a:hover {
            color: var(--color-primary-h);
        }

        img {
            max-width: 100%;
            display: block;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 600;
            line-height: 1.3;
        }

        /* ═══════════════════════════════════════════════════
       SIDEBAR
    ═══════════════════════════════════════════════════ */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            overflow: hidden;
            transition: transform .25s cubic-bezier(.4, 0, .2, 1);
        }

        .stat-icon {
            padding: 10px !important;
            border-radius: 10px;
        }

        /* Brand */
        .sidebar-brand {
            height: var(--header-h);
            padding: 0 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--sidebar-border);
            flex-shrink: 0;
            text-decoration: none;
        }

        .sidebar-brand-logo {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, #767676, #a4a4a4);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(255, 255, 255, .16);
        }

        .sidebar-brand-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--color-text);
            letter-spacing: -.2px;
        }

        .sidebar-brand-tag {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #767676;
            background: rgba(116, 116, 116, 0.12);
            padding: 2px 5px;
            border-radius: 3px;
            margin-top: 1px;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 10px 0;
            scrollbar-width: thin;
            scrollbar-color: #e8e3dc transparent;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #e8e3dc;
            border-radius: 2px;
        }

        .nav-section-title {
            padding: 14px 16px 4px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--color-text-dis);
        }

        .nav-list {
            list-style: none;
            padding: 0 8px;
        }

        .nav-list+.nav-section-title {
            margin-top: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: var(--radius-sm);
            color: var(--sidebar-text);
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--t);
            position: relative;
            margin-bottom: 1px;
            white-space: nowrap;
        }

        .nav-link:hover {
            background: var(--sidebar-surface);
            color: var(--sidebar-text-h);
            text-decoration: none;
        }

        .nav-link.active {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-active-c);
            font-weight: 600;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 3px;
            background: var(--color-primary);
            border-radius: 0 2px 2px 0;
            margin-left: -8px;
        }

        .nav-icon {
            font-size: 17px;
            width: 18px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: .7;
        }

        .nav-link:hover .nav-icon,
        .nav-link.active .nav-icon {
            opacity: 1;
        }

        .nav-badge {
            margin-left: auto;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 10px;
            background: var(--color-primary);
            color: #fff;
        }

        /* User footer */
        .sidebar-footer {
            padding: 8px;
            border-top: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }

        .sidebar-user-btn {
            display: flex;
            align-items: center;
            gap: 9px;
            width: 100%;
            padding: 8px 10px;
            border-radius: var(--radius-sm);
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
            transition: var(--t);
        }

        .sidebar-user-btn:hover {
            background: var(--sidebar-surface);
        }

        .sidebar-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-h));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--color-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 130px;
        }

        .sidebar-user-meta {
            font-size: 11px;
            color: var(--sidebar-text);
        }

        /* ═══════════════════════════════════════════════════
       LAYOUT
    ═══════════════════════════════════════════════════ */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left .25s cubic-bezier(.4, 0, .2, 1);
        }

        /* ═══════════════════════════════════════════════════
       TOPBAR
    ═══════════════════════════════════════════════════ */
        .topbar {
            position: sticky;
            top: 0;
            height: var(--header-h);
            background: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 12px;
            z-index: 900;
            flex-shrink: 0;
        }

        .topbar-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-border);
            background: none;
            cursor: pointer;
            font-size: 17px;
            color: var(--color-text);
            transition: var(--t);
        }

        .topbar-toggle:hover {
            background: var(--color-bg);
        }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0;
            font-size: 13px;
        }

        .bc-sep {
            color: var(--color-border);
            margin: 0 5px;
            font-size: 14px;
            line-height: 1;
        }

        .bc-link {
            color: var(--color-text-sub);
            transition: color .12s;
        }

        .bc-link:hover {
            color: var(--color-primary);
        }

        .bc-current {
            color: var(--color-text);
            font-weight: 500;
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .topbar-icon-btn {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-border);
            background: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            color: var(--color-text-sub);
            cursor: pointer;
            text-decoration: none;
            transition: var(--t);
        }

        .topbar-icon-btn:hover {
            background: var(--color-bg);
            color: var(--color-primary);
            border-color: var(--color-primary);
            text-decoration: none;
        }

        .topbar-divider {
            width: 1px;
            height: 20px;
            background: var(--color-border);
            margin: 0 2px;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 3px 8px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--t);
            border: 1px solid transparent;
        }

        .topbar-user:hover {
            background: var(--color-bg);
            border-color: var(--color-border);
        }

        .topbar-user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-h));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
        }

        .topbar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--color-text);
        }

        .topbar-user-role {
            font-size: 11px;
            color: var(--color-text-sub);
        }

        /* ═══════════════════════════════════════════════════
       PAGE CONTENT
    ═══════════════════════════════════════════════════ */
        .page-content {
            flex: 1;
            padding: 24px 28px 36px;
            max-width: 1280px;
        }

        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--color-border);
        }

        .page-header-left {}

        .page-title {
            font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--color-text);
            letter-spacing: -.3px;
            margin: 0 0 4px;
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--color-text-sub);
            margin: 0;
        }

        .page-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .page-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0;
            font-size: 12px;
            color: var(--color-text-sub);
            margin-bottom: 8px;
        }

        .page-breadcrumb .bc-sep {
            margin: 0 4px;
            font-size: 12px;
        }

        /* ═══════════════════════════════════════════════════
       CARDS
    ═══════════════════════════════════════════════════ */
        .card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: box-shadow .2s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            padding: 14px 18px;
            border-bottom: 1px solid var(--color-border);
            background: none;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0 !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .card-title {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--color-text);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .card-body {
            padding: 18px;
        }

        .card-footer {
            padding: 12px 18px;
            border-top: 1px solid var(--color-border);
            background: var(--color-bg-subdued);
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-section {
            padding: 18px;
            border-bottom: 1px solid var(--color-border);
        }

        .card-section:last-child {
            border-bottom: none;
        }

        .card-section-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .7px;
            text-transform: uppercase;
            color: var(--color-text-dis);
            margin-bottom: 14px;
        }

        /* KPI */
        .kpi-card {
            padding: 18px;
        }

        .kpi-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--color-text-sub);
            margin-bottom: 8px;
            letter-spacing: .3px;
            text-transform: uppercase;
        }

        .kpi-value {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--color-text);
            line-height: 1;
        }

        .kpi-delta {
            font-size: 12px;
            margin-top: 8px;
            color: var(--color-text-sub);
        }

        .kpi-icon {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        /* ═══════════════════════════════════════════════════
       TABLES
    ═══════════════════════════════════════════════════ */
        .table {
            font-size: 13px;
            margin: 0;
        }

        .table thead th {
            font-size: 11px;
            font-weight: 600;
            color: var(--color-text-sub);
            background: var(--color-bg-subdued);
            border-bottom: 1px solid var(--color-border) !important;
            padding: 11px 16px;
            white-space: nowrap;
            letter-spacing: .4px;
            text-transform: uppercase;
        }

        .table tbody td {
            padding: 13px 16px;
            border-color: var(--color-border);
            vertical-align: middle;
            color: var(--color-text);
        }

        .table-hover tbody tr {
            transition: background .15s ease;
        }

        .table-hover tbody tr:hover td {
            background: rgba(118, 118, 118, .08);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .resource-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            transition: background .12s;
        }

        .resource-item:hover {
            background: var(--color-bg);
        }

        .resource-thumbnail {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            border: 1px solid var(--color-border);
            flex-shrink: 0;
            background: var(--color-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-text-dis);
        }

        /* ═══════════════════════════════════════════════════
       BADGES
    ═══════════════════════════════════════════════════ */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 100px;
            letter-spacing: .1px;
        }

        .badge-success {
            background: var(--color-success-l);
            color: #1e4a2f;
        }

        .badge-warning {
            background: var(--color-warning-l);
            color: #92400e;
        }

        .badge-critical {
            background: var(--color-critical-l);
            color: #991b1b;
        }

        .badge-danger {
            background: var(--color-critical-l);
            color: #991b1b;
        }

        .badge-info {
            background: var(--color-highlight-l);
            color: #475569;
        }

        .badge-primary {
            background: var(--color-primary-l);
            color: #767676;
        }

        .badge-neutral {
            background: #f5f4f2;
            color: var(--color-text-sub);
        }

        .badge-muted {
            background: #f5f4f2;
            color: var(--color-text-sub);
        }

        .badge .dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        /* ═══════════════════════════════════════════════════
       BUTTONS
    ═══════════════════════════════════════════════════ */
        .btn {
            font-size: 13px;
            font-weight: 500;
            padding: 7px 16px;
            border-radius: var(--radius-sm);
            transition: var(--t);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            line-height: 1.4;
            cursor: pointer;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--color-primary);
            border-color: var(--color-primary);
            color: #fff;
            box-shadow: 0 1px 3px rgba(255, 255, 255, .25);
        }

        .btn-primary:hover {
            background: var(--color-primary-h);
            border-color: var(--color-primary-h);
            color: #fff;
            box-shadow: 0 3px 10px rgba(255, 255, 255, .3);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: none;
        }

        .btn-default {
            background: var(--color-surface);
            border-color: var(--color-border-sub);
            color: var(--color-text);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
        }

        .btn-default:hover {
            background: var(--color-bg);
            color: var(--color-text);
        }

        .btn-outline {
            background: transparent;
            border-color: var(--color-border-sub);
            color: var(--color-text);
        }

        .btn-outline:hover {
            background: var(--color-bg);
        }

        .btn-light {
            background: var(--color-bg);
            border-color: var(--color-border);
            color: var(--color-text);
        }

        .btn-light:hover {
            background: var(--color-border);
            color: var(--color-text);
        }

        .btn-critical,
        .btn-danger {
            background: var(--color-critical);
            border-color: var(--color-critical);
            color: #fff;
        }

        .btn-critical:hover,
        .btn-danger:hover {
            background: #991b1b;
            border-color: #991b1b;
            color: #fff;
        }

        .btn-sm {
            padding: 5px 11px;
            font-size: 12px;
        }

        .btn-xs {
            padding: 3px 8px;
            font-size: 11px;
        }

        .btn-lg {
            padding: 10px 22px;
            font-size: 14px;
        }

        .btn-icon {
            width: 30px;
            height: 30px;
            padding: 0;
            justify-content: center;
            border-radius: var(--radius-sm);
        }

        .btn-icon.btn-sm {
            width: 26px;
            height: 26px;
        }

        .btn-outline-secondary {
            background: transparent;
            border-color: var(--color-border);
            color: var(--color-text-sub);
        }

        .btn-outline-secondary:hover {
            background: var(--color-bg);
            color: var(--color-text);
        }

        /* ═══════════════════════════════════════════════════
       FORMS
    ═══════════════════════════════════════════════════ */
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--color-text);
            margin-bottom: 5px;
        }

        .form-hint {
            font-size: 12px;
            color: var(--color-text-sub);
            margin-top: 4px;
        }

        .form-error {
            font-size: 12px;
            color: var(--color-critical);
            margin-top: 4px;
            display: none;
        }

        .field-group {
            margin-bottom: 16px;
        }

        .form-control,
        .form-select {
            font-size: 13px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-border);
            padding: 8px 12px;
            color: var(--color-text);
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, .16);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--color-text-dis);
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: var(--color-critical);
            box-shadow: none;
        }

        .image-upload-zone {
            border: 2px dashed var(--color-border-sub);
            border-radius: var(--radius-lg);
            padding: 28px 20px;
            text-align: center;
            transition: var(--t);
            background: var(--color-bg-subdued);
            position: relative;
        }

        .image-upload-zone:hover {
            border-color: var(--color-primary);
            background: var(--color-primary-l);
        }

        /* ═══════════════════════════════════════════════════
       DROPDOWNS
    ═══════════════════════════════════════════════════ */
        .dropdown-menu {
            font-size: 13px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            padding: 6px;
            background: var(--color-surface);
        }

        .dropdown-item {
            border-radius: var(--radius-sm);
            padding: 8px 12px;
            color: var(--color-text-sub);
            display: flex;
            align-items: center;
            gap: 9px;
            transition: background .12s;
        }

        .dropdown-item:hover {
            background: var(--color-bg);
            color: var(--color-text);
        }

        .dropdown-item.text-danger {
            color: var(--color-critical);
        }

        .dropdown-item.text-danger:hover {
            background: var(--color-critical-l);
            color: var(--color-critical);
        }

        .dropdown-divider {
            border-color: var(--color-border);
            margin: 4px 0;
        }

        .dropdown-header {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: var(--color-text-dis);
            padding: 7px 12px 4px;
        }

        /* ═══════════════════════════════════════════════════
       MODALS
    ═══════════════════════════════════════════════════ */
        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            border-bottom: 1px solid var(--color-border);
            padding: 16px 20px;
        }

        .modal-title {
            font-size: 15px;
            font-weight: 600;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            border-top: 1px solid var(--color-border);
            padding: 12px 20px;
        }

        /* ═══════════════════════════════════════════════════
       TOASTS
    ═══════════════════════════════════════════════════ */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast-msg {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: 12px 16px;
            box-shadow: var(--shadow-lg);
            font-size: 13px;
            font-weight: 500;
            pointer-events: all;
            animation: slideIn .25s ease;
            min-width: 260px;
            max-width: 340px;
        }

        .toast-msg.success {
            border-left: 3px solid var(--color-success);
        }

        .toast-msg.error {
            border-left: 3px solid var(--color-critical);
        }

        .toast-msg.warning {
            border-left: 3px solid var(--color-warning);
        }

        .toast-msg.info {
            border-left: 3px solid var(--color-primary);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(16px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1
            }

            to {
                opacity: 0;
                transform: translateX(16px)
            }
        }

        /* ═══════════════════════════════════════════════════
       ALERTS
    ═══════════════════════════════════════════════════ */
        .alert {
            font-size: 13px;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
        }

        .alert-success {
            background: var(--color-success-l);
            border-color: rgba(61, 107, 79, .2);
            color: #1e4a2f;
        }

        .alert-danger {
            background: var(--color-critical-l);
            border-color: rgba(185, 28, 28, .2);
            color: #991b1b;
        }

        .alert-warning {
            background: var(--color-warning-l);
            border-color: rgba(180, 83, 9, .2);
            color: #92400e;
        }

        /* ═══════════════════════════════════════════════════
       EMPTY STATE
    ═══════════════════════════════════════════════════ */
        .empty-state {
            text-align: center;
            padding: 52px 20px;
        }

        .empty-state-icon {
            font-size: 44px;
            color: var(--color-border);
            margin-bottom: 16px;
            display: block;
        }

        .empty-state-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--color-text);
            margin-bottom: 6px;
        }

        .empty-state-desc {
            font-size: 13px;
            color: var(--color-text-sub);
            max-width: 300px;
            margin: 0 auto 20px;
        }

        /* ═══════════════════════════════════════════════════
       PAGE FOOTER
    ═══════════════════════════════════════════════════ */
        .page-footer {
            padding: 14px 28px;
            border-top: 1px solid var(--color-border);
            background: var(--color-surface);
            font-size: 12px;
            color: var(--color-text-dis);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            letter-spacing: .2px;
        }

        .page-footer a {
            color: var(--color-text-dis);
        }

        .page-footer a:hover {
            color: var(--color-primary);
        }

        /* ═══════════════════════════════════════════════════
       UTILITIES
    ═══════════════════════════════════════════════════ */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #e8e3dc;
            border-radius: 3px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .35);
            z-index: 1039;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.show {
            display: block;
        }

        .fade-up {
            animation: fadeUp .3s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(8px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        .text-primary {
            color: var(--color-primary) !important;
        }

        .bg-primary {
            background: var(--color-primary) !important;
        }

        .text-muted {
            color: var(--color-text-sub) !important;
        }

        /* ── Responsive ── */
        @media (max-width: 991.98px) {
            .app-sidebar {
                transform: translateX(-100%);
            }

            .app-sidebar.sidebar-open {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0 !important;
            }

            .topbar-toggle {
                display: flex !important;
            }

            .page-content {
                padding: 16px 14px;
            }

            .page-header {
                flex-direction: column;
                gap: 12px;
            }

            .page-actions {
                width: 100%;
            }
        }
    </style>

    <?= $extraCss ?? '' ?>
</head>