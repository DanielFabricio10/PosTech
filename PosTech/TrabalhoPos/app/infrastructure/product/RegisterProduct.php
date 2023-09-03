<?php

namespace infrastructure\product;

Class RegisterProduct {

    private $product;
    private $conn;

    function __construct($product, $conn){

        $this->product = $product;
        $this->conn = $conn;
    }

    function registerProduct($json){

        //Seta os atributos do produto
        $this->product->setNameProduct($json->nameProduct);
        $this->product->setDescription($json->description);
        $this->product->setCategory($json->category);
        $this->product->setReference($json->reference);
        $this->product->setPrice($json->price);
        $this->product->setQuantity($json->quantity);

        $responseDB = $this->insertDB();

    }

    function insertDB(){

        $nameProduct = $this->product->getNameProduct();
        $description = $this->product->getDescription();
        $category = $this->product->getCategory();
        $reference = $this->product->getReference();
        $price = $this->product->getPrice();
        $quantity = $this->product->getQuantity();
        $datacriacao = date('Y-m-d');

        $stmt = $this->conn->prepare('INSERT INTO Product (nameproduct, description, category, reference, price, quantity, datacriacao) VALUES (:nameproduct, :description, :category, :reference, :price, :quantity, :datacriacao)');

        $stmt->bindParam(':nameproduct', $nameProduct);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':reference', $reference);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':datacriacao', $datacriacao);

        $stmt->execute();

        $stmt2 = $this->conn->prepare("SELECT * FROM Product WHERE reference = :reference");

        $stmt2->bindParam(':reference', $reference);
        $stmt2->execute();

        $result = $stmt2->fetch($this->conn::FETCH_ASSOC);

        if(empty($result['reference'])){
            return false;
        }

        $json = [
            'ID' => $result['ID'],
            'nameProduct' => $result['nameproduct'],
            'description' => $result['description'],
            'category' => $result['category'],
            'reference' => $result['reference'],
            'price' => $result['price'],
            'quantity' => $result['quantity'],
            'creationDate' => $result['datacriacao'],
        ];

        return json_encode($json, JSON_UNESCAPED_SLASHES);
    }
}
