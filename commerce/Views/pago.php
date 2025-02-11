<?php
    ob_start(); // Activa el almacenamiento en búfer de salida
    $require_login = true;  // No requiere iniciar sesión
    $allowed_roles = ['Administrador', 'Repositor', 'Empleado', 'Cliente'];

    include_once 'Layouts/Tienda/header.php';
    include_once '../Models/Detalle_Pedido.php';
    require_once '../Vendor/autoload.php';
    $access_token = 'TEST-3151295562123864-052713-872d7df01ebad8c4c2145cd5a5d93cad-1832413874';

    if (!isset($_SESSION['payer'])) {
        $_SESSION['payer'] = [
            'nombre' => '',
            'apellido' => '',
            'email' => ''
        ];
    }

    use MercadoPago\MercadoPagoConfig;
    use MercadoPago\Client\Preference\PreferenceClient;
    use MercadoPago\Client\Common\RequestOptions;
    use MercadoPago\Exceptions\MPApiException;
    MercadoPagoConfig::setAccessToken($access_token);
    MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);

    $detalle_pedido = new Detalle_Pedido();
    $id_usuario = $_SESSION['id'];
    $cartItems = $detalle_pedido->obtenerDetallesPedido($id_usuario);

    $total = 0;

    // Verifica si el carrito está vacío
    if (empty($cartItems)) {
        echo "El carrito está vacío.";
    } else {
        foreach ($cartItems as $cartItem) {
            $total += floatval($cartItem->precio_unitario) * $cartItem->cantidad;
        }

        $direccion_envio = isset($_SESSION['direccion_envio']) ? $_SESSION['direccion_envio'] : '';
        $costo_envio = isset($_SESSION['costo_envio']) ? $_SESSION['costo_envio'] : 0;
        $total += $costo_envio;

        $payerSession = $_SESSION['payer'];

        $payer = [
            "name" => $payerSession['nombre'],
            "surname" => $payerSession['apellido'],
            "email" => $payerSession['email'],
        ];

        // Prepara la solicitud de preferencia
        $request = [
            "items" => [
                [
                    "title"=> "Productos seleccionados",
                    "description"=> "Productos seleccionados",
                    "quantity"=> 1,
                    "currency_id"=> "ARS",
                    "unit_price"=> $total,
                ]
            ],
            "payer" => $payer,
            "back_urls" => [
                "success" => "https://d298-190-138-186-169.ngrok-free.app/GrupoNOR/commerce/Views/pago_exitoso.php",
                "failure" => "https://d298-190-138-186-169.ngrok-free.app/GrupoNOR/commerce/Views/pago_exitoso.php"
            ],
            "statement_descriptor" => "NAME_DISPLAYED_IN_USER_BILLING",
            "payment_methods" => [
                "excluded_payment_types" => [],
                "installments" => 12
            ],
            "auto_return" => 'approved',
            "binary_mode" => true,
        ];

        // Crea la preferencia
        $client = new PreferenceClient();
        $request_options = new RequestOptions();
        $request_options->setCustomHeaders(["X-Idempotency-Key: <SOME_UNIQUE_VALUE>"]);
        try {
            $preference = $client->create($request, $request_options);
            echo "<script>var mercadoPagoUrl = '" . $preference->init_point ."';</script>";
        } catch (MPApiException $e) {
            echo "Status code: " . $e->getApiResponse()->getStatusCode() . "\n";
            echo "Content: ";
            var_dump($e->getApiResponse()->getContent());
            echo "\n";
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

ob_end_flush(); // Envía el contenido del búfer al cliente
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pago</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            .back-button {
                font-size: 1.25em;
                color: #000;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <a href="./carrito.php" class="back-button">&larr; Volver</a>
            <div class="row">
                <div class="col-md-6">
                    <h3 class="mb-4 text-dark" style="font-size: 2em;">Resumen del pedido</h3>
                    <p class="mb-1 text-dark"><strong class="text-dark">Dirección de envío:</strong> <?php echo $direccion_envio;?></p>
                    <p class="mb-1 text-dark"><strong class="text-dark">Subtotal:</strong> $<?php echo $total - $costo_envio; ?></p>
                    <p class="mb-1 text-dark"><strong class="text-dark">Costo de envío:</strong> $<?php echo $costo_envio; ?></p>
                    <p class="mb-4 text-dark"><strong class="text-dark">Total:</strong> $<?php echo $total; ?></p>
                    <button id="pay-button" class="boton-checkout btn-block" style="background-color: rgb(59, 157, 207); border:none;">Pagar</button>
                </div>
                <div class="col-md-6">
                    
                </div>
            </div>
        </div>
        <div class="userData">
            <input type="hidden" id="nombre">
            <input type="hidden" id="apellido">
            <input type="hidden" id="email">
        </div>
        <!-- Incluir jQuery y Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://sdk.mercadopago.com/js/v2"></script>
        <script>
            $("#pay-button").click(function(){
                window.location.href = mercadoPagoUrl;
            });

            // Agrega el SDK de Mercado Pago a tu sitio
            const mp = new MercadoPago('TEST-16c998fa-2cba-4d84-aeb8-a0ce02afc408', {
                locale: 'es-AR'
            });

            // Inicializa el checkout
            mp.checkout({
                preference: {
                    id: '<?php echo $preference->id ?>',
                    redirectMode: 'modal',
                },
            });

        </script>
        <!-- Incluir tu archivo JavaScript -->
        <script src="./pago.js" type="module"></script>
    </body>
</html>

<?php
    include_once 'Layouts/Tienda/footer.php';
?>