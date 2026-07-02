<?php
session_start();
$_SESSION['user'] = ['id' => 1];
$_POST = [];
$json = json_encode(['product_id' => 1, 'qty' => 1, 'price' => 100]);
file_put_contents('php://input', $json);
$_SERVER['REQUEST_METHOD'] = 'POST';

// Mock php://input by overriding it in the script?
// Can't easily override php://input. So I'll just simulate json_body.
// Let's directly call the cart function.
include 'model/connectdb.php';
include 'model/cart.php';
$result = add_to_cart(1, 1, 1, 100);
var_dump($result);
?>
