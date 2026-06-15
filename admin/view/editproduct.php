<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title">Chỉnh sửa sản phẩm</div>
        <div class="page-subtitle"><?= htmlspecialchars($product['name']) ?></div>
    </div>
    <a href="index.php?act=products" class="btn-secondary-custom">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div style="max-width: 700px;">
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="fas fa-pen" style="color:var(--info); margin-right:8px;"></i>Thông tin sản phẩm</span>
        </div>
        <div class="panel-body">
            <form action="index.php?act=editproduct" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <input type="hidden" name="old_img" value="<?= htmlspecialchars($product['img']) ?>">

                <div class="form-group">
                    <label class="form-label-custom">Tên sản phẩm <span style="color:var(--danger);">*</span></label>
                    <input type="text" class="form-control-custom" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                </div>

                <div class="row-2">
                    <div class="form-group">
                        <label class="form-label-custom">Giá bán ($) <span style="color:var(--danger);">*</span></label>
                        <input type="number" step="0.01" class="form-control-custom" name="price" value="<?= $product['price'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Giá gốc ($)</label>
                        <input type="number" step="0.01" class="form-control-custom" name="old_price" value="<?= $product['old_price'] ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label-custom">Danh mục <span style="color:var(--danger);">*</span></label>
                    <select class="form-control-custom" name="category_id" required>
                        <?php foreach($category_list as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Current image -->
                <div class="form-group">
                    <label class="form-label-custom">Hình ảnh hiện tại</label>
                    <div style="display:flex; align-items:center; gap:14px; padding:12px; background:var(--bg-base); border-radius:var(--radius-md); border:1px solid var(--border);">
                        <img src="../<?= htmlspecialchars($product['img']) ?>" alt="" class="img-preview"
                             onerror="this.style.opacity='0.3';">
                        <div>
                            <div style="font-size:12px; color:var(--text-secondary); font-weight:500; margin-bottom:2px;">
                                <?= basename($product['img']) ?>
                            </div>
                            <div style="font-size:11px; color:var(--text-muted);">Tải ảnh mới bên dưới để thay thế</div>
                        </div>
                    </div>
                </div>

                <!-- New image upload -->
                <div class="form-group">
                    <label class="form-label-custom">Thay đổi hình ảnh <span style="color:var(--text-muted); font-weight:400;">(tùy chọn)</span></label>
                    <div class="file-upload-area">
                        <input type="file" name="img" accept="image/*" onchange="previewFile(this)">
                        <span class="icon"><i class="fas fa-cloud-upload-alt"></i></span>
                        <p>Kéo thả hoặc <strong>nhấp để chọn ảnh mới</strong></p>
                        <p style="font-size:11px; margin-top:4px;">PNG, JPG, WEBP – Tối đa 5MB</p>
                    </div>
                    <img id="filePreview" class="img-preview" style="display:none; margin-top:12px;" alt="Preview">
                </div>

                <div class="divider"></div>

                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <a href="index.php?act=products" class="btn-secondary-custom">Hủy</a>
                    <button type="submit" name="editproduct" class="btn-primary-custom">
                        <i class="fas fa-check"></i> Cập nhật sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewFile(input) {
    const preview = document.getElementById('filePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
