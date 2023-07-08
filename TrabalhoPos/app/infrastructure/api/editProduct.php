<?php

ini_set('display_errors', 'Off');

/*
    {
	"nameProduct": "X-bacons",
	"description": "Um lanche com hamburguer salada bacon e muito sabor",
	"category": "Lanches",
	"reference": "asfasf",
	"price": "27.50",
	"quantity":"15"

}
*/

require_once '../../autoload.php';
require_once '../../databaseConnection.php';

$auth = base64_encode($_SERVER['PHP_AUTH_USER'].':'.$_SERVER['PHP_AUTH_PW']);

if(empty($auth) || $auth != 'ZGFuaWVsOnRlc3Rl') {
    exit(http_response_code(403));
}

if(strtoupper($_SERVER['REQUEST_METHOD']) != 'PUT') {
    exit(http_response_code(405));
}

if(empty($_GET['reference'])) {
    header('Content-Type:application/json');
    http_response_code(400);
    exit('{ "message": "invalid body"}');
}

$reference = $_GET['reference'];

$json = json_decode(file_get_contents('php://input'));

if(empty($json)) {
    header('Content-Type:application/json');
    http_response_code(400);
    exit('{ "message": "invalid body"}');
}

use infrastructure\product\FetchProduct;

$FetchProduct = new FetchProduct($connectionDB);
$returnProduct = $FetchProduct->searchProduct($json->reference);

if($returnProduct === false){
	header('Content-Type:application/json');
	http_response_code(400);
	echo json_encode(['message' => 'Produto não encontrado no sistema']);
	exit();
}

use domain\entities\Product;
use infrastructure\product\EditProduct;

$Product = new Product();
$EditProduct = new EditProduct($Product, $connectionDB);
$response = $EditProduct->editProduct($json, $returnProduct);

if($response === false){
	header('Content-Type:application/json');
    http_response_code(400);
    echo json_encode(['message' => 'Erro ao atualizar produto, os campos estão iguais']);
    exit();
}else{
	header('Content-Type:application/json');
    http_response_code(200);
    echo json_encode(['message' => 'Sucesso ao atualizar produto']);
    exit();
}


