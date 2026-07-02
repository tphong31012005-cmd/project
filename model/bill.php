<?php
// Auto create tables if they don't exist
function init_bill_tables() {
    $conn = connectdb();
    if ($conn) {
        try {
            $sql1 = "CREATE TABLE IF NOT EXISTS bill (
                id INT AUTO_INCREMENT PRIMARY KEY,
                bill_code VARCHAR(50) NOT NULL UNIQUE,
                user_id INT NOT NULL,
                fullname VARCHAR(255) NOT NULL,
                address VARCHAR(255) NOT NULL,
                tel VARCHAR(20) NOT NULL,
                email VARCHAR(150) NOT NULL,
                note TEXT,
                total_price DECIMAL(10, 2) NOT NULL,
                payment_method TINYINT NOT NULL DEFAULT 0,
                status TINYINT NOT NULL DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )";
            $conn->exec($sql1);

            $sql2 = "CREATE TABLE IF NOT EXISTS bill_details (
                id INT AUTO_INCREMENT PRIMARY KEY,
                bill_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL DEFAULT 1,
                price DECIMAL(10, 2) NOT NULL,
                FOREIGN KEY (bill_id) REFERENCES bill(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            )";
            $conn->exec($sql2);
        } catch (PDOException $e) {
            // Log error or ignore
        }
    }
}

// Run table initialization
init_bill_tables();

function create_bill($user_id, $bill_code, $fullname, $tel, $email, $address, $note, $total_price, $payment_method, $coupon_code = null) {
    $conn = connectdb();
    $sql = "INSERT INTO bill (user_id, bill_code, fullname, tel, email, address, note, total_price, payment_method, status, coupon_code) 
            VALUES (:user_id, :bill_code, :fullname, :tel, :email, :address, :note, :total_price, :payment_method, 0, :coupon_code)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':bill_code', $bill_code);
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':tel', $tel);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':note', $note);
    $stmt->bindParam(':total_price', $total_price);
    $stmt->bindParam(':payment_method', $payment_method);
    $stmt->bindParam(':coupon_code', $coupon_code);
    
    if ($stmt->execute()) {
        return $conn->lastInsertId();
    }
    return false;
}

function create_bill_detail($bill_id, $product_id, $quantity, $price) {
    $conn = connectdb();
    $sql = "INSERT INTO bill_details (bill_id, product_id, quantity, price) 
            VALUES (:bill_id, :product_id, :quantity, :price)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':bill_id', $bill_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);
    
    $result = $stmt->execute();
    
    if ($result) {
        // Decrease product stock
        $update_stock_sql = "UPDATE products SET quantity = quantity - :qty WHERE id = :pid";
        $update_stmt = $conn->prepare($update_stock_sql);
        $update_stmt->bindParam(':qty', $quantity);
        $update_stmt->bindParam(':pid', $product_id);
        $update_stmt->execute();
    }
    
    return $result;
}

function clear_cart($user_id) {
    $conn = connectdb();
    $sql = "DELETE FROM cart WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    return $stmt->execute();
}

function get_bill_by_code($bill_code) {
    $conn = connectdb();
    $sql = "SELECT * FROM bill WHERE bill_code = :bill_code";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':bill_code', $bill_code);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_bill_details($bill_id) {
    $conn = connectdb();
    $sql = "SELECT bd.*, p.name, p.img 
            FROM bill_details bd 
            JOIN products p ON bd.product_id = p.id 
            WHERE bd.bill_id = :bill_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':bill_id', $bill_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// -----------------------------------------
// ADMIN FUNCTIONS
// -----------------------------------------

function get_all_bills() {
    $conn = connectdb();
    $sql = "SELECT b.*, u.username 
            FROM bill b 
            LEFT JOIN users u ON b.user_id = u.id 
            ORDER BY b.id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_bill_by_id($id) {
    $conn = connectdb();
    $sql = "SELECT b.*, u.username 
            FROM bill b 
            LEFT JOIN users u ON b.user_id = u.id 
            WHERE b.id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function update_bill_status($id, $status) {
    $conn = connectdb();
    
    // Fetch current status to check if we are transitioning to cancelled
    $current_stmt = $conn->prepare("SELECT status FROM bill WHERE id = :id");
    $current_stmt->bindParam(':id', $id);
    $current_stmt->execute();
    $current_status = $current_stmt->fetchColumn();
    
    $sql = "UPDATE bill SET status = :status WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $id);
    $result = $stmt->execute();
    
    if ($result && $current_status != 4 && $status == 4) {
        // Order cancelled by admin, restore stock
        $details = get_bill_details($id);
        foreach ($details as $item) {
            $update_stock_sql = "UPDATE products SET quantity = quantity + :qty WHERE id = :pid";
            $update_stmt = $conn->prepare($update_stock_sql);
            $update_stmt->bindParam(':qty', $item['quantity']);
            $update_stmt->bindParam(':pid', $item['product_id']);
            $update_stmt->execute();
        }
        
        // Restore coupon
        $order_info_stmt = $conn->prepare("SELECT user_id, coupon_code FROM bill WHERE id = ?");
        $order_info_stmt->execute([$id]);
        $ord_info = $order_info_stmt->fetch(PDO::FETCH_ASSOC);
        if ($ord_info && !empty($ord_info['coupon_code'])) {
            restore_coupon($ord_info['coupon_code'], $ord_info['user_id']);
        }
    }
    
    return $result;
}

function delete_bill($id) {
    $conn = connectdb();
    $sql = "DELETE FROM bill WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

// -----------------------------------------
// USER FUNCTIONS
// -----------------------------------------

function get_bills_by_user($user_id) {
    $conn = connectdb();
    $sql = "SELECT * FROM bill WHERE user_id = :user_id ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function cancel_order($bill_id, $user_id) {
    $conn = connectdb();
    // Only allow cancelling if status is 0 (Pending) and it belongs to the user
    $sql = "UPDATE bill SET status = 4 WHERE id = :id AND user_id = :user_id AND status = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $bill_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // Increase product stock back
        $details = get_bill_details($bill_id);
        foreach ($details as $item) {
            $update_stock_sql = "UPDATE products SET quantity = quantity + :qty WHERE id = :pid";
            $update_stmt = $conn->prepare($update_stock_sql);
            $update_stmt->bindParam(':qty', $item['quantity']);
            $update_stmt->bindParam(':pid', $item['product_id']);
            $update_stmt->execute();
        }
        
        // Restore coupon if applied
        $bill_stmt = $conn->prepare("SELECT coupon_code FROM bill WHERE id = ?");
        $bill_stmt->execute([$bill_id]);
        $coupon_code = $bill_stmt->fetchColumn();
        if (!empty($coupon_code)) {
            restore_coupon($coupon_code, $user_id);
        }
        return true;
    }
    return false;
}
?>
