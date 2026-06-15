<?php
function connectdb() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "WindyStore";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        return null;
    }
}

function get_all($sql) {
    $conn = connectdb();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetchAll();
}

function get_one($sql) {
    $conn = connectdb();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetch();
}

function execute($sql) {
    $conn = connectdb();
    $conn->exec($sql);
}
?>
