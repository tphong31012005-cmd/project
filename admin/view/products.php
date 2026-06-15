<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title">Quản lý Sản phẩm</div>
        <div class="page-subtitle"><?= count($product_list) ?> sản phẩm trong cửa hàng</div>
    </div>
    <a href="index.php?act=addproduct" class="btn-primary-custom">
        <i class="fas fa-plus"></i> Thêm sản phẩm mới
    </a>
</div>

<!-- Products Table -->
<div class="panel">
    <div class="panel-header">
        <span class="panel-title"><i class="fas fa-box" style="color:var(--accent-light); margin-right:8px;"></i>Danh sách sản phẩm</span>
    </div>
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Danh mục</th>
                    <th style="text-align:right;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($product_list as $p): ?>
                <tr>
                    <td><span class="id-chip">#<?= $p['id'] ?></span></td>
                    <td>
                        <img src="../<?= htmlspecialchars($p['img']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-thumb"
                             onerror="this.style.background='var(--bg-surface-3)'; this.src='';">
                    </td>
                    <td>
                        <span class="text-truncate-custom" style="display:block; font-weight:600; color:var(--text-primary);">
                            <?= htmlspecialchars($p['name']) ?>
                        </span>
                    </td>
                    <td>
                        <span style="font-weight:700; color:var(--accent-light);">$<?= number_format($p['price'], 2) ?></span>
                    </td>
                    <td><span class="badge-custom badge-cat"><?= htmlspecialchars($p['cat_name']) ?></span></td>
                    <td style="text-align:right;">
                        <div style="display:flex; gap:8px; justify-content:flex-end;">
                            <a href="index.php?act=editproduct&id=<?= $p['id'] ?>" class="btn-edit-custom">
                                <i class="fas fa-pen"></i> Sửa
                            </a>
                            <a href="index.php?act=delproduct&id=<?= $p['id'] ?>" class="btn-danger-custom"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if(empty($product_list)): ?>
        <div style="text-align:center; padding:48px 20px; color:var(--text-muted);">
            <i class="fas fa-box-open" style="font-size:36px; margin-bottom:12px; display:block;"></i>
            Chưa có sản phẩm nào. <a href="index.php?act=addproduct" style="color:var(--accent-light);">Thêm sản phẩm đầu tiên</a>
        </div>
        <?php endif; ?>
    </div>
</div>
