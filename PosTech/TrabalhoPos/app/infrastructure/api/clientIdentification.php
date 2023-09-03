<?php

ini_set('display_errors', 'Off');

require_once '../../autoload.php';
require_once '../../databaseConnection.php';

$auth = base64_encode($_SERVER['PHP_AUTH_USER'].':'.$_SERVER['PHP_AUTH_PW']);

if(empty($auth) || $auth != 'cG9zdGVjaDp0ZXN0ZQ==') {
    exit(http_response_code(403));
}

if(strtoupper($_SERVER['REQUEST_METHOD']) != 'GET') {
    exit(http_response_code(405));
}

$cpf = isset($_GET['cpf']) && !empty($_GET['cpf']) ? $_GET['cpf'] : '';

if(empty($cpf)){
    header('Content-Type: application/json');
    http_response_code(400);
    exit(json_encode(['message' => 'CPF enviado vazio por parametro'], JSON_UNESCAPED_SLASHES));
}

use infrastructure\client\FetchClient;

$FetchClient = new FetchClient($connectionDB);
$response = $FetchClient->clientIdentification($cpf);

if($response === false){
    header('Content-Type: application/json');
    http_response_code(400);
    exit(json_encode(["message" => "Cliente não encontrado na base"], JSON_UNESCAPED_SLASHES));
}else{
    header('Content-Type: application/json');
    http_response_code(200);
    exit($response);
}