<?php
    ob_start(); // Inicia el búfer de salida
    
    //include '../Util/Config/config.php';
    $require_login = true;  // No requiere iniciar sesión
    $allowed_roles = ['Administrador', 'Repositor', 'Empleado', 'Cliente'];
    include_once 'Layouts/General/header.php'; // Mover esta línea después de las llamadas a header()
    ob_end_flush(); // Vacía (envía) el búfer de salida
?>

<head>
    <style>
        .productos-container {
            display: flex;
            flex-direction: column;
            gap: 10px; /* Espacio entre productos */
        }
        .producto-item {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .producto-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 15px;
        }
        .detalles-producto {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        .detalles-texto {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        .detalles-texto p {
            margin: 0;
        }
    </style>
</head>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detalles del pedido</h3>
                    </div>
                    <div class="card-body">
                        <h4>Número de pedido: <span id="pedido-id"></span></h4>

                        <h4>Productos:</h4>
                        <div id="lista-productos" class="productos-container">
                            <!-- Los productos se agregarán aquí -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    include_once 'Layouts/General/footer.php';
?>

<script src="./resumen_pedido.js" type="module"></script>
