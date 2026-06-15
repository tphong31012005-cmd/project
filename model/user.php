<?php
function checkuser($username, $password) {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

function register_user($username, $password, $email, $fullname = "", $address = "", $tel = "") {
    $conn = connectdb();
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password, email, fullname, address, tel) 
            VALUES (:username, :password, :email, :fullname, :address, :tel)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':tel', $tel);
    return $stmt->execute();
}
?>
