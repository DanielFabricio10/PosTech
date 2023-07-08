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

if(empty($_GET['category'])) {
    header('Content-Type:application/json');
    http_response_code(400);
    exit('{ "message": "invalid body"}');
}

$category = $_GET['category'];

use infrastructure\product\FetchProduct;

$FetchProduct = new FetchProduct($connectionDB);
$returnProduct = $FetchProduct->searchProductByCategory($category);

if($returnProduct !== false){
	header('Content-Type:application/json');
	http_response_code(200);
	echo ($returnProduct);
	exit();
}else{
    header('Content-Type:application/json');
	http_response_code(400);
	echo json_encode(['message' => 'Categoria de produtos não encontrada']);
}