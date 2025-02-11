<?php
include_once '../Models/Pedido.php';
include_once '../Models/Detalle_Pedido.php';
include_once '../Models/Producto.php';
include '../Util/Config/config.php';
//esta variable esta siendo instanciada en Producto.php y a la vez en Conexion.php
$producto = new Producto();
$pedido = new Pedido();
$detalle_pedido = new Detalle_Pedido();
//sirve para saber cuando el usuario entra en su sesion
session_start();

    if($_POST['funcion'] == 'crear_pedido'){
        $id_usuario = $_SESSION['id_usuario'];
        $fecha_registro = date('Y-m-d');
        $total = $_POST['total'];
        $metodo_pago = $_POST['metodo_pago'];
        $envio = $_POST['envio'];
        $estado = 'Pendiente';
        $pedido = new Pedido();
        $detalle_pedido = new DetallePedido();

        try {
            // Iniciar transacción
            $detalle_pedido->acceso->beginTransaction();

            // Obtener los detalles del pedido (carrito)
            $detalle_pedido->obtenerDetallesPedido($id_usuario);
            $error = false;

            foreach($detalle_pedido->objetos as $objeto){
                // Verificar el stock antes de descontar
                if($detalle_pedido->verificarStock($objeto->id_producto, $objeto->cantidad)){
                    // Descontar del stock
                    $producto->actualizarStock($objeto->id_producto, $objeto->cantidad);

                } else {
                    // Si no hay suficiente stock, establecer un error
                    $error = true;
                    break;
                }
            }

            if ($error) {
                // Deshacer la transacción si hubo un error
                $detalle_pedido->acceso->rollBack();
                echo json_encode(array('status' => 'error', 'message' => 'No hay suficiente stock para uno o más productos.'));
            } else {
                // Confirmar la transacción si todo fue exitoso
                $detalle_pedido->acceso->commit();
                // Crear el pedido
                $idPedido = $pedido->crear_pedido($id_usuario, $fecha_registro, $total, $metodo_pago, $envio, $estado);
                echo json_encode(array('message' => 'Pedido creado', 'status' => 'success', 'idPedido' => $idPedido));
            }
        } catch (Exception $e) {
            // Deshacer la transacción en caso de excepción
            $detalle_pedido->acceso->rollBack();
            echo json_encode(array('status' => 'error', 'message' => 'Error al procesar el pedido: ' . $e->getMessage()));
        }
    }

    if($_POST['funcion']=='obtener_pedidos'){
        $pedido->obtener_pedidos();
        $json=array();
        
        foreach($pedido->objetos as $objeto){
            $json[]=array(
                'id'=>$objeto->id,
                'fecha'=>$objeto->fecha,
                'total'=>$objeto->total,
                'metodo_pago'=>$objeto->metodo_pago,
                'envio'=>$objeto->precio_envio,
                'estado'=>$objeto->estado,
                'ruta_pdf'=>$objeto->ruta_pdf,
                'direccion_envio'=>$objeto->direccion_envio,
                'nombres'=>$objeto->nombres,
                'apellidos'=>$objeto->apellidos,
                'dni'=>$objeto->dni
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

    if($_POST['funcion']=='obtener_pedidos_usuario'){
        if(isset($_SESSION['id'])){
            $id_usuario = $_SESSION['id'];
            $pedido->obtener_pedidos_usuario($id_usuario);
            $json=array();
            foreach($pedido->objetos as $objeto){
                $json[]=array(
                    'id'=>$objeto->id,
                    'fecha'=>$objeto->fecha,
                    'total'=>$objeto->total,
                    'metodo_pago'=>$objeto->metodo_pago,
                    'envio'=>$objeto->precio_envio,
                    'estado'=>$objeto->estado,
                    'ruta_pdf'=>$objeto->ruta_pdf,
                    'direccion_envio'=>$objeto->direccion_envio
                );
            }
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }else{
            echo json_encode(array('message' => 'No se ha iniciado sesión', 'status' => 'error'));
        }

    }

    if($_POST['funcion']=='modificar_pedido'){
        $id = $_POST['id'];
        $estado = $_POST['estado'];
        $ruta_pdf = "../Util/Pdf/" . basename($_FILES['factura']['name']);
    
        if(move_uploaded_file($_FILES['factura']['tmp_name'], $ruta_pdf)) {
            $pedido->modificar_pedido($id, $estado, $ruta_pdf);
            echo json_encode(['message' => 'Pedido modificado', 'status' => 'success']);
        } else {
            echo json_encode(['message' => 'Error al subir el archivo', 'status' => 'error']);
        }
    }

    if($_POST['funcion']=='eliminar_pedido'){
        $id_pedido = $_POST['id'];

        $detalle_pedido->obtener_Detalle_Pedido_Id($id_pedido);
        foreach($detalle_pedido->objetos as $objeto){
            $producto->reestablecerStock($objeto->id_producto, $objeto->cantidad);
        }

        $pedido->eliminar_pedido($id_pedido);
        echo json_encode(['message' => 'Pedido eliminado', 'status' => 'success']);
    }