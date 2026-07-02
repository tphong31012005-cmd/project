<main class="main-content">
    <div class="page-header">
        <div>
            <h1 class="page-title">Quản lý đơn hàng</h1>
            <p class="page-subtitle">Theo dõi và cập nhật trạng thái các đơn đặt hàng</p>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title">Danh sách đơn hàng</h2>
        </div>
        <div class="panel-body" style="padding: 0; overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th style="text-align: right;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($bill_list) && count($bill_list) > 0): ?>
                        <?php foreach ($bill_list as $b): ?>
                            <tr>
                                <td>
                                    <span class="id-chip"><?= htmlspecialchars($b['bill_code']) ?></span>
                                    <?php if (!empty($b['coupon_code'])): ?>
                                        <div style="font-size: 11px; margin-top: 4px;" class="text-danger">
                                            <i class="fas fa-ticket-alt"></i> <?= htmlspecialchars($b['coupon_code']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--text-primary);"><?= htmlspecialchars($b['fullname']) ?></div>
                                    <div style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($b['tel']) ?></div>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($b['created_at'])) ?></td>
                                <td style="font-weight: 600; color: var(--success);"><?= number_format($b['total_price'], 0, ',', '.') ?> đ</td>
                                <td>
                                    <?php 
                                        $status_text = '';
                                        $badge_class = '';
                                        switch ($b['status']) {
                                            case 0: $status_text = 'Chờ xác nhận'; $badge_class = 'badge-custom badge-user'; break;
                                            case 1: $status_text = 'Đã xác nhận'; $badge_class = 'badge-custom badge-cat'; break;
                                            case 2: $status_text = 'Đang giao hàng'; $badge_class = 'badge-custom badge-cat'; break;
                                            case 3: $status_text = 'Hoàn thành'; $badge_class = 'badge-custom" style="background: var(--success-bg); color: var(--success); border: 1px solid rgba(34,197,94,0.15);'; break;
                                            case 4: $status_text = 'Đã hủy'; $badge_class = 'badge-custom badge-admin'; break;
                                            default: $status_text = 'Không xác định'; $badge_class = 'badge-custom badge-user'; break;
                                        }
                                    ?>
                                    <span class="<?= $badge_class ?>"><?= $status_text ?></span>
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <a href="index.php?act=order_detail&id=<?= $b['id'] ?>" class="btn-edit-custom">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </a>
                                    <a href="index.php?act=delorder&id=<?= $b['id'] ?>" class="btn-danger-custom" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này? Thao tác này không thể hoàn tác.');">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                                <i class="fas fa-box-open" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                                Chưa có đơn hàng nào trong hệ thống.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
