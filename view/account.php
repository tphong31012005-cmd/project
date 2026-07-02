<section class="account-section py-5">
    <div class="container">
        <div class="row">
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
                            <a href="index.php?act=account" class="list-group-item list-group-item-action border-0 px-0 active"><i class="fas fa-user-circle me-2"></i> Thông tin tài khoản</a>
                            <a href="index.php?act=my_orders" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi</a>
                            <a href="index.php?act=wishlist" class="list-group-item list-group-item-action border-0 px-0"><i class="fas fa-heart me-2"></i> Danh sách yêu thích</a>
                            <a href="index.php?act=logout" class="list-group-item list-group-item-action border-0 px-0 text-danger"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Thông tin chi tiết</h5>
                        <?php if(isset($_GET['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Cập nhật thông tin tài khoản thành công!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Có lỗi xảy ra trong quá trình cập nhật!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form action="index.php?act=update_account" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small text-muted">Họ và tên</label>
                                    <input type="text" name="fullname" class="form-control" value="<?= $_SESSION['user']['fullname'] ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small text-muted">Tên đăng nhập (Không thể đổi)</label>
                                    <input type="text" class="form-control" value="<?= $_SESSION['user']['username'] ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= $_SESSION['user']['email'] ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Số điện thoại</label>
                                <input type="text" name="tel" class="form-control" value="<?= $_SESSION['user']['tel'] ?>">
                            </div>
                            <div class="mb-4">
                                <label class="form-label small text-muted">Địa chỉ</label>
                                <textarea name="address" class="form-control" rows="3"><?= $_SESSION['user']['address'] ?></textarea>
                            </div>
                            <button type="submit" name="update_account" value="1" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Lưu thay đổi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
