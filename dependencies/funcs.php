<?php

//powerlevels of employees

$admin = 3;
$manager = 2;
$employee = 1;

$roles = array();

$roles[$admin] = "admin"; $roles[$manager] = "manager"; $roles[$employee] = "employee"; 

//might for customers do 0 in future

//this file contains usefull functions 

//making a random token
function getToken($length, $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"){
    $token = "";
    $max = strlen($codeAlphabet);
    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[mt_rand(0, $max-1)];
    }
    return $token;
}

//making a salt based on input string
function MakeSalt($input, $length = 22){
    return substr(base64_encode(str_pad($input, $length, 'ZPgBbt4s4txMgENpsVIpwZ')), 0, $length);
}

//cutting hash data of the password currently made for BCRYPT
function CutHash($input){
    return substr(explode('$', $input)[3], 22);
}

//making a password usable for database
function EncryptPass($input, $salt){
    return CutHash(password_hash($input, PASSWORD_BCRYPT, array("salt" => MakeSalt($salt))));
}
