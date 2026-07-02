<?php
/**
 * Review Model
 */

// Auto create reviews table if it doesn't exist
function init_review_tables() {
    $conn = connectdb();
    if ($conn) {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS reviews (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                product_id INT NOT NULL,
                rating INT NOT NULL,
                content TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            )";
            $conn->exec($sql);
        } catch (PDOException $e) {}
    }
}
init_review_tables();

function add_review($user_id, $product_id, $rating, $content) {
    $conn = connectdb();
    if (!$conn) return false;
    try {
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, content, created_at) VALUES (?, ?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $product_id, $rating, $content]);
    } catch (PDOException $e) { return false; }
}

function get_reviews_by_product($product_id) {
    $conn = connectdb();
    if (!$conn) return [];
    try {
        $stmt = $conn->prepare("
            SELECT r.*, u.username, u.fullname
            FROM reviews r 
            JOIN users u ON r.user_id = u.id
            WHERE r.product_id = ? 
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { return []; }
}

function get_reviews_stats($product_id) {
    $conn = connectdb();
    if (!$conn) return ['rating' => 0.0, 'count' => 0];
    try {
        $stmt = $conn->prepare("
            SELECT AVG(rating) as avg_rating, COUNT(*) as count
            FROM reviews 
            WHERE product_id = ?
        ");
        $stmt->execute([$product_id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return [
            'rating' => $res['avg_rating'] ? round(floatval($res['avg_rating']), 1) : 5.0,
            'count' => (int) $res['count']
        ];
    } catch (PDOException $e) { return ['rating' => 0.0, 'count' => 0]; }
}

function get_all_reviews() {
    $conn = connectdb();
    if (!$conn) return [];
    try {
        $stmt = $conn->prepare("
            SELECT r.*, u.username, p.name as product_name, p.img as product_img
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            JOIN products p ON r.product_id = p.id
            ORDER BY r.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) { return []; }
}

function delete_review($id) {
    $conn = connectdb();
    if (!$conn) return false;
    try {
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) { return false; }
}
?>
