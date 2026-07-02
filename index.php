<?php
session_start();
ob_start();
include "model/connectdb.php";
include "model/user.php";
include "model/product.php";
include "model/cart.php";
include "model/bill.php";
include "model/wishlist.php";
include "model/review.php";
include "model/coupon.php";

include "view/header.php";

if (isset($_GET['act'])) {
    $act = $_GET['act'];
    switch ($act) {
        case 'about':
            include "view/about.php";
            break;
        case 'contact':
            include "view/contact.php";
            break;
        case 'shop':
            include "view/shop.php";
            break;
        case 'shop-single':
            if (!isset($_GET['id']) || !intval($_GET['id'])) {
                header('location: index.php?act=shop');
                exit;
            }
            include "view/product_detail.php";
            break;
        case 'addtocart':
            if(!isset($_SESSION['user'])){
                header('location: index.php?act=login');
                exit;
            }
            if(isset($_POST['addtocart']) && ($_POST['addtocart'])){
                $id = $_POST['id'];
                $name = $_POST['name'];
                $img = $_POST['img'];
                $price = $_POST['price'];
                $qty = 1;
                add_to_cart($_SESSION['user']['id'], $id, $qty, $price);
                header('location: index.php?act=cart');
                exit;
            }
            break;
        case 'cart':
            if(!isset($_SESSION['user'])){
                header('location: index.php?act=login');
                exit;
            }
            include "view/cart.php"; 
            break;
        case 'checkout':
            if(!isset($_SESSION['user'])){
                header('location: index.php?act=login');
                exit;
            }
            $cart_items = get_cart_items($_SESSION['user']['id']);
            if(count($cart_items) == 0){
                header('location: index.php?act=cart');
                exit;
            }
            include "view/checkout.php";
            break;
        case 'place_order':
            if(!isset($_SESSION['user'])){
                header('location: index.php?act=login');
                exit;
            }
            if(isset($_POST['place_order'])) {
                $user_id = $_SESSION['user']['id'];
                $fullname = trim($_POST['fullname']);
                $tel = trim($_POST['tel']);
                $email = trim($_POST['email']);
                $address = trim($_POST['address']);
                $note = trim($_POST['note']);
                $payment_method = isset($_POST['payment_method']) ? (int)$_POST['payment_method'] : 0;
                $coupon_code = trim($_POST['coupon_code'] ?? '');
                
                $cart_items = get_cart_items($user_id);
                if (count($cart_items) > 0) {
                    $total_price = 0;
                    foreach ($cart_items as $item) {
                        $total_price += $item['price'] * $item['quantity'];
                    }
                    
                    $valid_coupon = null;
                    if (!empty($coupon_code)) {
                        $valid_coupon = validate_coupon($coupon_code, $user_id);
                        if ($valid_coupon) {
                            $discount = $total_price * 0.1;
                            $total_price = $total_price - $discount;
                            $coupon_code = $valid_coupon['code'];
                        } else {
                            $coupon_code = null;
                        }
                    } else {
                        $coupon_code = null;
                    }
                    
                    $bill_code = "WINDY-" . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
                    
                    $bill_id = create_bill($user_id, $bill_code, $fullname, $tel, $email, $address, $note, $total_price, $payment_method, $coupon_code);
                    
                    if ($bill_id) {
                        if ($coupon_code) {
                            mark_coupon_used($coupon_code, $user_id);
                        }
                        foreach ($cart_items as $item) {
                            create_bill_detail($bill_id, $item['product_id'], $item['quantity'], $item['price']);
                        }
                        clear_cart($user_id);
                        header("location: index.php?act=order_success&code=$bill_code");
                        exit;
                    }
                }
            }
            header('location: index.php?act=cart');
            break;
        case 'order_success':
            if(!isset($_SESSION['user'])){
                header('location: index.php?act=login');
                exit;
            }
            include "view/order_success.php";
            break;
        case 'delcart':
            if(isset($_GET['id'])){
                delete_cart_item($_GET['id']);
            }
            header('location: index.php?act=cart');
            break;
        case 'updatecart':
            if(isset($_GET['id']) && isset($_GET['type'])){
                update_cart_quantity($_GET['id'], $_GET['type']);
            }
            header('location: index.php?act=cart');
            break;
        case 'login':
            if(isset($_POST['login']) && ($_POST['login'])){
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                
                $login_errors = [];
                if(empty($username)) $login_errors['username'] = "Tên đăng nhập không được để trống";
                if(empty($password)) $login_errors['password'] = "Mật khẩu không được để trống";

                if(empty($login_errors)){
                    $user = checkuser($username, $password);
                    if($user){
                        $_SESSION['user'] = $user;
                        header('location: index.php?act=account');
                        exit;
                    } else {
                        $txt_error = "Tên đăng nhập hoặc mật khẩu không chính xác!";
                    }
                }
            }
            include "view/login.php";
            break;
        case 'signup':
            if(isset($_POST['signup']) && ($_POST['signup'])){
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $email = trim($_POST['email']);
                $fullname = trim($_POST['fullname']);
                $tel = trim($_POST['tel']);
                $address = trim($_POST['address']);

                $errors = [];

                if(empty($username)) $errors['username'] = "Tên đăng nhập không được để trống";
                if(empty($email)) $errors['email'] = "Email không được để trống";
                elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Email không đúng định dạng";
                
                if(empty($password)) $errors['password'] = "Mật khẩu không được để trống";
                elseif(strlen($password) < 6) $errors['password'] = "Mật khẩu phải từ 6 ký tự trở lên";
                
                if($password != $confirm_password) $errors['confirm_password'] = "Mật khẩu xác nhận không khớp";

                if(empty($errors)){
                    // Check if exists
                    $conn = connectdb();
                    $check = $conn->prepare("SELECT id FROM users WHERE username = :u OR email = :e");
                    $check->execute(['u' => $username, 'e' => $email]);
                    if($check->rowCount() > 0){
                        $txt_error = "Tên đăng nhập hoặc Email đã tồn tại trong hệ thống!";
                    } else {
                        $result = register_user($username, $password, $email, $fullname, $address, $tel);
                        if($result){
                            header('location: index.php?act=login&signup_success=1');
                            exit;
                        } else {
                            $txt_error = "Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại!";
                        }
                    }
                }
            }
            include "view/signup.php";
            break;
        case 'account':
            if(!isset($_SESSION['user'])){
                header('location: index.php?act=login');
                exit;
            }
            include "view/account.php";
            break;
        case 'update_account':
            if(!isset($_SESSION['user'])){
                header('location: index.php?act=login');
                exit;
            }
            if(isset($_POST['update_account'])){
                $id = $_SESSION['user']['id'];
                $fullname = trim($_POST['fullname']);
                $email = trim($_POST['email']);
                $tel = trim($_POST['tel']);
                $address = trim($_POST['address']);
                
                if (update_user_info($id, $fullname, $email, $tel, $address)) {
                    // Update session
                    $_SESSION['user']['fullname'] = $fullname;
                    $_SESSION['user']['email'] = $email;
                    $_SESSION['user']['tel'] = $tel;
                    $_SESSION['user']['address'] = $address;
                    header('location: index.php?act=account&success=1');
                } else {
                    header('location: index.php?act=account&error=1');
                }
            } else {
                header('location: index.php?act=account');
            }
            break;
        case 'my_orders':
            if(!isset($_SESSION['user'])){
                header('location: index.php?act=login');
                exit;
            }
            $user_id = $_SESSION['user']['id'];
            $my_bills = get_bills_by_user($user_id);
            include "view/my_orders.php";
            break;
        case 'my_order_detail':
            if(!isset($_SESSION['user'])){
                header('location: index.php?act=login');
                exit;
            }
            if (isset($_GET['id'])) {
                $order_id = $_GET['id'];
                $user_id = $_SESSION['user']['id'];
                
                // Fetch order details, verifying ownership
                $order = null;
                $all_my_bills = get_bills_by_user($user_id);
                foreach ($all_my_bills as $b) {
                    if ($b['id'] == $order_id) {
                        $order = $b;
                        break;
                    }
                }
                
                if ($order) {
                    $order_details = get_bill_details($order_id);
                    include "view/my_order_detail.php";
                } else {
                    header('location: index.php?act=my_orders');
                }
            } else {
                header('location: index.php?act=my_orders');
            }
            break;
            
        case 'wishlist':
            if(!isset($_SESSION['user'])){
                header('location: index.php?act=login');
                exit;
            }
            $user_id = $_SESSION['user']['id'];
            $wishlist_items = get_wishlist($user_id);
            include "view/wishlist.php";
            break;
            
        case 'addwishlist':
            if(!isset($_SESSION['user'])){
                header('location: index.php?act=login');
                exit;
            }
            if(isset($_GET['id'])){
                $product_id = $_GET['id'];
                $user_id = $_SESSION['user']['id'];
                add_to_wishlist($user_id, $product_id);
            }
            // Quay lại trang trước đó
            if(isset($_SERVER['HTTP_REFERER'])) {
                header('location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                header('location: index.php?act=wishlist');
            }
            break;
            
        case 'delwishlist':
            if(!isset($_SESSION['user'])){
                header('location: index.php?act=login');
                exit;
            }
            if(isset($_GET['id'])){
                $product_id = $_GET['id'];
                $user_id = $_SESSION['user']['id'];
                remove_from_wishlist($user_id, $product_id);
            }
            header('location: index.php?act=wishlist');
            break;
            
        case 'cancel_order':
            if(!isset($_SESSION['user'])){
                header('location: index.php?act=login');
                exit;
            }
            if (isset($_GET['id'])) {
                $order_id = $_GET['id'];
                $user_id = $_SESSION['user']['id'];
                cancel_order($order_id, $user_id);
            }
            header('location: index.php?act=my_orders');
            break;
        case 'logout':
            if(isset($_SESSION['user'])) unset($_SESSION['user']);
            header('location: index.php');
            exit;
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
?>
