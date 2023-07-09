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
use infrastructure\order\RegisterOrder;
use infrastructure\client\FetchClient;
use infrastructure\product\FetchProduct;

$RegisterOrder = new RegisterOrder(new Client(), new FetchClient($connectionDB), new Adress(), new Product(), new FetchProduct($connectionDB), new Order(), $connectionDB);
$response = $RegisterOrder->registerClient($json);

if($response->status != 1) {
    header('Content-Type:application/json');
    http_response_code(400);
    exit('{ "message": "'.$response->message.'"}');
}else{
    header('Content-Type:application/json');
    http_response_code(200);
    exit('{ "message": "'.$response->message.'"}');
}