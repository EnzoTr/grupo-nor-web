<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registrarse</title>

<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="../Util/Css/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="../Util/Css/adminlte.min.css">
<!-- Toastr -->
<link rel="stylesheet" href="../Util/Css/toastr.min.css">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="../Util/Css/sweetalert2.min.css" >
</head>
<!-- Modal -->
<div class="modal fade" id="terminos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border:none; box-shadow:none;">
            <div class="card card-success rounded border-0" style="border:none; box-shadow:none;">
                <div class="card-header">
                    <h5 class="card-title">Terminos y condiciones</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card-body">
                    <p>- Utilizaremos sus datos para generar publicidad de acuerdo a sus gustos</p>
                    <p>- La empresa no se hace responsable de posibles fraudes o estafas</p>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<body class="hold-transition login-page" style="background: #fff;">
<div class="mt-5">
<div class="card" style="align-items:end; box-shadow:none">
    <!-- logo -->
    <img src="../Util/Assets/Grupo Nor Logo 2 [Recuperado].png" class=" img-fluid border-0 float-end" style="width:9em">
</div>
<!-- /.login-logo -->
<div class="card mt-5"  style="background: transparent; border:none; box-shadow: none">
    <div class="card-body login-card-body" style="background: transparent; border:none;">

    <div class="card mb-5" style="box-shadow:none; border:none;">
        <h2 style="font-size:2.25em; color: #303030">
            Crea una cuenta
        </h2>
        <p style="opacity:.8">
            Solo te tomará unos segundos
        </p>
    </div>

    <form id="form-register" style="background: transparent; border:none;">
        <div class="row mb-5">
            <div class="col-sm-12">
                <div class="form-group">
                    <input type="text" name="username" class="form-control mb-5" style="background: transparent; border:none; border-radius: 0; border-bottom: 2px solid rgba(80, 80, 80, 0.5)" id="username" placeholder="Ingrese un nombre de usuario">
                </div>
            </div>
            <div class="col-sm-6" style="padding-right:1em">
                <div class="form-group">
                    <input type="password" name="pass" class="form-control mb-5" style="background: transparent; border:none; border-radius: 0; border-bottom: 2px solid rgba(80, 80, 80, 0.5)" id="pass" placeholder="Ingrese una contraseña">
                </div>
                <div class="form-group">
                    <input type="text" name="nombres" class="form-control mb-5" style="background: transparent; border:none; border-radius: 0; border-bottom: 2px solid rgba(80, 80, 80, 0.5)" id="nombres" placeholder="Ingrese su nombre">
                </div>
                <div class="form-group">
                    <input type="text" name="dni" class="form-control mb-5" style="background: transparent; border:none; border-radius: 0; border-bottom: 2px solid rgba(80, 80, 80, 0.5)" id="dni" placeholder="Ingrese su DNI">
                </div>
                <div class="form-group">
                    <input type="text" name="telefono" class="form-control mb-5" style="background: transparent; border:none; border-radius: 0; border-bottom: 2px solid rgba(80, 80, 80, 0.5)" id="telefono" placeholder="Ingrese su telefono">
                </div>
            </div>
            <div class="col-sm-6" style="padding-left:1em">
                <div class="form-group">
                    <input type="password" name="pass_repeat" class="form-control mb-5" style="background: transparent; border:none; border-radius: 0; border-bottom: 2px solid rgba(80, 80, 80, 0.5)" id="pass_repeat" placeholder="Ingrese de nuevo su contraseña">
                </div>
                <div class="form-group">
                    <input type="text" name="apellidos" class="form-control mb-5" style="background: transparent; border:none; border-radius: 0; border-bottom: 2px solid rgba(80, 80, 80, 0.5)" id="apellidos" placeholder="Ingrese su apellido">
                </div>
                <div class="form-group">
                    <input type="text" name="email" class="form-control mb-5" style="background: transparent; border:none; border-radius: 0; border-bottom: 2px solid rgba(80, 80, 80, 0.5)" id="email" placeholder="Ingrese su email">
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group mb-3 ml-3">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="terms" class="custom-control-input" id="terms" style="text-decoration:none;">
                    <label class="custom-control-label" for="terms">Estoy de acuerdo con los<a href="#" data-toggle="modal" data-target="#terminos"> terminos de servicio</a>.</label>
                </div>
            </div>
        </div>
        <!-- boton enviar -->
        <div class="card-footer text-center" style="background: none;">
            <button type="submit" class="btn btn-lg btn-block" style="border:none; border-radius: 100px; background-color:rgba(185, 70, 74, 1); color:#ffff">Registrarme</button>
        </div>
    </form>

    </div>
    <!-- /.login-card-body -->
</div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../Util/Js/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../Util/Js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../Util/Js/adminlte.min.js"></script>
<!-- Toastr js -->
<script src="../Util/Js/toastr.min.js"></script>
<!-- SweetAlert2 -->
<script src="../Util/Js/sweetalert2.min.js"></script>
<!-- Js de register -->
<script src="register.js"></script>
<!-- Js de los metodos de validacion -->
<script src="../Util/Js/additional-methods.min.js"></script>
<script src="../Util/Js/jquery.validate.min.js"></script>
</body>
</html>


