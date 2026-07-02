<div class="panel" style="max-width: 600px; margin: 0 auto;">
    <div class="panel-header">
        <h2 class="panel-title">Thêm Danh Mục Mới</h2>
        <a href="index.php?act=categories" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
    <div class="panel-body">
        <form action="index.php?act=addcategory" method="POST">
            <div class="mb-4">
                <label class="form-label text-muted small fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control bg-dark text-white border-secondary" placeholder="Nhập tên danh mục..." required autofocus>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-secondary">Nhập lại</button>
                <button type="submit" name="addcategory" class="btn btn-primary"><i class="fas fa-save me-2"></i>Thêm danh mục</button>
            </div>
        </form>
    </div>
</div>

<?php include "footer.php"; ?>
