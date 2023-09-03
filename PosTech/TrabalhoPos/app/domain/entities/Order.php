<?php

namespace domain\entities;

Class Order {

    private $order;
    private $price;
    private $idClient;
    private $quantity;

    public function getIdClient() {
        return $this->idClient;
    }

    public function setIdClient($idClient) {

        if(!empty($idClient) && is_int($idClient)){
            $this->idClient = $idClient;
            return true;
        }else{
            return false;
        }
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {

        if(!empty($quantity) && is_int($quantity)){
            $this->quantity = $quantity;
            return true;
        }else{
            return false;
        }
    }

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {

        if(!empty($order) && is_int($order)){
            $this->order = $order;
            return true;
        }else{
            return false;
        }
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($value) {

        if(!empty($price) && is_float($price)){
            $this->price = $price;
            return true;
        }else{
            return false;
        }
    }
}