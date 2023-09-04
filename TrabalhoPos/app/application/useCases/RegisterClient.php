<?php

namespace application\useCases;

use infrastructure\client\RegisterClientDB;

Class RegisterClient{

    public $client;
    public $adress;
    public $conn;

    private $insertClient;

    function __construct($client, $adress, $conn) {

        $this->client = $client;
        $this->adress = $adress;
        $this->conn   = $conn;

        $this->insertClient = new RegisterClientDB($client, $adress, $conn);
    }

    function registerClient($json) {

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

        $responseDB = $this->insertClient->registerClientDB();

        if($responseDB->status != 1){
            return false;
        }
       
        return json_encode(['message' => 'Sucesso ao inserir o cliente'], JSON_UNESCAPED_SLASHES);
    }

}