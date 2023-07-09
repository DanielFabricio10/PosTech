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

$reference = isset($_GET['reference']) && !empty($_GET['reference']) ? $_GET['reference'] : '';

if(empty($reference)) {
    header('Content-Type:application/json');
    http_response_code(400);
    exit('{ "message": "invalid body"}');
}

use infrastructure\product\FetchProduct;

$FetchProduct = new FetchProduct($connectionDB);
$returnProduct = $FetchProduct->searchProduct($reference);

if($returnProduct !== false) {
	header('Content-Type:application/json');
	http_response_code(200);
	exit($returnProduct);
} else {
    header('Content-Type:application/json');
	http_response_code(400);
	echo json_encode(['message' => 'Produto não encontrado na base']);
}