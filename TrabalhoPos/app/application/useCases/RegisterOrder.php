<?php

namespace application\useCases;
use infrastructure\order\RegisterOrderDB;

Class RegisterOrder{

    public $client;
    public $fetchClient;
    public $adress;
    public $product;
    public $fetchProduct;
    public $order;
    public $conn;

    private $insertOrder;

    function __construct($client, $fetchClient, $adress, $product, $fetchProduct, $order, $conn){

        $this->client = $client;
        $this->fetchClient = $fetchClient;
        $this->adress = $adress;
        $this->product = $product;
        $this->fetchProduct = $fetchProduct;
        $this->order = $order;
        $this->conn  = $conn;

        $this->insertOrder = new RegisterOrderDB($this->client,$conn);
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

        $responseInsert = $this->insertOrder->insertOrderDB($arrayItens);

        if($responseInsert->code === false){
           return (Object)['status' => 0, 'message' => 'Erro ao inserir produtos no pedido'];
        }

        return (Object)['status' => 1, 'message' => 'Sucesso! Seu codigo de pedido: '.$responseInsert->idPedido];

    }

    function validateProduct(){

        $responseProduct = $this->fetchProduct->searchProduct($this->product->getReference());
        $responseProduct = json_decode($responseProduct);

        if($responseProduct === false){
            return (Object)['status' => 0, 'message' => 'Produto não encontrado na base' ];
        }

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


}