<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar sesión</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../Util/Css/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../Util/Css/adminlte.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../Util/Css/toastr.min.css">
</head>
</head>
<body class="hold-transition login-page" style="background: #fff;">
<div class="login-box" style="box-shadow:none">
  <div class="login-logo" style="align-items:end; box-shadow:none">
    <!-- logo -->
    <img src="../Util/Assets/Grupo Nor Logo 2 [Recuperado].png" class=" img-fluid border-0 float-end" style="width:8em">
  </div>
  <!-- /.login-logo -->
  <div class="card mt-5" style="background: transparent; border:none; box-shadow: none">
    <div class="card-body login-card-body" style="background: transparent; border:none;">

    <div class="card mb-5" style="box-shadow:none; border:none; text-align:center;">
        <h2 style="font-size:2.25em; color: #303030">
            Bienvenido
        </h2>
        <p style="opacity:.8">
            Por favor ingrese sus datos
        </p>
    </div>

      <form id="form-login" class="mb-5" style="background: transparent; border:none;">
        <div class="input-group mb-5">
          <input id="user" type="text" class="form-control" style="background: transparent; border:none; border-radius: 0; border-bottom: 2px solid rgba(80, 80, 80, 0.5)" placeholder="Usuario" required>
          <div class="input-group-append">
            <div class="input-group-text" style=" border:none; border-radius:0; border-bottom: 2px solid rgba(80, 80, 80, 0.5)">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-5">
          <input id="pass" type="password" class="form-control" style="background: transparent; border:none; border-radius: 0; border-bottom: 2px solid rgba(80, 80, 80, 0.5)" placeholder="Contraseña" required>
          <div class="input-group-append">
            <div class="input-group-text" style=" border:none; border-radius:0; border-bottom: 2px solid rgba(80, 80, 80, 0.5)">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="social-auth-links text-center mb-3">
        <button type="submit" href="#" class="btn btn-lg btn-block mb-4" style="border:none; border-radius: 100px; background-color:rgba(185, 70, 74, 1); color:#ffff">
        Iniciar sesión
        </button>
        </div>
      </form>

      
      <!-- /.social-auth-links -->

      <p class="mb-0">
        <p style="opacity: .75;">No puedes iniciar sesión? <a style="color: #303030; font-width: 1000;" href="">Recuperar contraseña</a></p>
        
      </p>
      <p class="mb-0">
        <p style="opacity: .75;">No tienes una cuenta? <a style="color: #303030; font-width: 1000;"  href="register.php" class="text-center">Registrarse</a></p>
        
      </p>


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
<!-- Js de login -->
<script src="login.js"></script>
</body>
</html>

