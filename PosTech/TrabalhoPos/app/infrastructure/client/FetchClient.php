<?php

namespace infrastructure\client;

use domain\entities\Client;

class FetchClient extends Client {

    private $conn;

    function __construct($conn){

        $this->conn = $conn;
    }

    function clientIdentification($cpf){

        if($this->setCpf($cpf) === true){

            $cpf = $this->getCpf();

            $stmt = $this->conn->prepare("SELECT * FROM Client WHERE cpfcnpj = :cpfcnpj");

            $stmt->bindParam(':cpfcnpj', $cpf);
            $stmt->execute();

            $result = $stmt->fetch($this->conn::FETCH_ASSOC);

            if(empty($result['cpfcnpj'])){
                return false;
            }

            $idCliente  = $result['cpfcnpj'];
            $name       = $result['name'];
            $lastName   = $result['lastname'];
            $birthDate  = $result['phone'];
            $phone      = $result['email'];
            $email      = $result['birthdate'];

            $json = [
                'ID' => $idCliente,
                'CPF' => $cpf,
                'Name' => $name,
                'LastName' => $lastName,
                'DateOfBirth' => $birthDate,
                'Email' => $email,
                'Phone' => $phone,
            ];

            return json_encode($json, JSON_UNESCAPED_SLASHES);
        }else{
            return false;
        }
    }


}