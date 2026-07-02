<section class="account-section py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="avatar-wrapper mb-3">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['fullname'] ?: $_SESSION['user']['username']) ?>&background=random&size=128" alt="Avatar" class="rounded-circle shadow-sm border border-3 border-white">
                        </div>
                        <h5 class="fw-bold mb-1"><?= $_SESSION['user']['fullname'] ?: $_SESSION['user']['username'] ?></h5>
                        <p class="text-muted small mb-4"><?= $_SESSION['user']['email'] ?></p>
                        <hr>
                        <div class="list-group list-group-flush text-start">
                            <a href="index.php?act=account" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-user-circle me-2"></i> Thông tin tài khoản</a>
                            <a href="index.php?act=my_orders" class="list-group-item list-group-item-action border-0 px-0 active"><i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi</a>
                            <a href="index.php?act=wishlist" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-heart me-2"></i> Danh sách yêu thích</a>
                            <a href="index.php?act=logout" class="list-group-item list-group-item-action border-0 px-0 text-danger"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Chi tiết đơn hàng <span class="text-primary">#<?= htmlspecialchars($order['bill_code']) ?></span></h5>
                            <a href="index.php?act=my_orders" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
                        </div>
                        
                        <!-- Order Tracking Stepper -->
                        <div class="order-tracking-wrapper mb-5 mt-4 px-2">
                            <div class="stepper-wrapper">
                                <?php if($order['status'] == 4): ?>
                                    <div class="stepper-item completed">
                                        <div class="step-counter"><i class="fas fa-file-invoice"></i></div>
                                        <div class="step-name small mt-2 fw-semibold text-center">Đã đặt hàng</div>
                                    </div>
                                    <div class="stepper-item cancelled">
                                        <div class="step-counter"><i class="fas fa-times"></i></div>
                                        <div class="step-name small mt-2 fw-bold text-center">Đã hủy</div>
                                    </div>
                                <?php else: ?>
                                    <div class="stepper-item <?= $order['status'] >= 0 ? 'completed' : '' ?>">
                                        <div class="step-counter"><i class="fas fa-file-invoice"></i></div>
                                        <div class="step-name small mt-2 fw-semibold text-center">Đã đặt hàng</div>
                                    </div>
                                    <div class="stepper-item <?= $order['status'] >= 1 ? 'completed' : '' ?>">
                                        <div class="step-counter"><i class="fas fa-user-check"></i></div>
                                        <div class="step-name small mt-2 fw-semibold text-center">Đã xác nhận</div>
                                    </div>
                                    <div class="stepper-item <?= $order['status'] >= 2 ? 'completed' : '' ?>">
                                        <div class="step-counter"><i class="fas fa-shipping-fast"></i></div>
                                        <div class="step-name small mt-2 fw-semibold text-center">Đang giao</div>
                                    </div>
                                    <div class="stepper-item <?= $order['status'] >= 3 ? 'completed' : '' ?>">
                                        <div class="step-counter"><i class="fas fa-box-open"></i></div>
                                        <div class="step-name small mt-2 fw-semibold text-center">Hoàn thành</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Order Status Alert -->
                        <div class="alert 
                            <?php 
                                if($order['status'] == 0) echo 'alert-warning';
                                elseif($order['status'] == 1 || $order['status'] == 2) echo 'alert-info';
                                elseif($order['status'] == 3) echo 'alert-success';
                                elseif($order['status'] == 4) echo 'alert-danger';
                            ?> border-0 rounded-3 d-flex align-items-center justify-content-between">
                            
                            <div>
                                <h6 class="mb-1 fw-bold">
                                    <?php 
                                        switch ($order['status']) {
                                            case 0: echo '<i class="fas fa-clock me-2"></i> Chờ xác nhận'; break;
                                            case 1: echo '<i class="fas fa-check-circle me-2"></i> Đã xác nhận'; break;
                                            case 2: echo '<i class="fas fa-truck me-2"></i> Đang giao hàng'; break;
                                            case 3: echo '<i class="fas fa-box-open me-2"></i> Hoàn thành'; break;
                                            case 4: echo '<i class="fas fa-times-circle me-2"></i> Đã hủy'; break;
                                        }
                                    ?>
                                </h6>
                                <p class="mb-0 small opacity-75">Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                            </div>

                            <!-- Nút hủy đơn hàng -->
                            <?php if($order['status'] == 0): ?>
                                <a href="index.php?act=cancel_order&id=<?= $order['id'] ?>" class="btn btn-sm btn-danger fw-bold shadow-sm" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                                    Hủy đơn hàng
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Customer Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 text-muted text-uppercase small" style="letter-spacing: 1px;">Thông tin nhận hàng</h6>
                                <p class="mb-1 fw-semibold"><?= htmlspecialchars($order['fullname']) ?></p>
                                <p class="mb-1 text-muted small"><i class="fas fa-phone-alt me-2"></i><?= htmlspecialchars($order['tel']) ?></p>
                                <p class="mb-1 text-muted small"><i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($order['address']) ?></p>
                                <?php if(!empty($order['note'])): ?>
                                    <p class="mb-0 text-warning small mt-2"><i class="fas fa-sticky-note me-2"></i>Ghi chú: <?= htmlspecialchars($order['note']) ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 text-muted text-uppercase small" style="letter-spacing: 1px;">Thanh toán</h6>
                                <p class="mb-1 text-muted small">
                                    <i class="fas fa-money-bill-wave me-2 text-success"></i> 
                                    <?= $order['payment_method'] == 0 ? 'Thanh toán khi nhận hàng (COD)' : 'Thanh toán trực tuyến' ?>
                                </p>
                            </div>
                        </div>

                        <!-- Products List -->
                        <h6 class="fw-bold mb-3 text-muted text-uppercase small" style="letter-spacing: 1px;">Sản phẩm đã mua</h6>
                        <div class="table-responsive border rounded-3 mb-4">
                            <table class="table table-borderless align-middle mb-0">
                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th colspan="2">Sản phẩm</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-end">Đơn giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order_details as $item): ?>
                                    <tr class="border-bottom">
                                        <td style="width: 70px;">
                                            <img src="<?= htmlspecialchars($item['img']) ?>" alt="Product" class="img-fluid rounded border" style="width: 60px; height: 60px; object-fit: cover;">
                                        </td>
                                        <td>
                                            <p class="mb-0 fw-semibold text-dark"><?= htmlspecialchars($item['name']) ?></p>
                                        </td>
                                        <td class="text-center text-muted">x<?= $item['quantity'] ?></td>
                                        <td class="text-end fw-semibold text-danger"><?= number_format($item['price'], 0, ',', '.') ?> đ</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                         <!-- Total Summary -->
                         <?php 
                         $subtotal = 0;
                         foreach($order_details as $item) {
                             $subtotal += $item['price'] * $item['quantity'];
                         }
                         $has_discount = !empty($order['coupon_code']);
                         ?>
                         <div class="card border p-3 rounded-3 bg-light">
                             <div class="d-flex justify-content-between mb-2">
                                 <span class="text-muted">Tạm tính:</span>
                                 <span class="fw-semibold text-dark"><?= number_format($subtotal, 0, ',', '.') ?> đ</span>
                             </div>
                             <?php if ($has_discount): ?>
                             <div class="d-flex justify-content-between mb-2 text-danger">
                                 <span>Giảm giá (10% - <?= htmlspecialchars($order['coupon_code']) ?>):</span>
                                 <span class="fw-semibold">-<?= number_format(round($subtotal * 0.1), 0, ',', '.') ?> đ</span>
                             </div>
                             <?php endif; ?>
                             <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                 <span class="fw-bold text-muted">Tổng thanh toán:</span>
                                 <span class="fw-bold fs-4 text-danger"><?= number_format($order['total_price'], 0, ',', '.') ?> đ</span>
                             </div>
                         </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.stepper-wrapper {
  display: flex;
  justify-content: space-between;
  position: relative;
}
.stepper-item {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  flex: 1;
  z-index: 1;
}
.stepper-item::before {
  position: absolute;
  content: "";
  border-bottom: 3px solid #e9ecef;
  width: 100%;
  top: 22px;
  right: 50%;
  z-index: -1;
}
.stepper-item:first-child::before {
  content: none;
}
.stepper-item.completed::before {
  border-bottom-color: #0d6efd;
}
.stepper-item.cancelled::before {
  border-bottom-color: #dc3545;
}

.stepper-item .step-counter {
  position: relative;
  z-index: 5;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 46px;
  height: 46px;
  border-radius: 50%;
  background: #e9ecef;
  color: #adb5bd;
  font-size: 1.2rem;
  transition: all 0.3s ease;
  box-shadow: 0 0 0 5px #fff;
}
.stepper-item.completed .step-counter {
  background-color: #0d6efd;
  color: white;
}
.stepper-item.completed .step-name {
  color: #0d6efd;
}
.stepper-item.cancelled .step-counter {
  background-color: #dc3545;
  color: white;
}
.stepper-item.cancelled .step-name {
  color: #dc3545;
}
.stepper-item .step-name {
  color: #adb5bd;
}
</style>
