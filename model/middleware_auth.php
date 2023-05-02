<?php
$path = $_SERVER['DOCUMENT_ROOT'] . '/8_CRUD_MVC v10 proves/8_MVC_CRUD/';
include($path . "model/JWT.php");

function decode_token($token){
    $jwt = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/8_CRUD_MVC v10 proves/8_MVC_CRUD/model/credentials.ini',true);
    $secret = $jwt['JWT']['secret'];

    $JWT = new JWT;
    $token_dec = $JWT->decode($token, $secret);
    $rt_token = json_decode($token_dec, TRUE);
    return $rt_token;
}

function create_token($username){
    $jwt = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/8_CRUD_MVC v10 proves/8_MVC_CRUD/model/credentials.ini',true);
    $header = $jwt['JWT']['header'];
    $secret = $jwt['JWT']['secret'];
    $payload = '{"iat":"' . time() . '","exp":"' . time() + (600) . '","username":"' . $username . '"}';

    $JWT = new JWT;
    $token = $JWT->encode($header, $payload, $secret);
    return $token;
}


function create_token_refresh($username){
    $jwt = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/8_CRUD_MVC v10 proves/8_MVC_CRUD/model/credentials.ini',true);
    $header = $jwt['JWT']['header'];
    $secret = $jwt['JWT']['secret'];
    $payload = '{"iat":"' . time() . '","exp":"' . time() + (60) . '","username":"' . $username . '"}';

    $JWT = new JWT;
    $token = $JWT->encode($header, $payload, $secret);
    return $token;
}