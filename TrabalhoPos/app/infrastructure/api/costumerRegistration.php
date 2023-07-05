<?php

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

use domain\services\CPF;

try {
    $cpf = new Cpf($json->cpf);
} catch(Exception $error) {
    print_r($error->getMessage());
}

