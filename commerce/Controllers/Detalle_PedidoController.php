<?php
include_once '../Models/Detalle_Pedido.php';
include_once '../Models/Producto.php';
include '../Util/Config/config.php';
//esta variable esta siendo instanciada en Producto.php y a la vez en Conexion.php
$detalle_pedido = new Detalle_Pedido();
$producto = new Producto();
//sirve para saber cuando el usuario entra en su sesion
session_start();

    if($_POST['funcion']=='agregar_carrito'){
        $id_producto = openssl_decrypt($_SESSION['product-verification'], CODE, KEY);
        $id_usuario = $_SESSION['id'];

        $detalle_pedido->obtener_Detalle_Pedido_Id_Producto($id_producto, $id_usuario);

        if (count($detalle_pedido->objetos) > 0) {
            if ($detalle_pedido->objetos[0]->stock == 0 || $detalle_pedido->objetos[0]->stock < $detalle_pedido->objetos[0]->cantidad + $_POST['cantidad']) {
                echo json_encode(['message' => 'Producto sin stock', 'status' => 'error']);
                return;
            }
            $detalle_pedido->cambiarCantidad($detalle_pedido->objetos[0]->id, $detalle_pedido->objetos[0]->cantidad + $_POST['cantidad']);
            echo json_encode(['message' => 'Producto agregado al carrito', 'status' => 'success']);
        } else {
            $cantidad = $_POST['cantidad'];
            $precio = floatval($_POST['precio']);
            //$tipoTecho = $_POST['tipoTecho'];
            //$color = $_POST['color'];
            $id_usuario = $_SESSION['id'];
            $resultado = $detalle_pedido->agregarDetallePedido(null, $id_producto, $cantidad, $precio, $id_usuario);
            $_SESSION['product-verification'] = null;

            if ($resultado) {
                echo json_encode(['message' => 'Producto agregado al carrito', 'status' => 'success']);
            } else {
                echo json_encode(['message' => 'Error al agregar el producto al carrito', 'status' => 'error']);
            }
        }
    }

    if ($_POST['funcion'] == 'agregar_carrito_tinglado') {
        $id_producto = $_POST['id_producto'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];
        $id_usuario = $_SESSION['id'];
        $resultado = $detalle_pedido->agregarDetallePedido(null, $id_producto, $cantidad, $precio, $id_usuario);
        $_SESSION['product-verification'] = null;

        if ($resultado) {
            echo json_encode(['message' => 'Producto agregado al carrito', 'status' => 'success']);
        } else {
            echo json_encode(['message' => 'Error al agregar el producto al carrito', 'status' => 'error']);
        }
    }

    if($_POST['funcion']=='obtener_carrito'){
        $id_usuario = $_SESSION['id'];
        $detalle_pedido->obtenerDetallesPedido($id_usuario);
        $json=array();
        
        foreach($detalle_pedido->objetos as $objeto){
            $json[]=array(
                'id'=>$objeto->id,
                'nombre_producto'=>$objeto->nombre_producto,
                'foto'=>$objeto->producto_foto,
                'id_producto'=>$objeto->id_producto,
                'cantidad'=>$objeto->cantidad,
                'precio'=>$objeto->precio_unitario,
                'precio_envio_km'=>$objeto->precio_envio_km,
                'stock'=>$objeto->stock,
                'nombre_categoria'=>$objeto->nombre_categoria
            );
        }
        $_SESSION['carrito'] = $json;
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

    if($_POST['funcion']=='eliminar_carrito'){
        $id_detalle_pedido = $_POST['id_detalle_pedido'];

        $detalle_pedido->obtener_Detalle_Pedido_Id($id_detalle_pedido);
        $id_producto = $detalle_pedido->objetos[0]->id_producto;
        $nombre_producto = $detalle_pedido->objetos[0]->nombre_producto;

        if ($nombre_producto === 'Tinglado Personalizado') {
            $producto->eliminar_producto($id_producto);
        }

        $detalle_pedido->eliminarDetallePedido($id_detalle_pedido);
        echo json_encode(['message' => 'Producto eliminado del carrito', 'status' => 'success']);
    }

    if ($_POST['funcion'] == 'cambiar_cantidad') {
        $id = $_POST['id'];
        $cantidad = $_POST['cantidad'];
    
        $detalle_pedido = new Detalle_Pedido();
        $detalle_pedido->cambiarCantidad($id, $cantidad);
    }

    if ($_POST['funcion'] == 'obtener_Detalle_Pedido_Id') {
        $id_pedido = $_POST['id_pedido'];
        $detalle_pedido->obtener_Detalle_Pedido_Id($id_pedido);
        $json = array();
        
        foreach($detalle_pedido->objetos as $objeto){
            $json[]=array(
                'id_producto'=>$objeto->id_producto,
                'nombre_producto'=>$objeto->nombre_producto,
                'producto_foto'=>$objeto->producto_foto,
                'precio_unitario'=>$objeto->precio_unitario,
                'cantidad'=>$objeto->cantidad,
                'tipo_techo'=>$objeto->tipo_techo,
                'color'=>$objeto->color
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }