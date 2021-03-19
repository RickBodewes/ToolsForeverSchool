<?php

session_start();

require '../../dependencies/PDOcon.php';
require '../../dependencies/funcs.php';
require '../../dependencies/checkLogin.php';

$success = false;
$data = array();

if($loggedIn){
    $stmt = $con->prepare("INSERT INTO locationstock (locationID, toolID, stockAmount, minStockAmount) VALUES (:locationid, :toolid, :stockamount, :minstockamount)");
    $stmt->bindValue(":locationid", $_POST["locationID"]);
    $stmt->bindValue(":toolid", $_POST["toolID"]);
    $stmt->bindValue(":stockamount", $_POST["stockAmount"]);
    $stmt->bindValue(":minstockamount", $_POST["minStockAmount"]);
    $stmt->execute();

    $success = true;
}

echo json_encode(array("success" => $success));
