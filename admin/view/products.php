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

<?php if(isset($_GET['error']) && $_GET['error'] == 'cannot_delete_product'): ?>
<div style="margin-bottom:16px; padding:14px 18px; background:rgba(239,68,68,0.12); border:1px solid var(--danger); border-radius:var(--radius-md); color:var(--danger); display:flex; align-items:center; gap:10px;">
    <i class="fas fa-ban"></i>
    <span>Không thể xóa sản phẩm. Chức năng này đã bị vô hiệu hóa để bảo vệ dữ liệu.</span>
</div>
<?php endif; ?>

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
                    <th>Số lượng</th>
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
                        <span style="font-weight:700; color:var(--accent-light);"><?= number_format($p['price'], 0, ',', '.') ?> đ</span>
                    </td>
                    <td>
                        <span style="font-weight:600; color:var(--text-secondary);"><?= $p['quantity'] ?></span>
                    </td>
                    <td><span class="badge-custom badge-cat"><?= htmlspecialchars($p['cat_name']) ?></span></td>
                    <td style="text-align:right;">
                        <div style="display:flex; gap:8px; justify-content:flex-end;">
                            <a href="index.php?act=editproduct&id=<?= $p['id'] ?>" class="btn-edit-custom">
                                <i class="fas fa-pen"></i> Sửa
                            </a>
                            <span style="font-size:11px; color:var(--text-muted); display:flex; align-items:center; gap:4px;" title="Chức năng xóa đã bị vô hiệu hóa">
                                <i class="fas fa-lock"></i> Không thể xóa
                            </span>
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
