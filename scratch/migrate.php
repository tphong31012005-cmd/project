<?php
include 'model/connectdb.php';
$conn = connectdb();

try {
    echo "Starting migration...\n";

    // 1. Check if products table has quantity column
    $stmt = $conn->query("DESCRIBE products");
    $fields = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('quantity', $fields)) {
        echo "Adding quantity column to products table...\n";
        $conn->exec("ALTER TABLE products ADD COLUMN quantity INT NOT NULL DEFAULT 50");
        echo "Added quantity column.\n";
    } else {
        echo "quantity column already exists in products table.\n";
    }

    // 2. Modify column types to support larger VND prices
    echo "Altering table column types to DECIMAL(15, 2)...\n";
    $conn->exec("ALTER TABLE products MODIFY COLUMN price DECIMAL(15, 2) NOT NULL");
    $conn->exec("ALTER TABLE products MODIFY COLUMN old_price DECIMAL(15, 2) NULL");
    $conn->exec("ALTER TABLE cart MODIFY COLUMN price DECIMAL(15, 2) NOT NULL");
    $conn->exec("ALTER TABLE bill MODIFY COLUMN total_price DECIMAL(15, 2) NOT NULL");
    $conn->exec("ALTER TABLE bill_details MODIFY COLUMN price DECIMAL(15, 2) NOT NULL");
    echo "Column types altered successfully.\n";

    // 3. Scale prices by 25,000 (only if they are still USD size, say < 100000)
    // Let's check if any product has price < 100000. If yes, it's likely still in USD.
    $check_stmt = $conn->query("SELECT COUNT(*) FROM products WHERE price < 10000");
    $usd_count = $check_stmt->fetchColumn();

    if ($usd_count > 0) {
        echo "Converting prices to VND (multiplying by 25,000)...\n";
        $conn->exec("UPDATE products SET price = price * 25000, old_price = CASE WHEN old_price IS NOT NULL AND old_price > 0 THEN old_price * 25000 ELSE NULL END");
        echo "Prices updated to VND successfully.\n";
    } else {
        echo "Prices are already converted to VND (no products with price < 10,000 found).\n";
    }

    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
}
?>
