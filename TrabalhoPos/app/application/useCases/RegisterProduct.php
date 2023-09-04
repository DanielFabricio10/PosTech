<?php

namespace application\useCases;
use infrastructure\product\RegisterProductDB;

Class RegisterProduct {

    private $product;
    private $conn;

    private $insertProduct;

    function __construct($product, $conn) {

        $this->product = $product;
        $this->conn = $conn;

        $this->insertProduct = new RegisterProductDB($this->product, $this->conn);
    }

    function registerProduct($json){

        //Seta os atributos do produto
        $this->product->setNameProduct($json->nameProduct);
        $this->product->setDescription($json->description);
        $this->product->setCategory($json->category);
        $this->product->setReference($json->reference);
        $this->product->setPrice($json->price);
        $this->product->setQuantity($json->quantity);

        $responseDB = $this->insertProduct->insertDB();

        if(!isset($responseDB->json) || empty($responseDB->json)) {
            return false;
        }

        return $responseDB->json;
    }
}