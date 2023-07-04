<?php

$dsn = 'mysql:host=my_sql;dbname=PosTech;port=3306;';
$user = 'root';
$pass = 'root';


try {
 
    $teste = new PDO($dsn, $user, $pass);

    var_dump($teste);


} catch(PDOException $e) {
    echo 'aqui '.$e->getMessage();
}
