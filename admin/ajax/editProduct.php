<?php

session_start();

require '../../dependencies/PDOcon.php';
require '../../dependencies/funcs.php';
require '../../dependencies/checkLogin.php';

$success = false;
$data = array();

if($loggedIn){
    $stmt = $con->prepare("UPDATE t4etools SET toolID = :typeid, Name = :name, manufacturer = :manufacturer, buyPrice = :buyprice, sellPrice = :sellprice WHERE toolID = :toolid");
    $stmt->bindValue(":toolid", $_POST["toolID"]);
    $stmt->bindValue(":typeid", $_POST["typeID"]);
    $stmt->bindValue(":name", $_POST["name"]);
    $stmt->bindValue(":manufacturer", $_POST["manufacturer"]);
    $stmt->bindValue(":buyprice", $_POST["buyprice"]);
    $stmt->bindValue(":sellprice", $_POST["sellprice"]);
    $stmt->execute();

    $success = true;
}

echo json_encode(array("success" => $success));
