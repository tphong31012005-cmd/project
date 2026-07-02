<div class="panel">
    <div class="panel-header">
        <h2 class="panel-title">Quản lý Đánh giá sản phẩm</h2>
    </div>
    <div class="panel-body">
        <?php if(empty($review_list)): ?>
            <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info">
                <i class="fas fa-info-circle me-2"></i> Chưa có đánh giá nào từ khách hàng.
            </div>
        <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="15%">Người dùng</th>
                        <th width="25%">Sản phẩm</th>
                        <th width="15%">Số sao</th>
                        <th width="25%">Nội dung</th>
                        <th width="15%">Ngày gửi</th>
                        <th width="5%" style="text-align: right;">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($review_list as $r): ?>
                    <tr>
                        <td><strong>#<?= $r['id'] ?></strong></td>
                        <td><span class="fw-semibold text-primary"><?= htmlspecialchars($r['username']) ?></span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="../<?= $r['product_img'] ?>" alt="" class="product-thumb" style="width:40px; height:40px; object-fit:cover; border-radius: 6px;">
                                <span class="text-truncate-custom" title="<?= htmlspecialchars($r['product_name']) ?>"><?= htmlspecialchars($r['product_name']) ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="text-warning">
                                <?= str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']) ?>
                            </span>
                            <span class="ms-1 fw-bold">(<?= $r['rating'] ?>/5)</span>
                        </td>
                        <td>
                            <span title="<?= htmlspecialchars($r['content']) ?>" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-size:13px;line-height:1.4;">
                                <?= htmlspecialchars($r['content']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="text-secondary small">
                                <?= date('d/m/Y H:i', strtotime($r['created_at'])) ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="index.php?act=delreview&id=<?= $r['id'] ?>" class="btn-danger-custom btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này? Hành động này không thể hoàn tác!');" title="Xóa">
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
