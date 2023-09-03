<?php

namespace domain\entities;

use domain\services\ClientGadget;

Class Client extends ClientGadget {

    private $name;
    private $lastName;
    private $dateOfBirth;
    private $phone;
    private $email;
    private $cpf;

    public function getName() {
        return $this->name;
    }

    public function setName($name) {

        if(!empty($name) && is_string($name)){
            $this->name = $name;
            return true;
        }else{
            return false;
        }
    }

    public function getLastname() {
        return $this->lastName;
    }

    public function setLastName($lastName) {

        if(!empty($lastName) && is_string($lastName)){
            $this->lastName = $lastName;
            return true;
        }else{
            return false;
        }
    }

    public function getDateOfBirth() {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth($dateOfBirth) {

        $newDate = $this->rectifyDate($dateOfBirth);

        if($newDate !== false){
            $this->dateOfBirth = $newDate;
            return true;
        }else{
            return false;
        }
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
       
        if($this->validatePhone($phone) === true){
            $this->phone = $phone;
            return true;
        }else{
            return false;
        }
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {

        if($this->validateEmail($email) === true){
            $this->email = $email;
            return true;
        }else{
            return false;
        }
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function setCpf($cpf) {

        if($this->validateCPF($cpf) === true){
            $this->cpf = $cpf;
            return true;
        }else{
            return false;
        }
    }
}