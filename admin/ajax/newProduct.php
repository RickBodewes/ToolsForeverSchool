<?php

session_start();

require '../../dependencies/PDOcon.php';
require '../../dependencies/funcs.php';
require '../../dependencies/checkLogin.php';

$success = false;
$data = array();

if($loggedIn){
    $stmt = $con->prepare("INSERT INTO tools (typeID, Name, manufacturer, buyPrice, sellPrice) VALUES (:typeid, :name, :manufacturer, :buyprice, :sellprice)");
    $stmt->bindValue(":typeid", $_POST["typeID"]);
    $stmt->bindValue(":name", $_POST["name"]);
    $stmt->bindValue(":manufacturer", $_POST["manufacturer"]);
    $stmt->bindValue(":buyprice", $_POST["buyprice"]);
    $stmt->bindValue(":sellprice", $_POST["sellprice"]);
    $stmt->execute();

    $success = true;
}

echo json_encode(array("success" => $success));
