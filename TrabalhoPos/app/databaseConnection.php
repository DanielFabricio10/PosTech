<?php

define('DSN', 'mysql:host=my_sql;dbname=PosTech;port=3306;');
define('USER', 'root');
define('PASS', 'root');

try {
    $connectionDB = new PDO(DSN, USER, PASS);
} catch(PDOException $e) {
    exit('Erro conexão banco de dados: '.$e->getMessage());
}
