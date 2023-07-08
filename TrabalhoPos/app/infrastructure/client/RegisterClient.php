<?php

namespace infrastructure\client;

Class RegisterClient{

    public $client;
    public $adress;
    public $conn;

    function __construct($client, $adress, $conn){

        $this->client = $client;
        $this->adress = $adress;
        $this->conn  = $conn;
    }

    function registerClient($json){

        //Seta os atributos do cliente
        $this->client->setCpf($json->cpf);
        $this->client->setName($json->name);
        $this->client->setLastName($json->lastName);
        $this->client->setDateOfBirth($json->dateOfBirth);
        $this->client->setPhone($json->phone);
        $this->client->setEmail($json->email);

        //seta os atributos do endereço
        $this->adress->setStreet($json->adress->street);
        $this->adress->setNumber($json->adress->number);
        $this->adress->setZipCode($json->adress->zipcode);
        $this->adress->setNeighborhood($json->adress->neighborhood);
        $this->adress->setCity($json->adress->city);
        $this->adress->setUf($json->adress->uf);

        $responseDB = $this->insertDB();

        if($responseDB->status != 1){
            return false;
        }
       $json =  ['message' => 'Sucesso ao inserir o cliente'];

        return json_encode($json, JSON_UNESCAPED_SLASHES);
    }

    function insertDB(){
        $cpf    = $this->client->getCpf();
        $name   = $this->client->getName();
        $lastName = $this->client->getLastName();
        $phone = $this->client->getPhone();
        $email = $this->client->getEmail();
        $dateBirth = $this->client->getDateOfBirth();

        $street    = $this->adress->getStreet();
        $number   = $this->adress->getNumber();
        $zipCode = $this->adress->getZipCode();
        $neighborhood = $this->adress->getNeighborhood();
        $city = $this->adress->getCity();
        $uf = $this->adress->getUf();

        //select pra validar se cliente ja existe

        $stmt = $this->conn->prepare('INSERT INTO Client (cpfcnpj, name, lastname, phone, email, birthdate) VALUES (:cpfcnpj, :name, :lastname, :phone, :email, :birthdate)');
        $stmt->bindParam(':cpfcnpj', $cpf);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':lastname', $lastName);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':birthdate', $dateBirth);

        $stmt->execute();

        $stmt2 = $this->conn->prepare('INSERT INTO Address (cpfcnpj, street, number, zipcode, neighborhood, city, uf) VALUES (:cpfcnpj, :street, :number, :zipcode, :neighborhood, :city, :uf)');
        $stmt2->bindParam(':cpfcnpj',  $cpf);
        $stmt2->bindParam(':street', $street);
        $stmt2->bindParam(':number',$number );
        $stmt2->bindParam(':zipcode',  $zipCode);
        $stmt2->bindParam(':neighborhood',$neighborhood );
        $stmt2->bindParam(':city',$city );
        $stmt2->bindParam(':uf',  $uf );

        $stmt2->execute();

        return (Object)['status' => 1, 'message' => 'sucesso'];
    }

}