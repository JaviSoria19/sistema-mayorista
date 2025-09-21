<?php

function helper_tituloPagina(){
    return "SISTEMA MAYORISTA";
}

function helper_versionApp(){
    return "0.1 en desarrollo";
}

function helper_encrypt($string)
{
    $result = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr(env('PHP_ENCRYPT_AND_DECRYPT_KEY'), ($i % strlen(env('PHP_ENCRYPT_AND_DECRYPT_KEY'))) - 1, 1);
        $char = chr(ord($char) + ord($keychar));
        $result .= $char;
    }
    return base64_encode($result);
}

function helper_decrypt($string)
{
    $result = '';
    $string = base64_decode($string);
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr(env('PHP_ENCRYPT_AND_DECRYPT_KEY'), ($i % strlen(env('PHP_ENCRYPT_AND_DECRYPT_KEY'))) - 1, 1);
        $char = chr(ord($char) - ord($keychar));
        $result .= $char;
    }
    return $result;
}