<?php

session_start();

require '../../dependencies/PDOcon.php';
require '../../dependencies/funcs.php';
require '../../dependencies/checkLogin.php';

$success = false;
$data = array();

if($loggedIn){
    $stmt = $con->prepare("SELECT tools.toolID, tools.name, locationstock.stockAmount FROM locationstock JOIN tools ON locationstock.toolID = tools.toolID WHERE locationstock.locationID = :locationid");
    $stmt->bindValue(":locationid", $_GET["locationID"]);
    $stmt->execute();
    $data = $stmt->fetchAll();

    $success = true;
}

echo json_encode(array("success" => $success, "data" => $data));