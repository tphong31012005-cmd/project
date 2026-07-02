<?php
session_start();
include "../model/connectdb.php";
include "../model/cart.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user']['id'];

// Parse request: support both form-data POST and JSON body
$json_body = [];
$raw = file_get_contents('php://input');
if ($raw) {
    $decoded = json_decode($raw, true);
    if ($decoded) $json_body = $decoded;
}

// Determine action
$action = $json_body['action'] ?? $_POST['action'] ?? '';

// Detect add-to-cart from product_detail page (JSON body with product_id)
if (!$action && isset($json_body['product_id'])) {
    $action = 'add';
}

/* ──────────────────────────────────────────────────────────
   ACTION: add  (from product_detail.php via fetch() JSON)
   ────────────────────────────────────────────────────────── */
if ($action === 'add') {
    $product_id = intval($json_body['product_id'] ?? $_POST['product_id'] ?? 0);
    $qty        = intval($json_body['qty'] ?? $_POST['qty'] ?? 1);
    $price      = floatval($json_body['price'] ?? $_POST['price'] ?? 0);

    if ($product_id <= 0 || $qty <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }

    $result     = add_to_cart($user_id, $product_id, $qty, $price);
    $cart_count = get_cart_count($user_id);

    echo json_encode([
        'success'    => (bool) $result,
        'cart_count' => intval($cart_count),
        'message'    => $result ? 'Đã thêm vào giỏ hàng!' : 'Không thể thêm (kiểm tra tồn kho)',
    ]);
    exit;
}

/* ──────────────────────────────────────────────────────────
   ACTION: update / delete  (from cart.php via form/fetch)
   ────────────────────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id            = intval($_POST['id'] ?? 0);
    $error_message = 'Thao tác thất bại!';

    if ($action === 'update') {
        $type = $_POST['type'] ?? '';

        // Check stock limit before incrementing
        $conn = connectdb();
        $stmt = $conn->prepare("SELECT quantity, product_id FROM cart WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($item) {
            $stmt_prod = $conn->prepare("SELECT quantity FROM products WHERE id = ?");
            $stmt_prod->execute([$item['product_id']]);
            $prod  = $stmt_prod->fetch(PDO::FETCH_ASSOC);
            $stock = $prod ? intval($prod['quantity']) : 0;

            if ($type === 'inc' && ($item['quantity'] + 1) > $stock) {
                $error_message = "Số lượng vượt quá tồn kho (Tồn kho: {$stock})!";
            }
        }

        $result = update_cart_quantity($id, $type);

    } elseif ($action === 'delete') {
        $result = delete_cart_item($id);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ!']);
        exit;
    }

    if ($result) {
        $cart_items = get_cart_items($user_id);
        $cart_count = get_cart_count($user_id);

        $total_bill = 0;
        foreach ($cart_items as $item) {
            $total_bill += $item['price'] * $item['quantity'];
        }

        echo json_encode([
            'status'         => 'success',
            'success'        => true,
            'cart_items'     => $cart_items,
            'cart_count'     => intval($cart_count),
            'total_bill'     => number_format($total_bill, 0, ',', '.') . ' đ',
            'total_bill_raw' => $total_bill,
        ]);
    } else {
        echo json_encode(['status' => 'error', 'success' => false, 'message' => $error_message]);
    }
}
?>
