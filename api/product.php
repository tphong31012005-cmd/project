<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$base = dirname(__DIR__);
include $base . "/model/connectdb.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

$conn = connectdb();

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed. Please check XAMPP MySQL is running.']);
    exit;
}

// Get product with category name
$stmt = $conn->prepare("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.id = ? AND p.status = 1
");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Increment view count
$conn->prepare("UPDATE products SET view = view + 1 WHERE id = ?")->execute([$id]);

// Get related products (same category, exclude current)
$stmt2 = $conn->prepare("
    SELECT id, name, img, price, old_price, quantity, view 
    FROM products 
    WHERE category_id = ? AND id != ? AND status = 1 
    ORDER BY view DESC 
    LIMIT 8
");
$stmt2->execute([$product['category_id'], $id]);
$related = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Format numbers
$product['price'] = floatval($product['price']);
$product['old_price'] = $product['old_price'] ? floatval($product['old_price']) : null;
$product['quantity'] = intval($product['quantity']);
$product['view'] = intval($product['view']);

foreach ($related as &$r) {
    $r['price'] = floatval($r['price']);
    $r['old_price'] = $r['old_price'] ? floatval($r['old_price']) : null;
    $r['quantity'] = intval($r['quantity']);
}

echo json_encode([
    'success' => true,
    'product' => $product,
    'related' => $related
]);
