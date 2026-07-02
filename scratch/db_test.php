<?php
include 'model/connectdb.php';
$conn = connectdb();
echo "--- PRODUCTS TABLE ---\n";
try {
    $q = $conn->query("DESCRIBE products");
    print_r($q->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error products: " . $e->getMessage() . "\n";
}

echo "--- CART TABLE ---\n";
try {
    $q = $conn->query("DESCRIBE cart");
    print_r($q->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error cart: " . $e->getMessage() . "\n";
}
?>
