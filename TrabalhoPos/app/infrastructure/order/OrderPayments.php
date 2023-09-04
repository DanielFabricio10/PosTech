<?php

namespace infrastructure\order;

class OrderPayments {

    protected $conn;

    private $url = 'https://api.mercadopago.com';
    private $tokenMercadoPago = 'TEST-8849568952271777-090317-6e93260f213b156493ccf14f77d16f98-503810986';

    private $headers = [];

    function __construct($conn) {
        $this->conn = $conn;
    }

    function checkoutPedido($idPedido) {
        
        $dadosPedido = $this->conn->prepare("SELECT c.name, c.lastname, o.idpedido, c.email, SUM(op.price) as valorTotal
        FROM `Order` o
        INNER JOIN Client c on o.cpfcnpj = c.cpfcnpj 
        INNER JOIN OrderProduct op on o.idpedido = op.idpedido 
        INNER JOIN Product p on op.reference = p.reference 
        WHERE o.idpedido = :idpedido;
        ");

        $dadosPedido->bindParam(':idpedido', intval($idPedido));
        $dadosPedido->execute();

        $result = $dadosPedido->fetchAll($this->conn::FETCH_ASSOC);
        $result = $result[0];
        
        if(empty($result['idpedido'])) {
            return false;
        }

        $jsonPix = '{
            "description": "Payment for Order '.$result['idpedido'].'",
            "external_reference": "'.$result['idpedido'].'",
            "installments": 1,
            "metadata": {},
            "payer": {
                "first_name":"'.$result['name'].'",
                "last_name":"'.$result['lastname'].'",
                "email":"'.$result['email'].'",
                "identification": {
                    "type":"customer",
                    "number":"1469557394"
                }
            },
            "payment_method_id": "pix",
            "token": "ff8080814c11e237014c1ff593b57b4d'.$result['idpedido'].'",
            "transaction_amount": '.floatval($result['valorTotal']).'
        }';

        $response = $this->request('/v1/payments', 'POST', $jsonPix);
       
        if(!in_array($response->statusHttp,[200,201,204]) || empty($response->response->point_of_interaction->transaction_data->ticket_url)) {
            return $this->cancelaPedido($idPedido);
        }

        return (object)['status' => 1, 'qrCode' => $response->response->point_of_interaction->transaction_data->ticket_url];
    }


    private function setHeaders() {
        $this->headers = [
			"Content-Type: application/json",
			"Accept: application/json",
			"Authorization: Bearer ".$this->tokenMercadoPago
		];
    }

    
    private function request($uri, $metodo, $body) {

         $this->setHeaders();

        $curlOptions = array(
			CURLOPT_URL => $this->url.$uri,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_CONNECTTIMEOUT => 10,					
			CURLOPT_TIMEOUT => 10,  						
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => strtoupper($metodo),
			CURLOPT_HTTPHEADER => $this->headers
		);
		
		if(in_array(strtoupper($metodo),['POST','PUT']) && $body != '') {
			$curlOptions[CURLOPT_POSTFIELDS] = $body;
		}
		
		$curl = curl_init();
		curl_setopt_array($curl, $curlOptions);

		$responseText = curl_exec($curl);
		$response     = json_decode($responseText);
		$http_status  = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$error = curl_error($curl);
		curl_close($curl);
		
		if($error) {
			return (object)['status' => 0,'statusHttp' => $http_status, 'erro' => $error];
		} 

		return (object)['status' => 1, 'statusHttp' => $http_status, 'response' => $response, 'responseText' => $responseText];
    }

    function cancelaPedido($idPedido) {

        $stmt = $this->conn->prepare("UPDATE `Order`
        SET statuspedido = 'Cancelado'
        WHERE idpedido = :idpedido;");

        $stmt->bindParam(':idpedido', $idPedido);
        $stmt->execute();

        return (object)['status' => 1, 'message' => 'Pedido '.$idPedido.' Cancelado com sucesso'];
    }

    function aprovaPedido($idPedido) {

        $stmt = $this->conn->prepare("UPDATE `Order`
        SET statuspedido = 'Em Preparação'
        WHERE idpedido = :idpedido;");

        $stmt->bindParam(':idpedido', $idPedido);
        $stmt->execute();

        return (object)['status' => 1, 'message' => 'Pedido '.$idPedido.' Aprovado com sucesso'];
    }
}