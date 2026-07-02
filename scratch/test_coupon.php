<?php
include "model/connectdb.php";
include "model/coupon.php";

try {
    $conn = connectdb();
    echo "Database connected successfully.\n";
    
    // Check coupons table structure
    $q = $conn->query("SHOW TABLES LIKE 'coupons'");
    if ($q->rowCount() > 0) {
        echo "Table 'coupons' exists.\n";
        $cols = $conn->query("DESCRIBE coupons")->fetchAll(PDO::FETCH_ASSOC);
        print_r($cols);
    } else {
        echo "Table 'coupons' DOES NOT exist.\n";
    }
    
    // Check bill table column coupon_code
    $check = $conn->query("SHOW COLUMNS FROM bill LIKE 'coupon_code'");
    if ($check->rowCount() > 0) {
        echo "Column 'coupon_code' exists in 'bill' table.\n";
    } else {
        echo "Column 'coupon_code' DOES NOT exist in 'bill' table.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
