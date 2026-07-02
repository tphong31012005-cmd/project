<?php
session_start();
header('Content-Type: application/json');

include dirname(__DIR__) . "/model/connectdb.php";
include dirname(__DIR__) . "/model/review.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
    if ($product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Mã sản phẩm không hợp lệ']);
        exit;
    }
    
    $reviews = get_reviews_by_product($product_id);
    $stats = get_reviews_stats($product_id);
    
    echo json_encode([
        'success' => true,
        'reviews' => $reviews,
        'rating'  => $stats['rating'],
        'count'   => $stats['count']
    ]);
    exit;
}

if ($method === 'POST') {
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để gửi đánh giá']);
        exit;
    }
    
    $user_id = intval($_SESSION['user']['id']);
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    
    $product_id = isset($body['product_id']) ? intval($body['product_id']) : 0;
    $rating = isset($body['rating']) ? intval($body['rating']) : 0;
    $content = isset($body['content']) ? trim($body['content']) : '';
    
    if ($product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Mã sản phẩm không hợp lệ']);
        exit;
    }
    
    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng chọn số sao từ 1 đến 5']);
        exit;
    }
    
    if (empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập nội dung đánh giá']);
        exit;
    }
    
    $result = add_review($user_id, $product_id, $rating, $content);
    
    if ($result) {
        $reviews = get_reviews_by_product($product_id);
        $stats = get_reviews_stats($product_id);
        
        echo json_encode([
            'success' => true,
            'message' => 'Gửi đánh giá thành công!',
            'reviews' => $reviews,
            'rating'  => $stats['rating'],
            'count'   => $stats['count']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể gửi đánh giá, vui lòng thử lại']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
?>
