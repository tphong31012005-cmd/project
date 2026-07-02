<?php
session_start();
ob_start();
include "../model/connectdb.php";
include "../model/user.php";
include "../model/product.php";
include "../model/bill.php";
include "../model/review.php";
include "../model/coupon.php";

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
            // Ràng buộc: Không cho phép xóa người dùng (kể cả admin)
            header('location: index.php?act=users&error=cannot_delete_user');
            break;
            
        case 'categories':
            $cat_list = get_all_categories();
            include "view/categories.php";
            break;
            
        case 'addcategory':
            if(isset($_POST['addcategory'])){
                $name = trim($_POST['name']);
                if(!empty($name)){
                    insert_category($name);
                    header('location: index.php?act=categories');
                    exit;
                }
            }
            include "view/addcategory.php";
            break;
            
        case 'editcategory':
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                $cat = get_category($id);
                if(isset($_POST['editcategory'])){
                    $name = trim($_POST['name']);
                    if(!empty($name)){
                        update_category($id, $name);
                        header('location: index.php?act=categories');
                        exit;
                    }
                }
                include "view/editcategory.php";
            } else {
                header('location: index.php?act=categories');
            }
            break;
            
        case 'delcategory':
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                delete_category($id);
            }
            header('location: index.php?act=categories');
            break;
            
        case 'products':
            $product_list = get_all_products();
            include "view/products.php";
            break;
        case 'addproduct':
            if(isset($_POST['addproduct'])){
                $name = $_POST['name'];
                $price = floatval($_POST['price']);
                $old_price = ($_POST['old_price'] !== '') ? floatval($_POST['old_price']) : null;
                $quantity = intval($_POST['quantity']);
                $category_id = $_POST['category_id'];
                
                $error_msg = "";
                if ($price < 0) {
                    $error_msg = "Giá bán không được là số âm!";
                } elseif ($old_price !== null && $old_price < 0) {
                    $error_msg = "Giá gốc không được là số âm!";
                } elseif ($quantity < 0) {
                    $error_msg = "Số lượng tồn kho không được là số âm!";
                }

                if ($error_msg == "") {
                    $img = ""; // Handle upload later if needed, for now just text path
                    if($_FILES['img']['name'] != ""){
                        $img = "assets/images/product/" . $_FILES['img']['name'];
                        move_uploaded_file($_FILES['img']['tmp_name'], "../" . $img);
                    }
                    insert_product($name, $img, $price, $old_price, $category_id, $quantity);
                    header('location: index.php?act=products');
                    exit;
                }
            }
            $category_list = get_all_categories();
            include "view/addproduct.php";
            break;
        case 'editproduct':
            if(isset($_POST['editproduct'])){
                $id = $_POST['id'];
                $name = $_POST['name'];
                $price = floatval($_POST['price']);
                $old_price = ($_POST['old_price'] !== '') ? floatval($_POST['old_price']) : null;
                $quantity = intval($_POST['quantity']);
                $category_id = $_POST['category_id'];
                
                $error_msg = "";
                if ($price < 0) {
                    $error_msg = "Giá bán không được là số âm!";
                } elseif ($old_price !== null && $old_price < 0) {
                    $error_msg = "Giá gốc không được là số âm!";
                } elseif ($quantity < 0) {
                    $error_msg = "Số lượng tồn kho không được là số âm!";
                }

                if ($error_msg == "") {
                    $img = $_POST['old_img'];
                    if($_FILES['img']['name'] != ""){
                        $img = "assets/images/product/" . $_FILES['img']['name'];
                        move_uploaded_file($_FILES['img']['tmp_name'], "../" . $img);
                    }
                    update_product($id, $name, $img, $price, $old_price, $category_id, $quantity);
                    header('location: index.php?act=products');
                    exit;
                }
            }
            if(isset($_GET['id'])){
                $product = get_product_by_id($_GET['id']);
                $category_list = get_all_categories();
                include "view/editproduct.php";
            }
            break;
        case 'delproduct':
            // Ràng buộc: Không cho phép xóa sản phẩm
            header('location: index.php?act=products&error=cannot_delete_product');
            break;
        case 'orders':
            $bill_list = get_all_bills();
            include "view/orders.php";
            break;
        case 'order_detail':
            if (isset($_GET['id'])) {
                $order_id = $_GET['id'];
                $order = get_bill_by_id($order_id);
                if ($order) {
                    $order_details = get_bill_details($order_id);
                    include "view/order_detail.php";
                } else {
                    header('location: index.php?act=orders');
                }
            } else {
                header('location: index.php?act=orders');
            }
            break;
        case 'update_order':
            if (isset($_POST['update_status']) && isset($_POST['id'])) {
                $id = $_POST['id'];
                $status = intval($_POST['status']);
                // Lấy trạng thái hiện tại để kiểm tra ràng buộc
                $conn = connectdb();
                $cur_stmt = $conn->prepare("SELECT status FROM bill WHERE id = ?");
                $cur_stmt->execute([$id]);
                $current_status = intval($cur_stmt->fetchColumn());
                
                // Ràng buộc: chỉ được tăng trạng thái, không được lùi lại
                // Ngoại lệ: có thể hủy (status=4) nếu đơn chưa hoàn thành (status != 3)
                $valid = false;
                if ($status > $current_status) {
                    // Chỉ cho phép tăng 1 bước (trừ hủy đơn)
                    if ($status == 4 && $current_status < 3) {
                        $valid = true; // Hủy đơn nếu chưa hoàn thành
                    } elseif ($status == $current_status + 1) {
                        $valid = true; // Tăng đúng 1 bước
                    }
                }
                
                if ($valid) {
                    update_bill_status($id, $status);
                    header("location: index.php?act=order_detail&id=$id&success=status_updated");
                } else {
                    header("location: index.php?act=order_detail&id=$id&error=invalid_status");
                }
            } else {
                header('location: index.php?act=orders');
            }
            break;
        case 'delorder':
            if (isset($_GET['id'])) {
                delete_bill($_GET['id']);
            }
            header('location: index.php?act=orders');
            break;
        case 'reviews':
            $review_list = get_all_reviews();
            include "view/reviews.php";
            break;
        case 'delreview':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                delete_review($id);
            }
            header('location: index.php?act=reviews');
            break;
        case 'coupons':
            $coupon_list = get_all_coupons();
            include "view/coupons.php";
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
function insert_product($name, $img, $price, $old_price, $category_id, $quantity) {
    $conn = connectdb();
    $sql = "INSERT INTO products (name, img, price, old_price, category_id, quantity) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$name, $img, $price, $old_price, $category_id, $quantity]);
}
function update_product($id, $name, $img, $price, $old_price, $category_id, $quantity) {
    $conn = connectdb();
    $sql = "UPDATE products SET name=?, img=?, price=?, old_price=?, category_id=?, quantity=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$name, $img, $price, $old_price, $category_id, $quantity, $id]);
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
