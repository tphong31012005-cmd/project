<!DOCTYPE html>
<html lang="vi" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel – WindyStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ─── CSS Variables ─────────────────────────────── */
        :root {
            --bg-base:        #0a0a0f;
            --bg-surface:     #111118;
            --bg-surface-2:   #16161f;
            --bg-surface-3:   #1c1c28;
            --border:         rgba(255,255,255,0.07);
            --border-hover:   rgba(255,255,255,0.14);
            --text-primary:   #f0f0f5;
            --text-secondary: #8b8b9a;
            --text-muted:     #55556a;
            --accent:         #6366f1;
            --accent-light:   #818cf8;
            --accent-glow:    rgba(99,102,241,0.25);
            --accent-hover:   #4f46e5;
            --success:        #22c55e;
            --success-bg:     rgba(34,197,94,0.12);
            --danger:         #ef4444;
            --danger-bg:      rgba(239,68,68,0.12);
            --warning:        #f59e0b;
            --warning-bg:     rgba(245,158,11,0.12);
            --info:           #06b6d4;
            --info-bg:        rgba(6,182,212,0.12);
            --sidebar-w:      260px;
            --header-h:       64px;
            --radius-sm:      8px;
            --radius-md:      12px;
            --radius-lg:      16px;
            --radius-xl:      24px;
            --transition:     all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm:      0 1px 3px rgba(0,0,0,0.4);
            --shadow-md:      0 4px 20px rgba(0,0,0,0.5);
            --shadow-glow:    0 0 0 1px rgba(99,102,241,0.3), 0 4px 24px rgba(99,102,241,0.15);
        }

        /* ─── Reset & Base ──────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 14px;
            background: var(--bg-base);
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── Scrollbar ─────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--bg-surface-3); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--border-hover); }

        /* ─── Layout ────────────────────────────────────── */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ─── Sidebar ───────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--bg-surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: var(--transition);
        }

        .sidebar-brand {
            height: var(--header-h);
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid var(--border);
            gap: 12px;
            text-decoration: none;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--accent), #a78bfa);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #fff;
            flex-shrink: 0;
            box-shadow: var(--shadow-glow);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.3px;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 0 8px;
            margin: 16px 0 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: var(--transition);
            margin-bottom: 2px;
            position: relative;
            white-space: nowrap;
        }

        .sidebar-link:hover {
            background: var(--bg-surface-2);
            color: var(--text-primary);
        }

        .sidebar-link.active {
            background: var(--accent-glow);
            color: var(--accent-light);
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: var(--accent);
            border-radius: 0 99px 99px 0;
        }

        .sidebar-link .icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        .sidebar-link .badge-count {
            margin-left: auto;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 99px;
            background: var(--bg-surface-3);
            color: var(--text-secondary);
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--border);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: var(--radius-sm);
            background: var(--bg-surface-2);
            cursor: default;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--accent), #a78bfa);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .user-info { flex: 1; overflow: hidden; }
        .user-name { font-size: 13px; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 11px; color: var(--text-muted); }

        .sidebar-divider {
            height: 1px;
            background: var(--border);
            margin: 8px 0;
        }

        /* ─── Top Header Bar ────────────────────────────── */
        .top-bar {
            height: var(--header-h);
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(12px);
        }

        .top-bar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .top-bar-breadcrumb .sep { color: var(--text-muted); }
        .top-bar-breadcrumb .current { color: var(--text-primary); font-weight: 600; }

        .top-bar-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-icon {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-sm);
            background: var(--bg-surface-2);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            cursor: pointer;
            transition: var(--transition);
            font-size: 14px;
            text-decoration: none;
        }

        .btn-icon:hover {
            background: var(--bg-surface-3);
            border-color: var(--border-hover);
            color: var(--text-primary);
        }

        /* ─── Main Content ──────────────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 32px 28px;
        }

        /* ─── Page Header ───────────────────────────────── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* ─── Stats Cards ───────────────────────────────── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-hover), transparent);
        }

        .stat-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: 14px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -1px;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ─── Card ──────────────────────────────────────── */
        .panel {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }

        .panel-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .panel-body {
            padding: 20px;
        }

        /* ─── Table ─────────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead tr {
            border-bottom: 1px solid var(--border);
        }

        .data-table th {
            padding: 12px 16px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            text-align: left;
            white-space: nowrap;
        }

        .data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            color: var(--text-primary);
            font-size: 13.5px;
        }

        .data-table tbody tr:last-child td { border-bottom: none; }

        .data-table tbody tr {
            transition: var(--transition);
        }

        .data-table tbody tr:hover { background: var(--bg-surface-2); }

        .product-thumb {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: var(--bg-surface-2);
        }

        /* ─── Buttons ───────────────────────────────────── */
        .btn-primary-custom {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            line-height: 1;
        }

        .btn-primary-custom:hover {
            background: var(--accent-hover);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px var(--accent-glow);
        }

        .btn-secondary-custom {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            background: var(--bg-surface-2);
            color: var(--text-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            line-height: 1;
        }

        .btn-secondary-custom:hover {
            background: var(--bg-surface-3);
            border-color: var(--border-hover);
            color: var(--text-primary);
        }

        .btn-danger-custom {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            background: var(--danger-bg);
            color: var(--danger);
            border: 1px solid rgba(239,68,68,0.15);
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            line-height: 1;
        }

        .btn-danger-custom:hover {
            background: var(--danger);
            color: #fff;
            border-color: var(--danger);
        }

        .btn-edit-custom {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            background: var(--info-bg);
            color: var(--info);
            border: 1px solid rgba(6,182,212,0.15);
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            line-height: 1;
        }

        .btn-edit-custom:hover {
            background: var(--info);
            color: #fff;
            border-color: var(--info);
        }

        /* ─── Badges ────────────────────────────────────── */
        .badge-custom {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-admin { background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .badge-user  { background: rgba(100,116,139,0.12); color: #94a3b8; border: 1px solid rgba(100,116,139,0.2); }
        .badge-cat   { background: var(--info-bg); color: var(--info); border: 1px solid rgba(6,182,212,0.15); }

        /* ─── Form ──────────────────────────────────────── */
        .form-group { margin-bottom: 20px; }

        .form-label-custom {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control-custom {
            width: 100%;
            padding: 10px 14px;
            background: var(--bg-base);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-size: 13.5px;
            font-family: inherit;
            transition: var(--transition);
            outline: none;
        }

        .form-control-custom:hover { border-color: var(--border-hover); }
        .form-control-custom:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
        .form-control-custom::placeholder { color: var(--text-muted); }

        .form-control-custom option { background: var(--bg-surface-2); color: var(--text-primary); }

        /* File input */
        .file-upload-area {
            border: 2px dashed var(--border);
            border-radius: var(--radius-md);
            padding: 24px;
            text-align: center;
            transition: var(--transition);
            cursor: pointer;
            position: relative;
        }

        .file-upload-area:hover, .file-upload-area:focus-within { border-color: var(--accent); background: var(--accent-glow); }
        .file-upload-area input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
        .file-upload-area .icon { font-size: 28px; color: var(--text-muted); margin-bottom: 8px; display: block; }
        .file-upload-area p { font-size: 13px; color: var(--text-muted); margin: 0; }
        .file-upload-area strong { color: var(--accent-light); }

        /* Preview image */
        .img-preview {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
        }

        /* ─── ID chip ───────────────────────────────────── */
        .id-chip {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            background: var(--bg-surface-3);
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            font-family: monospace;
        }

        /* ─── Welcome Banner ────────────────────────────── */
        .welcome-banner {
            background: linear-gradient(135deg, rgba(99,102,241,0.15) 0%, rgba(167,139,250,0.08) 100%);
            border: 1px solid rgba(99,102,241,0.2);
            border-radius: var(--radius-lg);
            padding: 28px;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 160px; height: 160px;
            background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .welcome-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .welcome-sub { font-size: 13px; color: var(--text-secondary); }

        /* ─── Divider ───────────────────────────────────── */
        .divider {
            height: 1px;
            background: var(--border);
            margin: 20px 0;
        }

        /* ─── Row grid ──────────────────────────────────── */
        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        @media (max-width: 576px) { .row-2 { grid-template-columns: 1fr; } }

        /* ─── Tooltip helper ────────────────────────────── */
        .text-truncate-custom {
            max-width: 220px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ─── Spinner for page transition ───────────────── */
        .sidebar-link, .btn-primary-custom, .btn-secondary-custom,
        .btn-danger-custom, .btn-edit-custom { user-select: none; }

        /* ─── Responsive toggle ─────────────────────────── */
        .sidebar-toggle {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 0 0 60px rgba(0,0,0,0.8); }
            .main-wrapper { margin-left: 0; }
            .sidebar-toggle { display: flex; }
            .overlay {
                position: fixed; inset: 0;
                background: rgba(0,0,0,0.6);
                z-index: 99;
                display: none;
            }
            .overlay.show { display: block; }
        }

    </style>
</head>
<body>
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>
<div class="admin-layout">

    <!-- ══ Sidebar ══════════════════════════════════════ -->
    <aside class="sidebar" id="sidebar">

        <!-- Brand -->
        <a href="index.php" class="sidebar-brand">
            <div class="brand-icon"><i class="fas fa-bolt"></i></div>
            <div class="brand-text">
                <span class="brand-name">WindyStore</span>
                <span class="brand-sub">Admin Console</span>
            </div>
        </a>

        <!-- Nav -->
        <nav class="sidebar-nav">
            <span class="nav-section-label">Tổng quan</span>
            <a href="index.php" class="sidebar-link <?= !isset($_GET['act']) ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-home"></i></span>
                Dashboard
            </a>

            <span class="nav-section-label">Quản lý</span>
            <a href="index.php?act=categories" class="sidebar-link <?= isset($_GET['act']) && in_array($_GET['act'], ['categories', 'addcategory', 'editcategory']) ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-tags"></i></span>
                Danh mục
            </a>
            <a href="index.php?act=products" class="sidebar-link <?= isset($_GET['act']) && in_array($_GET['act'], ['products', 'addproduct', 'editproduct']) ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-box-open"></i></span>
                Sản phẩm
            </a>
            <a href="index.php?act=users" class="sidebar-link <?= isset($_GET['act']) && $_GET['act'] == 'users' ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-users"></i></span>
                Người dùng
            </a>
            <a href="index.php?act=orders" class="sidebar-link <?= isset($_GET['act']) && in_array($_GET['act'], ['orders', 'order_detail']) ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-file-invoice-dollar"></i></span>
                Đơn hàng
            </a>
            <a href="index.php?act=reviews" class="sidebar-link <?= isset($_GET['act']) && in_array($_GET['act'], ['reviews']) ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-star"></i></span>
                Đánh giá
            </a>
            <a href="index.php?act=coupons" class="sidebar-link <?= isset($_GET['act']) && in_array($_GET['act'], ['coupons']) ? 'active' : '' ?>">
                <span class="icon"><i class="fas fa-ticket-alt"></i></span>
                Giảm giá
            </a>

            <div class="sidebar-divider"></div>
            <a href="../index.php" class="sidebar-link" style="color: #f87171;">
                <span class="icon"><i class="fas fa-arrow-left"></i></span>
                Về trang chủ
            </a>
            <a href="../index.php?act=logout" class="sidebar-link" style="color: #f87171;">
                <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                Đăng xuất
            </a>
        </nav>

        <!-- Footer user -->
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="user-avatar">
                    <?= strtoupper(substr($_SESSION['user']['username'] ?? 'A', 0, 1)) ?>
                </div>
                <div class="user-info">
                    <div class="user-name"><?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin') ?></div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- ══ Main Wrapper ══════════════════════════════════ -->
    <div class="main-wrapper">

        <!-- Top Bar -->
        <header class="top-bar">
            <button class="btn-icon sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="top-bar-breadcrumb">
                <span>WindyStore</span>
                <span class="sep">/</span>
                <?php
                $acts = [
                    'categories'  => 'Danh mục',
                    'addcategory' => 'Thêm danh mục',
                    'editcategory'=> 'Sửa danh mục',
                    'products'    => 'Sản phẩm',
                    'addproduct'  => 'Thêm sản phẩm',
                    'editproduct' => 'Sửa sản phẩm',
                    'users'       => 'Người dùng',
                    'orders'      => 'Đơn hàng',
                    'order_detail'=> 'Chi tiết đơn hàng',
                    ''            => 'Dashboard',
                ];
                $current_act = $_GET['act'] ?? '';
                $label = $acts[$current_act] ?? ucfirst($current_act);
                ?>
                <span class="current"><?= $label ?></span>
            </div>
            <div class="top-bar-actions">
                <a href="../index.php" class="btn-icon" title="Xem trang shop">
                    <i class="fas fa-external-link-alt" style="font-size:12px;"></i>
                </a>
                <a href="../index.php?act=logout" class="btn-icon" title="Đăng xuất" style="color: #f87171;">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </header>

        <!-- Page Content starts here -->
        <main class="main-content">

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('show');
}
</script>
