<?php
  session_start();
  include '../Models/Usuario.php';

  // Verificar si el usuario tiene permisos de administrador
  $usuario = new Usuario();

  // Verificar si el usuario está logueado
  if (isset($_SESSION['id'])) {
      $rolUsuario = $usuario->obtener_rol($_SESSION['id']);
      // Verificar si obtener_rol() devolvió un resultado
      if (isset($rolUsuario[0])) {
          $rol = $rolUsuario[0];
      }
  }

  // Check if the user role is allowed
  if (!in_array($rol->tipo, $allowed_roles)) {
      header('Location: ./index.php');
      exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
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
<body class="hold-transition">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
    <a class="navbar-brand mr-4" href="../Views/index.php">
          <!-- Icono de la empresa -->
          <img src="../Util/Assets/Grupo Nor Logo 2 [Recuperado].png" alt="Company Logo" class="" style="opacity: 1; width: 6em; margin-left:1em">
      </a>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      <li id="notificacion" class="nav-item dropdown">
        <a class="nav-link" href="./carrito.php">
          <!-- icono carrito -->
          <i class="fas fa-shopping-cart"></i>
          <span class="badge badge-danger navbar-badge"></span>
        </a>
      </li>
      <li class="nav-item" id="nav_register">
        <!-- Ruta a registrarse -->
        <a class="nav-link"  href="register.php" role="button">
          <!-- Icono de registrarse -->
          <i class="fas fa-user-plus"></i> Registrarse 
        </a>
      </li>
      <li class="nav-item" id="nav_login">
        <!-- Ruta al LogIn -->
        <a class="nav-link"  href="login.php" role="button">
          <!-- Icono de usuarios -->
          <i class="far fa-user"></i> Iniciar sesión 
        </a>
      </li>
      <li class="nav-item dropdown" id="nav_usuario">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <!-- avatar del usuario -->
          <img id="avatar_nav" src="" width="30" height="30" class="img-fluid img-circle">
          <span id="usuario_nav"> Usuario logueado</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <?php
            if($rol !== null ) {
                if ($rol->tipo === 'Repositor') {
                    echo '<a class="dropdown-item" href="mi_perfil.php"><i class="fas fa-user-cog"></i> Mi perfil</a>';
                    echo '<a class="dropdown-item" href="stock.php"><i class="fas fa-cubes"></i> Stock</a>';
                    echo '<a class="dropdown-item" href="categoria.php"><i class="fas fa-tags"></i> Categorías</a>';
                } else if ($rol->tipo === 'Empleado') {
                    echo '<a class="dropdown-item" href="mi_perfil.php"><i class="fas fa-user-cog"></i> Mi perfil</a>';
                    echo '<a class="dropdown-item" href="pedidoAdmin.php"><i class="fas fa-shopping-basket"></i> Pedidos</a>';
                } else if ($rol->tipo === 'Administrador'){
                    echo '<a class="dropdown-item" href="mi_perfil.php"><i class="fas fa-user-cog"></i> Mi perfil</a>';
                    echo '<a class="dropdown-item" href="pedidoAdmin.php"><i class="fas fa-shopping-basket"></i> Pedidos</a>';
                    echo '<a class="dropdown-item" href="usuarios.php"><i class="fas fa-users"></i> Usuarios</a>';
                    echo '<a class="dropdown-item" href="stock.php"><i class="fas fa-cubes"></i> Stock</a>';
                    echo '<a class="dropdown-item" href="categoria.php"><i class="fas fa-tags"></i> Categorías</a>';
                } else {
                    echo '<a class="dropdown-item" href="mi_perfil.php"><i class="fas fa-user-cog"></i> Mi perfil</a>';
                    echo '<a class="dropdown-item" href="tienda.php"><i class="fas fa-store"></i> Tienda</a>';
                    echo '<a class="dropdown-item" href="mis_pedidos.php"><i class="fas fa-shopping-basket"></i> Mis pedidos</a>';
                }
            }
          ?>
          <!-- Controlador para cerrar sesion -->
          <a class="dropdown-item" href="../Controllers/logout.php"><i class="fas fa-user-times"></i> Cerrar sesión</a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
