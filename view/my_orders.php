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
                        <h5 class="fw-bold mb-4">Đơn hàng của tôi</h5>
                        
                        <?php if (isset($my_bills) && count($my_bills) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mã ĐH</th>
                                            <th>Ngày đặt</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th class="text-end">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($my_bills as $b): ?>
                                            <tr>
                                                <td class="fw-bold text-primary">#<?= htmlspecialchars($b['bill_code']) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($b['created_at'])) ?></td>
                                                <td class="fw-semibold text-danger"><?= number_format($b['total_price'], 0, ',', '.') ?> đ</td>
                                                <td>
                                                    <?php 
                                                        switch ($b['status']) {
                                                            case 0: echo '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Chờ xác nhận</span>'; break;
                                                            case 1: echo '<span class="badge bg-info"><i class="fas fa-check-circle me-1"></i> Đã xác nhận</span>'; break;
                                                            case 2: echo '<span class="badge bg-primary"><i class="fas fa-truck me-1"></i> Đang giao</span>'; break;
                                                            case 3: echo '<span class="badge bg-success"><i class="fas fa-box-open me-1"></i> Hoàn thành</span>'; break;
                                                            case 4: echo '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Đã hủy</span>'; break;
                                                            default: echo '<span class="badge bg-secondary">Không rõ</span>'; break;
                                                        }
                                                    ?>
                                                </td>
                                                <td class="text-end">
                                                    <a href="index.php?act=my_order_detail&id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                        Xem chi tiết
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-3 text-muted">
                                    <i class="fas fa-box-open fa-4x"></i>
                                </div>
                                <h5>Bạn chưa có đơn hàng nào!</h5>
                                <p class="text-muted">Hãy lượn lờ shop và sắm cho mình những món đồ yêu thích nhé.</p>
                                <a href="index.php?act=shop" class="btn btn-primary rounded-pill px-4 mt-2">Đến cửa hàng ngay</a>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
