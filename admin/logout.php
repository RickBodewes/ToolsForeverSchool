<?php

session_start();

$_SESSION['loggedIn'] = false;
if(isset($_SESSION['lUserID'])){
    unset($_SESSION['lUserID']);
}
if(isset($_SESSION['lUserToken'])){
    unset($_SESSION['lUserToken']);
}

header("location: /");