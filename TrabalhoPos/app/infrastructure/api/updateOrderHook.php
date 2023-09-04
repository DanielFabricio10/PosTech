<?php

ini_set('display_errors', 'Off');

require_once '../../autoload.php';
require_once '../../databaseConnection.php';

$auth = base64_encode($_SERVER['PHP_AUTH_USER'].':'.$_SERVER['PHP_AUTH_PW']);

if(empty($auth) || $auth != 'cG9zdGVjaDp0ZXN0ZQ==') {
    exit(http_response_code(403));
}

if(!in_array(strtoupper($_SERVER['REQUEST_METHOD']),['PUT','POST'])) {
    exit(http_response_code(405));
}

$json = json_decode(file_get_contents('php://input'));

if(empty($json)) {
    header('Content-Type:application/json');
    http_response_code(400);
    exit('{ "message": "invalid body"}');
}

use infrastructure\order\OrderPayments;

$orderUpdateClass = new OrderPayments($connectionDB);

if($json->status != 'approved') {
    $retorno = $orderUpdateClass->cancelaPedido(intval($json->orderCode));
} else {
    $retorno = $orderUpdateClass->aprovaPedido(intval($json->orderCode));
}

header('Content-Type:application/json');
http_response_code(200);
echo json_encode($retorno);