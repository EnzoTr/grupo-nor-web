<?php
//llamamos a la conexion de la bd
    include_once 'Conexion.php';
    class Provincia{
        var $objetos;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }
        //consultas a la bd
        function llenar_provincia(){
            $sql ="SELECT * FROM provincias";
            $query = $this->acceso->prepare($sql); 
            $query->execute();
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }
    }