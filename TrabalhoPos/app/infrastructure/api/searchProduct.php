<?php

ini_set('display_errors', 'Off');

require_once '../../autoload.php';
require_once '../../databaseConnection.php';

$auth = base64_encode($_SERVER['PHP_AUTH_USER'].':'.$_SERVER['PHP_AUTH_PW']);

if(empty($auth) || $auth != 'ZGFuaWVsOnRlc3Rl') {
    exit(http_response_code(403));
}

if(strtoupper($_SERVER['REQUEST_METHOD']) != 'GET') {
    exit(http_response_code(405));
}

if(empty($_GET['reference'])) {
    header('Content-Type:application/json');
    http_response_code(400);
    exit('{ "message": "invalid body"}');
}

$reference = $_GET['reference'];

use infrastructure\product\FetchProduct;

$FetchProduct = new FetchProduct($connectionDB);
$returnProduct = $FetchProduct->searchProduct($reference);

if($returnProduct !== false){
	header('Content-Type:application/json');
	http_response_code(200);
	echo ($returnProduct);
	exit();
}else{
    header('Content-Type:application/json');
	http_response_code(400);
	echo json_encode(['message' => 'Produto não encontrado na base']);
}