<?php
function add_to_cart($user_id, $product_id, $quantity, $price) {
    $conn = connectdb();

    // Fetch product stock
    $stmt_prod = $conn->prepare("SELECT quantity FROM products WHERE id = ?");
    $stmt_prod->execute([$product_id]);
    $prod = $stmt_prod->fetch(PDO::FETCH_ASSOC);
    $stock = $prod ? intval($prod['quantity']) : 0;

    if ($stock <= 0) {
        return false;
    }

    // Check if item already exists in cart for this user
    $sql_check = "SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':user_id', $user_id);
    $stmt_check->bindParam(':product_id', $product_id);
    $stmt_check->execute();
    $existing_item = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if($existing_item){
        $new_qty = $existing_item['quantity'] + $quantity;
        if ($new_qty > $stock) {
            $new_qty = $stock;
        }
        if ($new_qty <= 0) {
            return delete_cart_item($existing_item['id']);
        }
        $sql_update = "UPDATE cart SET quantity = :quantity WHERE id = :id";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bindParam(':quantity', $new_qty);
        $stmt_update->bindParam(':id', $existing_item['id']);
        return $stmt_update->execute();
    } else {
        if ($quantity > $stock) {
            $quantity = $stock;
        }
        if ($quantity <= 0) {
            return false;
        }
        $sql_insert = "INSERT INTO cart (user_id, product_id, quantity, price) VALUES (:user_id, :product_id, :quantity, :price)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindParam(':user_id', $user_id);
        $stmt_insert->bindParam(':product_id', $product_id);
        $stmt_insert->bindParam(':quantity', $quantity);
        $stmt_insert->bindParam(':price', $price);
        return $stmt_insert->execute();
    }
}

function get_cart_count($user_id) {
    if(!$user_id) return 0;
    $conn = connectdb();
    $sql = "SELECT SUM(quantity) as total FROM cart WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ? $result['total'] : 0;
}

function get_cart_items($user_id) {
    $conn = connectdb();
    $sql = "SELECT c.*, p.name, p.img, p.price as current_price 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function update_cart_quantity($id, $type) {
    $conn = connectdb();
    // Get current quantity and product_id
    $stmt = $conn->prepare("SELECT quantity, product_id FROM cart WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($item){
        $product_id = $item['product_id'];
        
        // Fetch product stock
        $stmt_prod = $conn->prepare("SELECT quantity FROM products WHERE id = ?");
        $stmt_prod->execute([$product_id]);
        $prod = $stmt_prod->fetch(PDO::FETCH_ASSOC);
        $stock = $prod ? intval($prod['quantity']) : 0;

        if ($type == 'inc') {
            $new_qty = $item['quantity'] + 1;
            if ($new_qty > $stock) {
                return false; // Exceeds stock limit
            }
        } else {
            $new_qty = $item['quantity'] - 1;
        }

        if($new_qty > 0){
            $stmt_upd = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            return $stmt_upd->execute([$new_qty, $id]);
        } else {
            return delete_cart_item($id);
        }
    }
    return false;
}

function delete_cart_item($id) {
    $conn = connectdb();
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    return $stmt->execute([$id]);
}
?>
