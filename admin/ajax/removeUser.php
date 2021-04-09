<?php

session_start();

require '../../dependencies/PDOcon.php';
require '../../dependencies/funcs.php';
require '../../dependencies/checkLogin.php';

$success = false;
$data = array();

if($loggedIn){
    $stmt = $con->prepare("DELETE FROM t4eusers WHERE userID = :userid");
    $stmt->bindValue(":userid", $_POST["userID"]);
    $stmt->execute();

    $success = true;
}

echo json_encode(array("success" => $success));
