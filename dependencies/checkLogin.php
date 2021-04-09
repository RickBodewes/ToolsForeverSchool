<?php

$loggedIn = false;

if(isset($_SESSION['loggedIn'])){
    if($_SESSION['loggedIn']){
        if(isset($_SESSION['lUserID']) && isset($_SESSION['lUserToken'])){
            $query = "SELECT * FROM t4eusers WHERE userID = :userid AND sessionID = :sessionid LIMIT 1";
            $stmt = $con->prepare($query);
            $stmt->bindValue(':userid', $_SESSION['lUserID']);
            $stmt->bindValue(':sessionid', $_SESSION['lUserToken']);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user === false){
                $loggedIn = false;
                $_SESSION['loggedIn'] = false;
                if(isset($_SESSION['lUserID'])){
                    unset($_SESSION['lUserID']);
                }
                if(isset($_SESSION['lUserToken'])){
                    unset($_SESSION['lUserToken']);
                }
            }else{
                $loggedIn = true;
            }
        }
    }else{
        if(isset($_SESSION['lUserID'])){
            unset($_SESSION['lUserID']);
        }
        if(isset($_SESSION['lUserToken'])){
            unset($_SESSION['lUserToken']);
        }
    }
}else{
    $_SESSION['loggedIn'] = false;
    if(isset($_SESSION['lUserID'])){
        unset($_SESSION['lUserID']);
    }
    if(isset($_SESSION['lUserToken'])){
        unset($_SESSION['lUserToken']);
    }
}
