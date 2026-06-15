<?php
session_start();
ob_start();
include "../model/connectdb.php";
include "../model/user.php";
include "../model/product.php";

// Simple security check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
    header('location: ../index.php?act=login');
    exit;
}

include "view/header.php";

if (isset($_GET['act'])) {
    $act = $_GET['act'];
    switch ($act) {
        case 'users':
            $user_list = get_all_users();
            include "view/users.php";
            break;
        case 'deluser':
            if(isset($_GET['id'])){
                $id_to_del = $_GET['id'];
                // Get user info to check role
                $conn = connectdb();
                $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
                $stmt->execute([$id_to_del]);
                $u_to_del = $stmt->fetch();
                
                // Prevent deleting self or other admins
                if($id_to_del != $_SESSION['user']['id'] && $u_to_del['role'] != 1){
                    delete_user($id_to_del);
                }
            }
            header('location: index.php?act=users');
            break;
        case 'products':
            $product_list = get_all_products();
            include "view/products.php";
            break;
        case 'addproduct':
            if(isset($_POST['addproduct'])){
                $name = $_POST['name'];
                $price = $_POST['price'];
                $old_price = $_POST['old_price'];
                $category_id = $_POST['category_id'];
                $img = ""; // Handle upload later if needed, for now just text path
                if($_FILES['img']['name'] != ""){
                    $img = "assets/images/product/" . $_FILES['img']['name'];
                    move_uploaded_file($_FILES['img']['tmp_name'], "../" . $img);
                }
                insert_product($name, $img, $price, $old_price, $category_id);
                header('location: index.php?act=products');
            }
            $category_list = get_all_categories();
            include "view/addproduct.php";
            break;
        case 'editproduct':
            if(isset($_POST['editproduct'])){
                $id = $_POST['id'];
                $name = $_POST['name'];
                $price = $_POST['price'];
                $old_price = $_POST['old_price'];
                $category_id = $_POST['category_id'];
                $img = $_POST['old_img'];
                if($_FILES['img']['name'] != ""){
                    $img = "assets/images/product/" . $_FILES['img']['name'];
                    move_uploaded_file($_FILES['img']['tmp_name'], "../" . $img);
                }
                update_product($id, $name, $img, $price, $old_price, $category_id);
                header('location: index.php?act=products');
            }
            if(isset($_GET['id'])){
                $product = get_product_by_id($_GET['id']);
                $category_list = get_all_categories();
                include "view/editproduct.php";
            }
            break;
        case 'delproduct':
            if(isset($_GET['id'])){
                delete_product($_GET['id']);
            }
            header('location: index.php?act=products');
            break;
        default:
            include "view/home.php";
            break;
    }
} else {
    include "view/home.php";
}

include "view/footer.php";
ob_end_flush();

// Helper functions for admin
function get_all_users() {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM users ORDER BY id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function delete_user($id) {
    $conn = connectdb();
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
function get_all_products() {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function insert_product($name, $img, $price, $old_price, $category_id) {
    $conn = connectdb();
    $sql = "INSERT INTO products (name, img, price, old_price, category_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$name, $img, $price, $old_price, $category_id]);
}
function update_product($id, $name, $img, $price, $old_price, $category_id) {
    $conn = connectdb();
    $sql = "UPDATE products SET name=?, img=?, price=?, old_price=?, category_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$name, $img, $price, $old_price, $category_id, $id]);
}
function delete_product($id) {
    $conn = connectdb();
    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}
function get_product_by_id($id) {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
