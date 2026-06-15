<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title">Thêm sản phẩm mới</div>
        <div class="page-subtitle">Điền thông tin để thêm sản phẩm vào cửa hàng</div>
    </div>
    <a href="index.php?act=products" class="btn-secondary-custom">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div style="max-width: 700px;">
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="fas fa-plus" style="color:var(--accent-light); margin-right:8px;"></i>Thông tin sản phẩm</span>
        </div>
        <div class="panel-body">
            <form action="index.php?act=addproduct" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label class="form-label-custom">Tên sản phẩm <span style="color:var(--danger);">*</span></label>
                    <input type="text" class="form-control-custom" name="name" placeholder="Nhập tên sản phẩm..." required>
                </div>

                <div class="row-2">
                    <div class="form-group">
                        <label class="form-label-custom">Giá bán ($) <span style="color:var(--danger);">*</span></label>
                        <input type="number" step="0.01" class="form-control-custom" name="price" placeholder="0.00" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Giá gốc ($)</label>
                        <input type="number" step="0.01" class="form-control-custom" name="old_price" placeholder="0.00">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label-custom">Danh mục <span style="color:var(--danger);">*</span></label>
                    <select class="form-control-custom" name="category_id" required>
                        <option value="" disabled selected>Chọn danh mục...</option>
                        <?php foreach($category_list as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label-custom">Hình ảnh sản phẩm <span style="color:var(--danger);">*</span></label>
                    <div class="file-upload-area" id="uploadArea">
                        <input type="file" name="img" accept="image/*" required onchange="previewFile(this)">
                        <span class="icon"><i class="fas fa-cloud-upload-alt"></i></span>
                        <p>Kéo thả hoặc <strong>nhấp để chọn ảnh</strong></p>
                        <p style="font-size:11px; margin-top:4px;">PNG, JPG, WEBP – Tối đa 5MB</p>
                    </div>
                    <img id="filePreview" class="img-preview" style="display:none; margin-top:12px;" alt="Preview">
                </div>

                <div class="divider"></div>

                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <a href="index.php?act=products" class="btn-secondary-custom">Hủy</a>
                    <button type="submit" name="addproduct" class="btn-primary-custom">
                        <i class="fas fa-check"></i> Lưu sản phẩm
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
