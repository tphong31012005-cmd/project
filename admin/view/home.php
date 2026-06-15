<?php
// Get stats for welcome banner
$conn = connectdb();
$total_p = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_u = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
?>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="welcome-title">Chào mừng trở lại, <?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin') ?>! 👋</div>
    <div class="welcome-sub">Quản lý toàn bộ cửa hàng WindyStore từ bảng điều khiển này.</div>
</div>

<!-- Stats Grid -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(99,102,241,0.15); color: #818cf8;">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-value"><?= $total_p ?></div>
        <div class="stat-label">Sản phẩm</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(34,197,94,0.12); color: #4ade80;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-value"><?= $total_u ?></div>
        <div class="stat-label">Người dùng</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(245,158,11,0.12); color: #fbbf24;">
            <i class="fas fa-tags"></i>
        </div>
        <?php $total_cat = $conn->query("SELECT COUNT(*) FROM categories")->fetchColumn(); ?>
        <div class="stat-value"><?= $total_cat ?></div>
        <div class="stat-label">Danh mục</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(6,182,212,0.12); color: #22d3ee;">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-value">Live</div>
        <div class="stat-label">Trạng thái</div>
    </div>
</div>

<!-- Quick Access -->
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Truy cập nhanh</span>
    </div>
    <div class="panel-body" style="display:flex; gap:12px; flex-wrap:wrap;">
        <a href="index.php?act=addproduct" class="btn-primary-custom">
            <i class="fas fa-plus"></i> Thêm sản phẩm
        </a>
        <a href="index.php?act=products" class="btn-secondary-custom">
            <i class="fas fa-box"></i> Xem sản phẩm
        </a>
        <a href="index.php?act=users" class="btn-secondary-custom">
            <i class="fas fa-users"></i> Xem người dùng
        </a>
        <a href="../index.php" class="btn-secondary-custom" target="_blank">
            <i class="fas fa-external-link-alt"></i> Xem trang web
        </a>
    </div>
</div>
