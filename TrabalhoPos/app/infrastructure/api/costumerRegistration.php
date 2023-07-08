<?php
ini_set('display_errors', 'Off');

/*{
	"cpf": 1124214,
	"name": "danilo",
	"lastName": "salles",
	"dateOfBirth": "2000/02/16",
	"phone": 997533471,
	"email": "danilo.ti@precode.com.br",
	"adress": {
		"street": "rua matinhos",
		"number": 962,
		"zipcode": 87045170,
		"neighborhood": "Vila Nova",
		"city": "maringa",
		"uf": "pr"
	}
}*/

require_once '../../autoload.php';
require_once '../../databaseConnection.php';

$auth = base64_encode($_SERVER['PHP_AUTH_USER'].':'.$_SERVER['PHP_AUTH_PW']);

if(empty($auth) || $auth != 'ZGFuaWVsOnRlc3Rl') {
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
use infrastructure\client\FetchClient;

$FetchClient = new FetchClient($connectionDB);
$returnClient = $FetchClient->clientIdentification($json->cpf);

if($returnClient !== false){
	header('Content-Type:application/json');
	http_response_code(400);
	echo json_encode(['message' => 'Cpf do cliente já está cadastrado']);
	exit();
}

use domain\entities\Client;
use domain\entities\Adress;
use infrastructure\client\RegisterClient;

$Client = new Client();
$Adress = new Adress();

$RegisterClient = new RegisterClient($Client, $Adress, $connectionDB);
$response = $RegisterClient->registerClient($json);

if($response === false){
	header('Content-Type:application/json');
    http_response_code(400);
    echo json_encode(['message' => 'Erro ao cadastrar cliente']);
    exit();
}else{
	header('Content-Type:application/json');
    http_response_code(200);
    echo json_encode(['message' => 'Sucesso ao cadastrar cliente']);
    exit();
}

