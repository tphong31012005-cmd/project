<!-- Menu Section -->
<div class="container mt-2 mt-sm-2 mt-md-2 mt-lg-4">
    <div class="d-block d-sm-block d-md-block d-lg-flex">
        <div class="container-left">
            <div class="category-menu" style="display: none;">
                <div class="menu-title d-none d-sm-none d-md-none d-lg-block"><i class="fas fa-bars mr-2"></i></i>Danh mục điện tử</div>
                <ul class="list-unstyled">
                    <li>
                        <a href="#" class="more-menu">Máy tính & Laptop</a>
                        <ul class="list-unstyled">
                            <li>
                                <a href="#" class="more-menu">Laptop Gaming</a>
                                <ul class="list-unstyled">
                                    <li><a href="#">ASUS ROG</a></li>
                                    <li><a href="#">MSI Gaming</a></li>
                                </ul>
                            </li>
                            <li><a href="#">MacBook</a></li>
                            <li><a href="#">Laptop Văn phòng</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Điện thoại thông minh</a></li>
                    <li><a href="#">Máy tính bảng</a></li>
                    <li>
                        <a href="#" class="more-menu">Âm thanh</a>
                        <ul class="list-unstyled">
                            <li><a href="#">Tai nghe Bluetooth</a></li>
                            <li><a href="#">Loa di động</a></li>
                            <li><a href="#">Dàn âm thanh</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Đồng hồ thông minh</a></li>
                    <li><a href="#">Máy ảnh & Quay phim</a></li>
                    <li><a href="#">Phụ kiện công nghệ</a></li>
                    <li><a href="#">Tivi & Màn hình</a></li>
                    <li><a href="#">Linh kiện PC</a></li>
                    <li><a href="#">Sản phẩm nổi bật</a></li>
                </ul>
            </div>
        </div>
        <div class="container-right"></div>
    </div>
</div>
<!-- /Menu Section End -->

<?php
$idcat = isset($_GET['idcat']) ? intval($_GET['idcat']) : 0;
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";
$sort = isset($_GET['sort']) ? $_GET['sort'] : "newest";
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$limit = 9; // Hiển thị 9 SP mỗi trang

$category_name = "";
if ($idcat > 0) {
    $category = get_category($idcat);
    if ($category) {
        $category_name = $category['name'];
    }
}

$filter_result = get_products_with_filter($keyword, $idcat, $sort, $page, $limit);
$products = $filter_result['data'];
$total_pages = $filter_result['total_pages'];
$total_items = $filter_result['total_items'];

// Hàm hỗ trợ tạo URL
function build_shop_url($params_to_update) {
    $params = $_GET;
    foreach ($params_to_update as $k => $v) {
        $params[$k] = $v;
    }
    return 'index.php?' . http_build_query($params);
}
?>
<!-- breadcrumbs -->
<nav class="container mt-4 mt-sm-4 mt-md-4 mt-lg-3 d-flex flex-wrap align-items-center gap-2 px-3">
    <div class="d-flex align-items-center gap-2">
        <a href="index.php">
            <svg class="text-primary" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 576 512" height="20px" width="20px" xmlns="http://www.w3.org/2000/svg">
                <path d="M280.37 148.26L96 300.11V464a16 16 0 0 0 16 16l112.06-.29a16 16 0 0 0 15.92-16V368a16 16 0 0 1 16-16h64a16 16 0 0 1 16 16v95.64a16 16 0 0 0 16 16.05L464 480a16 16 0 0 0 16-16V300L295.67 148.26a12.19 12.19 0 0 0-15.3 0zM571.6 251.47L488 182.56V44.05a12 12 0 0 0-12-12h-56a12 12 0 0 0-12 12v72.61L318.47 43a48 48 0 0 0-61 0L4.34 251.47a12 12 0 0 0-1.6 16.9l25.5 31A12 12 0 0 0 45.15 301l235.22-193.74a12.19 12.19 0 0 1 15.3 0L530.9 301a12 12 0 0 0 16.9-1.6l25.5-31a12 12 0 0 0-1.7-16.93z"></path>
            </svg>
        </a>
        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="text-muted ml-2" height="18px" width="18px" xmlns="http://www.w3.org/2000/svg">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </div>
    <div class="d-flex align-items-center gap-2 ml-1">
        <a class="text-muted text-decoration-none" href="index.php?act=shop">Cửa hàng</a>
    </div>
    <?php if ($category_name != ""): ?>
        <div class="d-flex align-items-center gap-2 ml-1">
            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="text-muted ml-2" height="18px" width="18px" xmlns="http://www.w3.org/2000/svg">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
            <a class="text-muted text-decoration-none pointer-events-none" href="#"><?php echo htmlspecialchars($category_name); ?></a>
        </div>
    <?php endif; ?>
    <?php if ($keyword != ""): ?>
        <div class="d-flex align-items-center gap-2 ml-1">
            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="text-muted ml-2" height="18px" width="18px" xmlns="http://www.w3.org/2000/svg">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
            <a class="text-muted text-decoration-none pointer-events-none" href="#">Tìm kiếm: "<?php echo htmlspecialchars($keyword); ?>"</a>
        </div>
    <?php endif; ?>
</nav>
<!-- end breadcrumbs -->
<div class="shop-section">
    <!-- section -->
    <div class="mb-5 mt-50">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row gx-10">
                <!-- col -->
                <div class="col-lg-3 col-md-4 mb-2 order-2 order-sm-2 order-md-2 order-lg-1">
                    <aside class="offcanvas-lg offcanvas-start" tabindex="-1" id="filter-section" aria-labelledby="navbar-defaultLabel">
                        <div class="offcanvas-header d-lg-none">
                            <h2 class="fw-semibold fs-3">Bộ lọc</h2>
                            <button type="button" class="btn-close" id="filter-section-close"></button>
                        </div>
                        <div class="offcanvas-body p-lg-0">
                            <div class="flex-grow-1">
                                <div class="mb-4 border-bottom pb-3 filter-box-item">
                                <p class="mb-3 filter-title">Danh mục</p>
                                <hr>
                                <div class="mt-4 filter-category">
                                    <ul class="list-unstyled">
                                        <?php
                                        $categories = get_all_categories();
                                        foreach ($categories as $cat) {
                                            $conn = connectdb();
                                            $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE category_id = ? AND status = 1");
                                            $stmt->execute([$cat['id']]);
                                            $count = $stmt->fetchColumn();
                                            $active = ($idcat == $cat['id']) ? 'fw-bold text-primary' : '';
                                            echo '<li><a class="'.$active.'" href="index.php?act=shop&idcat='.$cat['id'].'">'.$cat['name'].'</a><span class="p-count">('.$count.')</span></li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="mb-4 border-bottom pb-3 filter-box-item">
                                <p class="mb-3 filter-title">Lọc theo giá</p>
                                <hr>
                                <div class="mt-4">
                                    <div id="priceRange" class="mb-4"></div>
                                    <small class="text-muted">Giá:</small>
                                    <span id="priceRange-value" class="small"></span>
                                </div>
                            </div>
    
                            <div class="mb-4 border-bottom pb-3 filter-box-item">
                                <!-- Color -->
                                <p class="mb-3 filter-title">Màu sắc</p>
                                <hr>
                                <div class="mt-4">
                                    <div class="form-check mb-2 d-flex justify-content-between">
                                        <div>
                                            <input class="form-check-input" type="checkbox" value="" id="tRed" checked>
                                            <label class="form-check-label" for="tRed">Đỏ</label>
                                        </div>
                                        <span class="p-count">(5)</span>
                                    </div>
                                    <div class="form-check mb-2 d-flex justify-content-between">
                                        <div>
                                            <input class="form-check-input" type="checkbox" value="" id="tBlue">
                                            <label class="form-check-label" for="tBlue">Xanh dương</label>
                                        </div>
                                        <span class="p-count">(2)</span>
                                    </div>
                                    <div class="form-check mb-2 d-flex justify-content-between">
                                        <div>
                                            <input class="form-check-input" type="checkbox" value="" id="tBlack">
                                            <label class="form-check-label" for="tBlack">Đen</label>
                                        </div>
                                        <span class="p-count">(2)</span>
                                    </div>
                                </div>
                            </div>
    
                            <div class="mb-4 border-bottom pb-3 filter-box-item">
                                <!-- Size -->
                                <p class="mb-3 filter-title">Kích thước</p>
                                <hr>
                                <div class="mt-4">
                                <div class="form-check mb-2 d-flex justify-content-between">
                                    <div>
                                        <input class="form-check-input" type="checkbox" value="" id="tSmall" checked>
                                        <label class="form-check-label" for="tSmall">Nhỏ (S)</label>
                                    </div>
                                    <span class="p-count">(2)</span>
                                </div>
                                <div class="form-check mb-2 d-flex justify-content-between">
                                    <div>
                                        <input class="form-check-input" type="checkbox" value="Medium" id="tMedium">
                                        <label class="form-check-label" for="tMedium">Vừa (M)</label>
                                    </div>
                                    <span class="p-count">(2)</span>
                                </div>
                                <div class="form-check mb-2 d-flex justify-content-between">
                                    <div>
                                        <input class="form-check-input" type="checkbox" value="Large" id="tLarge">
                                        <label class="form-check-label" for="tLarge">Lớn (L)</label>
                                    </div>
                                    <span class="p-count">(2)</span>
                                </div>
                                </div>
                            </div>
                            <div class="mb-4 border-bottom pb-3 filter-box-item">
                                <!-- Rating -->
                                <p class="mb-3 filter-title">Đánh giá</p>
                                <hr>
                                <div class="mt-4">
                                    <div class="form-check mb-2 d-flex justify-content-between">
                                        <div>
                                            <input class="form-check-input" type="checkbox" value="" id="rating5">
                                            <label class="form-check-label" for="rating5">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            </label>
                                        </div>
                                        <span class="p-count">(2)</span>
                                    </div>
                                    <div class="form-check mb-2 d-flex justify-content-between">
                                        <div>
                                            <input class="form-check-input" type="checkbox" value="" id="rating4">
                                            <label class="form-check-label" for="rating4">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                            </label>
                                        </div>
                                        <span class="p-count">(2)</span>
                                    </div>
                                    <div class="form-check mb-2 d-flex justify-content-between">
                                        <div>
                                            <input class="form-check-input" type="checkbox" value="" id="rating3">
                                            <label class="form-check-label" for="rating3">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                            </label>
                                        </div>
                                        <span class="p-count">(2)</span>
                                    </div>
                                    <div class="form-check mb-2 d-flex justify-content-between">
                                        <div>
                                            <input class="form-check-input" type="checkbox" value="" id="rating2">
                                            <label class="form-check-label" for="rating2">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                            </label>
                                        </div>
                                        <span class="p-count">(2)</span>
                                    </div>
                                    <div class="form-check mb-2 d-flex justify-content-between">
                                        <div>
                                            <input class="form-check-input" type="checkbox" value="" id="ratingOne">
                                            <label class="form-check-label" for="ratingOne">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                            </label>
                                        </div>
                                        <span class="p-count">(2)</span>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </aside>
                </div>

                <section class="col-lg-9 col-md-12 order-1 order-sm-1 order-md-1 order-lg-2">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-2">
                        <div class="mb-3 mb-md-0">
                            <p class="mb-0">Có <?php echo $total_items; ?> sản phẩm</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="d-lg-none me-2">
                                <a class="btn border px-4 text-muted" type="button" data-bs-toggle="offcanvas" data-bs-target="#filter-section" aria-controls="filter-section" aria-label="Toggle navigation">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter me-2">
                                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                    </svg>
                                    <span class="d-none d-md-inline-block d-lg-inline-block">Bộ lọc</span>
                                </a>
                            </div>
                            <div class="d-flex align-items-center">
                                <label for="sortSelect" class="me-2 mb-0" style="white-space: nowrap;">Sắp xếp theo:</label>
                                <select class="form-select w-auto" id="sortSelect" onchange="window.location.href=this.value;">
                                    <option value="<?= build_shop_url(['sort' => 'newest', 'page' => 1]) ?>" <?= $sort == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                                    <option value="<?= build_shop_url(['sort' => 'popular', 'page' => 1]) ?>" <?= $sort == 'popular' ? 'selected' : '' ?>>Phổ biến nhất</option>
                                    <option value="<?= build_shop_url(['sort' => 'price-low', 'page' => 1]) ?>" <?= $sort == 'price-low' ? 'selected' : '' ?>>Giá: Thấp đến Cao</option>
                                    <option value="<?= build_shop_url(['sort' => 'price-high', 'page' => 1]) ?>" <?= $sort == 'price-high' ? 'selected' : '' ?>>Giá: Cao đến Thấp</option>
                                    <option value="<?= build_shop_url(['sort' => 'rating', 'page' => 1]) ?>" <?= $sort == 'rating' ? 'selected' : '' ?>>Đánh giá khách hàng</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="product mt-4">
                        <div class="row row-cols-xl-3 row-cols-lg-3 row-cols-2 row-cols-md-2 mt-1">
                            <?php
                            foreach ($products as $p) {
                                extract($p);
                                $discount = "";
                                if($old_price > 0 && $old_price > $price){
                                    $discount_percent = round((($old_price - $price) / $old_price) * 100);
                                    $discount = '<span class="discount">-'.$discount_percent.'%</span>';
                                }
                                $detail_url = 'index.php?act=shop-single&id='.$id;
                                echo '<div class="col mb-4">
                                        <div class="product-card">
                                            <div class="product-actions">
                                                <a href="'.$detail_url.'" class="action-btn" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="index.php?act=addwishlist&id='.$id.'" class="action-btn" title="Thêm vào yêu thích">
                                                    <i class="fas fa-heart"></i>
                                                </a>
                                            </div>
                                            <div class="product-img-container">
                                                <a href="'.$detail_url.'">
                                                    <img src="'.$img.'" class="product-img" alt="'.$name.'">
                                                </a>
                                            </div>
                                            <h6><a href="'.$detail_url.'">'.$name.'</a></h6>
                                            <div class="rating mb-2">
                                                <span class="d-flex align-items-center text-warning small">★★★★★</span>
                                            </div>
                                            <div class="price-wrap">
                                                <span class="price">'.number_format($price, 0, ',', '.').' đ</span>
                                                '.($old_price > 0 ? '<span class="old-price text-muted text-decoration-line-through small">'.number_format($old_price, 0, ',', '.').' đ</span>' : '').'
                                                '.$discount.'
                                            </div>
                                            <div class="cart-btn">
                                                '.show_btn_addtocart($id, $name, $img, $price, $quantity).'
                                            </div>
                                        </div>
                                    </div>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row mb-5 my-5">
                        <div class="col-lg-12">
                            <div class="my-4 border rounded d-flex p-3 align-items-center justify-content-between">
                                <?php if ($total_pages > 1): ?>
                                <ul class="d-flex align-items-center list-unstyled mb-0 pagination gap-1">
                                    <!-- Prev -->
                                    <?php if ($page > 1): ?>
                                    <li class="cursor-pointer rounded">
                                        <a class="px-2 py-1 text-muted text-decoration-none" href="<?= build_shop_url(['page' => $page - 1]) ?>">
                                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="20px" width="20px" xmlns="http://www.w3.org/2000/svg">
                                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                                <polyline points="12 19 5 12 12 5"></polyline>
                                            </svg>
                                        </a>
                                    </li>
                                    <?php endif; ?>

                                    <!-- Page numbers -->
                                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="rounded <?= ($i == $page) ? 'bg-primary' : 'cursor-pointer' ?>">
                                        <a href="<?= build_shop_url(['page' => $i]) ?>" class="px-3 py-1 text-decoration-none <?= ($i == $page) ? 'text-white' : 'text-dark' ?>"><?= $i ?></a>
                                    </li>
                                    <?php endfor; ?>

                                    <!-- Next -->
                                    <?php if ($page < $total_pages): ?>
                                    <li class="cursor-pointer rounded">
                                        <a class="px-2 py-1 text-muted text-decoration-none" href="<?= build_shop_url(['page' => $page + 1]) ?>">
                                            <svg class="text-primary" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="20px" width="20px" xmlns="http://www.w3.org/2000/svg">
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                                <polyline points="12 5 19 12 12 19"></polyline>
                                            </svg>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                                <?php endif; ?>
                                
                                <div class="d-none d-lg-flex text-right mr-3">
                                    <?php
                                        $start_item = ($page - 1) * $limit + 1;
                                        $end_item = min($page * $limit, $total_items);
                                        if ($total_items > 0) {
                                            echo "Hiển thị $start_item - $end_item trong số $total_items sản phẩm";
                                        } else {
                                            echo "Không tìm thấy sản phẩm nào";
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
