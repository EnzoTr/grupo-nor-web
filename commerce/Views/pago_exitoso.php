<?php
    $require_login = true;  // No requiere iniciar sesión
    $allowed_roles = ['Administrador', 'Repositor', 'Empleado', 'Cliente'];

    include_once 'Layouts/Tienda/header.php';
    include_once '../Models/Pedido.php';
    include_once '../Models/Detalle_Pedido.php';
    include_once '../Models/Producto.php';
    include '../Util/Config/config.php';

    $payment = isset($_GET['payment_id']) ? $_GET['payment_id'] : null;
    $status = isset($_GET['status']) ? $_GET['status'] : null;
    $payment_type = isset($_GET['payment_type']) ? $_GET['payment_type'] : null;
    $order_id = isset($_GET['merchant_order_id']) ? $_GET['merchant_order_id'] : null;
    $direccion_envio = isset($_SESSION['direccion_envio']) ? $_SESSION['direccion_envio'] : '';

    $id_usuario = $_SESSION['id'];
    $fecha_registro = date('Y-m-d H:i:s');
    switch($payment_type) {
        case 'credit_card':
            $metodo_pago = 'Credito';
            break;
        case 'debit_card':
            $metodo_pago = 'Debito';
            break;
        case 'account_money':
            $metodo_pago = 'Transferencia';
            break;
        default:
            $metodo_pago = 'Otro';
            break;
    }
    $total = 0;
    $envio = isset($_SESSION['costo_envio']) ? $_SESSION['costo_envio'] : 0;
    $total += $envio;
    $estado = 'Pendiente';
    
    $pedido = new Pedido();
    $detalle_pedido = new Detalle_Pedido();
    $producto = new Producto();
    if ($status === 'approved') {
        try {
            // Iniciar transacción
            $detalle_pedido->acceso->beginTransaction();
        
            // Obtener los detalles del pedido (carrito)
            $detalle_pedido->obtenerDetallesPedido($id_usuario);
            $error = false;
        
            foreach($detalle_pedido->objetos as $objeto){
                if ($objeto->nombre_producto !== 'Tinglado Personalizado') {
                    $total += floatval($objeto->precio_unitario) * $objeto->cantidad;
                    // Verificar el stock antes de descontar
                    if($producto->verificarStock($objeto->id_producto, $objeto->cantidad)){
                        // Descontar del stock
                        $producto->actualizarStock($objeto->id_producto, $objeto->cantidad);
                    } else {
                        // Si no hay suficiente stock, establecer un error
                        $error = true;
                        break;
                    }
                }
            }
        
            if ($error) {
                // Deshacer la transacción si hubo un error
                $detalle_pedido->acceso->rollBack();
            } else {
                // Confirmar la transacción si todo fue exitoso
                $detalle_pedido->acceso->commit();
                // Crear el pedido
                $idPedido = $pedido->crear_pedido($id_usuario, $fecha_registro, $total, $metodo_pago, $envio, $estado, $direccion_envio);
                $detalle_pedido->carritoComprado($id_usuario, $idPedido);
            }
        } catch (Exception $e) {
            // Deshacer la transacción en caso de excepción
            $detalle_pedido->acceso->rollBack();
            echo json_encode(array('status' => 'error', 'message' => 'Error al procesar el pedido: ' . $e->getMessage()));
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card mt-5">
                    <div class="card-body text-center" style="text-align: center;">
                        <?php 
                            if ($error) {
                                echo '<h1 class="card-title text-danger" style="font-size:1em; font-weight:600">Error</h1>';
                                echo '<p class="card-text text-dark">No queda stock de un determinado producto</p';
                            } elseif ($status === 'rejected') {
                                echo '<h1 class="card-title text-danger" style="font-size:1em; font-weight:600">Pago Rechazado</h1>';
                                echo '<p class="card-text text-dark">Tu pago ha sido rechazado. Por favor, intenta nuevamente.</p>';
                            } else {
                                echo '<h1 class="card-title text-success style="font-size:1em; font-weight:600">Pago Exitoso</h1>';
                                echo '<p class="card-text text-dark">Tu pago ha sido procesado con éxito. ¡Gracias por tu compra!</p>';
                            }
                        ?>
                        <p class="card-text text-dark" id="redireccion-info">Serás redireccionado en <span id="contador-segundos" class=" text-dark">5</span> segundos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir scripts de JavaScript necesarios -->
    <script src="./pago_exitoso.js" type="module"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.7.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        const error = <?php echo json_encode($error); ?>;   
        const statusMP = <?php echo json_encode($status); ?>;  
    </script>
</body>
</html>

<?php
    // Incluir el pie de página y cualquier cierre necesario
    include_once 'Layouts/Tienda/footer.php';
?>