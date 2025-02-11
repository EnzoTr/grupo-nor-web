<?php
    if(!empty($_GET['id']) && $_GET['name']){
        $require_login = false;  // No requiere iniciar sesión
        $allowed_roles = ['Administrador', 'Repositor', 'Empleado', 'Cliente', null];
        //echo $_SESSION['product-verification'];
        include_once './Layouts/Tienda/header.php';
        $_SESSION['product-verification'] = $_GET['id'];
    }
?>
    
<title> <?php echo $_GET['name'] ?> </title>
<head>
    <style>
        .custom-select {
            width: 100%;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }

        .custom-select:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
        }
    </style>
</head>

<!-- Content Header (Page header) -->
<section class="content-header" style="padding: 0 1.5em">
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
            <h1 id="nombre_producto"></h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="./tienda.php">Inicio</a></li>
            <li class="breadcrumb-item active"><?php echo $_GET['name'] ?></li>
            </ol>
        </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
    <!-- Main content -->
    <section class="content">

    <!-- Default box -->
    <div class="card card-solid" style="padding: 0 1.5em; max-width:2560px;margin:auto; ">
        <div class="card-body">
            <div class="row">
                <div id="imagenes" class="col-12 col-sm-6">            
                </div>
            <div class="col-12 col-sm-6">
                <h4  id="id_producto" class="my-3 text-dark" style="font-weight: 700;"></h2>
                <hr>             
                <div class="mt-4">
                    <h4 class="text-dark">Cantidad</h4>
                    <input class="rounded text-dark text-center" style="width: 100%; height:2em;padding-left:15px; border:none; background-color: rgba(80,80,80,0.2);" type="number" id="product_quantity" min="1" value="1">
                </div>
                <div class="mt-4">
                    <span id="warningStock" class="text-danger"></span>
                </div>
                <div class="mt-4">
                    <span id="warningStock" class="text-danger"></span>
                </div>

                <div id="product_options" class="mt-4"></div>

                <div id="product_options" class="mt-4"></div>

                <div class="bg-gray py-2 px-3 mt-4 border rounded">
                    <h4 class="mb-0 text-center" id="precio_producto">
                    </h4>
                </div>

                <div class="mt-4" class="input-group mb-3 card-footer" style="background:none; padding:0;">
                    <button class="agregar-carrito btn btn-primary btn-flat btn-block rounded" style="width:100%; height:100%; padding: .75em 0;">
                        <i class="fas fa-cart-plus fa-lg mr-2"></i>
                        Agregar al carrito
                    </button>                            
                </div>

                </div>
            </div>
            <div class="row mt-4">
                <nav class="w-100">
                    <div class="nav nav-tabs" id="product-tab" role="tablist">
                        <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Descripcion</a>
                    </div>
                </nav>
                <div class="tab-content p-3" id="nav-tabContent">
                    <div class="tab-pane fade show active text-dark" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                        <pre id="product-description-content">Aqui va la descripcion</pre>
                    </div>
                </div>
            </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->




<?php
    include_once 'Layouts/Tienda/footer.php';
    
?>

<script src="./descripcion.js" type="module"></script>
