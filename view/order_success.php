<?php
if(!isset($_GET['code'])) {
    header('location: index.php');
    exit;
}

$bill_code = $_GET['code'];
$bill = get_bill_by_code($bill_code);

if(!$bill || $bill['user_id'] != $_SESSION['user']['id']) {
    header('location: index.php');
    exit;
}

$bill_details = get_bill_details($bill['id']);
?>
<section class="order-success-section py-5">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Delightful Checkmark Animation -->
                <div class="success-checkmark-wrapper mb-4">
                    <div class="success-checkmark">
                        <div class="check-icon">
                            <span class="icon-line line-tip"></span>
                            <span class="icon-line line-long"></span>
                            <div class="icon-circle"></div>
                            <div class="icon-fix"></div>
                        </div>
                    </div>
                </div>
                
                <h2 class="fw-bold mb-2 text-dark-emphasis">Đặt hàng thành công!</h2>
                <p class="text-muted mb-5">Cảm ơn bạn đã mua sắm tại WindyStore. Đơn hàng của bạn đang được xử lý.</p>
                
                <div class="card border-0 shadow-sm rounded-4 text-start p-4 mb-4 text-dark-emphasis">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary-subtle">
                        <div>
                            <span class="text-muted small text-uppercase">Mã đơn hàng</span>
                            <h5 class="fw-bold text-primary mb-0"><?= htmlspecialchars($bill['bill_code']) ?></h5>
                        </div>
                        <div class="text-end">
                            <span class="text-muted small text-uppercase">Ngày đặt hàng</span>
                            <h6 class="fw-bold mb-0"><?= date('d/m/Y H:i', strtotime($bill['created_at'])) ?></h6>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="fw-bold text-primary mb-3">Thông tin giao hàng</h6>
                            <p class="mb-1 fw-bold"><?= htmlspecialchars($bill['fullname']) ?></p>
                            <p class="mb-1 text-muted"><i class="fa-solid fa-phone me-2"></i><?= htmlspecialchars($bill['tel']) ?></p>
                            <p class="mb-1 text-muted"><i class="fa-solid fa-envelope me-2"></i><?= htmlspecialchars($bill['email']) ?></p>
                            <p class="mb-0 text-muted"><i class="fa-solid fa-location-dot me-2"></i><?= htmlspecialchars($bill['address']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">Thanh toán & Ghi chú</h6>
                            <p class="mb-2">Phương thức: <strong><?= $bill['payment_method'] == 1 ? 'Chuyển khoản ngân hàng' : 'Thanh toán khi nhận hàng (COD)' ?></strong></p>
                            <p class="mb-0 text-muted">Ghi chú: <em><?= !empty($bill['note']) ? htmlspecialchars($bill['note']) : 'Không có ghi chú' ?></em></p>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold text-primary mb-3">Sản phẩm đã mua</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0 text-dark-emphasis">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th class="ps-3 py-2 small text-uppercase text-muted">Sản phẩm</th>
                                    <th class="py-2 small text-uppercase text-muted text-center">Số lượng</th>
                                    <th class="pe-3 py-2 small text-uppercase text-muted text-end">Tổng cộng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($bill_details as $item): ?>
                                <tr>
                                    <td class="ps-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= $item['img'] ?>" alt="<?= $item['name'] ?>" class="rounded border me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div class="min-w-0">
                                                <h6 class="mb-0 text-truncate fw-bold" style="max-width: 250px;"><?= $item['name'] ?></h6>
                                                <small class="text-muted"><?= number_format($item['price'], 0, ',', '.') ?> đ</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center"><?= $item['quantity'] ?></td>
                                    <td class="pe-3 py-3 text-end fw-bold text-primary"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?> đ</td>
                                  </tr>
                                  <?php endforeach; ?>
                                  <?php 
                                  $subtotal = 0;
                                  foreach($bill_details as $item) {
                                      $subtotal += $item['price'] * $item['quantity'];
                                  }
                                  $has_discount = !empty($bill['coupon_code']);
                                  ?>
                                  <tr class="border-top border-secondary-subtle">
                                      <td colspan="2" class="ps-3 py-2 fw-semibold text-end text-muted">Tạm tính:</td>
                                      <td class="pe-3 py-2 fw-semibold text-end text-muted"><?= number_format($subtotal, 0, ',', '.') ?> đ</td>
                                  </tr>
                                  <?php if ($has_discount): ?>
                                  <tr>
                                      <td colspan="2" class="ps-3 py-2 fw-semibold text-end text-danger">Giảm giá (10% - <?= htmlspecialchars($bill['coupon_code']) ?>):</td>
                                      <td class="pe-3 py-2 fw-semibold text-end text-danger">-<?= number_format(round($subtotal * 0.1), 0, ',', '.') ?> đ</td>
                                  </tr>
                                  <?php endif; ?>
                                  <tr>
                                      <td colspan="2" class="ps-3 py-3 fw-bold text-end">Tổng cộng:</td>
                                      <td class="pe-3 py-3 fw-bold text-end text-primary fs-5"><?= number_format($bill['total_price'], 0, ',', '.') ?> đ</td>
                                  </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="index.php?act=shop" class="btn btn-primary px-4 py-2.5 rounded-pill shadow-sm">Tiếp tục mua sắm</a>
                    <a href="index.php?act=my_order_detail&id=<?= $bill['id'] ?>" class="btn btn-outline-secondary px-4 py-2.5 rounded-pill"><i class="fas fa-search-location me-2"></i>Theo dõi đơn hàng</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Animated Checkmark Styles -->
<style>
.success-checkmark-wrapper {
    height: 120px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.success-checkmark {
    width: 80px;
    height: 80px;
}
.success-checkmark .check-icon {
    width: 80px;
    height: 80px;
    position: relative;
    border-radius: 50%;
    box-sizing: content-box;
    border: 4px solid #4CAF50;
}
.success-checkmark .check-icon::before {
    top: 3px;
    left: -2px;
    width: 30px;
    transform-origin: 100% 50%;
    border-radius: 100px 0 0 100px;
}
.success-checkmark .check-icon::after {
    top: 0;
    left: 30px;
    width: 60px;
    transform-origin: 0 50%;
    border-radius: 0 100px 100px 0;
    animation: rotate-circle 4.25s ease-in;
}
.success-checkmark .check-icon::before, .success-checkmark .check-icon::after {
    content: '';
    height: 100px;
    position: absolute;
    background: var(--bs-body-bg);
    transform: rotate(-45deg);
    z-index: 1;
}
.success-checkmark .check-icon .icon-circle {
    top: -4px;
    left: -4px;
    z-index: 1;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 4px solid rgba(76, 175, 80, 0.5);
    box-sizing: content-box;
    position: absolute;
}
.success-checkmark .check-icon .icon-fix {
    top: 8px;
    width: 5px;
    left: 26px;
    z-index: 1;
    height: 85px;
    position: absolute;
    background: var(--bs-body-bg);
    transform: rotate(-45deg);
}
.success-checkmark .check-icon .icon-line {
    height: 5px;
    background-color: #4CAF50;
    display: block;
    border-radius: 2px;
    position: absolute;
    z-index: 10;
}
.success-checkmark .check-icon .icon-line.line-tip {
    top: 46px;
    left: 19px;
    width: 25px;
    transform: rotate(45deg);
    animation: icon-line-tip 0.75s;
}
.success-checkmark .check-icon .icon-line.line-long {
    top: 38px;
    right: 8px;
    width: 47px;
    transform: rotate(-45deg);
    animation: icon-line-long 0.75s;
}

@keyframes icon-line-tip {
    0% {
        width: 0;
        left: 1px;
        top: 19px;
    }
    54% {
        width: 0;
        left: 1px;
        top: 19px;
    }
    70% {
        width: 50px;
        left: -8px;
        top: 37px;
    }
    84% {
        width: 17px;
        left: 21px;
        top: 48px;
    }
    100% {
        width: 25px;
        left: 19px;
        top: 46px;
    }
}
@keyframes icon-line-long {
    0% {
        width: 0;
        right: 46px;
        top: 54px;
    }
    65% {
        width: 0;
        right: 46px;
        top: 54px;
    }
    84% {
        width: 55px;
        right: 0px;
        top: 35px;
    }
    100% {
        width: 47px;
        right: 8px;
        top: 38px;
    }
}
</style>
