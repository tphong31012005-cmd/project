<?php
// Get stats for welcome banner
$conn = connectdb();
$total_p = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_u = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_revenue = $conn->query("SELECT SUM(total_price) FROM bill WHERE status != 4")->fetchColumn() ?: 0;
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
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-value text-nowrap" style="font-size: 22px; font-weight: 800;"><?= number_format($total_revenue, 0, ',', '.') ?> đ</div>
        <div class="stat-label">Tổng doanh thu</div>
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

<!-- Thống kê doanh thu -->
<div class="panel mt-4">
    <div class="panel-header">
        <span class="panel-title"><i class="fas fa-chart-pie me-2"></i> Thống kê Doanh thu & Lượt bán Sản phẩm</span>
    </div>
    <div class="panel-body">
        <div class="row">
            <!-- Top Cao Nhất -->
            <div class="col-md-6 mb-4 mb-md-0">
                <h6 class="text-success fw-bold mb-3"><i class="fas fa-arrow-up me-2"></i>Top 5 Sản phẩm Bán chạy & Doanh thu cao</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Đã bán</th>
                                <th class="text-end">Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_top = "SELECT p.name, SUM(bd.quantity) as total_sold, SUM(bd.quantity * bd.price) as total_revenue
                                        FROM products p
                                        JOIN bill_details bd ON p.id = bd.product_id
                                        JOIN bill b ON bd.bill_id = b.id
                                        WHERE b.status != 4
                                        GROUP BY p.id
                                        ORDER BY total_revenue DESC, total_sold DESC
                                        LIMIT 5";
                            $top_products = $conn->query($sql_top)->fetchAll(PDO::FETCH_ASSOC);
                            
                            if (count($top_products) > 0) {
                                foreach ($top_products as $item) {
                                    echo '<tr>';
                                    echo '<td><span class="text-truncate d-inline-block" style="max-width: 220px;" title="'.htmlspecialchars($item['name']).'">'.htmlspecialchars($item['name']).'</span></td>';
                                    echo '<td class="text-center"><span class="badge bg-success rounded-pill px-3">'.$item['total_sold'].'</span></td>';
                                    echo '<td class="text-end fw-bold text-danger">'.number_format($item['total_revenue'], 0, ',', '.').' đ</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3" class="text-center text-muted py-3">Chưa có dữ liệu đơn hàng hợp lệ</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Top Thấp Nhất -->
            <div class="col-md-6">
                <h6 class="text-danger fw-bold mb-3"><i class="fas fa-arrow-down me-2"></i>Top 5 Sản phẩm Bán chậm & Doanh thu thấp</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Đã bán</th>
                                <th class="text-end">Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_bottom = "SELECT p.name, COALESCE(SUM(bd.quantity), 0) as total_sold, COALESCE(SUM(bd.quantity * bd.price), 0) as total_revenue
                                        FROM products p
                                        LEFT JOIN (
                                            SELECT bd.product_id, bd.quantity, bd.price 
                                            FROM bill_details bd 
                                            JOIN bill b ON bd.bill_id = b.id 
                                            WHERE b.status != 4
                                        ) bd ON p.id = bd.product_id
                                        GROUP BY p.id
                                        ORDER BY total_revenue ASC, total_sold ASC
                                        LIMIT 5";
                            $bottom_products = $conn->query($sql_bottom)->fetchAll(PDO::FETCH_ASSOC);
                            
                            if (count($bottom_products) > 0) {
                                foreach ($bottom_products as $item) {
                                    echo '<tr>';
                                    echo '<td><span class="text-truncate d-inline-block" style="max-width: 220px;" title="'.htmlspecialchars($item['name']).'">'.htmlspecialchars($item['name']).'</span></td>';
                                    echo '<td class="text-center"><span class="badge bg-secondary rounded-pill px-3">'.$item['total_sold'].'</span></td>';
                                    echo '<td class="text-end fw-bold text-danger">'.number_format($item['total_revenue'], 0, ',', '.').' đ</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3" class="text-center text-muted py-3">Chưa có dữ liệu</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
