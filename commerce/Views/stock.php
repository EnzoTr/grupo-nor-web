<?php
    ob_start(); // Inicia el búfer de salida
    include '../Util/Config/config.php';
    // Verificar si el usuario está logueado
    $allowed_roles = ['Administrador', 'Repositor'];
    include_once 'Layouts/General/header.php'; // Mover esta línea después de las llamadas a header()

    // Función para actualizar un valor definido en el archivo config.php
    function actualizar_valor_config($nombre_constante, $nuevo_valor) {
        $archivo = '../Util/Config/config.php';

        // Leer el contenido del archivo
        $contenido = file($archivo);

        // Recorrer cada línea y buscar la definición de la constante
        foreach ($contenido as &$linea) {
            if (strpos($linea, "define('$nombre_constante'") !== false) {
                // Actualizar la línea con el nuevo valor
                $linea = "define('$nombre_constante', $nuevo_valor);\n";
            }
        }

        // Escribir el contenido modificado de vuelta en el archivo
        file_put_contents($archivo, implode('', $contenido));
    }

    // Verificar si se ha enviado el formulario para actualizar los precios
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["precio_base"]) && isset($_POST["precio_mayor_12"]) && isset($_POST["precio_mayor_15"])) {
            // Validar y sanitizar los nuevos precios
            $nuevo_precio_base = filter_var($_POST["precio_base"], FILTER_SANITIZE_NUMBER_INT);
            $nuevo_precio_mayor_12 = filter_var($_POST["precio_mayor_12"], FILTER_SANITIZE_NUMBER_INT);
            $nuevo_precio_mayor_15 = filter_var($_POST["precio_mayor_15"], FILTER_SANITIZE_NUMBER_INT);

            // Actualizar los precios en el archivo de configuración
            actualizar_valor_config('PRECIO_BASE', $nuevo_precio_base);
            actualizar_valor_config('PRECIO_MAYOR_12', $nuevo_precio_mayor_12);
            actualizar_valor_config('PRECIO_MAYOR_15', $nuevo_precio_mayor_15);

            // Redirigir para evitar reenvío del formulario
            $_SESSION['precios_actualizados'] = true;
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit();
        }
        // Verificar si se ha enviado el formulario para actualizar el precio de envío
        if (isset($_POST["precio_envio_km"])) {
            // Validar y sanitizar el nuevo precio de envío
            $nuevo_precio_envio = filter_var($_POST["precio_envio_km"], FILTER_SANITIZE_NUMBER_INT);

            // Actualizar el precio de envío en el archivo de configuración
            actualizar_valor_config('PRECIO_ENVIO_KM', $nuevo_precio_envio);

            // Redirigir para evitar reenvío del formulario
            $_SESSION['precio_envio_actualizado'] = true;
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit();
        }
    }

    include_once 'Layouts/General/header.php';

    // Verificar si se han actualizado los precios anteriormente
    if (isset($_SESSION['precios_actualizados']) && $_SESSION['precios_actualizados'] === true) {
        echo '<script>alert("¡Precios actualizados con éxito!");</script>';
        unset($_SESSION['precios_actualizados']); // Limpiar la variable de sesión
    }

    // Verificar si se ha actualizado el precio de envío anteriormente
    if (isset($_SESSION['precio_envio_actualizado']) && $_SESSION['precio_envio_actualizado'] === true) {
        echo '<script>alert("¡Precio de envío actualizado con éxito!");</script>';
        unset($_SESSION['precio_envio_actualizado']); // Limpiar la variable de sesión
    }

    ob_end_flush(); // Vacía (envía) el búfer de salida
?>

<section class="content">
    <div class="container-fluid mt-5" style="border:none; box-shadow:none; ">
        <div class="row" style="border:none; box-shadow:none; ">
            <div class="col-md-12" style="border:none; box-shadow:none; ">
                <div class="card" style="border:none; box-shadow:none; ">
                    <div class="card-header" style="border:none; box-shadow:none;">
                        <h3 class="card-title"  style="font-weight: 700; font-size:1.75em; ">Inventario Actual</h3>
                        <!-- Botón para abrir el modal -->
                        <div class="float-right" style="display:flex; gap:.5em">
                            <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#productModal">
                                Agregar Producto
                            </button>
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#editPrecioKm2Modal">
                                Editar Precios por km2
                            </button>
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#editPrecioEnvioModal">
                                Editar Precios de envio
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            
                        </div>
                        <!-- Agregar la tabla con la información del inventario -->
                        <table id="inventoryTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th data-columna="p.nombre"><p style="opacity:.4">Nombre</th>
                                    <th data-columna="c.nombre"><p style="opacity:.4">Categoría</th>
                                    <th data-columna="p.precio_unitario"><p style="opacity:.4">Precio unitario</th>
                                    <th data-columna="p.costo_unidad"><p style="opacity:.4">Costo unitario</th>
                                    <th data-columna="p.precio_envio"><p style="opacity:.4">Precio de envío</th>
                                    <th data-columna="p.sector"><p style="opacity:.4">Sector</th>
                                    <th data-columna="p.descripcion"><p style="opacity:.4">Descripción</th>
                                    <th data-columna="p.fecha_registro"><p style="opacity:.4">Fecha de registro</th>
                                    <th data-columna="p.fecha_actualizacion"><p style="opacity:.4">Fecha de actualización</th>
                                    <th data-columna="p.cantidad_disponible"><p style="opacity:.4">Cantidad disponible</th>
                                    <th data-columna="p.estado"><p style="opacity:.4">Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se llenará la tabla con la información del inventario -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Código del modal para editar el precio base -->
    <div class="modal fade" id="editPrecioKm2Modal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="post" id="editPrecioBaseForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Editar precio por Km2</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="precio_base">Precio:</label>
                            <input type="text" id="precio_base" name="precio_base" value="<?php echo PRECIO_BASE; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="precio_mayor_12">Precio mayor a 12:</label>
                            <input type="text" id="precio_mayor_12" name="precio_mayor_12" value="<?php echo PRECIO_MAYOR_12; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="precio_mayor_15">Precio mayor a 15:</label>
                            <input type="text" id="precio_mayor_15" name="precio_mayor_15" value="<?php echo PRECIO_MAYOR_15; ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Código del modal para editar el precio base -->
    <div class="modal fade" id="editPrecioEnvioModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="post" id="editPrecioEnvioForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Editar precio de envio por Km</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="precio_envio_km">Precio:</label>
                            <input type="text" id="precio_envio_km" name="precio_envio_km" value="<?php echo PRECIO_ENVIO_KM; ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Agregar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="id_categoria">Categoría:</label>
                            <select class="form-control" id="id_categoria" name="id_categoria">
                                <!-- Las opciones se llenarán con JavaScript -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>
                            <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cantidad_disponible">Cantidad Disponible:</label>
                            <input type="number" class="form-control" id="cantidad_disponible" name="cantidad_disponible">
                        </div>
                        <div class="form-group">
                            <label for="costo_unidad">Costo por Unidad:</label>
                            <input type="number" class="form-control" id="costo_unidad" name="costo_unidad">
                        </div>
                        <div class="form-group">
                            <label for="precio_unitario">Precio por Unidad:</label>
                            <input type="number" class="form-control" id="precio_unitario" name="precio_unitario">
                        </div>
                        <div class="form-group">
                            <label for="precio_envio">Precio de envio:</label>
                            <input type="number" class="form-control" id="precio_envio" name="precio_envio">
                        </div>
                        <div class="form-group">
                            <label for="sector">Sector:</label>
                            <input type="text" class="form-control" id="sector" name="sector">
                        </div>
                        <div class="form-group">
                            <label for="foto">Foto principal:</label>
                            <input type="file" class="form-control" id="foto" name="foto">
                        </div>
                        <div class="form-group">
                            <label for="fotos">Agregar fotos extras:</label>
                            <input type="file" class="form-control" id="fotos" name="fotos[]" multiple>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Producto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de edición de productos -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form id="editProductForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Editar producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Campos a la izquierda -->
                                <input type="hidden" id="id" name="id">
                                <div class="form-group">
                                    <label for="id_categoria">Categoría:</label>
                                    <select class="form-control" id="id_categoria" name="id_categoria"></select>
                                </div>
                                <div class="form-group">
                                    <label for="nombre">Nombre:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="form-group">
                                    <label for="descripcion">Descripción:</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="cantidad_disponible">Cantidad Disponible:</label>
                                    <input type="number" class="form-control" id="cantidad_disponible" name="cantidad_disponible">
                                </div>
                                <div class="form-group">
                                    <label for="costo_unidad">Costo por Unidad:</label>
                                    <input type="number" class="form-control" id="costo_unidad" name="costo_unidad">
                                </div>
                                <div class="form-group">
                                    <label for="precio_unitario">Precio por Unidad:</label>
                                    <input type="number" class="form-control" id="precio_unitario" name="precio_unitario">
                                </div>
                                <div class="form-group">
                                    <label for="precio_envio">Precio de envio:</label>
                                    <input type="number" class="form-control" id="precio_envio" name="precio_envio">
                                </div>
                                <div class="form-group">
                                    <label for="sector">Sector:</label>
                                    <input type="text" class="form-control" id="sector" name="sector">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Campos a la derecha -->
                                <div class="form-group">
                                    <label for="foto">Foto principal:</label>
                                    <img id="fotoExistente" src="" style="width: 50px; height: 50px;">
                                    <span id="nombreFoto"></span>
                                    <input type="file" class="form-control" id="foto" name="foto">
                                </div>
                                <div id="fotos">
                                    <!-- Las fotos se llenarán con JavaScript -->
                                </div>
                                <div class="form-group">
                                    <label for="nuevasFotos">Agregar nuevas fotos:</label>
                                    <input type="file" class="form-control" id="nuevasFotos" name="fotos[]" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addStockModal" tabindex="-1" role="dialog" aria-labelledby="addStockModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockModalLabel">Modificar cantidad disponible</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addStockForm">
                <div class="form-group">
                    <label for="cantidad">Cantidad disponible:</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" min="0">
                </div>
                <input type="hidden" id="productId" name="productId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Guardar cambios</button>
            </div>
            </div>
        </div>
    </div>
</section> 

<?php
    include_once 'Layouts/General/footer.php';
?>
<script src="./stock.js" type="module"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
