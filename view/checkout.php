<?php
$user_id = $_SESSION['user']['id'];
$cart_items = get_cart_items($user_id);
$total_bill = 0;
foreach($cart_items as $item) {
    $total_bill += $item['price'] * $item['quantity'];
}
?>
<section class="checkout-section py-5">
    <div class="container">
        <h2 class="fw-bold mb-4">Thanh toán</h2>
        
        <form action="index.php?act=place_order" method="POST" class="needs-validation" novalidate>
            <div class="row">
                <!-- Billing Details Form -->
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h4 class="fw-bold mb-4 text-primary">Thông tin giao hàng</h4>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="fullname" class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3 py-2" id="fullname" name="fullname" 
                                       value="<?= htmlspecialchars($_SESSION['user']['fullname'] ?? '') ?>" required>
                                <div class="invalid-feedback">Vui lòng điền họ tên người nhận.</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tel" class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control rounded-3 py-2" id="tel" name="tel" 
                                       value="<?= htmlspecialchars($_SESSION['user']['tel'] ?? '') ?>" required>
                                <div class="invalid-feedback">Vui lòng điền số điện thoại.</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Địa chỉ Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control rounded-3 py-2" id="email" name="email" 
                                       value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>" required>
                                <div class="invalid-feedback">Vui lòng điền email hợp lệ.</div>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label fw-semibold">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3 py-2" id="address" name="address" 
                                       value="<?= htmlspecialchars($_SESSION['user']['address'] ?? '') ?>" required>
                                <div class="invalid-feedback">Vui lòng điền địa chỉ giao hàng.</div>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="note" class="form-label fw-semibold">Ghi chú đơn hàng (Tùy chọn)</label>
                                <textarea class="form-control rounded-3" id="note" name="note" rows="4" 
                                          placeholder="Lưu ý về thời gian giao hàng, địa điểm chi tiết..."></textarea>
                            </div>
                        </div>

                        <hr class="my-4">
                        
                        <h4 class="fw-bold mb-3 text-primary">Phương thức thanh toán</h4>
                        
                        <div class="payment-methods">
                            <!-- COD Option -->
                            <div class="card border mb-3 rounded-3 payment-card active" onclick="selectPayment(0)">
                                <div class="card-body d-flex align-items-center justify-content-between p-3" style="cursor: pointer;">
                                    <div class="d-flex align-items-center w-100">
                                        <input class="form-check-input me-3" type="radio" name="payment_method" id="payment_cod" value="0" checked style="cursor: pointer;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fw-bold">Thanh toán khi nhận hàng (COD)</h6>
                                            <small class="text-muted">Nhận hàng rồi mới thanh toán tiền mặt</small>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-money-bill-wave text-success fs-4"></i>
                                </div>
                            </div>
                            
                            <!-- Bank Transfer Option -->
                            <div class="card border mb-3 rounded-3 payment-card" onclick="selectPayment(1)">
                                <div class="card-body d-flex align-items-center justify-content-between p-3" style="cursor: pointer;">
                                    <div class="d-flex align-items-center w-100">
                                        <input class="form-check-input me-3" type="radio" name="payment_method" id="payment_bank" value="1" style="cursor: pointer;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fw-bold">Chuyển khoản ngân hàng</h6>
                                            <small class="text-muted">Chuyển khoản qua ngân hàng hoặc ví điện tử</small>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-building-columns text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bank Transfer details info card, shown only when bank option is selected -->
                        <div id="bank_details_box" class="card border-0 bg-light rounded-3 p-3 mb-3 d-none animate__animated animate__fadeIn text-dark">
                            <h6 class="fw-bold mb-2">Thông tin tài khoản ngân hàng:</h6>
                            <div class="small mb-1">Ngân hàng: <strong>TP Bank</strong></div>
                            <div class="small mb-1">Số tài khoản: <strong>0866462647</strong></div>
                            <div class="small mb-1">Chủ tài khoản: <strong>WINDY STORE</strong></div>
                            <div class="small mb-0">Nội dung chuyển khoản: <strong>[Họ tên] + [Số điện thoại]</strong></div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary Column -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4 sticky-lg-top" style="top: 2rem; z-index: 10;">
                        <h4 class="fw-bold mb-4 text-primary">Tóm tắt đơn hàng</h4>
                        
                        <div class="checkout-product-list mb-4 overflow-y-auto" style="max-height: 280px;">
                            <?php foreach($cart_items as $item): ?>
                            <div class="d-flex align-items-center mb-3">
                                <div class="position-relative me-3">
                                    <img src="<?= $item['img'] ?>" alt="<?= $item['name'] ?>" class="rounded-3 border" style="width: 60px; height: 60px; object-fit: cover;">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary" style="font-size: 0.7rem;">
                                        <?= $item['quantity'] ?>
                                    </span>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <h6 class="mb-0 text-truncate fw-bold"><?= $item['name'] ?></h6>
                                    <small class="text-muted">ID: <?= $item['product_id'] ?></small>
                                </div>
                                <span class="fw-semibold text-primary ms-3"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?> đ</span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mb-3 mt-3">
                            <label for="coupon_code_input" class="form-label fw-semibold text-dark-theme-compat">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" id="coupon_code_input" name="coupon_code" class="form-control py-2 rounded-start-3" placeholder="Nhập mã giảm giá..." autocomplete="off">
                                <button type="button" id="btn_apply_coupon" class="btn btn-outline-primary rounded-end-3">Áp dụng</button>
                            </div>
                            <div id="coupon_message" class="small mt-2 d-none"></div>
                            
                            <?php
                            $user_coupon = get_user_coupon($user_id);
                            if ($user_coupon && $user_coupon['status'] == 0):
                            ?>
                                <div class="alert alert-info py-2 px-3 small mt-2 d-flex justify-content-between align-items-center rounded-3">
                                    <span>Phiếu giảm giá 10% của bạn: <strong><?= htmlspecialchars($user_coupon['code']) ?></strong></span>
                                    <button type="button" class="btn btn-sm btn-primary py-1 px-2 rounded-2" onclick="applyCouponCode('<?= htmlspecialchars($user_coupon['code']) ?>')">Áp dụng</button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính</span>
                            <span class="fw-bold"><?= number_format($total_bill, 0, ',', '.') ?> đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 d-none" id="discount_row">
                            <span class="text-muted text-danger fw-semibold">Giảm giá (10%)</span>
                            <span class="fw-bold text-danger" id="discount_amount">-0 đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Vận chuyển</span>
                            <span class="fw-bold text-success">Miễn phí</span>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 fw-bold text-dark-theme-compat">Tổng thanh toán</span>
                            <span class="fs-5 fw-bold text-primary" id="final_total_display"><?= number_format($total_bill, 0, ',', '.') ?> đ</span>
                        </div>
                        
                        <button type="submit" name="place_order" value="1" class="btn btn-primary w-100 py-3 fw-bold rounded-3 text-uppercase shadow-sm">
                            Đặt hàng ngay
                        </button>
                        
                        <div class="text-center mt-3">
                            <a href="index.php?act=cart" class="text-muted text-decoration-none small">Quay lại giỏ hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
.payment-card {
    transition: all 0.25s ease;
    border-color: var(--bs-border-color) !important;
}
.payment-card:hover {
    border-color: var(--bs-primary) !important;
    background-color: rgba(var(--bs-primary-rgb), 0.02);
}
.payment-card.active {
    border-color: var(--bs-primary) !important;
    background-color: rgba(var(--bs-primary-rgb), 0.04);
}
</style>

<script>
function selectPayment(type) {
    const codRadio = document.getElementById('payment_cod');
    const bankRadio = document.getElementById('payment_bank');
    const bankDetails = document.getElementById('bank_details_box');
    const cards = document.querySelectorAll('.payment-card');
    
    cards.forEach(card => card.classList.remove('active'));
    
    if (type === 0) {
        codRadio.checked = true;
        cards[0].classList.add('active');
        bankDetails.classList.add('d-none');
    } else {
        bankRadio.checked = true;
        cards[1].classList.add('active');
        bankDetails.classList.remove('d-none');
    }
}

function applyCouponCode(code) {
    document.getElementById('coupon_code_input').value = code;
    document.getElementById('btn_apply_coupon').click();
}

// Coupon validation handler
document.addEventListener('DOMContentLoaded', function() {
    var btnApply = document.getElementById('btn_apply_coupon');
    if (btnApply) {
        btnApply.addEventListener('click', function() {
            var codeInput = document.getElementById('coupon_code_input');
            var code = codeInput.value.trim();
            var msgDiv = document.getElementById('coupon_message');
            
            if (code === '') {
                msgDiv.className = 'small mt-2 text-danger';
                msgDiv.innerHTML = 'Vui lòng nhập mã giảm giá.';
                msgDiv.classList.remove('d-none');
                return;
            }
            
            // Perform fetch call to validate coupon
            var formData = new FormData();
            formData.append('code', code);
            
            fetch('api/coupon_validate.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    msgDiv.className = 'small mt-2 text-success alert alert-success py-1 px-2 rounded-2';
                    msgDiv.innerHTML = '<i class="fas fa-check-circle me-1"></i>Áp dụng mã giảm giá thành công! Giảm 10%';
                    msgDiv.classList.remove('d-none');
                    
                    var total = <?= $total_bill ?>;
                    var discount = Math.round(total * 0.1);
                    var finalTotal = total - discount;
                    
                    document.getElementById('discount_row').classList.remove('d-none');
                    document.getElementById('discount_amount').innerText = '-' + discount.toLocaleString('vi-VN') + ' đ';
                    document.getElementById('final_total_display').innerText = finalTotal.toLocaleString('vi-VN') + ' đ';
                } else {
                    msgDiv.className = 'small mt-2 text-danger alert alert-danger py-1 px-2 rounded-2';
                    msgDiv.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>' + (res.message || 'Mã giảm giá không hợp lệ.');
                    msgDiv.classList.remove('d-none');
                    
                    document.getElementById('discount_row').classList.add('d-none');
                    document.getElementById('final_total_display').innerText = <?= $total_bill ?>.toLocaleString('vi-VN') + ' đ';
                }
            })
            .catch(() => {
                msgDiv.className = 'small mt-2 text-danger';
                msgDiv.innerHTML = 'Có lỗi xảy ra, vui lòng thử lại.';
                msgDiv.classList.remove('d-none');
            });
        });
    }
});

// Bootstrap Form Validation Client Side
(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
})()
</script>
