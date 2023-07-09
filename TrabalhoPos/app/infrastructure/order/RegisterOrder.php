<?php

namespace infrastructure\order;

Class RegisterOrder{

    public $client;
    public $fetchClient;
    public $adress;
    public $product;
    public $fetchProduct;
    public $order;
    public $conn;

    function __construct($client, $fetchClient, $adress, $product, $fetchProduct, $order, $conn){

        $this->client = $client;
        $this->fetchClient = $fetchClient;
        $this->adress = $adress;
        $this->product = $product;
        $this->fetchProduct = $fetchProduct;
        $this->order = $order;
        $this->conn  = $conn;
    }

    function registerClient($json){

        $this->client->setCpf($json->cpf);

        if($this->validateClient()->status == 0){
            return (Object)['status' => 0, 'message' =>$this->validateClient()->message];
        }

        $arrayItens = [];

        foreach($json->products as $products){

            $this->product->setReference($products->reference);

            if($this->validateProduct()->status == 0 ){
                return (Object)['status' => 0, 'message' => $this->validateProduct()->message];
            }

            $this->product->setQuantity($products->quantity);

            array_push($arrayItens, [
                'reference' => $this->product->getReference(),
                'price' => $this->product->getPrice(),
                'quantity' => $this->product->getQuantity(),
            ]);
        }

        if($this->insertDB($arrayItens) === false){
           return (Object)['status' => 0, 'message' => 'Erro ao inserir produtos no pedido'];
        }

        return (Object)['status' => 1, 'message' => 'Sucesso pra inserir produto'];

    }

    function validateProduct(){

        $responseProduct = $this->fetchProduct->searchProduct($this->product->getReference());

        if(is_null($responseProduct) || $responseProduct === false) {
            return (Object)['status' => 0, 'message' => 'Produto não encontrado na base' ];
        }

        $responseProduct = json_decode($responseProduct);

        if($responseProduct->quantity <= 0){
            return (Object)['status' => 0, 'message' => 'Produto não tem estoque' ];
        }

        $this->product->setPrice($responseProduct->price);

        return (Object)['status' => 1, 'message' => 'Sucesso ao encontrar produto'];
    }

    function validateClient(){

        $responseClient = $this->fetchClient->clientIdentification($this->client->getCpf());

        if($responseClient === false){
            return (Object)['status' => 0, 'message' => 'Cliente não encontrado na base'];
        }

        $responseClient = json_decode($responseClient);

        $this->order->setIdClient($responseClient->ID);

        return (Object)['status' => 1, 'message' => 'Sucesso ao encontrar cliente'];
    }

    function insertDB($arrayItens){

        $stmt = $this->conn->prepare('INSERT INTO `Order` (cpfcnpj, statuspedido) VALUES (:cpfcnpj, :statuspedido)');

        $CPF = $this->client->getCpf();
        $statusPedido = 'pendente';

        $stmt->bindParam(':cpfcnpj', $CPF);
        $stmt->bindParam(':statuspedido', $statusPedido);

        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if($rowCount < 1){
            return false;
        }

        $idOrder = $this->conn->lastInsertId();

        $stmt2 = $this->conn->prepare('INSERT INTO OrderProduct (idpedido, reference, price, quantity) VALUES (:idpedido, :reference, :price, :quantity)');

        foreach($arrayItens as $itens){

            $stmt2->bindParam(':idpedido', $idOrder);
            $stmt2->bindParam(':reference', $itens['reference']);
            $stmt2->bindParam(':price',  $itens['price']);
            $stmt2->bindParam(':quantity',  $itens['quantity']);

            $stmt2->execute();
        }

        $this->fakeCheckout($idOrder);

        return true;
    }

    function fakeCheckout($idOrder){

        $stmt = $this->conn->prepare('UPDATE `Order` SET statuspedido = :statuspedido WHERE idpedido = :idpedido');

        $statusPedido = 'Em preparacao';

        $stmt->bindParam(':statuspedido', $statusPedido);
        $stmt->bindParam(':idpedido', $idOrder);

        $stmt->execute();

        $rowsAffected = $stmt->rowCount();

        if ($rowsAffected > 0) {

            $stmt2 = $this->conn->prepare('INSERT INTO QueueProducts (date, idpedido, status) VALUES (:date, :idpedido, :status)');

            $date = date('Y-m-d H:i:s');

            $status = 'Pendente';

            $stmt2->bindParam(':date',  $date);
            $stmt2->bindParam(':idpedido', $idOrder);
            $stmt2->bindParam(':status',  $status);

            $stmt2->execute();

        }else{
            return false;
        }

    }
}