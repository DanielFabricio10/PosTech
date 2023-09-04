<?php

namespace infrastructure\order;

class RegisterOrderDB{

    private $conn;
    private $client;

    function __construct($client, $conn){

        $this->conn  = $conn;
        $this->client  = $client;

    }

    function insertOrderDB($arrayItens){

        $stmt = $this->conn->prepare('INSERT INTO `Order` (cpfcnpj, statuspedido) VALUES (:cpfcnpj, :statuspedido)');

        $CPF = $this->client->getCpf();
        $statusPedido = 'pendente';

        $stmt->bindParam(':cpfcnpj', $CPF);
        $stmt->bindParam(':statuspedido', $statusPedido);

        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if($rowCount < 1){
            return false;
        }

        $idOrder = $this->conn->lastInsertId();

        $stmt2 = $this->conn->prepare('INSERT INTO OrderProduct (idpedido, reference, price, quantity) VALUES (:idpedido, :reference, :price, :quantity)');

        foreach($arrayItens as $itens){

            $stmt2->bindParam(':idpedido', $idOrder);
            $stmt2->bindParam(':reference', $itens['reference']);
            $stmt2->bindParam(':price',  $itens['price']);
            $stmt2->bindParam(':quantity',  $itens['quantity']);

            $stmt2->execute();
        }

        $this->fakeCheckout($idOrder);

        return (object) ['code' => true, 'idPedido' => $idOrder];
    }

    function fakeCheckout($idOrder){

        $stmt = $this->conn->prepare('UPDATE `Order` SET statuspedido = :statuspedido WHERE idpedido = :idpedido');

        $statusPedido = 'Em preparacao';

        $stmt->bindParam(':statuspedido', $statusPedido);
        $stmt->bindParam(':idpedido', $idOrder);

        $stmt->execute();

        $rowsAffected = $stmt->rowCount();

        if ($rowsAffected > 0) {

            $stmt2 = $this->conn->prepare('INSERT INTO QueueProducts (date, idpedido, status) VALUES (:date, :idpedido, :status)');

            $date = date('Y-m-d H:i:s');

            $status = 'Recebido';

            $stmt2->bindParam(':date',  $date);
            $stmt2->bindParam(':idpedido', $idOrder);
            $stmt2->bindParam(':status',  $status);

            $stmt2->execute();

        }else{
            return (object) ['code' => false, 'idPedido' => $idOrder];
        }

    }
}