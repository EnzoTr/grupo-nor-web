<?php
//llamamos a la conexion de la bd
    include_once 'Conexion.php';
    class Localidad{
        var $objetos;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }
        //consultas a la bd
        function llenar_localidad($id_provincia){
            $sql ="SELECT * FROM localidades
                    WHERE id_provincia = :id";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':id'=>$id_provincia));
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }
    }