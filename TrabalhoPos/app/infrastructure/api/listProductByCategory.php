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

$category = isset($_GET['category']) && !empty($_GET['category']) ? $_GET['category'] : '';

if(empty($category)) {
    header('Content-Type:application/json');
    http_response_code(400);
    exit('{ "message": "invalid body"}');
}

use infrastructure\product\FetchProduct;

$FetchProduct = new FetchProduct($connectionDB);
$returnProduct = $FetchProduct->searchProductByCategory($category);

if($returnProduct !== false){
	header('Content-Type:application/json');
	http_response_code(200);
	exit($returnProduct);
}else{
    header('Content-Type:application/json');
	http_response_code(400);
	echo json_encode(['message' => 'Categoria de produtos não encontrada']);
}