<?php
include 'model/connectdb.php';
$conn = connectdb();
$stmt = $conn->query("SELECT id, name, quantity FROM products LIMIT 10");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "QUANTITY DATA IN DATABASE:\n";
foreach($products as $p) {
    echo "ID: " . $p['id'] . " | Name: " . $p['name'] . " | Quantity: " . $p['quantity'] . "\n";
}
?>
