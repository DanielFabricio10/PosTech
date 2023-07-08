<?php

namespace infrastructure\product;

Class EditProduct {

    private $product;
    private $conn;

    function __construct($product, $conn){

        $this->product = $product;
        $this->conn = $conn;
    }

    function editProduct($json, $productAlreadyRegistry){

        $differences = $this->compararJSON(json_encode($json), $productAlreadyRegistry);

        if (empty($differences)) {
           return false;
        }

        //Seta os atributos do cliente
        $this->product->setNameProduct($json->nameProduct);
        $this->product->setDescription($json->description);
        $this->product->setCategory($json->category);
        $this->product->setReference($json->reference);
        $this->product->setPrice($json->price);
        $this->product->setQuantity($json->quantity);



        $responseDB = $this->updateDB();

    }

    function updateDB(){

        $nameProduct = $this->product->getNameProduct();
        $description = $this->product->getDescription();
        $category = $this->product->getCategory();
        $reference = $this->product->getReference();
        $price = $this->product->getPrice();
        $quantity = $this->product->getQuantity();

        $stmt = $this->conn->prepare('UPDATE Product SET nameproduct = :nameproduct, description = :description, category = :category, price = :price WHERE reference = :reference');

        $stmt->bindParam(':nameproduct', $nameProduct);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':reference', $reference);

        $stmt->execute();

        $rowsAffected = $stmt->rowCount();

        if ($rowsAffected > 0) {
            return true;
        }else{
            return false;
        }

    }

    function compararJSON($json1, $json2) {

        $array1 = json_decode($json1, true);
        $array2 = json_decode($json2, true);

        $diferencas = [];

        foreach ($array1 as $chave => $valor1) {
            if (isset($array2[$chave])) {
                $valor2 = $array2[$chave];

                if ($valor1 !== $valor2) {
                    $diferencas[$chave] = [
                        'valor1' => $valor1,
                        'valor2' => $valor2
                    ];
                }
            } else {
                $diferencas[$chave] = [
                    'valor1' => $valor1,
                    'valor2' => null
                ];
            }
        }

        if (empty($diferencas)) {
            return false;
        } else {
            return $diferencas;
        }
    }
}