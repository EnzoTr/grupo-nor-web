<?php

include_once 'Conexion.php';
class Detalle_pedido{
    var $objetos;
    public function __construct(){
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

    // Funcion para obtener el carrito del usuario
    function obtenerDetallesPedido($id_usuario){
        $sql = "SELECT detalles_pedido.*, producto.nombre AS nombre_producto, producto.foto AS producto_foto, producto.precio_envio_km, producto.cantidad_disponible AS stock, categoria.nombre AS nombre_categoria 
            FROM detalles_pedido 
            INNER JOIN producto ON detalles_pedido.id_producto = producto.id 
            INNER JOIN categoria ON producto.id_categoria = categoria.id
            WHERE detalles_pedido.id_usuario=:id_usuario AND detalles_pedido.id_pedido IS NULL";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_usuario'=>$id_usuario));
        $this->objetos = $query->fetchAll();
        return $this->objetos;
    }

    function obtener_Detalle_Pedido_Id($id_pedido){
        $sql = "SELECT dp.id_producto, dp.precio_unitario, dp.cantidad, p.nombre AS nombre_producto, p.foto AS producto_foto, p.cantidad_disponible AS stock, p.tipo_techo, p.color
            FROM detalles_pedido dp
            INNER JOIN producto p ON dp.id_producto = p.id
            WHERE dp.id_pedido=:id_pedido";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_pedido'=>$id_pedido));
        $this->objetos = $query->fetchAll();
        return $this->objetos;
    }

    function obtener_Detalle_Pedido_Id_Producto($id_producto, $id_usuario) {
        $sql = "SELECT dp.*, p.cantidad_disponible AS stock FROM detalles_pedido dp
            INNER JOIN producto p ON dp.id_producto = p.id 
            WHERE dp.id_producto=:id_producto AND dp.id_usuario=:id_usuario AND dp.id_pedido IS NULL";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_producto'=>$id_producto, ':id_usuario'=>$id_usuario));
        $this->objetos = $query->fetchAll();
        return $this->objetos;
    }

    function agregarDetallePedido($id_pedido, $id_producto, $cantidad, $precio_unitario, $id_usuario){
        try {
            $precio_unitario = floatval($precio_unitario); // Convertir el precio a float
            $sql = "INSERT INTO detalles_pedido (id_pedido, id_producto, id_usuario, cantidad, precio_unitario) 
                    VALUES (:id_pedido, :id_producto, :id_usuario, :cantidad, :precio_unitario)";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(
                ':id_pedido' => $id_pedido,
                ':id_producto' => $id_producto,
                ':id_usuario' => $id_usuario,
                ':cantidad' => $cantidad,
                ':precio_unitario' => $precio_unitario
            ));
            return true; // Retornar true si la inserción fue exitosa
        } catch (Exception $e) {
            error_log("Error al agregar detalle de pedido: " . $e->getMessage()); // Log del error
            return false; // Retornar false si hubo un error
        }
    }

    public function cambiarCantidad($id, $cantidad) {
        $sql = "UPDATE detalles_pedido SET cantidad = :cantidad WHERE id = :id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':cantidad'=>$cantidad, ':id'=>$id));
    }
    
    public function eliminarDetallePedido($id_detalle_pedido) {
        $sql = "DELETE FROM detalles_pedido WHERE id = :id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id_detalle_pedido));
    }

    public function carritoComprado($id_usuario, $id_pedido) {
        $sql = "UPDATE detalles_pedido SET id_pedido = :id_pedido WHERE id_usuario = :id_usuario AND id_pedido IS NULL";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id_pedido'=>$id_pedido, ':id_usuario'=>$id_usuario));
    }
    
}