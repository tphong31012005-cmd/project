<?php
include "model/connectdb.php";
$conn = connectdb();
if (!$conn) {
    echo "Connection failed\n";
    exit;
}
echo "Connected successfully\n";

$categories = get_all("SELECT * FROM categories");
echo "Categories count: " . count($categories) . "\n";
foreach ($categories as $cat) {
    $p_count = get_one("SELECT COUNT(*) as c FROM products WHERE category_id = " . $cat['id'])['c'];
    echo "ID: {$cat['id']} | Name: {$cat['name']} | Products count: {$p_count}\n";
}

$total_products = get_one("SELECT COUNT(*) as c FROM products")['c'];
echo "Total products: {$total_products}\n";
?>
