<?php
// customer/includes/head.php — new unified theme (saddle + black + white)
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Customer Portal') ?> — Texas Specialized Quartz &amp; Granite</title>

    <link rel="icon" type="image/png" href="/images/Granit-Img/logo-gg.jpg">

    <!-- ── PWA: installable desktop app ────────────────────────── -->
    <link rel="manifest" href="/BusinessPortal/customer/manifest.webmanifest">
    <meta name="theme-color" content="#1a1814">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="TSQG Portal">
    <link rel="apple-touch-icon" href="/images/Granit-Img/logo.png">

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700;9..144,800;9..144,900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════════════════════
           Texas Specialized Quartz — Admin
           Palette: saddle brown + black + white
        ═══════════════════════════════════════════════════════════ */
        :root {
            /* ── Core surfaces ── */
            --bg:               #ffffff;
            --surface:          #ffffff;
            --surface-muted:    #ffffff;

            /* ── Text ── */
            --text:             #1a1814;
            --text-sub:         #5c5650;
            --text-dis:         #a8a29e;

            /* ── Borders ── */
            --border:           #e8e3dc;
            --border-sub:       #d5cfc5;

            /* ── Brand accent — saddle brown ── */
            --brand:            #000000;
            --brand-h:          #2a2a2a;
            --brand-l:          #ececec;

            /* ── Semantic ── */
            --success:          #3d6b4f;
            --success-l:        #dff0e5;
            --warning:          #b45309;
            --warning-l:        #fef3c7;
            --danger:           #b91c1c;
            --danger-l:         #fee2e2;
            --info:             #1e3a5c;
            --info-l:           #e2e8f0;

            /* ── Legacy aliases (kept so existing pages don't break) ── */
            --color-surface:      var(--surface);
            --color-bg:           var(--bg);
            --color-bg-subdued:   var(--surface-muted);
            --color-border:       var(--border);
            --color-border-sub:   var(--border-sub);
            --color-text:         var(--text);
            --color-text-sub:     var(--text-sub);
            --color-text-dis:     var(--text-dis);
            --color-primary:      var(--text);
            --color-primary-h:    #000;
            --color-primary-l:    var(--bg);
            --color-success:      var(--success);
            --color-success-l:    var(--success-l);
            --color-warning:      var(--warning);
            --color-warning-l:    var(--warning-l);
            --color-critical:     var(--danger);
            --color-critical-l:   var(--danger-l);
            --color-highlight:    var(--info);
            --color-highlight-l:  var(--info-l);
            --primary:            var(--text);
            --text:               var(--text);
            --muted:              var(--text-sub);
            --success:            var(--success);
            --warning:            var(--warning);
            --danger:             var(--danger);
            --info:               var(--info);
            --primary-light:      var(--brand-l);
            --success-light:      var(--success-l);
            --warning-light:      var(--warning-l);
            --info-light:         var(--info-l);

            /* ── Shadows ── */
            --shadow-xs: 0 1px 2px rgba(24,20,15,.04);
            --shadow-sm: 0 1px 3px rgba(24,20,15,.06), 0 0 0 1px rgba(24,20,15,.04);
            --shadow-md: 0 4px 14px rgba(24,20,15,.06), 0 0 0 1px rgba(24,20,15,.04);
            --shadow-lg: 0 10px 30px rgba(24,20,15,.08);

            /* ── Radius ── */
            --radius-xs: 4px;
            --radius-sm: 6px;
            --radius:    8px;
            --radius-md: 10px;
            --radius-lg: 14px;

            /* ── Metrics ── */
            --topbar-h: 60px;
            --tabs-h:   48px;
        }

        * { box-sizing: border-box; }
        html, body { min-height: 100%; }
        body {
            margin: 0;
            font-family: 'Inter', -apple-system, Segoe UI, sans-serif;
            color: var(--text);
            background: var(--bg);
            font-size: 14px;
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
        }
        /* No italic anywhere on customer portal */
        em, i, cite, dfn, address, blockquote, q, var { font-style: normal !important; }
        a { color: inherit; text-decoration: none; }

        /* ── Topbar (brand + user) ────────────────────────── */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            height: var(--topbar-h);
        }
        .topbar-inner {
            max-width: 1400px;
            margin: 0 auto;
            height: 100%;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar-brand img { height: 36px; width: auto; }
        .topbar-brand-name {
            font-family: 'Fraunces', Georgia, 'Times New Roman', serif;
            font-size: 16px;
            line-height: 1.1;
            color: var(--text);
            max-width: 180px;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .topbar-icon-btn {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 0;
            background: transparent;
            color: var(--text-sub);
            font-size: 18px;
            cursor: pointer;
            transition: background .15s, color .15s;
        }
        .topbar-icon-btn:hover { background: var(--bg); color: var(--text); }
        .topbar-divider { width: 1px; height: 24px; background: var(--border); margin: 0 8px; }
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 8px 4px 4px;
            border-radius: 999px;
            cursor: pointer;
            transition: background .15s;
        }
        .topbar-user:hover { background: var(--bg); }
        .topbar-user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: #000000; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 12px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .topbar-user-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .topbar-user-name  { font-size: 13px; font-weight: 600; line-height: 1.1; }
        .topbar-user-role  { font-size: 11px; color: var(--text-sub); line-height: 1.1; margin-top: 2px; }

        /* ── Tabs nav (wraps to show all tabs, no scroll) ────── */
        .tabs-nav {
            position: sticky;
            top: var(--topbar-h);
            z-index: 40;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }
        .tabs-nav-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 8px 24px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 4px;
        }
        .tab-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-sub);
            border-radius: 999px;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }
        .tab-link i { font-size: 16px; }
        .tab-link:hover { background: var(--brand-l); color: var(--brand); }
        .tab-link.active {
            background: var(--brand);
            color: #fff;
        }
        .tab-link.active i { color: #fff; }

        /* ── Install PWA button (only shows when browser allows install) ── */
        .pwa-install-btn {
            width: auto !important;
            padding: 0 12px !important;
            gap: 6px;
            background: var(--brand, #b08d57) !important;
            color: #fff !important;
            border-color: var(--brand, #b08d57) !important;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: .04em;
        }
        .pwa-install-btn:hover {
            background: var(--text, #1a1814) !important;
            border-color: var(--text, #1a1814) !important;
            color: #fff !important;
        }
        .pwa-install-btn i { color: #fff !important; font-size: 16px; }
        .pwa-install-label { font-family: var(--tx-sans, Inter), sans-serif; }
        @media (max-width: 600px) {
            .pwa-install-label { display: none; }
            .pwa-install-btn { padding: 0 !important; width: 36px !important; }
        }

        /* ── Bottom nav (mobile, app-style) + More sheet ─────── */
        .bottombar, .more-sheet { display: none; }

        @media (max-width: 1024px) {
            .tabs-nav { display: none !important; }

            .bottombar {
                display: flex;
                position: fixed;
                left: 0; right: 0; bottom: 0;
                z-index: 70;
                background: var(--surface);
                border-top: 1px solid var(--border);
                padding: 6px 6px calc(6px + env(safe-area-inset-bottom));
                box-shadow: 0 -8px 24px -18px rgba(24,20,15,.25);
            }
            .bn-link {
                flex: 1;
                display: flex; flex-direction: column; align-items: center; justify-content: center;
                gap: 3px;
                padding: 5px 4px 6px;
                border: 0; background: none; cursor: pointer;
                font-size: 10.5px; font-weight: 600;
                color: var(--text-sub);
            }
            .bn-icon-wrap {
                width: 34px; height: 34px;
                display: flex; align-items: center; justify-content: center;
                border-radius: 50%;
                font-size: 20px;
                transition: background .15s, color .15s;
            }
            .bn-link.active { color: var(--brand); }
            .bn-link.active .bn-icon-wrap { background: var(--brand-l); color: var(--brand); }

            .main-wrapper { padding-bottom: 74px; }

            /* More sheet */
            .more-sheet {
                display: block;
                position: fixed; inset: 0; z-index: 80;
                visibility: hidden;
                pointer-events: none;
            }
            .more-sheet.open { visibility: visible; pointer-events: auto; }
            .more-sheet-backdrop {
                position: absolute; inset: 0;
                background: rgba(15,15,15,.5);
                opacity: 0;
                transition: opacity .25s;
            }
            .more-sheet.open .more-sheet-backdrop { opacity: 1; }
            .more-sheet-panel {
                position: absolute; left: 0; right: 0; bottom: 0;
                max-height: 78vh;
                overflow-y: auto;
                background: var(--surface);
                border-radius: 20px 20px 0 0;
                padding: 10px 18px calc(20px + env(safe-area-inset-bottom));
                transform: translateY(100%);
                transition: transform .3s cubic-bezier(.32,.72,0,1);
                box-shadow: 0 -20px 50px -20px rgba(24,20,15,.3);
            }
            .more-sheet.open .more-sheet-panel { transform: translateY(0); }
            .more-sheet-handle {
                width: 40px; height: 4px;
                background: var(--border-sub);
                border-radius: 999px;
                margin: 0 auto 12px;
            }
            .more-sheet-head {
                display: flex; align-items: center; justify-content: space-between;
                margin-bottom: 14px;
            }
            .more-sheet-title {
                font-family: var(--tx-serif, 'Fraunces'), Georgia, serif;
                font-size: 18px; font-weight: 700; color: var(--text);
            }
            .more-sheet-close {
                width: 32px; height: 32px;
                border: 1px solid var(--border);
                background: transparent;
                border-radius: 8px;
                font-size: 20px; color: var(--text-sub);
                display: flex; align-items: center; justify-content: center;
                cursor: pointer;
            }
            .more-sheet-close:hover { background: var(--brand-l); color: var(--brand); }
            .more-section { margin-bottom: 18px; }
            .more-section:last-child { margin-bottom: 4px; }
            .more-section-label {
                font-size: 11px; font-weight: 700; text-transform: uppercase;
                letter-spacing: .5px; color: var(--text-sub);
                margin-bottom: 10px;
            }
            .more-grid { display: flex; flex-wrap: wrap; gap: 10px; }
            .more-item {
                width: 78px;
                display: flex; flex-direction: column; align-items: center; gap: 6px;
                font-size: 11.5px; font-weight: 500; color: var(--text);
                text-align: center;
            }
            .more-item-icon {
                width: 52px; height: 52px;
                display: flex; align-items: center; justify-content: center;
                border-radius: 14px;
                background: var(--bg);
                color: var(--text-sub);
                font-size: 22px;
            }
            .more-item.active { color: var(--brand); font-weight: 700; }
            .more-item.active .more-item-icon { background: var(--brand); color: #fff; }
        }

        /* ── Page wrapper ─────────────────────────────────── */
        .main-wrapper { min-height: calc(100vh - var(--topbar-h) - var(--tabs-h)); }
        .page-content { padding: 28px 24px 40px; max-width: 1400px; margin: 0 auto; }

        /* ── Page header ──────────────────────────────────── */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }
        .page-header-left { flex: 1; min-width: 0; }
        .page-title {
            font-family: 'Fraunces', Georgia, 'Times New Roman', serif;
            font-size: 32px;
            line-height: 1.15;
            color: var(--text);
            margin: 0 0 4px;
            font-weight: 400;
        }
        .page-subtitle, .page-desc {
            font-size: 14px;
            color: var(--text-sub);
            margin: 0;
        }
        .page-actions { display: flex; gap: 8px; flex-wrap: wrap; }

        /* ── Cards ────────────────────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xs);
            overflow: hidden;
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            background: var(--surface);
        }
        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-title i { font-size: 16px; color: var(--text-sub); }
        .card-body, .card-section { padding: 20px; }

        .text-primary { color: var(--text) !important; }
        .text-muted   { color: var(--text-sub) !important; }
        .text-danger  { color: var(--danger)  !important; }

        /* ── KPI / stat cards ─────────────────────────────── */
        .stat-card { padding: 0; }
        .stat-card .card-body { padding: 20px; }
        .stat-label { font-size: 12px; color: var(--text-sub); font-weight: 500; text-transform: uppercase; letter-spacing: .4px; }
        .stat-value { font-size: 28px; font-weight: 700; color: var(--text); line-height: 1.1; margin-top: 4px; }
        .stat-footer { font-size: 12px; color: var(--text-sub); margin-top: 10px; }
        .stat-up { color: var(--success); font-weight: 600; }
        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
            background: var(--bg);
            color: var(--text);
        }

        .kpi-card { padding: 20px; display: flex; flex-direction: column; gap: 4px; }
        .kpi-label { font-size: 11px; color: var(--text-sub); text-transform: uppercase; letter-spacing: .5px; font-weight: 600; }
        .kpi-value { font-size: 26px; font-weight: 700; color: var(--text); line-height: 1.1; }
        .kpi-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }

        /* ── Buttons ──────────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid transparent;
            cursor: pointer;
            transition: background .15s, color .15s, border-color .15s;
        }
        .btn-primary {
            background: var(--brand);
            color: #fff;
            border-color: var(--brand);
        }
        .btn-primary:hover { background: var(--brand-h); border-color: var(--brand-h); color: #fff; }
        .btn-default, .btn-light {
            background: var(--surface);
            color: var(--text);
            border: 1px solid var(--border);
        }
        .btn-default:hover, .btn-light:hover { background: var(--bg); }
        /* ── Delete / destructive buttons: ALWAYS solid red + white ── */
        .btn.btn-danger,
        .btn.btn-critical,
        .btn.text-danger,
        .btn.btn-outline.text-danger,
        .btn.btn-light.text-danger,
        .btn-outline.text-danger,
        button.btn.text-danger,
        a.btn.text-danger,
        [class*="delete-"].btn,
        button[class*="delete-"],
        #confirmDialogOk.btn-danger {
            background-color: var(--danger) !important;
            color: #fff !important;
            border: 1px solid var(--danger) !important;
            --bs-btn-bg: var(--danger);
            --bs-btn-color: #fff;
            --bs-btn-border-color: var(--danger);
            --bs-btn-hover-bg: var(--danger);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-border-color: var(--danger);
            --bs-btn-active-bg: var(--danger);
            --bs-btn-active-color: #fff;
            --bs-btn-active-border-color: var(--danger);
        }
        .btn.btn-danger:hover, .btn.btn-danger:focus, .btn.btn-danger:active,
        .btn.btn-critical:hover, .btn.btn-critical:focus, .btn.btn-critical:active,
        .btn.text-danger:hover, .btn.text-danger:focus, .btn.text-danger:active,
        .btn.btn-outline.text-danger:hover, .btn.btn-outline.text-danger:focus, .btn.btn-outline.text-danger:active,
        .btn.btn-light.text-danger:hover, .btn.btn-light.text-danger:focus, .btn.btn-light.text-danger:active,
        .btn-outline.text-danger:hover, .btn-outline.text-danger:focus, .btn-outline.text-danger:active,
        button.btn.text-danger:hover, button.btn.text-danger:focus, button.btn.text-danger:active,
        a.btn.text-danger:hover, a.btn.text-danger:focus, a.btn.text-danger:active,
        [class*="delete-"].btn:hover, [class*="delete-"].btn:focus, [class*="delete-"].btn:active,
        button[class*="delete-"]:hover, button[class*="delete-"]:focus, button[class*="delete-"]:active {
            background-color: var(--danger) !important;
            border-color: var(--danger) !important;
            color: #fff !important;
        }
        .btn.btn-danger i,
        .btn.btn-critical i,
        .btn.text-danger i,
        .btn-outline.text-danger i { color: #fff !important; }
        .btn-outline {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text);
        }
        .btn-outline:hover { background: var(--bg); }
        .btn-sm  { padding: 6px 12px; font-size: 12px; }
        .btn-xs  { padding: 4px 10px; font-size: 11px; }
        .btn-icon { padding: 6px 10px; }
        .btn-icon.btn-sm { padding: 6px 8px; }

        /* ── Forms ────────────────────────────────────────── */
        .form-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 4px;
            display: block;
        }
        .form-control, .form-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 13px;
            color: var(--text);
            background: var(--surface);
            font-family: inherit;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--text);
            box-shadow: 0 0 0 3px rgba(26,24,20,.06);
        }
        .form-control::placeholder { color: var(--text-dis); }
        .form-control.is-invalid { border-color: var(--danger); }

        /* ── Tables ───────────────────────────────────────── */
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .table thead th {
            text-align: start;
            padding: 12px 16px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--text-sub);
            font-weight: 600;
            background: var(--surface-muted);
            border-bottom: 1px solid var(--border);
        }
        .table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        .table tbody tr:last-child td { border-bottom: 0; }
        .table-hover tbody tr:hover { background: var(--surface-muted); }

        /* ── Badges ───────────────────────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge .dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .badge-success  { background: var(--success-l); color: var(--success); }
        .badge-warning  { background: var(--warning-l); color: var(--warning); }
        .badge-critical { background: var(--danger-l);  color: var(--danger);  }
        .badge-neutral  { background: var(--bg);        color: var(--text-sub); }
        .badge-muted    { background: var(--bg);        color: var(--text-sub); }
        .badge-primary  { background: var(--bg);        color: var(--text); }

        /* ── Alerts ───────────────────────────────────────── */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            display: flex;
            gap: 8px;
        }
        .alert-success { background: var(--success-l); color: var(--success); }
        .alert-warning { background: var(--warning-l); color: var(--warning); }
        .alert-danger  { background: var(--danger-l);  color: var(--danger);  }

        /* ── Empty state ──────────────────────────────────── */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: var(--text-sub);
        }
        .empty-state-icon { font-size: 48px; color: var(--text-dis); margin-bottom: 12px; }
        .empty-state-title { font-size: 15px; font-weight: 600; color: var(--text); margin: 6px 0 4px; }
        .empty-state-desc { font-size: 13px; color: var(--text-sub); margin-bottom: 12px; }

        /* ── Toast ────────────────────────────────────────── */
        #toast-container {
            position: fixed; top: 76px; right: 20px; z-index: 9999;
            display: flex; flex-direction: column; gap: 8px;
            max-width: 360px;
        }
        .toast-msg {
            background: var(--text);
            color: #fff;
            padding: 12px 14px;
            border-radius: 10px;
            box-shadow: var(--shadow-lg);
            font-size: 13px;
            display: flex; gap: 8px; align-items: center;
            animation: toastIn .22s ease;
        }
        .toast-msg.success { background: var(--success); }
        .toast-msg.error   { background: var(--danger);  }
        .toast-msg.warning { background: var(--warning); }
        @keyframes toastIn  { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes toastOut { to { opacity: 0; transform: translateY(-8px); } }

        /* ── Utilities ────────────────────────────────────── */
        .fade-up { animation: fadeUp .25s ease; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
        .dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; display: inline-block; }

        .input-group { display: flex; align-items: stretch; }
        .input-group > .form-control:first-child { border-top-right-radius: 0; border-bottom-right-radius: 0; }
        .input-group > .form-control:last-child  { border-top-left-radius: 0;  border-bottom-left-radius: 0;  }
        .input-group .input-group-text {
            display: inline-flex; align-items: center; padding: 0 12px;
            background: var(--surface-muted);
            border: 1px solid var(--border);
            font-size: 13px; color: var(--text-sub);
        }
        .input-group > :not(:first-child) { border-left: 0; }
        .input-group > :not(:last-child)  { border-right: 0; }

        /* ── Mobile ───────────────────────────────────────── */
        @media (max-width: 768px) {
            .topbar { padding: 0 14px; }
            .topbar-brand { display: none; }
            .topbar-user-name, .topbar-user-role { display: none; }
            .page-content { padding: 20px 14px 32px; }
            .page-title { font-size: 24px; }
        }
    /* ═══ Modal shell ═══ */
    .pl-modal {
        border: 0;
        border-radius: 14px;
        box-shadow: 0 20px 60px rgba(24, 20, 15, .18);
        overflow: hidden;
    }
    .pl-modal-header {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        background: var(--surface);
    }
    .pl-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: var(--brand-l);
        color: var(--brand);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .pl-modal-title {
        font-family: 'Fraunces', Georgia, 'Times New Roman', serif;
        font-size: 22px;
        font-weight: 400;
        color: var(--text);
        margin: 0 0 2px;
    }
    .pl-modal-sub {
        font-size: 12px;
        color: var(--text-sub);
        margin: 0;
    }
    .pl-modal-body { padding: 20px 24px; }
    .pl-modal-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--border);
        background: var(--surface-muted);
        gap: 8px;
    }

    /* ═══ Field primitives ═══ */
    .pl-field { margin-bottom: 20px; }
    .pl-field:last-child { margin-bottom: 0; }
    .pl-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 6px;
        letter-spacing: .02em;
    }
    .pl-required { color: var(--danger); }
    .pl-optional { color: var(--text-dis); font-weight: 400; font-size: 11px; }
    .pl-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        color: var(--text);
        background: var(--surface);
        transition: border-color .15s, box-shadow .15s;
    }
    .pl-input:focus {
        outline: none;
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(139, 90, 43, .12);
    }

    /* ═══ File upload zone ═══ */
    .pl-file-zone {
        position: relative;
        border: 1.5px dashed var(--border-sub);
        border-radius: 12px;
        background: var(--surface-muted);
        min-height: 130px;
        transition: border-color .15s, background .15s;
    }
    .pl-file-zone:hover {
        border-color: var(--brand);
        background: var(--brand-l);
    }
    .pl-file-input {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .pl-file-placeholder {
        text-align: center;
        padding: 22px 16px;
        pointer-events: none;
    }
    .pl-file-placeholder i {
        font-size: 36px;
        color: var(--text-dis);
        display: block;
        margin-bottom: 6px;
    }
    .pl-file-title { font-size: 14px; font-weight: 500; color: var(--text); margin-bottom: 2px; }
    .pl-file-hint  { font-size: 12px; color: var(--text-sub); }

    .pl-file-selected {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
    }
    .pl-file-selected > i {
        font-size: 24px;
        color: var(--brand);
        flex-shrink: 0;
    }
    .pl-file-name {
        flex: 1;
        font-size: 13px;
        color: var(--text);
        font-weight: 500;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .pl-file-clear {
        background: var(--surface);
        border: 1px solid var(--border);
        color: var(--text-sub);
        border-radius: 8px;
        width: 30px; height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        z-index: 2;
        position: relative;
    }
    .pl-file-clear:hover { color: var(--danger); border-color: var(--danger); }

    /* ═══ Customer picker ═══ */
    .pl-picker {
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        overflow: hidden;
    }
    .pl-picker-head {
        padding: 14px 16px;
        background: var(--surface-muted);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pl-picker-head i { color: var(--brand); font-size: 18px; }
    .pl-picker-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }
    .pl-picker-badge {
        margin-inline-start: auto;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        background: var(--bg);
        color: var(--text-sub);
    }
    .pl-picker-badge.has-selection {
        background: var(--brand);
        color: #fff;
    }
    .pl-picker-search {
        padding: 10px 14px;
        border-bottom: 1px solid var(--border);
        position: relative;
    }
    .pl-picker-search i {
        position: absolute;
        left: 22px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-dis);
        font-size: 16px;
    }
    .pl-picker-search input {
        width: 100%;
        padding: 8px 12px 8px 34px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        background: var(--surface);
    }
    .pl-picker-search input:focus {
        outline: none;
        border-color: var(--brand);
    }
    .pl-picker-list {
        max-height: 240px;
        overflow-y: auto;
    }
    .pl-picker-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        cursor: pointer;
        border-bottom: 1px solid var(--border);
        transition: background .15s;
    }
    .pl-picker-item:last-child { border-bottom: 0; }
    .pl-picker-item:hover { background: var(--surface-muted); }
    .pl-picker-item input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--brand);
        cursor: pointer;
        flex-shrink: 0;
    }
    .pl-picker-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--brand-l);
        color: var(--brand);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }
    .pl-picker-info { flex: 1; min-width: 0; }
    .pl-picker-name { font-size: 13px; font-weight: 600; color: var(--text); line-height: 1.2; }
    .pl-picker-email {
        font-size: 11px;
        color: var(--text-sub);
        margin-top: 2px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .pl-picker-item.selected { background: var(--brand-l); }
    .pl-picker-item.hidden { display: none; }

    .pl-picker-footer {
        padding: 10px 16px;
        border-top: 1px solid var(--border);
        background: var(--surface-muted);
        display: flex;
        gap: 10px;
        font-size: 12px;
    }
    .pl-picker-footer button {
        background: none;
        border: 0;
        color: var(--brand);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        padding: 0;
    }
    .pl-picker-footer button:hover { text-decoration: underline; }
    .pl-picker-spacer { flex: 1; }

    .pl-picker-empty {
        text-align: center;
        padding: 32px 16px;
        color: var(--text-sub);
    }
    .pl-picker-empty i { font-size: 32px; display: block; margin-bottom: 8px; color: var(--text-dis); }

    /* Reused for "view assigned" modal */
    .pl-assigned-list { display: flex; flex-direction: column; gap: 4px; }
    .pl-assigned-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 10px;
        background: var(--surface-muted);
    }

    /* Loading spinner */
    .pl-spinner {
        display: inline-block;
        width: 22px;
        height: 22px;
        border: 2px solid var(--border);
        border-top-color: var(--brand);
        border-radius: 50%;
        animation: pl-spin .7s linear infinite;
    }
    @keyframes pl-spin { to { transform: rotate(360deg); } }

    /* ── FORCE RED delete buttons (hardcoded, highest priority) ── */
    html body .btn.text-danger,
    html body .btn.btn-danger,
    html body .btn-icon.text-danger,
    html body .btn-icon.btn-sm.text-danger,
    html body button.btn.text-danger,
    html body button.btn-icon.text-danger,
    html body button[class*="delete-"],
    html body [class*="delete-"].btn,
    html body [class*="delete-"].btn-icon,
    html body #confirmDialogOk.btn-danger {
        background-color: #dc2626 !important;
        background-image: none !important;
        color: #ffffff !important;
        border: 1px solid #dc2626 !important;
    }
    html body .btn.text-danger:hover,
    html body .btn.text-danger:focus,
    html body .btn.text-danger:active,
    html body .btn.btn-danger:hover,
    html body .btn.btn-danger:focus,
    html body .btn.btn-danger:active,
    html body button[class*="delete-"]:hover,
    html body button[class*="delete-"]:focus,
    html body button[class*="delete-"]:active,
    html body [class*="delete-"].btn:hover,
    html body [class*="delete-"].btn:focus,
    html body [class*="delete-"].btn:active {
        background-color: #dc2626 !important;
        color: #ffffff !important;
        border-color: #dc2626 !important;
    }
    html body .btn.text-danger i,
    html body .btn.btn-danger i,
    html body button[class*="delete-"] i,
    html body [class*="delete-"].btn i { color: #ffffff !important; }

    /* ─── Orders card grid (ord-* prefix) ────────────────────── */
    .ord-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 16px;
        padding: 18px;
    }
    .ord-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 18px 20px 16px;
        display: flex; flex-direction: column; gap: 12px;
        transition: border-color .2s, box-shadow .2s, transform .2s;
    }
    .ord-card:hover {
        border-color: var(--brand);
        box-shadow: 0 14px 30px -22px rgba(26,24,20,.22);
        transform: translateY(-1px);
    }
    .ord-card-head {
        display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;
    }
    .ord-card-id { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .ord-num {
        font-family: var(--tx-serif, 'Fraunces'), Georgia, serif;
        font-weight: 800; font-size: 18px; color: var(--text); letter-spacing: -.01em;
    }
    .ord-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 999px;
        background: var(--brand-l, rgba(176,141,87,.12));
        color: var(--brand, #8a6b3e);
        font-size: 10.5px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
    }
    .ord-pill-warn { background: rgba(217,142,42,.14); color: #b56a00; }
    .ord-card-actions { display: flex; gap: 4px; flex-shrink: 0; }
    .ord-iconbtn {
        width: 32px; height: 32px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; background: transparent;
        border: 1px solid var(--border); color: var(--text-sub);
        cursor: pointer; text-decoration: none;
        transition: background .15s, color .15s, border-color .15s;
        padding: 0;
    }
    .ord-iconbtn i { font-size: 16px; }
    .ord-iconbtn:hover { background: var(--brand-l); color: var(--brand); border-color: var(--brand); }
    .ord-iconbtn-danger:hover { background: rgba(220,53,69,.1); color: #b02a37; border-color: #b02a37; }
    .ord-iconbtn-primary {
        background: var(--brand); border-color: var(--brand); color: #fff;
        gap: 6px; padding: 0 12px; width: auto; font-size: 12px; font-weight: 700;
    }
    .ord-iconbtn-primary:hover { background: var(--text); border-color: var(--text); color: #fff; }
    .ord-iconbtn-primary span { font-size: 12px; }
    .ord-card-meta {
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        font-size: 12px; color: var(--text-sub);
    }
    .ord-meta-item { display: inline-flex; align-items: center; gap: 5px; }
    .ord-meta-item i { font-size: 14px; color: var(--brand, #b08d57); }
    .ord-meta-sep { color: var(--border); }
    .ord-jobs {
        list-style: none; margin: 0; padding: 0;
        display: flex; flex-direction: column; gap: 8px;
        border-top: 1px dashed var(--border); padding-top: 12px;
    }
    .ord-job {
        display: flex; gap: 10px;
        padding: 8px 10px;
        background: var(--bg, #f7f4ef);
        border-radius: 10px; border: 1px solid var(--border);
    }
    .ord-job-num {
        flex-shrink: 0; width: 22px; height: 22px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 50%; background: var(--brand); color: #fff;
        font-size: 11px; font-weight: 800;
    }
    .ord-job-body { flex: 1; min-width: 0; }
    .ord-job-title { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; }
    .ord-job-title strong { font-size: 13px; color: var(--text); font-weight: 700; }
    .ord-tag {
        display: inline-block; padding: 2px 7px; border-radius: 4px;
        background: var(--bg); color: var(--text-sub);
        font-size: 9.5px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase;
    }
    .ord-tag-warn { background: rgba(217,142,42,.14); color: #b56a00; }
    .ord-job-specs {
        display: flex; flex-wrap: wrap; gap: 4px 12px;
        font-size: 12px; color: var(--text-sub);
    }
    .ord-job-specs span { display: inline-flex; align-items: center; gap: 4px; }
    .ord-job-specs i { font-size: 13px; color: var(--brand, #b08d57); opacity: .8; }
    .ord-empty-search {
        text-align: center; padding: 40px 20px;
        color: var(--text-sub); font-size: 13px; margin: 0;
    }
    .ord-empty-search i { font-size: 24px; display: block; margin-bottom: 8px; color: var(--brand); }

    @media (max-width: 700px) {
        .ord-grid { grid-template-columns: 1fr; padding: 14px; gap: 12px; }
        .ord-card { padding: 14px 14px 12px; }
        .ord-num { font-size: 16px; }
    }
    </style>

    <?= $extraCss ?? '' ?>
</head>
