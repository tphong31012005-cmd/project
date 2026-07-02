<div class="page-header">
    <div>
        <h1 class="page-title">Quản lý phiếu giảm giá</h1>
        <div class="page-subtitle">Xem danh sách phiếu giảm giá của người dùng và các đơn hàng đã áp dụng</div>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <span class="panel-title"><i class="fas fa-ticket-alt me-2"></i>Danh sách phiếu giảm giá (10%)</span>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã giảm giá</th>
                        <th>Người sở hữu</th>
                        <th>Mức giảm</th>
                        <th>Trạng thái</th>
                        <th>Đơn hàng áp dụng</th>
                        <th>Tổng tiền đơn</th>
                        <th>Thời gian sử dụng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($coupon_list) > 0): ?>
                        <?php foreach ($coupon_list as $c): ?>
                            <tr>
                                <td><span class="id-chip"><?= htmlspecialchars($c['code']) ?></span></td>
                                <td><strong><?= htmlspecialchars($c['username']) ?></strong></td>
                                <td><span class="badge bg-primary px-3 rounded-pill"><?= $c['discount_percent'] ?>%</span></td>
                                <td>
                                    <?php if ($c['status'] == 1): ?>
                                        <span class="badge-custom badge-admin"><i class="fas fa-check-circle me-1"></i>Đã sử dụng</span>
                                    <?php else: ?>
                                        <span class="badge-custom badge-cat text-success" style="background: rgba(34,197,94,0.12); color: #22c55e;"><i class="fas fa-clock me-1"></i>Chưa sử dụng</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($c['bill_code']): ?>
                                        <a href="index.php?act=order_detail&id=<?= $c['bill_id'] ?>" class="text-accent fw-bold text-decoration-none">
                                            <i class="fas fa-receipt me-1"></i><?= htmlspecialchars($c['bill_code']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($c['total_price']): ?>
                                        <span class="fw-bold text-danger"><?= number_format($c['total_price'], 0, ',', '.') ?> đ</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($c['used_at']): ?>
                                        <span class="small text-muted"><?= date('H:i d/m/Y', strtotime($c['used_at'])) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Chưa có phiếu giảm giá nào được tạo.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
