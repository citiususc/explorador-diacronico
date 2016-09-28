<?php

$nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
$msg = filter_var($_POST["mensaje"], FILTER_SANITIZE_STRING);
$asunto = filter_var($_POST["asunto"], FILTER_SANITIZE_STRING);
$res = false;

if( $nombre != false && email != false && msg != false && asunto != false ){
    $res = mail("ivan.arroba@hotmail.com",$asunto,$msg);
}

echo $res ? 1 : 0;
