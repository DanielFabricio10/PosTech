<?php

ini_set('display_errors', 'Off');

require_once '../../autoload.php';
require_once '../../databaseConnection.php';

$auth = base64_encode($_SERVER['PHP_AUTH_USER'].':'.$_SERVER['PHP_AUTH_PW']);

if(empty($auth) || $auth != 'cG9zdGVjaDp0ZXN0ZQ==') {
    exit(http_response_code(403));
}

if(strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    exit(http_response_code(405));
}

$json = json_decode(file_get_contents('php://input'));

if(empty($json)) {
    header('Content-Type:application/json');
    http_response_code(400);
    exit('{ "message": "invalid body"}');
}

use infrastructure\product\FetchProduct;

$FetchProduct = new FetchProduct($connectionDB);
$returnProduct = $FetchProduct->searchProduct($json->reference);

if($returnProduct !== false) {
	header('Content-Type:application/json');
	http_response_code(400);
	exit(json_encode(['message' => 'Produto já cadastrado no sistema']));
}

use domain\entities\Product;
use application\useCases\RegisterProduct;

$Product = new Product();
$RegisterProduct = new RegisterProduct($Product, $connectionDB);
$response = $RegisterProduct->registerProduct($json);

if($response === false){
	header('Content-Type:application/json');
    http_response_code(400);
    exit(json_encode(['message' => 'Erro ao cadastrar produto']));
}else{
	header('Content-Type:application/json');
    http_response_code(200);
	exit(json_encode(['message' => 'Sucesso ao cadastrar produto']));
}