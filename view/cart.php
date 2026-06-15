<section class="cart-section py-5">
    <div class="container" id="cart-container">
        <h2 class="fw-bold mb-4">Giỏ hàng của bạn</h2>
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3">Sản phẩm</th>
                                    <th class="py-3">Giá</th>
                                    <th class="py-3">Số lượng</th>
                                    <th class="py-3">Tổng cộng</th>
                                    <th class="pe-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody id="cart-list">
                                <?php
                                $cart_items = get_cart_items($_SESSION['user']['id']);
                                $total_bill = 0;
                                if(count($cart_items) > 0):
                                    foreach($cart_items as $item):
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $total_bill += $subtotal;
                                ?>
                                <tr id="cart-item-<?= $item['id'] ?>">
                                    <td class="ps-4 py-4">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= $item['img'] ?>" alt="<?= $item['name'] ?>" class="rounded-3 me-3" style="width: 70px; height: 70px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0 fw-bold"><?= $item['name'] ?></h6>
                                                <p class="small text-muted mb-0">ID: <?= $item['product_id'] ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">$<?= number_format($item['price'], 2) ?></td>
                                    <td class="py-4">
                                        <div class="input-group input-group-sm" style="width: 100px;">
                                            <button onclick="updateCart(<?= $item['id'] ?>, 'dec')" class="btn btn-outline-secondary border-0 bg-light d-flex align-items-center">-</button>
                                            <input type="text" class="form-control border-0 bg-light text-center qty-input" value="<?= $item['quantity'] ?>" readonly>
                                            <button onclick="updateCart(<?= $item['id'] ?>, 'inc')" class="btn btn-outline-secondary border-0 bg-light d-flex align-items-center">+</button>
                                        </div>
                                    </td>
                                    <td class="py-4 fw-bold text-primary">$<?= number_format($subtotal, 2) ?></td>
                                    <td class="pe-4 py-4 text-end">
                                        <button onclick="deleteCartItem(<?= $item['id'] ?>)" class="btn btn-link text-danger text-decoration-none p-0 border-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        </button>
                                    </td>
                                </tr>
                                <?php 
                                    endforeach;
                                else:
                                ?>
                                <tr>
                                    <td colspan="5" class="py-5 text-center">
                                        <p class="text-muted mb-3">Giỏ hàng của bạn đang trống</p>
                                        <a href="index.php?act=shop" class="btn btn-primary px-4 rounded-pill">Tiếp tục mua sắm</a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Tóm tắt đơn hàng</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tạm tính</span>
                            <span class="fw-bold" id="bill-subtotal">$<?= number_format($total_bill, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Vận chuyển</span>
                            <span class="fw-bold text-success">Miễn phí</span>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 fw-bold">Tổng cộng</span>
                            <span class="fs-5 fw-bold text-primary" id="bill-total">$<?= number_format($total_bill, 2) ?></span>
                        </div>
                        <button class="btn btn-primary w-100 py-3 fw-bold rounded-3 text-uppercase shadow-sm">Tiến hành thanh toán</button>
                        <div class="text-center mt-3">
                            <a href="index.php?act=shop" class="text-muted text-decoration-none small">Tiếp tục mua hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
async function updateCart(id, type) {
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('id', id);
    formData.append('type', type);

    try {
        const response = await fetch('api/cart_update.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.status === 'success') {
            renderCart(data);
        }
    } catch (error) {
        console.error('Error updating cart:', error);
    }
}

async function deleteCartItem(id) {
    if (!confirm('Xóa sản phẩm này khỏi giỏ hàng?')) return;

    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', id);

    try {
        const response = await fetch('api/cart_update.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.status === 'success') {
            renderCart(data);
        }
    } catch (error) {
        console.error('Error deleting item:', error);
    }
}

function renderCart(data) {
    // Update Header Count
    const cartBadges = document.querySelectorAll('.badge.bg-primary.rounded-pill');
    cartBadges.forEach(badge => badge.innerText = data.cart_count);

    // Update Totals
    document.getElementById('bill-subtotal').innerText = '$' + data.total_bill;
    document.getElementById('bill-total').innerText = '$' + data.total_bill;

    if (data.cart_items.length === 0) {
        document.getElementById('cart-list').innerHTML = `
            <tr>
                <td colspan="5" class="py-5 text-center">
                    <p class="text-muted mb-3">Giỏ hàng của bạn đang trống</p>
                    <a href="index.php?act=shop" class="btn btn-primary px-4 rounded-pill">Tiếp tục mua sắm</a>
                </td>
            </tr>
        `;
        return;
    }

    let html = '';
    data.cart_items.forEach(item => {
        const subtotal = (item.price * item.quantity).toFixed(2);
        html += `
            <tr id="cart-item-${item.id}">
                <td class="ps-4 py-4">
                    <div class="d-flex align-items-center">
                        <img src="${item.img}" alt="${item.name}" class="rounded-3 me-3" style="width: 70px; height: 70px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 fw-bold">${item.name}</h6>
                            <p class="small text-muted mb-0">ID: ${item.product_id}</p>
                        </div>
                    </div>
                </td>
                <td class="py-4">$${parseFloat(item.price).toFixed(2)}</td>
                <td class="py-4">
                    <div class="input-group input-group-sm" style="width: 100px;">
                        <button onclick="updateCart(${item.id}, 'dec')" class="btn btn-outline-secondary border-0 bg-light d-flex align-items-center">-</button>
                        <input type="text" class="form-control border-0 bg-light text-center qty-input" value="${item.quantity}" readonly>
                        <button onclick="updateCart(${item.id}, 'inc')" class="btn btn-outline-secondary border-0 bg-light d-flex align-items-center">+</button>
                    </div>
                </td>
                <td class="py-4 fw-bold text-primary">$${parseFloat(subtotal).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                <td class="pe-4 py-4 text-end">
                    <button onclick="deleteCartItem(${item.id})" class="btn btn-link text-danger text-decoration-none p-0 border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>
                </td>
            </tr>
        `;
    });
    document.getElementById('cart-list').innerHTML = html;
}
</script>
