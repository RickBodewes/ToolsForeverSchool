<?php
$servername = "localhost";
$username = "php";
$password = "b2uK3ntm7cusHF4pfdKaBQfE8yMsZb";
$dbname = "toolsforever";
$poort = "3306";
try {
    $con = new PDO("mysql:host=" . $servername . ";dbname=" . $dbname .";port=" . $poort, $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "<br>";
}