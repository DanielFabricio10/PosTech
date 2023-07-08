<?php
ini_set('display_errors', 'ON');

require_once '../../autoload.php';
require_once '../../databaseConnection.php';

$auth = base64_encode($_SERVER['PHP_AUTH_USER'].':'.$_SERVER['PHP_AUTH_PW']);

if(empty($auth) || $auth != 'ZGFuaWVsOnRlc3Rl') {
    exit(http_response_code(403));
}

if(strtoupper($_SERVER['REQUEST_METHOD']) != 'GET') {
    exit(http_response_code(405));
}

if(empty($_GET['cpf'])){
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['message' => 'CPF enviado vazio por parametro'], JSON_UNESCAPED_SLASHES);
    exit();
}

use infrastructure\client\FetchClient;

$cpf = $_GET['cpf'];
$FetchClient = new FetchClient($connectionDB);
$response = $FetchClient->clientIdentification($cpf);

if($response === false){
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(["message" => "Cliente não encontrado na base"], JSON_UNESCAPED_SLASHES);
    exit();
}else{
    header('Content-Type: application/json');
    http_response_code(200);
    echo ($response);
    exit();
}