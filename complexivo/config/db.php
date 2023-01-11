<?php
class Database {

     private $hostname = "localhost";
     private $database = "tienda_online";
     private $username = "root";
     private $password = "12345";


     function conectar()
     {
        try{
        $conexion = "mysql:host=" . $this ->hostname . "; dbname=" . $this ->database ;

        $options =[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        $pdo = new PDO($conexion , $this->username , $this->password , $options);

        return $pdo;

    }catch(PDOException $e){
        echo 'Error Conexion: ' . $e -> getMessage();
        exit;
    }
    }
}

