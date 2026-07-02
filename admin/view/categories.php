<div class="panel">
    <div class="panel-header">
        <h2 class="panel-title">Quản lý Danh mục</h2>
        <a href="index.php?act=addcategory" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm danh mục mới</a>
    </div>
    <div class="panel-body">
        <?php if(empty($cat_list)): ?>
            <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info">
                <i class="fas fa-info-circle me-2"></i> Chưa có danh mục nào. Hãy thêm danh mục mới.
            </div>
        <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="70%">Tên danh mục</th>
                        <th width="20%" style="text-align: right;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cat_list as $cat): ?>
                    <tr>
                        <td><strong>#<?= $cat['id'] ?></strong></td>
                        <td><span class="fw-semibold text-primary"><?= htmlspecialchars($cat['name']) ?></span></td>
                        <td style="text-align: right;">
                            <a href="index.php?act=editcategory&id=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-primary" style="margin-right: 5px;" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="index.php?act=delcategory&id=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include "footer.php"; ?>
