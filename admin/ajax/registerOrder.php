<?php

session_start();

require '../../dependencies/PDOcon.php';
require '../../dependencies/funcs.php';
require '../../dependencies/checkLogin.php';

$success = false;

if($loggedIn){
    if(isset($_POST["tools"])){

        foreach($_POST["tools"] as $tool){
            $query = "UPDATE t4elocationstock SET stockAmount = stockAmount - :deliveryamount WHERE locationID = :locationid AND toolID = :toolid";
            $stmt = $con->prepare($query);
            $stmt->bindvalue(":deliveryamount", $tool["deliveryAmount"]);
            $stmt->bindvalue(":locationid", $tool["locationID"]);
            $stmt->bindvalue(":toolid", $tool["toolID"]);
            $stmt->execute();
        }

        $success = true;
    }
}

echo json_encode(array("success" => $success));