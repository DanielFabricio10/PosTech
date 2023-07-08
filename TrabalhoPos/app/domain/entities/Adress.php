<?php

namespace domain\entities;

use domain\services\AdressGadget;

Class Adress extends AdressGadget{

    private $street;
    private $number;
    private $zipCode;
    private $neighborhood;
    private $city;
    private $uf;

    public function getStreet() {
        return $this->street;
    }

    public function setStreet($street) {

        if(!empty($street) && is_string($street)){
            $this->street = $street;
            return true;
        }else{
            return false;
        }
    }

    public function getNumber() {
        return $this->number;
    }

    public function setNumber($number) {

        if(!empty($number) && is_int($number)){
            $this->number = $number;
            return true;
        }else{
            return false;
        }
    }

     public function getZipCode() {
        return $this->zipCode;
    }

    public function setZipCode($zipCode) {

        if($this->validateZipCode($zipCode)){
            $this->zipCode = $zipCode;
            return true;
        }else{
            return false;
        }
    }

    public function getNeighborhood() {
        return $this->neighborhood;
    }

    public function setNeighborhood($neighborhood) {

        if(!empty($neighborhood) && is_string($neighborhood)){
            $this->neighborhood = $neighborhood;
            return true;
        }else{
            return false;
        }
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {

        if(!empty($city) && is_string($city)){
            $this->city = $city;
            return true;
        }else{
            return false;
        }
    }

    public function getUf() {
        return $this->uf;
    }

    public function setUf($uf) {

        if($this->validateUf($uf) === true){
            $this->uf = $uf;
            return true;
        }else{
            return false;
        }
    }

}