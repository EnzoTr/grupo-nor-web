<?php

//llamamos a la conexion de la bd

    include_once 'Conexion.php';
    class Pedido{
        var $objetos;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }
    
        public function crear_pedido($id_usuario, $fecha_registro, $total, $metodo_pago, $envio, $estado, $direccion_envio) {
            $this->acceso->beginTransaction();
        
            try {
                $sql = "INSERT INTO pedido (fecha, estado, metodo_pago, id_usuario, total, precio_envio, direccion_envio) 
                        VALUES(:fecha, :estado, :metodo_pago, :id_usuario, :total, :precio_envio, :direccion_envio)";
        
                $query = $this->acceso->prepare($sql);
        
                $query->execute(array(
                    ':id_usuario' => $id_usuario, 
                    ':fecha' => $fecha_registro, 
                    ':total' => $total, 
                    ':metodo_pago' => $metodo_pago, 
                    ':estado' => $estado,
                    ':precio_envio' => $envio,
                    ':direccion_envio' => $direccion_envio
                ));
        
                $id_pedido = $this->acceso->lastInsertId();
        
                $this->acceso->commit();
        
                return $id_pedido;
            } catch (Exception $e) {
                $this->acceso->rollBack();
                throw $e;
            }
        }

        public function obtener_pedidos(){
            $sql = "SELECT p.*, u.nombres, u.apellidos, u.dni FROM pedido
                    p INNER JOIN usuario u ON p.id_usuario = u.id";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }

        public function obtener_pedidos_usuario($id_usuario){
            $sql = "SELECT * FROM pedido WHERE id_usuario=:id_usuario";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_usuario'=>$id_usuario));
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }

        public function modificar_pedido($id, $estado, $ruta_pdf) {
            $sql = "UPDATE pedido SET estado = :estado, ruta_pdf = :ruta_pdf WHERE id = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':estado'=>$estado, ':ruta_pdf'=>$ruta_pdf, ':id'=>$id));
        }

        public function eliminar_pedido($id_pedido) {
            $sql = "DELETE FROM pedido WHERE id = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id_pedido));
        }
    }