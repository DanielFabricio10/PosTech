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

use domain\entities\Order;
use domain\entities\Adress;
use domain\entities\Client;
use domain\entities\Product;

use application\useCases\RegisterOrder;
use infrastructure\client\FetchClient;
use infrastructure\order\OrderPayments;

use infrastructure\product\FetchProduct;

$RegisterOrder = new RegisterOrder(new Client(), new FetchClient($connectionDB), new Adress(), new Product(), new FetchProduct($connectionDB), new Order(), $connectionDB);
$response = $RegisterOrder->registerClient($json);

header('Content-Type:application/json');

if($response->status != 1) {
    http_response_code(400);
} else {

     $payments = new OrderPayments($connectionDB);
     $retorno  = $payments->checkoutPedido(preg_replace('/[^0-9]/', '', $response->message));    

     if(!isset($retorno->qrCode)) {
        http_response_code(400);
        exit(json_encode(['message' => 'Pedido cancelado', 'Erro' => 'Não foi possivel gerar pagamento']));
     } else {
        http_response_code(200);
        exit(json_encode(['message' => $response->message, 'status' => 'Aguardando Pagamento', 'qrCode' => str_replace(' ', '', $retorno->qrCode)]));
     }
}

