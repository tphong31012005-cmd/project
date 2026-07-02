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
                            <a href="index.php?act=my_orders" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi</a>
                            <a href="index.php?act=wishlist" class="list-group-item list-group-item-action border-0 px-0 active"><i class="fas fa-heart me-2"></i> Danh sách yêu thích</a>
                            <a href="index.php?act=logout" class="list-group-item list-group-item-action border-0 px-0 text-danger"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Danh sách yêu thích của tôi</h5>
                        
                        <?php if (isset($wishlist_items) && count($wishlist_items) > 0): ?>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th class="text-end">Giá</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($wishlist_items as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="index.php?act=shop-single&id=<?= $item['product_id'] ?>">
                                                            <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="img-fluid rounded border me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                        </a>
                                                        <div>
                                                            <a href="index.php?act=shop-single&id=<?= $item['product_id'] ?>" class="text-dark fw-semibold text-decoration-none">
                                                                <?= htmlspecialchars($item['name']) ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <span class="fw-bold text-danger"><?= number_format($item['price'], 0, ',', '.') ?> đ</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <?= show_btn_addtocart($item['product_id'], $item['name'], $item['img'], $item['price'], $item['quantity']) ?>
                                                        <a href="index.php?act=delwishlist&id=<?= $item['product_id'] ?>" class="btn btn-outline-danger" title="Xóa khỏi danh sách">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-3 text-muted">
                                    <i class="fas fa-heart-broken fa-4x text-light"></i>
                                </div>
                                <h5>Danh sách yêu thích trống!</h5>
                                <p class="text-muted">Bạn chưa lưu sản phẩm nào vào danh sách yêu thích.</p>
                                <a href="index.php?act=shop" class="btn btn-primary rounded-pill px-4 mt-2">Tiếp tục mua sắm</a>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
