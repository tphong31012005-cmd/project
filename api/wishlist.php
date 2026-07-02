<?php
session_start();
header('Content-Type: application/json');

include dirname(__DIR__) . "/model/connectdb.php";
include dirname(__DIR__) . "/model/wishlist.php";

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

$user_id    = intval($_SESSION['user']['id']);
$body       = json_decode(file_get_contents('php://input'), true) ?? [];
$product_id = intval($body['product_id'] ?? $_POST['product_id'] ?? 0);
$action     = $body['action'] ?? $_POST['action'] ?? 'toggle'; // toggle | add | remove

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ']);
    exit;
}

if ($action === 'toggle') {
    $in_wishlist = is_in_wishlist($user_id, $product_id);
    if ($in_wishlist) {
        $result = remove_from_wishlist($user_id, $product_id);
        $state  = false;
    } else {
        $result = add_to_wishlist($user_id, $product_id);
        $state  = true;
    }
} elseif ($action === 'add') {
    $result = add_to_wishlist($user_id, $product_id);
    $state  = true;
} elseif ($action === 'remove') {
    $result = remove_from_wishlist($user_id, $product_id);
    $state  = false;
} else {
    echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
    exit;
}

echo json_encode([
    'success'     => (bool) $result,
    'in_wishlist' => $state,
    'count'       => get_wishlist_count($user_id),
]);
?>
