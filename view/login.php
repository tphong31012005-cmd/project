<section class="login-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="auth-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-dark-theme-compat">Đăng nhập</h2>
                            <p class="text-muted">Chào mừng bạn quay lại với WindyStore</p>
                        </div>
                        <?php if(isset($_GET['signup_success']) && $_GET['signup_success'] == 1): ?>
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                Đăng ký thành công! Vui lòng đăng nhập bằng tài khoản của bạn.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php
                        if(isset($txt_error) && ($txt_error != "")){
                            echo '<div class="alert alert-danger py-2" role="alert">'.$txt_error.'</div>';
                        }
                        ?>
                        <form action="index.php?act=login" method="post" class="needs-validation">
                            <div class="mb-3">
                                <label for="username" class="form-label small fw-semibold text-dark-theme-compat">Tên đăng nhập</label>
                                <div class="auth-input-group">
                                    <input type="text" class="form-control auth-input <?= isset($login_errors['username']) ? 'is-invalid' : '' ?>" id="username" name="username" placeholder="Nhập tên đăng nhập" value="<?= isset($username) ? $username : '' ?>">
                                    <i class="fas fa-user auth-input-icon"></i>
                                    <?php if(isset($login_errors['username'])): ?>
                                        <div class="invalid-feedback"><?= $login_errors['username'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label small fw-semibold d-flex justify-content-between text-dark-theme-compat">
                                    Mật khẩu
                                    <a href="index.php?act=forgot-password" class="text-primary text-decoration-none fw-normal">Quên mật khẩu?</a>
                                </label>
                                <div class="auth-input-group">
                                    <input type="password" class="form-control auth-input <?= isset($login_errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Nhập mật khẩu">
                                    <i class="fas fa-lock auth-input-icon"></i>
                                    <?php if(isset($login_errors['password'])): ?>
                                        <div class="invalid-feedback"><?= $login_errors['password'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember">
                                <label class="form-check-label small text-muted" for="remember">Ghi nhớ đăng nhập</label>
                            </div>
                            <button type="submit" value="add" name="login" class="btn auth-btn w-100 py-2">Đăng nhập</button>
                        </form>
                        <div class="text-center mt-4">
                            <p class="small mb-0 text-muted">Chưa có tài khoản? <a href="index.php?act=signup" class="text-primary fw-bold text-decoration-none">Đăng ký ngay</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
