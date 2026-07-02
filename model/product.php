<?php
function get_products($limit = 0, $category_id = 0) {
    $conn = connectdb();
    $sql = "SELECT * FROM products WHERE status = 1";
    if($category_id > 0){
        $sql .= " AND category_id = " . $category_id;
    }
    $sql .= " ORDER BY id DESC";
    if($limit > 0){
        $sql .= " LIMIT " . $limit;
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_hot_products($limit = 6) {
    $conn = connectdb();
    $sql = "SELECT * FROM products WHERE status = 1 ORDER BY view DESC LIMIT " . $limit;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_categories() {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_category($id) {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insert_category($name) {
    $conn = connectdb();
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    return $stmt->execute([$name]);
}

function update_category($id, $name) {
    $conn = connectdb();
    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    return $stmt->execute([$name, $id]);
}

function delete_category($id) {
    $conn = connectdb();
    // Default action: when category is deleted, related products will either cascade delete or be set to NULL/0 depending on DB schema.
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    return $stmt->execute([$id]);
}


function show_btn_addtocart($id, $name, $img, $price, $stock = null) {
    if ($stock === null) {
        $conn = connectdb();
        $stmt = $conn->prepare("SELECT quantity FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $prod = $stmt->fetch();
        $stock = $prod ? $prod['quantity'] : 0;
    }
    if ($stock <= 0) {
        return '<button class="btn btn-secondary cart-btn w-100" style="background-color: #6c757d; border-color: #6c757d;" disabled>Hết hàng</button>';
    }
    return '
    <form action="index.php?act=addtocart" method="post">
        <input type="hidden" name="id" value="'.$id.'">
        <input type="hidden" name="name" value="'.$name.'">
        <input type="hidden" name="img" value="'.$img.'">
        <input type="hidden" name="price" value="'.$price.'">
        <button type="submit" name="addtocart" value="add" class="btn btn-primary cart-btn w-100">Thêm vào giỏ</button>
    </form>';
}
function get_category_id_by_name($name) {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
    $stmt->execute([$name]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['id'] : 0;
}

function get_products_with_filter($keyword = "", $category_id = 0, $sort = "newest", $page = 1, $limit = 6) {
    $conn = connectdb();
    
    // Base query
    $where = "WHERE status = 1";
    $params = [];
    
    if (!empty($keyword)) {
        $where .= " AND name LIKE :keyword";
        $params[':keyword'] = "%{$keyword}%";
    }
    
    if ($category_id > 0) {
        $where .= " AND category_id = :cat_id";
        $params[':cat_id'] = $category_id;
    }
    
    // Count total items for pagination
    $count_sql = "SELECT COUNT(*) as total FROM products " . $where;
    $count_stmt = $conn->prepare($count_sql);
    foreach ($params as $k => $v) {
        $count_stmt->bindValue($k, $v);
    }
    $count_stmt->execute();
    $total_items = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_items / $limit);
    
    // Sắp xếp
    $order_by = "ORDER BY id DESC"; // newest by default
    switch ($sort) {
        case 'price-low':
            $order_by = "ORDER BY price ASC";
            break;
        case 'price-high':
            $order_by = "ORDER BY price DESC";
            break;
        case 'popular':
            $order_by = "ORDER BY view DESC";
            break;
        case 'rating':
            $order_by = "ORDER BY view DESC"; // Giả định dùng view làm rating tạm
            break;
    }
    
    // Phân trang
    $offset = ($page - 1) * $limit;
    
    // Fetch data
    $sql = "SELECT * FROM products " . $where . " " . $order_by . " LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'data' => $products,
        'total_pages' => $total_pages,
        'total_items' => $total_items,
        'current_page' => $page
    ];
}
?>
