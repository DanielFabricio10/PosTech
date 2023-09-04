<?php

namespace infrastructure\client;

class RegisterClientDB {

    private $client;
    private $adress;
    private $conn;

    function __construct($client, $adress, $conn){

        $this->client = $client;
        $this->adress = $adress;
        $this->conn  = $conn;
    }

    function registerClientDB(){

        $cpf       = $this->client->getCpf();
        $name      = $this->client->getName();
        $lastName  = $this->client->getLastName();
        $phone     = $this->client->getPhone();
        $email     = $this->client->getEmail();
        $dateBirth = $this->client->getDateOfBirth();

        $street       = $this->adress->getStreet();
        $number       = $this->adress->getNumber();
        $zipCode      = $this->adress->getZipCode();
        $neighborhood = $this->adress->getNeighborhood();
        $city         = $this->adress->getCity();
        $uf           = $this->adress->getUf();

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