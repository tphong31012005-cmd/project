<?php
session_start();
ob_start();
include "model/connectdb.php";
include "model/user.php";
include "model/product.php";
include "model/cart.php";

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
