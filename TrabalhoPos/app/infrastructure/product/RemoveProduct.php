<?php

namespace infrastructure\product;

Class RemoveProduct{

    private $product;
    private $conn;

    function __construct($product, $conn){

        $this->product = $product;
        $this->conn = $conn;
    }

    function removeProduct($reference){

        $stmt = $this->conn->prepare('DELETE FROM Product WHERE reference = :reference');
        $stmt->bindParam(':reference', $reference);
        $stmt->execute();

        $rowsAffected = $stmt->rowCount();

        if ($rowsAffected > 0) {
            return true;
        }else{
            return false;
        }
    }
}