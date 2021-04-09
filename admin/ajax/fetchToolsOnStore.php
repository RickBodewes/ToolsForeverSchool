<?php

session_start();

require '../../dependencies/PDOcon.php';
require '../../dependencies/funcs.php';
require '../../dependencies/checkLogin.php';

$success = false;
$data = array();

if($loggedIn){
    $stmt = $con->prepare("SELECT t4etools.toolID, t4etools.name AS toolName, t4elocationstock.stockAmount, t4elocationstock.minStockAmount, t4elocations.locationID, t4elocations.name AS locationName FROM t4elocationstock JOIN t4etools ON t4elocationstock.toolID = t4etools.toolID JOIN t4elocations ON t4elocationstock.locationID = t4elocations.locationID WHERE t4elocationstock.locationID = :locationid");
    $stmt->bindValue(":locationid", $_GET["locationID"]);
    $stmt->execute();
    $data = $stmt->fetchAll();

    $success = true;
}

echo json_encode(array("success" => $success, "data" => $data));
