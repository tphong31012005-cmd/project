<footer class="mt-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-md-0">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-12">
                        <div class="footer_logo">
                            <a href="index.php" class="d-flex align-items-center text-decoration-none">
                                <div class="d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px; background: linear-gradient(135deg, var(--theme-primary), #6366f1); border-radius: 8px; color: #fff; box-shadow: 0 4px 12px rgba(20, 108, 218, 0.25);">
                                    <i class="fas fa-wind" style="font-size: 18px;"></i>
                                </div>
                                <span class="text-white" style="font-size: 26px; font-family: 'Inter', sans-serif; font-weight: 800; letter-spacing: -0.5px;">WindyStore</span>
                            </a>
                        </div>
                        <div class="mt-4">
                            <p>WindyStore cung cấp các sản phẩm công nghệ và thời trang chất lượng cao, mang lại trải nghiệm mua sắm tuyệt vời nhất cho khách hàng.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-12">
                        <div class="footer_newsletter pe-lg-5">
                            <h4 class="text-uppercase">Bản tin</h4>
                            <div class="input-group">
                                <input id="searchInput" class="form-control w-50" type="text"
                                    placeholder="Địa chỉ email của bạn">
                                <button type="button" class="btn btn-primary bg-gradient">Đăng ký</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3 mb-md-0">
                <div class="row">
                    <div class="col-6">
                        <div class="footer_menu">
                            <h4 class="footer_title">Tài khoản</h4>
                            <ul class="m-0 p-0 list-unstyled">
                                <li><a href="#">Đơn hàng</a></li>
                                <li><a href="#">Theo dõi đơn hàng</a></li>
                                <li><a href="#">Quản lý tài khoản</a></li>
                            </ul>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="footer_menu">
                            <h4 class="footer_title">Thông tin</h4>
                            <ul class="m-0 p-0 list-unstyled">
                                <li><a href="index.php?act=about">Về chúng tôi</a></li>
                                <li><a href="#">Chính sách đổi trả</a></li>
                                <li><a href="#">Chính sách bảo mật</a></li>
                                <li><a href="#">Câu hỏi thường gặp</a></li>
                            </ul>


                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="footer_download">
                    <div class="row">
                        <div class="col-lg-6 col-lg-12">
                            <h4 class="footer_title">Liên hệ</h4>
                            <div class="footer_contact">
                                <p>
                                    <span class="icn"><i class="fa-solid fa-location-dot"></i></span>
                                    Số 191, Đường Trần Văn Kiểu, <br>
                            Thành phố Hồ Chí Minh, Việt Nam <br>
                                </p>
                                <p class="phn">
                                    <span class="icn"><i class="fa-solid fa-phone"></i></span>
                                    0866462647
                                </p>
                                <p class="eml">
                                    <span class="icn"><i class="fa-solid fa-envelope"></i></span>
                                    support@WindyStore.vn
                                </p>
                            </div>
                        </div>
                        <div class="footer_social col-lg-6 col-lg-12">
                            <div class="footer_icon d-flex mt-1">
                                <a href="#" class="facebook pe-3"><i class="fa-brands fa-facebook"></i></a>
                                <a href="#" class="twitter pe-3"><i class="fa-brands fa-x-twitter"></i></a>
                                <a href="#" class="instagram pe-3"><i class="fa-brands fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="./assets/js/jquery-3.6.0.min.js"></script>
<script src="./assets/js/bootstrap.bundle.min.js"></script>
<script src="./assets/plugin/nice-select/jquery.nice-select.min.js"></script>
<script src="./assets/plugin/OwlCarousel2-2.3.4/dist/owl.carousel.min.js"></script>
<script src="./assets/plugin/nouislider/nouislider.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script src="./assets/js/main.js"></script>
<script>
$(document).ready(function() {
    console.log("Live Search Dropdown loaded!");
    var searchTimeout = null;
    $(document).on('input', '.header-search', function () {
        var $input = $(this);
        var $container = $input.closest('.search-container');
        var $resultsDropdown = $container.find('.search-dropdown-results');
        
        // Get the selected category
        var $categorySelect = $container.find('select[name="idcat"]');
        var category = $categorySelect.length ? $categorySelect.find('option:selected').text().trim() : '';
        
        var query = $input.val().trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            $resultsDropdown.addClass('d-none').html('');
            return;
        }
        
        searchTimeout = setTimeout(function () {
            $.ajax({
                url: 'api/search.php',
                type: 'GET',
                data: {
                    q: query,
                    category: category
                },
                dataType: 'json',
                success: function (data) {
                    if (data && data.length > 0) {
                        var html = '';
                        data.forEach(function (product) {
                            var priceFormatted = '$' + parseFloat(product.price).toFixed(2);
                            var oldPriceHtml = '';
                            if (product.old_price && parseFloat(product.old_price) > parseFloat(product.price)) {
                                oldPriceHtml = '<span class="search-dropdown-old-price">$' + parseFloat(product.old_price).toFixed(2) + '</span>';
                            }
                            
                            var productJsonEscaped = encodeURIComponent(JSON.stringify(product));
                            
                            html += '<a href="#" class="search-dropdown-item" data-product="' + productJsonEscaped + '">';
                            html += '  <img src="' + product.img + '" class="search-dropdown-img" width="50" height="50" style="object-fit: cover;" alt="' + product.name + '">';
                            html += '  <div class="search-dropdown-info">';
                            html += '    <h6 class="search-dropdown-name">' + product.name + '</h6>';
                            html += '    <div class="search-dropdown-price-wrap">';
                            html += '      <span class="search-dropdown-price">' + priceFormatted + '</span>';
                            html += '      ' + oldPriceHtml;
                            html += '    </div>';
                            html += '  </div>';
                            html += '</a>';
                        });
                        $resultsDropdown.html(html).removeClass('d-none');
                    } else {
                        $resultsDropdown.html('<div class="search-dropdown-no-results">Không tìm thấy sản phẩm nào</div>').removeClass('d-none');
                    }
                },
                error: function () {
                    $resultsDropdown.addClass('d-none').html('');
                }
            });
        }, 300);
    });
    
    // Close dropdown on click outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.search-container').length) {
            $('.search-dropdown-results').addClass('d-none');
        }
    });
    
    // Re-open dropdown when clicking search input if there are results
    $(document).on('focus', '.header-search', function () {
        var $dropdown = $(this).closest('.search-container').find('.search-dropdown-results');
        if ($dropdown.children().length > 0) {
            $dropdown.removeClass('d-none');
        }
    });

    // Handle click on dropdown product to open Quick View Modal
    $(document).on('click', '.search-dropdown-item', function (e) {
        e.preventDefault();
        var productJsonEscaped = $(this).attr('data-product');
        if (!productJsonEscaped) return;
        
        var product = JSON.parse(decodeURIComponent(productJsonEscaped));
        $('.search-dropdown-results').addClass('d-none');
        
        var descriptionHtml = product.description ? '<p class="product-description text-muted small mt-2">' + product.description + '</p>' : '<p class="product-description text-muted small mt-2">Chưa có mô tả chi tiết cho sản phẩm này.</p>';
        var oldPriceHtml = '';
        if (product.old_price && parseFloat(product.old_price) > parseFloat(product.price)) {
            oldPriceHtml = '<span class="old-price text-muted text-decoration-line-through me-2">$' + parseFloat(product.old_price).toFixed(2) + '</span>';
        }
        
        var modalHtml = `
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0 d-flex align-items-center justify-content-center">
                    <img src="${product.img}" class="img-fluid rounded border shadow-sm" style="max-height: 350px; object-fit: cover;" alt="${product.name}">
                </div>
                <div class="col-md-6 d-flex flex-column justify-content-between">
                    <div>
                        <span class="badge bg-primary mb-2 text-white">${product.cat_name || 'Sản phẩm'}</span>
                        <h4 class="fw-bold mb-2 text-dark-theme-compat">${product.name}</h4>
                        <div class="rating text-warning small mb-3">★★★★★</div>
                        <div class="price-wrap mb-3 d-flex align-items-baseline">
                            ${oldPriceHtml}
                            <span class="price fs-4 fw-bold text-primary">$${parseFloat(product.price).toFixed(2)}</span>
                        </div>
                        <hr class="my-2">
                        <h6 class="fw-semibold mt-3 text-dark-theme-compat">Mô tả sản phẩm:</h6>
                        ${descriptionHtml}
                    </div>
                    <div class="mt-4">
                        <form action="index.php?act=addtocart" method="post">
                            <input type="hidden" name="id" value="${product.id}">
                            <input type="hidden" name="name" value="${product.name}">
                            <input type="hidden" name="img" value="${product.img}">
                            <input type="hidden" name="price" value="${product.price}">
                            <button type="submit" name="addtocart" value="add" class="btn btn-primary btn-lg w-100 rounded-0 text-white"><i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ hàng</button>
                        </form>
                    </div>
                </div>
            </div>
        `;
        
        $('#quickViewModalBody').html(modalHtml);
        var quickViewModal = new bootstrap.Modal(document.getElementById('productQuickViewModal'));
        quickViewModal.show();
    });
});
</script>
</body>


</html>
