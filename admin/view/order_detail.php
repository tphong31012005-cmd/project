<main class="main-content">
    <div class="page-header">
        <div>
            <h1 class="page-title">Chi tiết đơn hàng <span class="id-chip">#<?= htmlspecialchars($order['bill_code']) ?></span></h1>
            <p class="page-subtitle">Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
        </div>
        <div>
            <a href="index.php?act=orders" class="btn-secondary-custom">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row-2">
        <!-- Thông tin và Cập nhật trạng thái -->
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Cập nhật & Thông tin</h2>
            </div>
            <div class="panel-body">
                <!-- Thông báo kết quả -->
                <?php if(isset($_GET['error']) && $_GET['error'] == 'invalid_status'): ?>
                <div style="margin-bottom:16px; padding:14px 18px; background:rgba(239,68,68,0.12); border:1px solid var(--danger); border-radius:var(--radius-md); color:var(--danger); display:flex; align-items:center; gap:10px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Không thể cập nhật. Trạng thái đơn hàng chỉ được tiến lên, không được quay lại bước trước.</span>
                </div>
                <?php elseif(isset($_GET['success']) && $_GET['success'] == 'status_updated'): ?>
                <div style="margin-bottom:16px; padding:14px 18px; background:rgba(34,197,94,0.12); border:1px solid var(--success); border-radius:var(--radius-md); color:var(--success); display:flex; align-items:center; gap:10px;">
                    <i class="fas fa-check-circle"></i>
                    <span>Cập nhật trạng thái đơn hàng thành công!</span>
                </div>
                <?php endif; ?>

                <!-- Form cập nhật trạng thái -->
                <?php
                $status_labels = [
                    0 => 'Chờ xác nhận',
                    1 => 'Đã xác nhận',
                    2 => 'Đang giao hàng',
                    3 => 'Hoàn thành',
                    4 => 'Đã hủy',
                ];
                $current = intval($order['status']);
                // Xác định các trạng thái được phép chuyển tới
                $allowed_next = [];
                if ($current < 3 && $current != 4) {
                    // Có thể tiến 1 bước
                    $allowed_next[] = $current + 1;
                    // Có thể hủy nếu chưa hoàn thành
                    if ($current + 1 != 4) {
                        $allowed_next[] = 4;
                    }
                }
                ?>
                <?php if (!empty($allowed_next)): ?>
                <form action="index.php?act=update_order" method="POST" style="margin-bottom: 30px; padding: 20px; background: var(--bg-surface-2); border-radius: var(--radius-md); border: 1px solid var(--border);">
                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                    <label class="form-label-custom">Cập nhật trạng thái</label>
                    <div style="margin-bottom: 10px; font-size: 13px; color: var(--text-secondary);">
                        Hiện tại: <strong style="color: var(--text-primary);"><?= $status_labels[$current] ?></strong>
                        &rarr; Chọn trạng thái tiếp theo:
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <select name="status" class="form-control-custom" style="flex: 1;">
                            <?php foreach($allowed_next as $next_status): ?>
                            <option value="<?= $next_status ?>">
                                <?php if($next_status == 4): ?>
                                    ⛔ <?= $status_labels[$next_status] ?>
                                <?php else: ?>
                                    → <?= $status_labels[$next_status] ?>
                                <?php endif; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="update_status" class="btn-primary-custom">
                            Cập nhật
                        </button>
                    </div>
                </form>
                <?php else: ?>
                <div style="margin-bottom: 30px; padding: 20px; background: var(--bg-surface-2); border-radius: var(--radius-md); border: 1px solid var(--border); text-align: center;">
                    <?php if($current == 3): ?>
                        <i class="fas fa-check-circle" style="color: var(--success); font-size: 24px; margin-bottom: 8px; display:block;"></i>
                        <span style="color: var(--success); font-weight: 600;">Đơn hàng đã hoàn thành</span>
                    <?php elseif($current == 4): ?>
                        <i class="fas fa-times-circle" style="color: var(--danger); font-size: 24px; margin-bottom: 8px; display:block;"></i>
                        <span style="color: var(--danger); font-weight: 600;">Đơn hàng đã bị hủy</span>
                    <?php endif; ?>
                    <p style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">Không có thao tác nào khác có thể thực hiện.</p>
                </div>
                <?php endif; ?>

                <!-- Thông tin khách hàng -->
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">Thông tin khách hàng</h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-secondary);">Họ tên:</span>
                            <span style="font-weight: 600; color: var(--text-primary);"><?= htmlspecialchars($order['fullname']) ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-secondary);">Số điện thoại:</span>
                            <span style="color: var(--text-primary);"><?= htmlspecialchars($order['tel']) ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-secondary);">Email:</span>
                            <span style="color: var(--text-primary);"><?= htmlspecialchars($order['email']) ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-secondary);">Tài khoản đặt:</span>
                            <span style="color: var(--accent-light);"><?= htmlspecialchars($order['username']) ?></span>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Địa chỉ giao hàng -->
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">Giao hàng</h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <div>
                            <span style="display: block; color: var(--text-secondary); margin-bottom: 4px;">Địa chỉ:</span>
                            <span style="color: var(--text-primary); line-height: 1.4; display: block;"><?= htmlspecialchars($order['address']) ?></span>
                        </div>
                        <?php if(!empty($order['note'])): ?>
                        <div>
                            <span style="display: block; color: var(--text-secondary); margin-bottom: 4px;">Ghi chú:</span>
                            <span style="color: var(--warning); line-height: 1.4; display: block; font-style: italic;"><?= htmlspecialchars($order['note']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Phương thức thanh toán -->
                <div>
                    <h3 style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">Thanh toán</h3>
                    <div>
                        <span style="color: var(--text-primary); display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <i class="fas fa-money-bill-wave" style="color: var(--success);"></i> 
                            <?= $order['payment_method'] == 0 ? 'Thanh toán khi nhận hàng (COD)' : 'Thanh toán trực tuyến' ?>
                        </span>
                        <?php if (!empty($order['coupon_code'])): ?>
                            <div style="display: flex; align-items: center; gap: 8px; color: var(--danger); font-size: 13px; margin-top: 8px;">
                                <i class="fas fa-ticket-alt"></i>
                                <span>Đã áp dụng mã: <span class="id-chip"><?= htmlspecialchars($order['coupon_code']) ?></span> (Giảm 10%)</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Sản phẩm đã đặt</h2>
            </div>
            <div class="panel-body" style="padding: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th style="text-align: center;">SL</th>
                            <th style="text-align: right;">Đơn giá</th>
                            <th style="text-align: right;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($order_details as $item): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="../<?= htmlspecialchars($item['img']) ?>" alt="Product" class="product-thumb">
                                    <div class="text-truncate-custom" style="font-weight: 500; color: var(--text-primary);" title="<?= htmlspecialchars($item['name']) ?>">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center; font-weight: 600; color: var(--text-secondary);">x<?= $item['quantity'] ?></td>
                            <td style="text-align: right; color: var(--text-muted);"><?= number_format($item['price'], 0, ',', '.') ?> đ</td>
                            <td style="text-align: right; font-weight: 600; color: var(--text-primary);"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?> đ</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div style="padding: 20px; background: var(--bg-surface-2); display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border);">
                    <span style="font-size: 16px; font-weight: 600; color: var(--text-secondary);">Tổng cộng:</span>
                    <span style="font-size: 24px; font-weight: 800; color: var(--success);"><?= number_format($order['total_price'], 0, ',', '.') ?> đ</span>
                </div>
            </div>
        </div>
    </div>
</main>
