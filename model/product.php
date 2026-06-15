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

function show_btn_addtocart($id, $name, $img, $price) {
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
?>
