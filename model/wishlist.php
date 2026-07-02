<?php
/**
 * Wishlist Model
 */

// Auto create tables if they don't exist
function init_wishlist_tables() {
    $conn = connectdb();
    if ($conn) {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS wishlists (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                product_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            )";
            $conn->exec($sql);
        } catch (PDOException $e) {}
    }
}
init_wishlist_tables();

function add_to_wishlist($user_id, $product_id) {
    $conn = connectdb();
    if (!$conn) return false;
    try {
        $stmt = $conn->prepare("SELECT id FROM wishlists WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        if ($stmt->fetch()) return true;
        $stmt = $conn->prepare("INSERT INTO wishlists (user_id, product_id, created_at) VALUES (?, ?, NOW())");
        return $stmt->execute([$user_id, $product_id]);
    } catch (PDOException $e) { return false; }
}

function remove_from_wishlist($user_id, $product_id) {
    $conn = connectdb();
    if (!$conn) return false;
    try {
        $stmt = $conn->prepare("DELETE FROM wishlists WHERE user_id = ? AND product_id = ?");
        return $stmt->execute([$user_id, $product_id]);
    } catch (PDOException $e) { return false; }
}

function get_wishlist($user_id) {
    $conn = connectdb();
    if (!$conn) return [];
    try {
        $stmt = $conn->prepare("
            SELECT w.*, p.name, p.img, p.price, p.old_price, p.quantity
            FROM wishlists w JOIN products p ON w.product_id = p.id
            WHERE w.user_id = ? ORDER BY w.created_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { return []; }
}

function get_wishlist_count($user_id) {
    $conn = connectdb();
    if (!$conn) return 0;
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM wishlists WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return (int) $stmt->fetchColumn();
    } catch (PDOException $e) { return 0; }
}

function is_in_wishlist($user_id, $product_id) {
    $conn = connectdb();
    if (!$conn) return false;
    try {
        $stmt = $conn->prepare("SELECT id FROM wishlists WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        return (bool) $stmt->fetch();
    } catch (PDOException $e) { return false; }
}
?>
