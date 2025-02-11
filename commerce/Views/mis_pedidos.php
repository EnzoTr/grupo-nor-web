<?php
    ob_start(); // Inicia el búfer de salida

    $require_login = true;
    $allowed_roles = ['Administrador', 'Repositor', 'Empleado', 'Cliente'];

    include_once 'Layouts/Tienda/header.php'; // Mover esta línea después de las llamadas a header()
    include '../Util/Config/config.php';
    // Verificar si el usuario está logueado
    if (!isset($_SESSION['id'])) {
        header('Location: ./index.php');
        exit();
    }
    ob_end_flush(); // Vacía (envía) el búfer de salida
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Select2 -->
  <link rel="stylesheet" href="../Util/Css/select2.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../Util/Css/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../Util/Css/adminlte.min.css">
  <!-- Sweetalert2 -->
  <link rel="stylesheet" href="../Util/Css/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
</head>

<section class="content">
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="border:none; box-shadow:none;">
                    <div class="card-header" style="border:none; box-shadow:none;">
                        <h3 class="card-title text-dark">Pedidos Actuales</h3>
                    </div>
                    <div class="card-body">
                        <!-- Agregar la tabla con la información de los pedidos -->
                        <table id="ordersTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-dark">Nro. Pedido</th>
                                    <th class="text-dark">Fecha del Pedido</th>
                                    <th class="text-dark">Precio de envío</th>
                                    <th class="text-dark">Total</th>
                                    <th class="text-dark">Dirección de envío</th>
                                    <th class="text-dark">Forma de Pago</th>
                                    <th class="text-dark">Estado</th>
                                    <th class="text-dark">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se llenará la tabla con la información de los pedidos -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<?php
    include_once 'Layouts/Tienda/footer.php';
?>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="./mis_pedidos.js" type="module"></script>