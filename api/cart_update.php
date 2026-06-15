<?php
session_start();
include "../model/connectdb.php";
include "../model/cart.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? 0;

    if ($action === 'update') {
        $type = $_POST['type'] ?? '';
        $result = update_cart_quantity($id, $type);
    } elseif ($action === 'delete') {
        $result = delete_cart_item($id);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        exit;
    }

    if ($result) {
        $user_id = $_SESSION['user']['id'];
        $cart_items = get_cart_items($user_id);
        $cart_count = get_cart_count($user_id);
        
        $total_bill = 0;
        foreach($cart_items as $item) {
            $total_bill += $item['price'] * $item['quantity'];
        }

        echo json_encode([
            'status' => 'success',
            'cart_items' => $cart_items,
            'cart_count' => $cart_count,
            'total_bill' => number_format($total_bill, 2),
            'total_bill_raw' => $total_bill
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Operation failed']);
    }
}
?>
