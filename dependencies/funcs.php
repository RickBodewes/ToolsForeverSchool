<?php

//this file contains usefull functions 

function getToken($length, $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"){
    $token = "";
    $max = strlen($codeAlphabet);
    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[mt_rand(0, $max-1)];
    }
    return $token;
}