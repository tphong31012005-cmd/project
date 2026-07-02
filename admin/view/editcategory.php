<div class="panel" style="max-width: 600px; margin: 0 auto;">
    <div class="panel-header">
        <h2 class="panel-title">Cập Nhật Danh Mục</h2>
        <a href="index.php?act=categories" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
    <div class="panel-body">
        <?php if(isset($cat) && $cat): ?>
        <form action="index.php?act=editcategory&id=<?= $cat['id'] ?>" method="POST">
            <div class="mb-4">
                <label class="form-label text-muted small fw-bold">ID Danh mục</label>
                <input type="text" class="form-control bg-dark text-muted border-secondary" value="#<?= $cat['id'] ?>" disabled>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted small fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control bg-dark text-white border-secondary" value="<?= htmlspecialchars($cat['name']) ?>" required autofocus>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" name="editcategory" class="btn btn-primary"><i class="fas fa-save me-2"></i>Lưu thay đổi</button>
            </div>
        </form>
        <?php else: ?>
            <div class="alert alert-danger">Không tìm thấy danh mục!</div>
        <?php endif; ?>
    </div>
</div>

<?php include "footer.php"; ?>
