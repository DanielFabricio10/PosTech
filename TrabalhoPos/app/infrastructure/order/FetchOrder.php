<?php

namespace infrastructure\order;

class FetchOrder{

    protected $conn;

    function __construct($conn){

        $this->conn = $conn;
    }

    function searchOrder(){

        $stmt = $this->conn->prepare("SELECT * FROM `Order` WHERE statuspedido NOT LIKE 'finalizado' ORDER BY CASE WHEN statuspedido = 'Pronto' THEN 1 WHEN statuspedido = 'Em preparacao' THEN 2 WHEN statuspedido = 'Recebido' THEN 3 END");
        $stmt->execute();

        $result = $stmt->fetchAll($this->conn::FETCH_ASSOC);

        $arrayOrder = [];

        foreach ($result as $result) {

            $arrayProducts = [];

            $stmt2 = $this->conn->prepare("SELECT * FROM OrderProduct WHERE :idpedido");
            $stmt2->bindParam(':idpedido', $result['idpedido']);

            $stmt2->execute();

            $jsonPedido = [
                'ID' => $result['idpedido'],
                'CPF' => $result['cpfcnpj'],
                'OrderStatus' => $result['statuspedido']
            ];

            $result2 = $stmt2->fetchAll($this->conn::FETCH_ASSOC);
            $valorTotal = 0;
            foreach ($result2 as $result2) {

                $valorTotal += $result2['price'] * $result2['quantity'];

                array_push($arrayProducts, [
                    'Reference' => $result2['reference'],
                    'UnitPrice' => $result2['price'],
                    'Quantity' => $result2['quantity']
                ]);
            }

            $jsonPedido['Products'] = $arrayProducts;
            $jsonPedido['TotalValue'] = $valorTotal;

            array_push($arrayOrder, $jsonPedido);
         }


         return json_encode($arrayOrder);
    }

    function searchOrderById($id) {

        $stmt3 = $this->conn->prepare("SELECT * FROM `Order` WHERE idpedido = :idpedido");
        $stmt3->bindParam(':idpedido', $id);

        $stmt3->execute();

        $result3 = $stmt3->fetch($this->conn::FETCH_ASSOC);

        if(intval($result3['idpedido']) > 0) {

            $jsonPedido = [
                'ID' => $result3['idpedido'],
                'CPF' => $result3['cpfcnpj'],
                'OrderStatus' => $result3['statuspedido']
            ];

        } else {
            $jsonPedido = ['message' => 'Pedido '.$id.' não encontrado'];
        }
        
        return json_encode($jsonPedido);
    }

}