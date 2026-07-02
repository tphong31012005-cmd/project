<?php
session_start();
include "../model/connectdb.php";
include "../model/coupon.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập.']);
    exit;
}

if (!isset($_POST['code'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã giảm giá.']);
    exit;
}

$code = trim($_POST['code']);
$user_id = $_SESSION['user']['id'];

$coupon = validate_coupon($code, $user_id);

if ($coupon) {
    echo json_encode([
        'success' => true,
        'discount_percent' => $coupon['discount_percent']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Mã giảm giá không hợp lệ hoặc đã được sử dụng.'
    ]);
}
?>
