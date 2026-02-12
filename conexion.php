<?php
$servername = "127.0.0.1";
$port = "localhost";
$dbname = "cursoscp";
$username = "chaimae";
$password = "123456";
try {
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8",$username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // echo "Conexión realizada correctamente";
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}
?>
