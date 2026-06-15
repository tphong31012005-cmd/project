<?php
// Script to add 152 products and download images from Bing Images search
set_time_limit(0); // No time limit for downloads
ob_implicit_flush(true);
if (ob_get_level() > 0) ob_end_flush();

include "model/connectdb.php";
$conn = connectdb();
if (!$conn) {
    die("Database connection failed\n");
}

echo "Database connected.\n";

// Define 152 products (4 per category for 38 categories)
$new_products = [
    // 1. Phụ kiện Laptop
    ['category_id' => 1, 'name' => 'Giá đỡ laptop nhôm Orico LST-S1', 'price' => 24.99, 'old_price' => 29.99, 'view' => 45],
    ['category_id' => 1, 'name' => 'Đế tản nhiệt laptop Cooler Master L2', 'price' => 18.50, 'old_price' => 22.00, 'view' => 38],
    ['category_id' => 1, 'name' => 'Túi chống sốc laptop Rivacase 14 inch', 'price' => 19.99, 'old_price' => 25.00, 'view' => 52],
    ['category_id' => 1, 'name' => 'Đầu chuyển HyperDrive USB-C Hub 6-in-1', 'price' => 69.99, 'old_price' => 79.99, 'view' => 61],

    // 2. Phụ kiện điện thoại
    ['category_id' => 2, 'name' => 'Kính cường lực MIPOW Kingbull iPhone 15 Pro Max', 'price' => 15.99, 'old_price' => 19.99, 'view' => 95],
    ['category_id' => 2, 'name' => 'Gậy chụp ảnh Selfie Tripod Xiaomi XMZPG05YM', 'price' => 14.50, 'old_price' => 18.00, 'view' => 70],
    ['category_id' => 2, 'name' => 'Giá đỡ điện thoại để bàn Baseus Metal', 'price' => 9.99, 'old_price' => 12.50, 'view' => 40],
    ['category_id' => 2, 'name' => 'Miếng dán PPF Full viền iPhone 14 Pro', 'price' => 7.50, 'old_price' => 9.99, 'view' => 33],

    // 3. Phụ kiện máy ảnh
    ['category_id' => 3, 'name' => 'Túi máy ảnh Peak Design Everyday Sling 6L', 'price' => 119.99, 'old_price' => 129.99, 'view' => 88],
    ['category_id' => 3, 'name' => 'Kính lọc Hoya HMC UV 58mm', 'price' => 22.00, 'old_price' => 28.00, 'view' => 25],
    ['category_id' => 3, 'name' => 'Bộ vệ sinh máy ảnh chuyên nghiệp VSGO', 'price' => 15.00, 'old_price' => 18.50, 'view' => 46],
    ['category_id' => 3, 'name' => 'Hộp chống ẩm máy ảnh Wonderful 20L', 'price' => 45.00, 'old_price' => 55.00, 'view' => 31],

    // 4. Trò chơi
    ['category_id' => 4, 'name' => 'Đĩa game Marvel\'s Spider-Man 2 PS5', 'price' => 69.99, 'old_price' => NULL, 'view' => 150],
    ['category_id' => 4, 'name' => 'Đĩa game Elden Ring PS5', 'price' => 59.99, 'old_price' => NULL, 'view' => 120],
    ['category_id' => 4, 'name' => 'Máy chơi game cầm tay ASUS ROG Ally Z1 Extreme', 'price' => 699.99, 'old_price' => 749.99, 'view' => 205],
    ['category_id' => 4, 'name' => 'Bộ điều khiển Nintendo Switch Joy-Con', 'price' => 79.99, 'old_price' => 85.00, 'view' => 98],

    // 5. Thiết bị đeo thông minh
    ['category_id' => 5, 'name' => 'Đồng hồ thông minh Apple Watch Ultra 2 GPS Cellular', 'price' => 799.00, 'old_price' => 849.00, 'view' => 180],
    ['category_id' => 5, 'name' => 'Vòng đeo tay thông minh Huawei Band 8', 'price' => 39.99, 'old_price' => 49.99, 'view' => 115],
    ['category_id' => 5, 'name' => 'Đồng hồ thông minh Samsung Galaxy Watch 6 44mm', 'price' => 299.99, 'old_price' => 329.99, 'view' => 140],
    ['category_id' => 5, 'name' => 'Đồng hồ GPS chạy bộ Garmin Venu 3', 'price' => 449.99, 'old_price' => NULL, 'view' => 89],

    // 6. Nhà thông minh
    ['category_id' => 6, 'name' => 'Cảm biến cửa thông minh Xiaomi Door Sensor 2', 'price' => 8.99, 'old_price' => 12.00, 'view' => 67],
    ['category_id' => 6, 'name' => 'Động cơ rèm cửa thông minh Aqara Curtain Controller', 'price' => 89.00, 'old_price' => 99.00, 'view' => 45],
    ['category_id' => 6, 'name' => 'Đèn ốp trần thông minh Yeelight Arwen 550C', 'price' => 79.99, 'old_price' => 89.99, 'view' => 54],
    ['category_id' => 6, 'name' => 'Khoá cửa thông minh Xiaomi Smart Door Lock E10', 'price' => 149.00, 'old_price' => 169.00, 'view' => 112],

    // 7. Máy tính bảng
    ['category_id' => 7, 'name' => 'Máy tính bảng iPad Air 5 M1 64GB Wifi', 'price' => 599.00, 'old_price' => 649.00, 'view' => 156],
    ['category_id' => 7, 'name' => 'Máy tính bảng Samsung Galaxy Tab S9 FE 128GB', 'price' => 449.00, 'old_price' => 499.00, 'view' => 98],
    ['category_id' => 7, 'name' => 'Máy tính bảng Xiaomi Pad 6 8GB 128GB', 'price' => 329.00, 'old_price' => 369.00, 'view' => 124],
    ['category_id' => 7, 'name' => 'Máy tính bảng iPad Pro 12.9 M2 128GB Wifi', 'price' => 1099.00, 'old_price' => 1199.00, 'view' => 142],

    // 8. Chuột
    ['category_id' => 8, 'name' => 'Chuột không dây Logitech Pebble M350', 'price' => 19.99, 'old_price' => 24.99, 'view' => 110],
    ['category_id' => 8, 'name' => 'Chuột gaming Razer Basilisk V3', 'price' => 59.99, 'old_price' => 69.99, 'view' => 135],
    ['category_id' => 8, 'name' => 'Chuột công thái học Logitech MX Master 3S', 'price' => 99.99, 'old_price' => 109.99, 'view' => 198],
    ['category_id' => 8, 'name' => 'Chuột không dây Corsair Harpoon RGB', 'price' => 29.99, 'old_price' => 35.00, 'view' => 74],

    // 9. Bàn phím
    ['category_id' => 9, 'name' => 'Bàn phím cơ ASUS ROG Strix Scope RX', 'price' => 129.99, 'old_price' => 149.99, 'view' => 87],
    ['category_id' => 9, 'name' => 'Bàn phím không dây Logitech MX Keys S', 'price' => 109.99, 'old_price' => 119.99, 'view' => 143],
    ['category_id' => 9, 'name' => 'Bàn phím cơ Keychron K2 Pro QMK/VIA', 'price' => 99.00, 'old_price' => 109.00, 'view' => 165],
    ['category_id' => 9, 'name' => 'Bàn phím không dây Rapoo E9050G Multi-mode', 'price' => 29.99, 'old_price' => 35.99, 'view' => 45],

    // 10. USB
    ['category_id' => 10, 'name' => 'USB 3.2 Kingston DataTraveler Exodia 64GB', 'price' => 6.99, 'old_price' => 8.99, 'view' => 52],
    ['category_id' => 10, 'name' => 'USB 3.1 Sandisk Ultra Dual Drive Go 128GB', 'price' => 14.99, 'old_price' => 18.00, 'view' => 81],
    ['category_id' => 10, 'name' => 'USB 3.2 Samsung Bar Plus 64GB Titan Gray', 'price' => 12.99, 'old_price' => 15.99, 'view' => 63],
    ['category_id' => 10, 'name' => 'USB Type-C Transcend JetFlash 930C 128GB', 'price' => 22.50, 'old_price' => 27.00, 'view' => 39],

    // 11. Thẻ nhớ
    ['category_id' => 11, 'name' => 'Thẻ nhớ MicroSD Sandisk Extreme Pro 128GB', 'price' => 21.99, 'old_price' => 26.99, 'view' => 92],
    ['category_id' => 11, 'name' => 'Thẻ nhớ MicroSD Samsung PRO Plus 256GB', 'price' => 34.99, 'old_price' => 39.99, 'view' => 74],
    ['category_id' => 11, 'name' => 'Thẻ nhớ SD SanDisk Extreme Pro 64GB 200MB/s', 'price' => 18.50, 'old_price' => 22.00, 'view' => 41],
    ['category_id' => 11, 'name' => 'Thẻ nhớ MicroSD Kingston Canvas Go Plus 64GB', 'price' => 11.99, 'old_price' => 14.99, 'view' => 33],

    // 12. Router
    ['category_id' => 12, 'name' => 'Router Wifi 6 ASUS RT-AX53U Chuẩn AX1800', 'price' => 59.99, 'old_price' => 69.99, 'view' => 84],
    ['category_id' => 12, 'name' => 'Bộ phát Wifi Mesh TP-Link Deco M4 (3-Pack)', 'price' => 109.00, 'old_price' => 129.00, 'view' => 96],
    ['category_id' => 12, 'name' => 'Router Wifi Xiaomi 4A Gigabit Edition', 'price' => 24.50, 'old_price' => 29.99, 'view' => 110],
    ['category_id' => 12, 'name' => 'Router Wifi 6 Linksys Atlas Pro 6 MX5501', 'price' => 129.00, 'old_price' => 149.00, 'view' => 38],

    // 13. Pin
    ['category_id' => 13, 'name' => 'Pin sạc AA Panasonic Eneloop Pro 2500mAh (4 viên)', 'price' => 22.99, 'old_price' => 27.99, 'view' => 115],
    ['category_id' => 13, 'name' => 'Pin tiểu AA Energizer Max (vỉ 4 viên)', 'price' => 3.50, 'old_price' => 4.50, 'view' => 88],
    ['category_id' => 13, 'name' => 'Pin sạc AAA GP ReCyko 800mAh (4 viên)', 'price' => 12.50, 'old_price' => 15.00, 'view' => 47],
    ['category_id' => 13, 'name' => 'Pin nút áo CR2025 Maxell (vỉ 5 viên)', 'price' => 2.99, 'old_price' => 3.99, 'view' => 35],

    // 14. Bộ sạc
    ['category_id' => 14, 'name' => 'Bộ sạc nhanh iPhone Anker Nano II 30W', 'price' => 24.99, 'old_price' => 29.99, 'view' => 165],
    ['category_id' => 14, 'name' => 'Đế sạc không dây 3 in 1 Mophie Snap Plus', 'price' => 79.99, 'old_price' => 89.99, 'view' => 54],
    ['category_id' => 14, 'name' => 'Bộ sạc nhanh Samsung 45W kèm cáp 5A', 'price' => 32.00, 'old_price' => 38.00, 'view' => 132],
    ['category_id' => 14, 'name' => 'Trạm sạc không dây Belkin BoostCharge 3-in-1', 'price' => 119.99, 'old_price' => 139.99, 'view' => 78],

    // 15. Bộ lưu điện (UPS)
    ['category_id' => 15, 'name' => 'Bộ lưu điện UPS APC Back-UPS Pro 1500VA', 'price' => 249.00, 'old_price' => 279.00, 'view' => 43],
    ['category_id' => 15, 'name' => 'Bộ lưu điện UPS CyberPower UT1500EG 1500VA', 'price' => 119.00, 'old_price' => 135.00, 'view' => 36],
    ['category_id' => 15, 'name' => 'Bộ lưu điện UPS Santak TG500 500VA', 'price' => 49.00, 'old_price' => 55.00, 'view' => 52],
    ['category_id' => 15, 'name' => 'Bộ lưu điện UPS Prolink PRO851SFC 850VA', 'price' => 59.00, 'old_price' => 69.00, 'view' => 28],

    // 16. Kính cường lực
    ['category_id' => 16, 'name' => 'Kính cường lực Samsung S24 Ultra Spigen EZ Fit', 'price' => 19.99, 'old_price' => 24.99, 'view' => 87],
    ['category_id' => 16, 'name' => 'Kính cường lực iPhone 15 Pro Max JCPAL Preserver', 'price' => 14.99, 'old_price' => 18.00, 'view' => 110],
    ['category_id' => 16, 'name' => 'Kính cường lực iPad Pro 11 inch ESR Tempered Glass', 'price' => 16.99, 'old_price' => 19.99, 'view' => 45],
    ['category_id' => 16, 'name' => 'Kính cường lực Apple Watch Series 9 Zeelot', 'price' => 12.50, 'old_price' => 15.00, 'view' => 39],

    // 17. Ốp lưng
    ['category_id' => 17, 'name' => 'Ốp lưng iPhone 15 Pro Max UAG Monarch Kevlar', 'price' => 79.99, 'old_price' => 89.99, 'view' => 142],
    ['category_id' => 17, 'name' => 'Ốp lưng Samsung S23 Ultra Spigen Optik Armor', 'price' => 34.99, 'old_price' => 39.99, 'view' => 68],
    ['category_id' => 17, 'name' => 'Ốp lưng iPhone 14 Pro Ringke Fusion-X chống va đập', 'price' => 14.99, 'old_price' => 18.00, 'view' => 83],
    ['category_id' => 17, 'name' => 'Ốp lưng iPhone 13 Pro Max ESR Cloud Soft Silicone', 'price' => 12.99, 'old_price' => 15.99, 'view' => 51],

    // 18. Cáp sạc
    ['category_id' => 18, 'name' => 'Cáp sạc Type-C sang Lightning Apple 1m', 'price' => 19.00, 'old_price' => NULL, 'view' => 145],
    ['category_id' => 18, 'name' => 'Cáp sạc nhanh USB-C sang USB-C Baseus 100W bọc dù', 'price' => 8.99, 'old_price' => 12.00, 'view' => 122],
    ['category_id' => 18, 'name' => 'Cáp sạc Anker PowerLine III USB-C sang Lightning 0.9m', 'price' => 14.99, 'old_price' => 18.00, 'view' => 95],
    ['category_id' => 18, 'name' => 'Cáp sạc nhanh 3 trong 1 Baseus Superior 1.5m', 'price' => 10.99, 'old_price' => 13.99, 'view' => 74],

    // 19. Củ sạc
    ['category_id' => 19, 'name' => 'Củ sạc GaN Ugreen Nexode 100W 4 cổng', 'price' => 49.99, 'old_price' => 59.99, 'view' => 118],
    ['category_id' => 19, 'name' => 'Củ sạc nhanh Apple USB-C 20W chính hãng', 'price' => 19.00, 'old_price' => NULL, 'view' => 250],
    ['category_id' => 19, 'name' => 'Củ sạc GaN Baseus Lite 65W 3 cổng', 'price' => 29.99, 'old_price' => 35.99, 'view' => 96],
    ['category_id' => 19, 'name' => 'Củ sạc Anker 312 25W Type-C sạc nhanh', 'price' => 12.99, 'old_price' => 15.99, 'view' => 85],

    // 20. Ống kính rời
    ['category_id' => 20, 'name' => 'Ống kính Sony FE 24-70mm f/2.8 GM II', 'price' => 2299.00, 'old_price' => 2399.00, 'view' => 94],
    ['category_id' => 20, 'name' => 'Ống kính Canon RF 85mm f/1.2L USM', 'price' => 2799.00, 'old_price' => 2899.00, 'view' => 61],
    ['category_id' => 20, 'name' => 'Ống kính Fujifilm XF 35mm f/1.4 R', 'price' => 599.00, 'old_price' => 649.00, 'view' => 125],
    ['category_id' => 20, 'name' => 'Ống kính Sigma 30mm f/1.4 DC DN for Sony E', 'price' => 289.00, 'old_price' => 329.00, 'view' => 143],

    // 21. Đèn Flash
    ['category_id' => 21, 'name' => 'Đèn Flash Godox V1 cho Sony', 'price' => 259.00, 'old_price' => 279.00, 'view' => 78],
    ['category_id' => 21, 'name' => 'Đèn Flash Yongnuo YN685 II cho Canon', 'price' => 119.00, 'old_price' => 135.00, 'view' => 45],
    ['category_id' => 21, 'name' => 'Đèn Flash Studio Godox MS300-V', 'price' => 109.00, 'old_price' => 119.00, 'view' => 32],
    ['category_id' => 21, 'name' => 'Đèn Flash Godox TT685II cho Nikon', 'price' => 129.00, 'old_price' => 139.00, 'view' => 53],

    // 22. Sạc dự phòng
    ['category_id' => 22, 'name' => 'Sạc dự phòng Anker 537 24000mAh 65W', 'price' => 89.99, 'old_price' => 99.99, 'view' => 134],
    ['category_id' => 22, 'name' => 'Sạc dự phòng MagSafe Baseus Magnetic 10000mAh', 'price' => 29.99, 'old_price' => 35.99, 'view' => 156],
    ['category_id' => 22, 'name' => 'Sạc dự phòng Xiaomi Redmi 20000mAh 18W', 'price' => 19.99, 'old_price' => 24.99, 'view' => 220],
    ['category_id' => 22, 'name' => 'Sạc dự phòng Energizer 20000mAh UE20011PQ', 'price' => 24.99, 'old_price' => 29.99, 'view' => 87],

    // 23. OTG USB
    ['category_id' => 23, 'name' => 'Đầu chuyển OTG USB-C sang USB 3.0 Apple', 'price' => 19.00, 'old_price' => NULL, 'view' => 96],
    ['category_id' => 23, 'name' => 'Đầu chuyển OTG Lightning sang USB Camera Apple', 'price' => 29.00, 'old_price' => NULL, 'view' => 84],
    ['category_id' => 23, 'name' => 'Đầu chuyển OTG USB-C sang USB-A Baseus', 'price' => 4.99, 'old_price' => 6.99, 'view' => 112],
    ['category_id' => 23, 'name' => 'Cáp OTG Micro USB sang USB-A Ugreen', 'price' => 3.99, 'old_price' => 5.00, 'view' => 45],

    // 24. Máy ảnh lấy liền
    ['category_id' => 24, 'name' => 'Máy ảnh lấy liền Fujifilm Instax Mini 12', 'price' => 79.00, 'old_price' => 89.00, 'view' => 134],
    ['category_id' => 24, 'name' => 'Máy ảnh lấy liền Fujifilm Instax Square SQ40', 'price' => 149.00, 'old_price' => NULL, 'view' => 58],
    ['category_id' => 24, 'name' => 'Máy ảnh lấy liền Polaroid Go Generation 2', 'price' => 99.99, 'old_price' => 109.99, 'view' => 71],
    ['category_id' => 24, 'name' => 'Máy ảnh lấy liền Fujifilm Instax Wide 300', 'price' => 129.00, 'old_price' => 139.00, 'view' => 43],

    // 25. Flycam
    ['category_id' => 25, 'name' => 'Flycam DJI Mini 4 Pro Fly More Combo', 'price' => 1099.00, 'old_price' => 1159.00, 'view' => 245],
    ['category_id' => 25, 'name' => 'Flycam DJI Air 3 Fly More Combo RC 2', 'price' => 1549.00, 'old_price' => NULL, 'view' => 135],
    ['category_id' => 25, 'name' => 'Flycam DJI Mavic 3 Pro Cine Premium Combo', 'price' => 4799.00, 'old_price' => 4999.00, 'view' => 84],
    ['category_id' => 25, 'name' => 'Flycam mini Hubsan Zino Mini Pro 64GB', 'price' => 499.00, 'old_price' => 549.00, 'view' => 61],

    // 26. DSLR
    ['category_id' => 26, 'name' => 'Máy ảnh DSLR Canon EOS 90D Body', 'price' => 1199.00, 'old_price' => 1299.00, 'view' => 95],
    ['category_id' => 26, 'name' => 'Máy ảnh DSLR Nikon D850 Body', 'price' => 2799.00, 'old_price' => 2999.00, 'view' => 84],
    ['category_id' => 26, 'name' => 'Máy ảnh DSLR Canon EOS 250D Kit 18-55mm IS STM', 'price' => 549.00, 'old_price' => 599.00, 'view' => 112],
    ['category_id' => 26, 'name' => 'Máy ảnh DSLR Pentax K-3 Mark III Body', 'price' => 1999.00, 'old_price' => NULL, 'view' => 38],

    // 27. Action Cam
    ['category_id' => 27, 'name' => 'Máy quay hành trình GoPro Hero 12 Black', 'price' => 399.99, 'old_price' => 449.99, 'view' => 280],
    ['category_id' => 27, 'name' => 'Máy quay hành trình DJI Osmo Action 4 Combo', 'price' => 329.00, 'old_price' => 399.00, 'view' => 189],
    ['category_id' => 27, 'name' => 'Máy quay hành trình Insta360 Ace Pro 8K', 'price' => 449.99, 'old_price' => NULL, 'view' => 142],
    ['category_id' => 27, 'name' => 'Máy quay hành trình GoPro Hero 11 Black Mini', 'price' => 299.99, 'old_price' => 349.99, 'view' => 95],

    // 28. Ống kính
    ['category_id' => 28, 'name' => 'Ống kính Sony E PZ 18-105mm f/4 G OSS', 'price' => 599.00, 'old_price' => 649.00, 'view' => 84],
    ['category_id' => 28, 'name' => 'Ống kính Canon EF 50mm f/1.4 USM', 'price' => 349.00, 'old_price' => 399.00, 'view' => 112],
    ['category_id' => 28, 'name' => 'Ống kính Nikon AF-S DX 35mm f/1.8G', 'price' => 199.00, 'old_price' => 219.00, 'view' => 135],
    ['category_id' => 28, 'name' => 'Ống kính Tamron 17-70mm f/2.8 Di III-A VC RXD', 'price' => 799.00, 'old_price' => 849.00, 'view' => 98],

    // 29. Chân máy
    ['category_id' => 29, 'name' => 'Chân máy ảnh Peak Design Travel Tripod (Nhôm)', 'price' => 349.99, 'old_price' => 379.99, 'view' => 112],
    ['category_id' => 29, 'name' => 'Chân máy ảnh Manfrotto Compact Light', 'price' => 69.99, 'old_price' => 79.99, 'view' => 78],
    ['category_id' => 29, 'name' => 'Chân máy ảnh Benro System Go Plus FGP18A', 'price' => 149.00, 'old_price' => 169.00, 'view' => 52],
    ['category_id' => 29, 'name' => 'Chân máy bạch tuộc Joby GorillaPod 5K Kit', 'price' => 119.00, 'old_price' => 135.00, 'view' => 84],

    // 30. Máy ảnh du lịch
    ['category_id' => 30, 'name' => 'Máy ảnh Sony Cyber-shot DSC-RX100 VII', 'price' => 1299.00, 'old_price' => NULL, 'view' => 154],
    ['category_id' => 30, 'name' => 'Máy ảnh Fujifilm X100VI Mirrorless', 'price' => 1599.00, 'old_price' => 1799.00, 'view' => 310],
    ['category_id' => 30, 'name' => 'Máy ảnh Canon PowerShot G7 X Mark III', 'price' => 749.00, 'old_price' => 799.00, 'view' => 143],
    ['category_id' => 30, 'name' => 'Máy ảnh du lịch Ricoh GR IIIx Street Edition', 'price' => 1049.00, 'old_price' => NULL, 'view' => 120],

    // 31. Tai nghe
    ['category_id' => 31, 'name' => 'Tai nghe True Wireless Apple AirPods Pro 2 USB-C', 'price' => 249.00, 'old_price' => 279.00, 'view' => 295],
    ['category_id' => 31, 'name' => 'Tai nghe không dây chống ồn Sony WH-1000XM4', 'price' => 279.00, 'old_price' => 349.00, 'view' => 210],
    ['category_id' => 31, 'name' => 'Tai nghe Gaming Razer BlackShark V2 X', 'price' => 49.99, 'old_price' => 59.99, 'view' => 134],
    ['category_id' => 31, 'name' => 'Tai nghe truyền dẫn xương Shokz OpenRun Pro', 'price' => 179.95, 'old_price' => NULL, 'view' => 64],

    // 32. Loa
    ['category_id' => 32, 'name' => 'Loa Bluetooth Marshall Tufton Black and Brass', 'price' => 449.00, 'old_price' => 499.00, 'view' => 154],
    ['category_id' => 32, 'name' => 'Loa Bluetooth di động JBL Charge 5', 'price' => 139.00, 'old_price' => 179.00, 'view' => 189],
    ['category_id' => 32, 'name' => 'Loa kéo karaoke Dalton TS-15G600X công suất lớn', 'price' => 299.00, 'old_price' => 349.00, 'view' => 78],
    ['category_id' => 32, 'name' => 'Loa Bluetooth Sony SRS-XE200 chống nước', 'price' => 99.00, 'old_price' => 129.00, 'view' => 112],

    // 33. Đồng hồ
    ['category_id' => 33, 'name' => 'Đồng hồ Casio G-Shock GA-2100-1A1DR chính hãng', 'price' => 99.00, 'old_price' => 115.00, 'view' => 165],
    ['category_id' => 33, 'name' => 'Đồng hồ nam Tissot Le Locle Powermatic 80', 'price' => 650.00, 'old_price' => 720.00, 'view' => 94],
    ['category_id' => 33, 'name' => 'Đồng hồ nam Seiko 5 Sports SRPD55K1', 'price' => 249.00, 'old_price' => 279.00, 'view' => 123],
    ['category_id' => 33, 'name' => 'Đồng hồ Citizen C7 NH8390-20L xanh dương', 'price' => 220.00, 'old_price' => 250.00, 'view' => 87],

    // 34. Thiết bị mạng
    ['category_id' => 34, 'name' => 'Switch chia mạng 8 cổng Gigabit TP-Link SG1008D', 'price' => 19.99, 'old_price' => 24.99, 'view' => 64],
    ['category_id' => 34, 'name' => 'Thiết bị cân bằng tải Router MikroTik hEX gr3', 'price' => 59.00, 'old_price' => 69.00, 'view' => 112],
    ['category_id' => 34, 'name' => 'Card mạng không dây PCI-E Asus PCE-AX58BT Wifi 6', 'price' => 49.99, 'old_price' => 59.99, 'view' => 54],
    ['category_id' => 34, 'name' => 'Bộ chia mạng Switch 5 cổng Gigabit D-Link DGS-1005A', 'price' => 12.99, 'old_price' => 15.99, 'view' => 38],

    // 35. Màn hình
    ['category_id' => 35, 'name' => 'Màn hình Dell UltraSharp U2422H 23.8 inch IPS', 'price' => 259.00, 'old_price' => 299.00, 'view' => 189],
    ['category_id' => 35, 'name' => 'Màn hình Gaming ASUS ROG Strix XG27AQ 27 inch 170Hz', 'price' => 399.00, 'old_price' => 449.00, 'view' => 143],
    ['category_id' => 35, 'name' => 'Màn hình đồ hoạ LG 27UP850N-W 27 inch IPS 4K', 'price' => 349.00, 'old_price' => 399.00, 'view' => 115],
    ['category_id' => 35, 'name' => 'Màn hình Samsung Odyssey G5 32 inch 2K 144Hz cong', 'price' => 279.00, 'old_price' => 319.00, 'view' => 96],

    // 36. Linh kiện
    ['category_id' => 36, 'name' => 'Card màn hình ASUS ROG Strix RTX 4070 Ti Super 16GB', 'price' => 899.00, 'old_price' => 949.00, 'view' => 167],
    ['category_id' => 36, 'name' => 'Bộ vi xử lý Intel Core i7-14700K chính hãng', 'price' => 409.00, 'old_price' => 439.00, 'view' => 124],
    ['category_id' => 36, 'name' => 'Ổ cứng SSD Samsung 990 Pro 1TB PCIe NVMe M.2', 'price' => 119.00, 'old_price' => 139.00, 'view' => 195],
    ['category_id' => 36, 'name' => 'Ram PC Corsair Vengeance RGB DDR5 32GB (2x16GB) 6000MHz', 'price' => 129.00, 'old_price' => 149.00, 'view' => 143],

    // 37. Phần mềm
    ['category_id' => 37, 'name' => 'Phần mềm diệt virus Kaspersky Total Security (1 PC/1 năm)', 'price' => 12.50, 'old_price' => 15.00, 'view' => 84],
    ['category_id' => 37, 'name' => 'Bản quyền Microsoft Office Home and Business 2021', 'price' => 199.00, 'old_price' => NULL, 'view' => 96],
    ['category_id' => 37, 'name' => 'Bản quyền Windows 11 Pro 64-bit FPP chính hãng', 'price' => 139.00, 'old_price' => 159.00, 'view' => 112],
    ['category_id' => 37, 'name' => 'Bản quyền Adobe Creative Cloud All Apps (1 năm)', 'price' => 599.00, 'old_price' => 649.00, 'view' => 54],

    // 38. Dịch vụ
    ['category_id' => 38, 'name' => 'Dịch vụ thay keo tản nhiệt và vệ sinh PC tại nhà', 'price' => 15.00, 'old_price' => 20.00, 'view' => 112],
    ['category_id' => 38, 'name' => 'Dịch vụ sửa bản lề laptop hư hỏng nặng lấy liền', 'price' => 25.00, 'old_price' => 30.00, 'view' => 95],
    ['category_id' => 38, 'name' => 'Dịch vụ lắp đặt và cài đặt hệ thống camera giám sát', 'price' => 99.00, 'old_price' => 120.00, 'view' => 67],
    ['category_id' => 38, 'name' => 'Dịch vụ cứu dữ liệu ổ cứng SSD/HDD lỗi cơ học', 'price' => 149.00, 'old_price' => NULL, 'view' => 48]
];

// Helper to fetch category name mapping to generate high-quality product description
$categories = [];
$res = get_all("SELECT id, name FROM categories");
foreach ($res as $row) {
    $categories[$row['id']] = $row['name'];
}

function search_and_download_image($query, $filepath) {
    // Escape query for url
    $url = "https://www.bing.com/images/search?q=" . urlencode($query);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $html = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 200 && $html) {
        // Find image urls from Bing Images format: murl&quot;:&quot;(http[^&]+)&quot;
        if (preg_match_all('/murl&quot;:&quot;(http[^&]+)&quot;/', $html, $matches)) {
            $img_urls = array_slice($matches[1], 0, 5); // Take first 5 to try
            foreach ($img_urls as $img_url) {
                if (filter_var($img_url, FILTER_VALIDATE_URL)) {
                    // Try to download
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $img_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36");
                    $img_data = curl_exec($ch);
                    $img_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    
                    if ($img_http_code == 200 && $img_data && strlen($img_data) > 1000) {
                        file_put_contents($filepath, $img_data);
                        return true;
                    }
                }
            }
        }
    }
    
    // Fallback: Use Picsum Photos
    $fallback_url = "https://picsum.photos/600/600";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fallback_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $img_data = curl_exec($ch);
    $img_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($img_http_code == 200 && $img_data) {
        file_put_contents($filepath, $img_data);
        return true;
    }
    
    return false;
}

$start_id = 119;
$sql_inserts = [];

echo "Starting download and insertion of " . count($new_products) . " products...\n";

foreach ($new_products as $index => $prod) {
    $current_id = $start_id + $index;
    $cat_name = isset($categories[$prod['category_id']]) ? $categories[$prod['category_id']] : 'Sản phẩm';
    
    // Build description in matching format
    $description = "Đây là sản phẩm " . $prod['name'] . " chất lượng cao thuộc danh mục " . $cat_name . ". Sản phẩm chính hãng, chính sách bảo hành uy tín và hỗ trợ kỹ thuật trọn đời từ WindyStore. Thiết kế hiện đại, độ bền tối ưu đáp ứng tốt nhu cầu sử dụng.";
    
    $img_name = "product_" . $current_id . ".jpg";
    $relative_img_path = "assets/images/product/" . $img_name;
    $absolute_img_path = "c:\\xampp\\htdocs\\project\\assets\\images\\product\\" . $img_name;
    
    $progress = "[" . ($index + 1) . "/" . count($new_products) . "]";
    echo "$progress Processing product ID $current_id: '{$prod['name']}'... ";
    
    // Download image
    $success = search_and_download_image($prod['name'], $absolute_img_path);
    if ($success) {
        echo "Image downloaded. ";
    } else {
        echo "Image download FAILED. ";
        $relative_img_path = "assets/images/product/product_1.jpg"; // Absolute fallback to existing product
    }
    
    // Insert into DB
    try {
        $stmt = $conn->prepare("INSERT INTO products (id, name, img, price, old_price, description, view, category_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->execute([
            $current_id,
            $prod['name'],
            $relative_img_path,
            $prod['price'],
            $prod['old_price'],
            $description,
            $prod['view'],
            $prod['category_id']
        ]);
        echo "DB Insert OK.\n";
    } catch (Exception $e) {
        echo "DB Insert ERROR: " . $e->getMessage() . "\n";
    }
    
    // Prep SQL script insert line
    $old_price_val = is_null($prod['old_price']) ? "NULL" : sprintf("%.2f", $prod['old_price']);
    $price_val = sprintf("%.2f", $prod['price']);
    $escaped_name = str_replace("'", "''", $prod['name']);
    $escaped_desc = str_replace("'", "''", $description);
    
    $sql_inserts[] = "($current_id, '$escaped_name', '$relative_img_path', $price_val, $old_price_val, '$escaped_desc', {$prod['view']}, {$prod['category_id']}, 1)";
}

// Now let's save these sql inserts to database.sql
$sql_file_path = "c:\\xampp\\htdocs\\project\\database.sql";
$sql_content = file_get_contents($sql_file_path);

if ($sql_content) {
    // Find the place where the product insertions end and insert the new lines
    // Product 118 ends with: (118, 'Giấy in ảnh Instax Mini hộp 20 tấm (Phiên bản V3)', 'assets/images/product/product_118.jpg', 223.71, NULL, '...', 31, 24, 1);
    // Let's search for (118, ...);
    $search_str = "(118, 'Giấy in ảnh Instax Mini hộp 20 tấm (Phiên bản V3)', 'assets/images/product/product_118.jpg', 223.71, NULL, 'Đây là sản phẩm Giấy in ảnh Instax Mini hộp 20 tấm (Phiên bản V3) chất lượng cao thuộc danh mục Máy ảnh lấy liền. Sản phẩm chính hãng, chính sách bảo hành uy tín và hỗ trợ kỹ thuật trọn đời từ WindyStore. Thiết kế hiện đại, độ bền tối ưu đáp ứng tốt nhu cầu sử dụng.', 31, 24, 1);";
    
    $new_sql_inserts_text = "\n\n-- Insert 152 new products (generated automatically)\nINSERT INTO products (id, name, img, price, old_price, description, view, category_id, status) VALUES\n" . implode(",\n", $sql_inserts) . ";\n";
    
    if (strpos($sql_content, $search_str) !== false) {
        $updated_sql_content = str_replace($search_str, $search_str . $new_sql_inserts_text, $sql_content);
        file_put_contents($sql_file_path, $updated_sql_content);
        echo "Updated database.sql successfully with new inserts.\n";
    } else {
        // Fallback: append at the end of the file or before users insert
        $user_insert_str = "-- Insert users";
        if (strpos($sql_content, $user_insert_str) !== false) {
            $updated_sql_content = str_replace($user_insert_str, $new_sql_inserts_text . "\n" . $user_insert_str, $sql_content);
            file_put_contents($sql_file_path, $updated_sql_content);
            echo "Appended new inserts before users insert in database.sql.\n";
        } else {
            file_put_contents($sql_file_path, $new_sql_inserts_text, FILE_APPEND);
            echo "Appended new inserts to the end of database.sql.\n";
        }
    }
} else {
    echo "Could not read database.sql to update it.\n";
}

echo "All products processed successfully!\n";
?>
