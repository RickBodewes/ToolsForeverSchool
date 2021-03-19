<?php

session_start();

require '../../dependencies/PDOcon.php';
require '../../dependencies/funcs.php';
require '../../dependencies/checkLogin.php';

$success = false;
$data = array();

if($loggedIn){
    $stmt = $con->prepare("DELETE FROM locationstock WHERE locationID = :locationid AND toolID = :toolid");
    $stmt->bindValue(":locationid", $_POST["locationID"]);
    $stmt->bindValue(":toolid", $_POST["toolID"]);
    $stmt->execute();

    $success = true;
}

echo json_encode(array("success" => $success));
