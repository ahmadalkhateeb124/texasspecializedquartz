
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Customers — Texas Specialized Quartz & Granite</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
   <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

   <style>
      /* ═══════════════════════════════════════════════════
       GRANITE ARTISTS — LUXURY CUSTOMER PORTAL
       Theme: Warm White + Champagne Gold
    ═══════════════════════════════════════════════════ */
      :root {
         --sidebar-w: 240px;
         --header-h: 60px;

         /* ── Primary — Champagne Gold ── */
         --primary: #767676;
         --primary-dark: #767676;
         --primary-light: #a4a4a4;

         /* ── Surfaces ── */
         --surface: #ffffff;
         --bg: #f8f6f2;
         --bg-sub: #fdfcfa;

         /* ── Borders ── */
         --border: #e8e3dc;
         --border-strong: #d5cfc5;

         /* ── Text — warm near-black ── */
         --text: #1c1917;
         --text-sec: #292524;
         --muted: #78716c;
         --subtle: #a8a29e;

         /* ── Semantic ── */
         --success: #3d6b4f;
         --success-light: #dff0e5;
         --warning: #b45309;
         --warning-light: #fef3c7;
         --danger: #b91c1c;
         --danger-light: #fee2e2;
         --info: #64748b;
         --info-light: #f1f5f9;

         /* ── Sidebar — clean white ── */
         --sidebar-bg: #ffffff;
         --sidebar-hover: #f8f6f2;
         --sidebar-active: rgba(201, 169, 110, .1);
         --sidebar-border: #e8e3dc;
         --sidebar-text: #78716c;

         /* ── Shadows ── */
         --shadow: 0 1px 4px rgba(255, 255, 255, .35), 0 0 0 1px rgba(255, 255, 255, .12);
         --shadow-md: 0 4px 16px rgba(255, 255, 255, .25), 0 0 0 1px rgba(255, 255, 255, .08);
         --shadow-lg: 0 8px 32px rgba(255, 255, 255, .18), 0 0 0 1px rgba(255, 255, 255, .06);

         /* ── Radius ── */
         --radius-sm: 6px;
         --radius: 8px;
         --radius-md: 10px;
         --radius-lg: 12px;

         --transition: all .15s ease;
      }

      /* ── Reset ── */
      *,
      *::before,
      *::after {
         box-sizing: border-box;
      }

      html {
         height: 100%;
      }

      body {
         font-family: 'Playfair Display', Georgia, serif !important;
         font-size: 14px;
         line-height: 1.6;
         color: var(--text);
         background: var(--bg);
         margin: 0;
         min-height: 100%;
         -webkit-font-smoothing: antialiased;
      }

      a {
         color: var(--primary);
         text-decoration: none;
      }

      a:hover {
         color: var(--primary-dark);
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

      .form-control-plaintext {
         background-color: #FAFAFA;
         padding: 10px;
         border-radius: 10px;
      }

      .sidebar-brand {
         height: var(--header-h);
         padding: 0 18px;
         display: flex;
         align-items: center;
         gap: 11px;
         border-bottom: 1px solid var(--sidebar-border);
         flex-shrink: 0;
         text-decoration: none;
      }

      .sidebar-brand-logo {
         width: 34px;
         height: 34px;
         border-radius: var(--radius);
         display: flex;
         align-items: center;
         justify-content: center;
         font-size: 16px;
         color: #fff;
         flex-shrink: 0;
      }

      .sidebar-brand-name {
         font-size: 14px;
         font-weight: 700;
         color: var(--text);
         letter-spacing: -.3px;
      }

      .sidebar-brand-tag {
         font-size: 9px;
         font-weight: 700;
         letter-spacing: 1px;
         text-transform: uppercase;
         color: #767676;
         background: rgba(201, 169, 110, .1);
         padding: 2px 6px;
         border-radius: 4px;
      }

      .sidebar-nav {
         flex: 1;
         overflow-y: auto;
         padding: 12px 0;
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

      .nav-label {
         padding: 12px 18px 4px;
         font-size: 10px;
         font-weight: 700;
         letter-spacing: 1.2px;
         text-transform: uppercase;
         color: var(--subtle);
      }

      .nav-list {
         list-style: none;
         margin: 0;
         padding: 0 8px;
      }

      .nav-list li+li {
         margin-top: 2px;
      }

      .nav-link {
         display: flex;
         align-items: center;
         gap: 10px;
         padding: 9px 12px;
         border-radius: var(--radius);
         color: var(--sidebar-text);
         font-size: 13.5px;
         font-weight: 500;
         text-decoration: none;
         transition: var(--transition);
         position: relative;
      }

      .nav-link:hover {
         background: var(--sidebar-hover);
         color: var(--text);
         text-decoration: none;
      }

      .nav-link.active {
         background: var(--sidebar-active);
         color: #767676;
         font-weight: 600;
      }

      .nav-link.active::before {
         content: '';
         position: absolute;
         left: 0;
         top: 25%;
         bottom: 25%;
         width: 3px;
         background: var(--primary);
         border-radius: 0 2px 2px 0;
         margin-left: -8px;
      }

      .nav-icon {
         font-size: 18px;
         width: 20px;
         flex-shrink: 0;
         display: flex;
         align-items: center;
         justify-content: center;
         opacity: .7;
      }

      .nav-link.active .nav-icon,
      .nav-link:hover .nav-icon {
         opacity: 1;
      }

      /* Company card */
      .sidebar-company-card {
         margin: 8px;
         padding: 12px;
         background: rgba(201, 169, 110, .06);
         border: 1px solid var(--sidebar-border);
         border-radius: var(--radius);
      }

      .sidebar-company-name {
         font-size: 12px;
         font-weight: 600;
         color: var(--text);
         margin-bottom: 2px;
      }

      .sidebar-company-meta {
         font-size: 11px;
         color: var(--muted);
      }

      .sidebar-footer {
         padding: 10px 8px;
         border-top: 1px solid var(--sidebar-border);
         flex-shrink: 0;
      }

      .sidebar-user-btn {
         display: flex;
         align-items: center;
         gap: 10px;
         width: 100%;
         padding: 8px 10px;
         border-radius: var(--radius);
         background: none;
         border: none;
         cursor: pointer;
         text-align: left;
         transition: var(--transition);
      }

      .sidebar-user-btn:hover {
         background: var(--sidebar-hover);
      }

      .sidebar-avatar {
         width: 32px;
         height: 32px;
         border-radius: 50%;
         background: linear-gradient(135deg, var(--primary), var(--primary-dark));
         display: flex;
         align-items: center;
         justify-content: center;
         font-size: 12px;
         font-weight: 700;
         color: #fff;
         flex-shrink: 0;
      }

      .sidebar-user-name {
         font-size: 12.5px;
         font-weight: 600;
         color: var(--text);
         white-space: nowrap;
         overflow: hidden;
         text-overflow: ellipsis;
         max-width: 130px;
      }

      .sidebar-user-role {
         font-size: 10.5px;
         color: var(--muted);
      }

      .sidebar-user-chevron {
         margin-left: auto;
         color: var(--muted);
         font-size: 15px;
         flex-shrink: 0;
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
         background: #fff;
         border-bottom: 1px solid var(--border);
         display: flex;
         align-items: center;
         padding: 0 20px;
         gap: 12px;
         z-index: 900;
         flex-shrink: 0;
      }

      .topbar-toggle {
         display: none;
         align-items: center;
         justify-content: center;
         width: 36px;
         height: 36px;
         border-radius: var(--radius-sm);
         border: 1px solid var(--border);
         background: none;
         cursor: pointer;
         font-size: 18px;
         color: var(--text);
      }

      .topbar-right {
         margin-left: auto;
         display: flex;
         align-items: center;
         gap: 6px;
      }

      .topbar-icon-btn {
         width: 36px;
         height: 36px;
         border-radius: var(--radius);
         border: 1px solid var(--border);
         background: none;
         display: flex;
         align-items: center;
         justify-content: center;
         font-size: 18px;
         color: var(--muted);
         cursor: pointer;
         text-decoration: none;
         transition: var(--transition);
      }

      .topbar-icon-btn:hover {
         background: var(--bg);
         color: var(--primary);
         border-color: var(--primary);
         text-decoration: none;
      }

      .topbar-divider {
         width: 1px;
         height: 22px;
         background: var(--border);
         margin: 0 2px;
      }

      .topbar-user {
         display: flex;
         align-items: center;
         gap: 8px;
         padding: 4px 8px;
         border-radius: var(--radius);
         cursor: pointer;
         transition: var(--transition);
         border: 1px solid transparent;
      }

      .topbar-user:hover {
         background: var(--bg);
         border-color: var(--border);
      }

      .topbar-user-avatar {
         width: 32px;
         height: 32px;
         border-radius: 50%;
         background: linear-gradient(135deg, var(--primary), var(--primary-dark));
         display: flex;
         align-items: center;
         justify-content: center;
         font-size: 12px;
         font-weight: 700;
         color: #fff;
      }

      .topbar-user-name {
         font-size: 13px;
         font-weight: 600;
         color: var(--text);
      }

      .topbar-user-role {
         font-size: 11px;
         color: var(--muted);
      }

      .topbar-breadcrumb {
         font-size: 13px;
         display: flex;
         align-items: center;
         gap: 0;
      }

      .bc-sep {
         color: var(--border);
         margin: 0 5px;
         font-size: 15px;
      }

      .bc-link {
         color: var(--muted);
         transition: color .15s;
      }

      .bc-link:hover {
         color: var(--primary);
      }

      .bc-current {
         color: var(--text);
         font-weight: 500;
      }

      /* ═══════════════════════════════════════════════════
       PAGE CONTENT
    ═══════════════════════════════════════════════════ */
      .page-content {
         flex: 1;
         padding: 22px 24px;
      }

      .page-header {
         margin-bottom: 22px;
      }

      .page-title {
         font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
         font-size: 21px;
         font-weight: 700;
         letter-spacing: -.3px;
         color: var(--text);
         margin: 0 0 3px;
      }

      .page-subtitle {
         font-size: 13px;
         color: var(--muted);
         margin: 0;
      }

      .page-desc {
         font-size: 13px;
         color: var(--muted);
         margin: 0;
      }

      /* ═══════════════════════════════════════════════════
       CARDS
    ═══════════════════════════════════════════════════ */
      .card {
         background: var(--surface);
         border: 1px solid var(--border);
         border-radius: var(--radius-lg);
         box-shadow: var(--shadow);
         transition: box-shadow .2s;
      }

      .card:hover {
         box-shadow: var(--shadow-md);
      }

      .card-header {
         padding: 14px 18px;
         border-bottom: 1px solid var(--border);
         background: none;
         border-radius: var(--radius-lg) var(--radius-lg) 0 0 !important;
         display: flex;
         align-items: center;
         justify-content: space-between;
         gap: 10px;
      }

      .card-title {
         font-size: 13.5px;
         font-weight: 600;
         color: var(--text);
         margin: 0;
         display: flex;
         align-items: center;
         gap: 7px;
      }

      .card-body {
         padding: 18px;
      }

      .card-section {
         padding: 18px;
         border-bottom: 1px solid var(--border);
      }

      .card-section:last-child {
         border-bottom: none;
      }

      /* Stat cards */
      .stat-icon {
         width: 44px;
         height: 44px;
         border-radius: var(--radius);
         display: flex;
         align-items: center;
         justify-content: center;
         font-size: 20px;
      }

      .stat-value {
         font-size: 26px;
         font-weight: 800;
         letter-spacing: -1px;
         line-height: 1.1;
         color: var(--text);
      }

      .stat-label {
         font-size: 11.5px;
         font-weight: 500;
         color: var(--muted);
         margin-top: 2px;
      }

      .stat-footer {
         margin-top: 12px;
         padding-top: 12px;
         border-top: 1px solid var(--border);
         font-size: 12px;
         color: var(--muted);
      }

      /* Profile */
      .profile-cover {
         height: 120px;
         background: linear-gradient(135deg, var(--primary), var(--primary-dark));
         border-radius: var(--radius-lg) var(--radius-lg) 0 0;
         position: relative;
      }

      .profile-avatar-wrap {
         position: absolute;
         bottom: -28px;
         left: 24px;
      }

      .profile-avatar {
         width: 60px;
         height: 60px;
         border-radius: 50%;
         background: #fff;
         border: 3px solid #fff;
         display: flex;
         align-items: center;
         justify-content: center;
         font-size: 22px;
         font-weight: 800;
         color: var(--primary);
         box-shadow: var(--shadow-md);
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
         color: var(--muted);
         background: var(--bg-sub);
         border-bottom: 1px solid var(--border) !important;
         padding: 10px 14px;
         white-space: nowrap;
         letter-spacing: .4px;
         text-transform: uppercase;
      }

      .table td {
         padding: 12px 14px;
         border-color: var(--border);
         vertical-align: middle;
      }

      .table-hover tbody tr:hover td {
         background: rgba(118, 118, 118, .08);
      }

      /* ═══════════════════════════════════════════════════
       BADGES
    ═══════════════════════════════════════════════════ */
      .badge {
         font-size: 11px;
         font-weight: 600;
         padding: 3px 9px;
         border-radius: 20px;
         display: inline-flex;
         align-items: center;
         gap: 4px;
      }

      .badge-success {
         background: var(--success-light);
         color: #1e4a2f;
      }

      .badge-warning {
         background: var(--warning-light);
         color: #92400e;
      }

      .badge-danger {
         background: var(--danger-light);
         color: #991b1b;
      }

      .badge-info {
         background: var(--info-light);
         color: #475569;
      }

      .badge-primary {
         background: var(--primary-light);
         color: #767676;
      }

      .badge-muted {
         background: #f5f4f2;
         color: var(--muted);
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
         padding: 8px 16px;
         border-radius: var(--radius);
         transition: var(--transition);
         display: inline-flex;
         align-items: center;
         gap: 6px;
         line-height: 1.4;
      }

      .btn-primary {
         background: var(--primary);
         border-color: var(--primary);
         color: #fff;
         box-shadow: 0 1px 3px rgba(255, 255, 255, .25);
      }

      .btn-primary:hover {
         background: var(--primary-dark);
         border-color: var(--primary-dark);
         color: #fff;
         box-shadow: 0 3px 10px rgba(255, 255, 255, .3);
         transform: translateY(-1px);
      }

      .btn-light {
         background: var(--bg);
         border-color: var(--border);
         color: var(--text);
      }

      .btn-light:hover {
         background: var(--border);
         color: var(--text);
      }

      .btn-default {
         background: var(--surface);
         border-color: var(--border-strong);
         color: var(--text);
      }

      .btn-default:hover {
         background: var(--bg);
      }

      .btn-outline-secondary {
         background: transparent;
         border-color: var(--border);
         color: var(--muted);
      }

      .btn-outline-secondary:hover {
         background: var(--bg);
         color: var(--text);
      }

      .btn-sm {
         padding: 5px 12px;
         font-size: 12px;
      }

      .btn-xs {
         padding: 3px 9px;
         font-size: 11px;
      }

      .btn-icon {
         width: 32px;
         height: 32px;
         padding: 0;
         justify-content: center;
         border-radius: var(--radius);
      }

      /* ═══════════════════════════════════════════════════
       FORMS
    ═══════════════════════════════════════════════════ */
      .form-label {
         font-size: 13px;
         font-weight: 500;
         color: var(--text);
         margin-bottom: 5px;
      }

      .form-control,
      .form-select {
         font-size: 13px;
         border-radius: var(--radius);
         border: 1px solid var(--border);
         padding: 8px 12px;
         color: var(--text);
         background: #fff;
         transition: border-color .15s, box-shadow .15s;
      }

      .form-control:focus,
      .form-select:focus {
         border-color: var(--primary);
         box-shadow: 0 0 0 3px rgba(201, 169, 110, .15);
         outline: none;
      }

      .form-control::placeholder {
         color: var(--subtle);
      }

      .form-control.is-invalid {
         border-color: var(--danger);
      }

      .image-upload-zone {
         border: 2px dashed var(--border-strong);
         border-radius: var(--radius-lg);
         padding: 28px 20px;
         text-align: center;
         transition: var(--transition);
         background: var(--bg-sub);
         position: relative;
      }

      .image-upload-zone:hover {
         border-color: var(--primary);
         background: var(--primary-light);
      }

      /* ═══════════════════════════════════════════════════
       DROPDOWNS
    ═══════════════════════════════════════════════════ */
      .dropdown-menu {
         font-size: 13px;
         border: 1px solid var(--border);
         border-radius: var(--radius-lg);
         box-shadow: var(--shadow-lg);
         padding: 6px;
      }

      .dropdown-item {
         border-radius: var(--radius-sm);
         padding: 8px 12px;
         color: var(--muted);
         display: flex;
         align-items: center;
         gap: 9px;
         transition: background .12s;
      }

      .dropdown-item:hover {
         background: var(--bg);
         color: var(--text);
      }

      .dropdown-item.text-danger {
         color: var(--danger);
      }

      .dropdown-item.text-danger:hover {
         background: var(--danger-light);
         color: var(--danger);
      }

      .dropdown-divider {
         border-color: var(--border);
         margin: 4px 0;
      }

      .dropdown-header {
         font-size: 11px;
         font-weight: 700;
         letter-spacing: .5px;
         text-transform: uppercase;
         color: var(--subtle);
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
         border-bottom: 1px solid var(--border);
         padding: 15px 18px;
      }

      .modal-title {
         font-size: 14px;
         font-weight: 600;
      }

      .modal-body {
         padding: 18px;
      }

      .modal-footer {
         border-top: 1px solid var(--border);
         padding: 12px 18px;
      }

      /* ═══════════════════════════════════════════════════
       TOAST
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
         border: 1px solid var(--border);
         border-radius: var(--radius);
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
         border-left: 3px solid var(--success);
      }

      .toast-msg.error {
         border-left: 3px solid var(--danger);
      }

      .toast-msg.warning {
         border-left: 3px solid var(--warning);
      }

      .toast-msg.info {
         border-left: 3px solid var(--primary);
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
         background: var(--success-light);
         border-color: rgba(61, 107, 79, .2);
         color: #1e4a2f;
      }

      .alert-danger {
         background: var(--danger-light);
         border-color: rgba(185, 28, 28, .2);
         color: #991b1b;
      }

      /* ═══════════════════════════════════════════════════
       EMPTY STATE
    ═══════════════════════════════════════════════════ */
      .empty-state {
         text-align: center;
         padding: 48px 20px;
      }

      .empty-state-icon {
         font-size: 44px;
         color: var(--border);
         margin-bottom: 14px;
         display: block;
      }

      .empty-state-title {
         font-size: 15px;
         font-weight: 600;
         color: var(--text);
         margin-bottom: 6px;
      }

      .empty-state-desc {
         font-size: 13px;
         color: var(--muted);
         max-width: 300px;
         margin: 0 auto 18px;
      }

      /* ═══════════════════════════════════════════════════
       PAGE FOOTER
    ═══════════════════════════════════════════════════ */
      .page-footer {
         padding: 12px 24px;
         border-top: 1px solid var(--border);
         background: var(--surface);
         font-size: 12px;
         color: var(--subtle);
         display: flex;
         align-items: center;
         justify-content: space-between;
         flex-shrink: 0;
      }

      .page-footer a {
         color: var(--subtle);
      }

      .page-footer a:hover {
         color: var(--primary);
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
         color: var(--primary) !important;
      }

      .bg-primary {
         background: var(--primary) !important;
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
            padding: 14px;
         }
      }
   </style>

   <?= $extraCss ?? '' ?>
</head>