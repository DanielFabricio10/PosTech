<?php

namespace domain\entities;

Class Product {

    private $nameProduct;
    private $description;
    private $category;
    private $reference;
    private $price;
    private $quantity;

    public function getNameProduct() {
        return $this->nameProduct;
    }

    public function setNameProduct($nameProduct) {

        if(!empty($nameProduct) && is_string($nameProduct)){
            $this->nameProduct = $nameProduct;
            return true;
        }else{
            return false;
        }
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {

        if(!empty($description) && is_string($description)){
            $this->description = $description;
            return true;
        }else{
            return false;
        }
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {

        if(!empty($category) && is_string($category)){
            $this->category = $category;
            return true;
        }else{
            return false;
        }
    }

    public function getReference() {
        return $this->reference;
    }

    public function setReference($reference) {

        if(!empty($reference) && is_string($reference)){
            $this->reference = $reference;
            return true;
        }else{
            return false;
        }
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {

        if(!empty($price) && is_string($price) && $price > 0){
            $this->price = $price;
            return true;
        }else{
            return false;
        }
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {

        if(!empty($quantity) && is_string($quantity) && $quantity > 0){
            $this->quantity = $quantity;
            return true;
        }else{
            return false;
        }
    }

}