<?php
//llamamos a la conexion de la bd
    include_once 'Conexion.php';
    class UsuarioLocalidad{
        var $objetos;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }
        //consultas a la bd
        function crear_direccion($id_usuario,$id_localidades, $direccion, $referencia){
            $sql ="INSERT INTO usuario_localidades(direccion, referencia, id_localidades, id_usuario) 
                    VALUES(:direccion, :referencia, :id_localidades, :id_usuario)";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':direccion'=>$direccion,':referencia'=>$referencia, ':id_localidades'=>$id_localidades, ':id_usuario'=>$id_usuario));
        }

        function llenar_direcciones($id_usuario){
            $sql ="SELECT ul.id as id, direccion, referencia, l.localidad as localidad, p.provincia as provincia
            FROM usuario_localidades ul
            JOIN localidades l ON l.id = ul.id_localidades
            JOIN provincias p ON p.id = l.id_provincia
            WHERE id_usuario = :id AND estado = 'A'";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':id'=>$id_usuario));
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }

        function eliminar_direccion($id_direccion){
            $sql ="UPDATE usuario_localidades SET estado = 'I'
            WHERE id = :id_direccion";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':id_direccion'=>$id_direccion));
        }
    }