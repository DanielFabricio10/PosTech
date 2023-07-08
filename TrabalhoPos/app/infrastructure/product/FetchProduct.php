<?php

namespace infrastructure\product;

use domain\entities\Product;

Class FetchProduct extends Product {

    protected $conn;

    function __construct($conn){

        $this->conn = $conn;
    }

    function searchProduct($reference){

        if(empty($reference)){
            return false;
        }

        $stmt = $this->conn->prepare("SELECT * FROM Product WHERE reference = :reference");

        $stmt->bindParam(':reference', $reference);
        $stmt->execute();

        $result = $stmt->fetch($this->conn::FETCH_ASSOC);

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

    function searchProductByCategory($category){

        if(empty($category)){
            return false;
        }

        $stmt = $this->conn->prepare("SELECT * FROM Product WHERE category = :category");

        $stmt->bindParam(':category', $category);
        $stmt->execute();

        $result = $stmt->fetchAll($this->conn::FETCH_ASSOC);

        if(empty($result)){
            return false;
        }

        foreach ($result as $result) {

            $json[] = [
                'ID' => $result['ID'],
                'nameProduct' => $result['nameproduct'],
                'description' => $result['description'],
                'category' => $result['category'],
                'reference' => $result['reference'],
                'price' => $result['price'],
                'quantity' => $result['quantity'],
                'creationDate' => $result['datacriacao'],
            ];

         }

       return json_encode($json, JSON_UNESCAPED_SLASHES);
    }
}