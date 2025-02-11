<?php
    ob_start(); // Activa el almacenamiento en búfer de salida

    $require_login = true;  // No requiere iniciar sesión
    $allowed_roles = ['Administrador', 'Repositor', 'Empleado', 'Cliente'];

    include_once 'Layouts/Tienda/header.php';
    include '../Util/Config/config.php';

    ob_end_flush(); // Envía el contenido del búfer al cliente
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Carrito de compras</title>
        <style>
            .disabled {
                pointer-events: none; /* No permite clics */
                opacity: 0.5; /* Se ve deshabilitado */
                cursor: not-allowed; /* Cursor de no permitido */
            }
            #pagarButton.disabled {
                background-color: lightgray !important; /* Cambia el color de fondo a gris claro */
            }
        </style>
    </head>
    
    <body>
        <div class="text-dark mb-5 mt-5 ml-5 mr-5 border-0" style="display: flex;flex:1;flex-wrap:wrap;border:none;">
            <div class="row border-0" style="border:none;">
                <div class="col-md-8 mr-4 border-0">
                    <h1 class="card-title mb-4 text-dark border-0" style="font-weight: 700; font-size:1.75em;border:none; ">Carrito de compras</h1>
                    <table class="table border-0">
                        <thead>
                            <tr style="opacity:.5; font-weight:700;">
                                <th></th>
                                <th class=" text-dark" style="font-weight:700;">Producto</th>
                                <th class=" text-dark" style="font-weight:700;">Precio</th>
                                <th class=" text-dark" style="font-weight:700;">Cantidad</th>
                                <th class=" text-dark" style="font-weight:700;">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            <!-- Los productos del carrito serán insertados aquí por JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <div class="col-md-4" style="display: flex;flex:2;flex-direction:column;">
                    <h3 class=" text-dark mb-5" style="font-size:1.5em">Resumen del carrito</h3>
                    <div id="cart-summary">
                        <p class=" text-dark mb-4" id="cart-subtotal">Subtotal: $<span id="subtotalPrice" class="text-dark">0</span></p>
                        <div>
                            <label class=" text-dark" for="direccion">Selecciona una dirección:</label>
                            <select class=" text-dark rounded mb-4" style="border:none; background:rgba(80,80,80,0.1); padding: .5em .5em" id="direccion" name="direccion">
                                <!-- Las direcciones del usuario serán insertadas aquí por JavaScript -->
                            </select>
                        </div>
                        <p class=" text-dark" id="cart-shipping">Envío: $<span class=" text-dark" id="shippingPrice">0</span></p>
                        <p class=" text-dark" id="cart-total">Total: $<span class=" text-dark" id="totalPrice">0</span></p>
                    </div>
                    <Button id="pagarButton" class="disabled" style="background-color:rgb(6, 160, 227);border:none;opacity:1;margin-top:auto">Continuar</Button>
                </div>
            </div>
        </div>
        <input type="hidden" id="precio_envio_km" value="<?php echo PRECIO_ENVIO_KM; ?>">
        <!-- Incluir jQuery y Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-U1qm3w7YKbDsjPniAzE7hlGcy3i4RkY&callback=initMap"></script>
        <!-- Incluir tu archivo JavaScript -->
        <script src="./carrito.js" type="module"></script>
    </body>
</html>

<?php
    include_once 'Layouts/Tienda/footer.php';
?>
