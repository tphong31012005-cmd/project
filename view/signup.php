<section class="signup-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="auth-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-dark-theme-compat">Đăng ký thành viên</h2>
                            <p class="text-muted">Tham gia cùng WindyStore để nhận nhiều ưu đãi</p>
                        </div>
                        <?php
                        if(isset($txt_success) && ($txt_success != "")){
                            echo '<div class="alert alert-success py-2" role="alert">'.$txt_success.'</div>';
                        }
                        if(isset($txt_error) && ($txt_error != "")){
                            echo '<div class="alert alert-danger py-2" role="alert">'.$txt_error.'</div>';
                        }
                        ?>
                        <form action="index.php?act=signup" method="post" class="needs-validation">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label small fw-semibold text-dark-theme-compat">Tên đăng nhập <span class="text-danger">*</span></label>
                                    <div class="auth-input-group">
                                        <input type="text" class="form-control auth-input <?= isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" name="username" placeholder="Ví dụ: user123" value="<?= isset($username) ? $username : '' ?>">
                                        <i class="fas fa-user auth-input-icon"></i>
                                        <?php if(isset($errors['username'])): ?>
                                            <div class="invalid-feedback"><?= $errors['username'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label small fw-semibold text-dark-theme-compat">Email <span class="text-danger">*</span></label>
                                    <div class="auth-input-group">
                                        <input type="email" class="form-control auth-input <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="example@mail.com" value="<?= isset($email) ? $email : '' ?>">
                                        <i class="fas fa-envelope auth-input-icon"></i>
                                        <?php if(isset($errors['email'])): ?>
                                            <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label small fw-semibold text-dark-theme-compat">Mật khẩu <span class="text-danger">*</span></label>
                                    <div class="auth-input-group">
                                        <input type="password" class="form-control auth-input <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="••••••••">
                                        <i class="fas fa-lock auth-input-icon"></i>
                                        <?php if(isset($errors['password'])): ?>
                                            <div class="invalid-feedback"><?= $errors['password'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label small fw-semibold text-dark-theme-compat">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <div class="auth-input-group">
                                        <input type="password" class="form-control auth-input <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password" placeholder="••••••••">
                                        <i class="fas fa-shield-alt auth-input-icon"></i>
                                        <?php if(isset($errors['confirm_password'])): ?>
                                            <div class="invalid-feedback"><?= $errors['confirm_password'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="fullname" class="form-label small fw-semibold text-dark-theme-compat">Họ và tên</label>
                                <div class="auth-input-group">
                                    <input type="text" class="form-control auth-input" id="fullname" name="fullname" placeholder="Nguyễn Văn A" value="<?= isset($fullname) ? $fullname : '' ?>">
                                    <i class="fas fa-id-card auth-input-icon"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="tel" class="form-label small fw-semibold text-dark-theme-compat">Số điện thoại</label>
                                <div class="auth-input-group">
                                    <input type="text" class="form-control auth-input" id="tel" name="tel" placeholder="090xxxxxxx" value="<?= isset($tel) ? $tel : '' ?>">
                                    <i class="fas fa-phone auth-input-icon"></i>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="address" class="form-label small fw-semibold text-dark-theme-compat">Địa chỉ</label>
                                <div class="auth-input-group">
                                    <input type="text" class="form-control auth-input" id="address" name="address" placeholder="Số nhà, đường, quận/huyện..." value="<?= isset($address) ? $address : '' ?>">
                                    <i class="fas fa-map-marker-alt auth-input-icon"></i>
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label small text-muted" for="terms">Tôi đồng ý với các <a href="#" class="text-primary text-decoration-none">điều khoản và điều kiện</a> của WindyStore</label>
                            </div>
                            <button type="submit" value="add" name="signup" class="btn auth-btn w-100 py-2">Đăng ký</button>
                        </form>
                        <div class="text-center mt-4">
                            <p class="small mb-0 text-muted">Đã có tài khoản? <a href="index.php?act=login" class="text-primary fw-bold text-decoration-none">Đăng nhập ngay</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
