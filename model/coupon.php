<?php
function init_coupon_tables() {
    $conn = connectdb();
    if ($conn) {
        try {
            // Create coupons table
            $sql = "CREATE TABLE IF NOT EXISTS coupons (
                id INT AUTO_INCREMENT PRIMARY KEY,
                code VARCHAR(50) NOT NULL UNIQUE,
                discount_percent INT DEFAULT 10,
                user_id INT NOT NULL,
                status TINYINT NOT NULL DEFAULT 0, -- 0: Unused, 1: Used
                used_at TIMESTAMP NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )";
            $conn->exec($sql);

            // Add coupon_code to bill table if not exists
            $check = $conn->query("SHOW COLUMNS FROM bill LIKE 'coupon_code'");
            if ($check->rowCount() == 0) {
                $conn->exec("ALTER TABLE bill ADD COLUMN coupon_code VARCHAR(50) NULL");
            }
        } catch (PDOException $e) {
            // Log or ignore
        }
    }
}

// Auto-run init
init_coupon_tables();

function get_user_coupon($user_id) {
    $conn = connectdb();
    
    // Check if user already has a coupon
    $stmt = $conn->prepare("SELECT * FROM coupons WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $coupon = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If not, automatically generate one for them
    if (!$coupon) {
        $code = "WINDY10-" . $user_id;
        $stmt_insert = $conn->prepare("INSERT INTO coupons (code, discount_percent, user_id, status) VALUES (?, 10, ?, 0)");
        $stmt_insert->execute([$code, $user_id]);
        
        // Fetch it again
        $stmt->execute([$user_id]);
        $coupon = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    return $coupon;
}

function validate_coupon($code, $user_id) {
    $conn = connectdb();
    $code = strtoupper(trim($code));
    if ($code === 'WINDY10') {
        $code = 'WINDY10-' . $user_id;
    }
    $stmt = $conn->prepare("SELECT * FROM coupons WHERE UPPER(code) = ? AND user_id = ? AND status = 0");
    $stmt->execute([$code, $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function mark_coupon_used($code, $user_id) {
    $conn = connectdb();
    $code = strtoupper(trim($code));
    if ($code === 'WINDY10') {
        $code = 'WINDY10-' . $user_id;
    }
    $stmt = $conn->prepare("UPDATE coupons SET status = 1, used_at = CURRENT_TIMESTAMP WHERE UPPER(code) = ? AND user_id = ?");
    return $stmt->execute([$code, $user_id]);
}

function get_all_coupons() {
    $conn = connectdb();
    // Fetch all coupons, joining user info and finding bills that used them
    $sql = "SELECT c.*, u.username, b.bill_code, b.id as bill_id, b.total_price, b.created_at as bill_created_at
            FROM coupons c
            JOIN users u ON c.user_id = u.id
            LEFT JOIN bill b ON c.code = b.coupon_code AND b.status != 4
            ORDER BY c.id DESC";
    return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function restore_coupon($coupon_code, $user_id) {
    $conn = connectdb();
    $stmt = $conn->prepare("UPDATE coupons SET status = 0, used_at = NULL WHERE code = ? AND user_id = ?");
    return $stmt->execute([$coupon_code, $user_id]);
}
?>
