<!DOCTYPE html>
<html lang="vi" id="html-root">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WindyStore – Mẫu website thương mại điện tử đa năng</title>
    <meta name="description" content="">
    <link rel="icon" type="image/x-icon" href="./assets/images/favicon.ico">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <link rel="stylesheet" href="./assets/plugin/nice-select/nice-select.css">
    <link rel="stylesheet" href="./assets/plugin/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="./assets/plugin/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="./assets/plugin/nouislider/nouislider.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="./assets/css/style.css?v=<?= time() ?>">
    <!-- Theme init: apply before render to prevent flash -->
    <script>
        (function(){
            var t = localStorage.getItem('WindyStore_theme') || 'light';
            if(t === 'dark') document.documentElement.setAttribute('data-theme','dark');
        })();

        function toggleTheme() {
            var html = document.documentElement;
            var isDark = html.getAttribute('data-theme') === 'dark';
            if (isDark) {
                html.removeAttribute('data-theme');
                localStorage.setItem('WindyStore_theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                localStorage.setItem('WindyStore_theme', 'dark');
            }
        }
    </script>
</head>
<!-- Header Section Start -->
<header>

    <div class="middle-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-1 col-sm-1 col-md-1 col-lg-1 d-block d-sm-block d-md-block d-lg-none">
                    <button class="navbar-toggler border-0 collapsed" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#navbar-default" aria-controls="navbar-default" aria-label="Toggle navigation">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"
                            class="icon-hamburger" fill="none" viewBox="0 0 18 16" width="20px" height="20px">
                            <path
                                d="M1 .5a.5.5 0 100 1h15.71a.5.5 0 000-1H1zM.5 8a.5.5 0 01.5-.5h15.71a.5.5 0 010 1H1A.5.5 0 01.5 8zm0 7a.5.5 0 01.5-.5h15.71a.5.5 0 010 1H1a.5.5 0 01-.5-.5z"
                                fill="currentColor">
                            </path>
                        </svg>
                    </button>
                </div>
                <div class="col-8 col-sm-8 col-md-8 col-lg-3 order-1 order-lg-1 d-flex justify-content-start align-items-center">
                    <a href="index.php" class="d-flex align-items-center text-decoration-none">
                        <div class="d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px; background: linear-gradient(135deg, var(--theme-primary), #6366f1); border-radius: 8px; color: #fff; box-shadow: 0 4px 12px rgba(20, 108, 218, 0.2);">
                            <i class="fas fa-wind" style="font-size: 18px;"></i>
                        </div>
                        <span class="text-dark-theme-compat" style="font-size: 26px; font-family: 'Inter', sans-serif; font-weight: 800; letter-spacing: -0.5px; background: linear-gradient(135deg, var(--theme-primary), #6366f1); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">WindyStore</span>
                    </a>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-6 order-3 order-lg-2 mt-3 mt-lg-0 d-none d-lg-block">
                    <form action="index.php" method="GET" class="search-container position-relative">
                        <input type="hidden" name="act" value="shop">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control header-search" placeholder="Tìm kiếm sản phẩm..." autocomplete="off" value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                            <select name="idcat" class="form-select flex-shrink-0" style="max-width: 150px;">
                                <option value="0">Tất cả danh mục</option>
                                <?php
                                $cats = get_all_categories();
                                foreach($cats as $c) {
                                    $selected = (isset($_GET['idcat']) && $_GET['idcat'] == $c['id']) ? 'selected' : '';
                                    echo '<option value="'.$c['id'].'" '.$selected.'>'.$c['name'].'</option>';
                                }
                                ?>
                            </select>
                            <button class="btn btn-primary" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" width="20" height="20"
                                    class="icon-search" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </button>
                        </div>
                        <div class="search-dropdown-results d-none position-absolute w-100 border shadow-sm rounded mt-1" style="top: 100%; left: 0; z-index: 1050; max-height: 400px; overflow-y: auto; background-color: var(--bs-body-bg);"></div>
                    </form>
                </div>
                <div class="col-3 col-sm-3 col-md-3 col-lg-3 order-2 order-lg-3 d-flex justify-content-end">
                    <div>
                        <div class="list-inline  d-flex mt-2">
                            <div class="list-inline-item d-none d-lg-block dropdown">
                                <?php if(isset($_SESSION['user'])): ?>
                                    <a href="#" class="text-muted dropdown-toggle" data-bs-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon-account" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <span class="small ms-1"><?= $_SESSION['user']['username'] ?></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php if($_SESSION['user']['role'] == 1): ?>
                                            <li><a class="dropdown-item fw-bold text-primary" href="admin/index.php">Quản trị hệ thống</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                        <?php endif; ?>
                                        <li><a class="dropdown-item" href="index.php?act=account">Tài khoản</a></li>
                                        <li><a class="dropdown-item" href="index.php?act=logout">Đăng xuất</a></li>
                                    </ul>
                                <?php else: ?>
                                    <a href="index.php?act=login" class="text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon-account" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="list-inline-item d-block d-lg-none">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#searchModal">
                                    <svg aria-hidden="true" fill="none" focusable="false" width="24" height="24"
                                        class="icon-search" viewBox="0 0 24 24">
                                        <path d="M10.364 3a7.364 7.364 0 1 0 0 14.727 7.364 7.364 0 0 0 0-14.727Z"
                                            stroke="#000" stroke-width="1.5" stroke-miterlimit="10"></path>
                                        <path d="M15.857 15.858 21 21.001" stroke="#000" stroke-width="1.5"
                                            stroke-miterlimit="10" stroke-linecap="round"></path>
                                    </svg>
                                </a>
                                <div class="search-container">
                                    <!-- Mobile, table Search Modal -->
                                    <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content rounded-0">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <form action="index.php" method="GET" class="search-container position-relative">
                                                            <input type="hidden" name="act" value="shop">
                                                            <div class="input-group">
                                                                <input type="text" name="keyword" class="form-control header-search" placeholder="Tìm kiếm sản phẩm..." autocomplete="off" value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                                                                <select name="idcat" class="form-select flex-shrink-0" style="max-width: 120px;">
                                                                    <option value="0">Tất cả</option>
                                                                    <?php
                                                                    $cats = get_all_categories();
                                                                    foreach($cats as $c) {
                                                                        $selected = (isset($_GET['idcat']) && $_GET['idcat'] == $c['id']) ? 'selected' : '';
                                                                        echo '<option value="'.$c['id'].'" '.$selected.'>'.$c['name'].'</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <button class="btn btn-primary" type="submit">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round" width="20" height="20"
                                                                        class="icon-search" viewBox="0 0 24 24">
                                                                        <circle cx="11" cy="11" r="8"></circle>
                                                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <div class="search-dropdown-results d-none position-absolute w-100 border shadow-sm rounded mt-1" style="top: 100%; left: 0; z-index: 1050; max-height: 400px; overflow-y: auto; background-color: var(--bs-body-bg);"></div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Dark/Light Toggle – luôn hiển thị ở mọi màn hình -->
                            <div class="list-inline-item">
                                <button
                                    class="theme-toggle-btn"
                                    id="themeToggle"
                                    onclick="toggleTheme()"
                                    title="Chuyển chế độ sáng/tối"
                                    aria-label="Toggle dark mode"
                                    style="cursor:pointer; vertical-align:middle;">
                                    <span class="toggle-thumb">
                                        <span class="toggle-icon-sun" style="line-height:1;">&#9728;</span>
                                        <span class="toggle-icon-moon" style="line-height:1;">&#9790;</span>
                                    </span>
                                </button>
                            </div>
                            <div class="list-inline-item me-3">
                                <a class="text-muted position-relative" href="index.php?act=wishlist" title="Danh sách yêu thích">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-heart">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                    <span class="position-absolute top-0 start-100 translate-middle badge badge-count rounded-pill bg-danger">
                                        <?php
                                        $wishlist_count = 0;
                                        if(isset($_SESSION['user'])){
                                            $wishlist_count = get_wishlist_count($_SESSION['user']['id']);
                                        }
                                        echo $wishlist_count;
                                        ?>
                                        <span class="visually-hidden">sản phẩm yêu thích</span>
                                    </span>
                                </a>
                            </div>
                            <div class="list-inline-item me-3 me-lg-0">
                                <a class="text-muted position-relative" href="index.php?act=cart">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-cart" width="25" height="25"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                        </path>
                                    </svg>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge badge-count rounded-pill bg-primary">
                                        <?php
                                        $cart_count = 0;
                                        if(isset($_SESSION['user'])){
                                            $cart_count = get_cart_count($_SESSION['user']['id']);
                                        }
                                        echo $cart_count;
                                        ?>
                                        <span class="visually-hidden">sản phẩm trong giỏ hàng</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Navs -->
    <div class="header-bottom">
        <nav class="navbar navbar-expand-lg navbar-light navbar-default py-0 px-0" aria-label="Offcanvas navbar large">
            <div class="container">
                <div class="offcanvas offcanvas-start" tabindex="-1" id="navbar-default"
                    aria-labelledby="navbar-defaultLabel">
                    <div class="offcanvas-header">
                        <div class="account-mobile d-flex align-items-center">
                            <?php if(isset($_SESSION['user'])): ?>
                                <a href="index.php?act=account" class="text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-account" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span class="ms-2 fw-semibold"><?= $_SESSION['user']['username'] ?></span>
                                </a>
                                <a href="index.php?act=logout" class="ms-3 text-danger small">Đăng xuất</a>
                            <?php else: ?>
                                <a href="index.php?act=login" class="text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-account" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span class="ms-2 fw-semibold">Đăng nhập & Đăng ký</span>
                                </a>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="d-block d-lg-none mb-4">
                            <a class="btn btn-primary w-100 d-flex justify-content-center align-items-center collapsed"
                                data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                                aria-controls="collapseExample">
                                <span class="me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" class="icon icon-element">
                                        <path
                                            d="M22 8.52V3.98C22 2.57 21.36 2 19.77 2H15.73C14.14 2 13.5 2.57 13.5 3.98V8.51C13.5 9.93 14.14 10.49 15.73 10.49H19.77C21.36 10.5 22 9.93 22 8.52ZM22 19.77V15.73C22 14.14 21.36 13.5 19.77 13.5H15.73C14.14 13.5 13.5 14.14 13.5 15.73V19.77C13.5 21.36 14.14 22 15.73 22H19.77C21.36 22 22 21.36 22 19.77ZM10.5 8.52V3.98C10.5 2.57 9.86 2 8.27 2H4.23C2.64 2 2 2.57 2 3.98V8.51C2 9.93 2.64 10.49 4.23 10.49H8.27C9.86 10.5 10.5 9.93 10.5 8.52ZM10.5 19.77V15.73C10.5 14.14 9.86 13.5 8.27 13.5H4.23C2.64 13.5 2 14.14 2 15.73V19.77C2 21.36 2.64 22 4.23 22H8.27C9.86 22 10.5 21.36 10.5 19.77Z"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                                Tất cả danh mục
                            </a>
                            <div class="mt-2 collapse" id="collapseExample">
                                <div class="card card-body">
                                    <ul class="mb-0 list-unstyled">
                                        <?php
                                        $header_categories = get_all_categories();
                                        foreach ($header_categories as $cat) {
                                            echo '<li><a class="dropdown-item" href="index.php?act=shop&idcat='.$cat['id'].'">'.htmlspecialchars($cat['name']).'</a></li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row w-100 mx-0 py-0 p-0">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 py-0 px-0 mx-0">
                                <div class="dropdown d-none d-lg-block categories-section-lg">
                                    <button class="btn w-100 text-nowrap department-all d-flex" type="button"
                                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg viewBox="0 0 32 32" class="mt-1" xmlns="http://www.w3.org/2000/svg"
                                            width="20" height="20" fill="#000">
                                            <path
                                                d="M17.5,11h-3a2,2,0,0,1-2-2V6a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2V9A2,2,0,0,1,17.5,11Z" />
                                            <path
                                                d="M9,11H6A2,2,0,0,1,4,9V6A2,2,0,0,1,6,4H9a2,2,0,0,1,2,2V9A2,2,0,0,1,9,11Z" />
                                            <path
                                                d="M26,11H23a2,2,0,0,1-2-2V6a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2V9A2,2,0,0,1,26,11Z" />
                                            <path
                                                d="M17.5,28h-3a2,2,0,0,1-2-2V23a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2v3A2,2,0,0,1,17.5,28Z" />
                                            <path
                                                d="M9,28H6a2,2,0,0,1-2-2V23a2,2,0,0,1,2-2H9a2,2,0,0,1,2,2v3A2,2,0,0,1,9,28Z" />
                                            <path
                                                d="M26,28H23a2,2,0,0,1-2-2V23a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2v3A2,2,0,0,1,26,28Z" />
                                            <path
                                                d="M17.5,19.5h-3a2,2,0,0,1-2-2v-3a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2v3A2,2,0,0,1,17.5,19.5Z" />
                                            <path
                                                d="M9,19.5H6a2,2,0,0,1-2-2v-3a2,2,0,0,1,2-2H9a2,2,0,0,1,2,2v3A2,2,0,0,1,9,19.5Z" />
                                            <path
                                                d="M26,19.5H23a2,2,0,0,1-2-2v-3a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2v3A2,2,0,0,1,26,19.5Z" />
                                        </svg>
                                        <span class="ms-4">Tất cả danh mục</span>
                                    </button>
                                    <ul class="dropdown-menu p-0 m-0 custom-dropdown"
                                        aria-labelledby="dropdownMenuButton1">
                                        <?php
                                        $header_categories = get_all_categories();
                                        foreach ($header_categories as $cat) {
                                            echo '<li><a class="dropdown-item" href="index.php?act=shop&idcat='.$cat['id'].'">'.htmlspecialchars($cat['name']).'</a></li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-9 overflow-x-auto px-0">
                                <div class="menu-item-list">
                                    <ul class="navbar-nav align-items-start align-items-lg-center  py-0">
                                        <li class="nav-item dropdown me-0 me-lg-2">
                                            <a class="nav-link " href="index.php">Trang chủ</a>
                                        </li>
                                        <li class="nav-item dropdown  me-0 me-lg-2">
                                            <a class="nav-link text-nowrap" href="index.php?act=shop">Khuyến mãi hot</a>
                                        </li>
                                        <li class="nav-item dropdown me-0 me-lg-2">
                                            <a class="nav-link text-nowrap" href="index.php?act=shop">Cửa hàng</a>
                                        </li>
                                        <li class="nav-item dropdown dropdown-fullwidth me-0 me-lg-2">
                                            <a class="nav-link dropdown-toggle text-nowrap" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">Danh mục lớn</a>
                                            <div class="dropdown-menu pb-0">
                                                <div class="row p-2 p-lg-4">
                                                    <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                                        <h6 class="text-primary ps-3">Phụ kiện Laptop</h6>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Chuột')?>">Chuột</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Bàn phím')?>">Bàn phím</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('USB')?>">USB</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Thẻ nhớ')?>">Thẻ nhớ</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Router')?>">Router</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Pin')?>">Pin</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Bộ sạc')?>">Bộ sạc</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Bộ lưu điện (UPS)')?>">Bộ lưu điện (UPS)</a>
                                                    </div>
                                                    <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                                        <h6 class="text-primary ps-3">Phụ kiện điện thoại</h6>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Kính cường lực')?>">Kính cường lực</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Ốp lưng')?>">Ốp lưng</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Cáp sạc')?>">Cáp sạc</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Củ sạc')?>">Củ sạc</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Ống kính rời')?>">Ống kính rời</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Đèn Flash')?>">Đèn Flash</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Sạc dự phòng')?>">Sạc dự phòng</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('OTG USB')?>">OTG USB</a>
                                                    </div>
                                                    <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                                        <h6 class="text-primary ps-3">Phụ kiện máy ảnh</h6>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Máy ảnh lấy liền')?>">Máy ảnh lấy liền</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Flycam')?>">Flycam</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('DSLR')?>">DSLR</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Đèn Flash')?>">Đèn Flash</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Action Cam')?>">Action Cam</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Ống kính')?>">Ống kính</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Chân máy')?>">Chân máy</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Máy ảnh du lịch')?>">Máy ảnh du lịch</a>
                                                    </div>
                                                    <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                                        <h6 class="text-primary ps-3">Khác</h6>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Tai nghe')?>">Tai nghe</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Loa')?>">Loa</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Đồng hồ')?>">Đồng hồ</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Thiết bị mạng')?>">Thiết bị mạng</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Màn hình')?>">Màn hình</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Linh kiện')?>">Linh kiện</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Phần mềm')?>">Phần mềm</a>
                                                        <a class="dropdown-item" href="index.php?act=shop&idcat=<?=get_category_id_by_name('Dịch vụ')?>">Dịch vụ</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="nav-item dropdown dropdown-fullwidth me-0 me-lg-2">
                                            <a class="nav-link dropdown-toggle text-nowrap" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">Trang</a>
                                            <div class="dropdown-menu pb-0">
                                                <div class="row p-2 p-lg-4">
                                                    <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                                        <h6 class="text-primary ps-3">Cửa hàng</h6>
                                                        <a class="dropdown-item" href="index.php?act=shop">Danh sách sản phẩm</a>
                                                        <a class="dropdown-item" href="index.php?act=shop-single">Chi tiết sản phẩm</a>
                                                        <a class="dropdown-item" href="index.php?act=cart">Giỏ hàng</a>
                                                        <a class="dropdown-item" href="index.php?act=checkout">Thanh toán</a>
                                                    </div>
                                                    <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                                        <h6 class="text-primary ps-3">Tài khoản</h6>
                                                        <a class="dropdown-item" href="index.php?act=login">Đăng nhập</a>
                                                        <a class="dropdown-item" href="index.php?act=signup">Đăng ký</a>
                                                        <a class="dropdown-item" href="index.php?act=forgot-password">Quên mật khẩu</a>
                                                        <a class="dropdown-item" href="index.php?act=account">Tài khoản của tôi</a>
                                                    </div>
                                                    <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                                        <h6 class="text-primary ps-3">Tin tức</h6>
                                                        <a class="dropdown-item" href="index.php?act=blog">Blog</a>
                                                        <a class="dropdown-item" href="index.php?act=blog-single">Chi tiết bài viết</a>
                                                    </div>
                                                    <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                                        <h6 class="text-primary ps-3">Khác</h6>
                                                        <a class="dropdown-item" href="index.php?act=about">Giới thiệu</a>
                                                        <a class="dropdown-item" href="index.php?act=contact">Liên hệ</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navs -->
</header>
<!-- Header Section End -->

<!-- Product Quick View Modal -->
<div class="modal fade" id="productQuickViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 8px; overflow: hidden;">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="padding: 1rem;"></button>
            </div>
            <div class="modal-body p-4 pt-0" id="quickViewModalBody">
                <!-- Content loaded dynamically via JS -->
            </div>
        </div>
    </div>
</div>


