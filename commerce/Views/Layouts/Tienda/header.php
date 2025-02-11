<?php
  session_start();
  include '../Models/Usuario.php';

  // Definir la variable $require_login si no está definida en la vista
  if (!isset($require_login)) {
      $require_login = true; // Por defecto, se requiere inicio de sesión
  }

  // Verificar si el usuario está logueado
  $usuario = new Usuario();
  $rol = null;

  if (isset($_SESSION['id'])) {
      $rolUsuario = $usuario->obtener_rol($_SESSION['id']);
      // Verificar si obtener_rol() devolvió un resultado
      if (isset($rolUsuario[0])) {
          $rol = $rolUsuario[0];
          $_SESSION['rol'] = $rol->tipo;
      }
  }

  // Check if the user role is allowed or if login is required
  if ($require_login) {
      // Si se requiere inicio de sesión y el usuario no está logueado
      if ($rol === null) {
          header('Location: ./index.php'); // Redirigir a la página de inicio
          exit();
      }

      // Si el usuario está logueado, verificar su rol
      if (!in_array($rol->tipo, $allowed_roles)) {
          header('Location: ./index.php');
          exit();
      }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tienda</title>

  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="../Util/Css/bootstrap.min.css">
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
  <link rel="stylesheet" type="text/css" href="../Util/Css/styles.css">
  
  <style>
    #dropdown-submenu {
      position: relative;
    }

    #dropdown-submenu > #dropdown-menu {
      top: 0;
      left: 100%;
      margin-top: -1px;
      display: none; /* Ocultar inicialmente */
    }

    #dropdown-submenu:hover > #dropdown-menu {
      display: block; /* Mostrar cuando se hace hover */
    }

    .navbar-nav #dropdown-menu {
      margin-top: 0;
      display: none; /* Ocultar inicialmente */
    }

    .navbar-nav #dropdown:hover > #dropdown-menu {
      display: block; /* Mostrar cuando se hace hover */
    }
  </style>
</head>
<body class="hold-transition">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-white navbar-light" style="align-items:center;">
      <!-- Brand -->
      <a class="navbar-brand mr-4" href="../Views/index.php">
          <!-- Icono de la empresa -->
          <img src="../Util/Assets/Grupo Nor Logo 2 [Recuperado].png" alt="Company Logo" class="" style="opacity: 1; width: 10em; margin-left:1em">
      </a>

      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
          <ul id="categorias" class="navbar-nav">
              <!-- Categorías se insertarán aquí -->
          </ul>
      </div>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
          <div class="search-container">
              <form id="searchForm" class="header-search-form" action="tienda.php" method="GET">
                <input id="inputSearch" type="text" placeholder="Buscar" name="search" class="header-search-input">
                <button type="submit" class="header-search-btn"><i class="fa fa-search"></i></button>
              </form>
          </div>
        </li>

        <!-- Messages Dropdown Menu -->
        <li id="notificacion" class="nav-item dropdown ml-2 mr-2" style="height:100%">
          <a class="nav-link rounded"  href="./carrito.php">
            <!-- icono carrito -->
            <i class="fas fa-shopping-cart" style="color:rgb(80, 80, 80);"></i>
            <span class="badge badge-danger navbar-badge"></span>
          </a>
        </li>
        <li class="nav-item" id="nav_register">
          <!-- Ruta a registrarse -->
          <a class="nav-link ml-3 mr-3 rounded"  href="register.php" role="button">
            <!-- Icono de registrarse -->
            <i class="fas fa-user-plus mr-2" style="color:rgb(80, 80, 80);"></i> Registrarse 
          </a>
        </li>
        <li class="nav-item" id="nav_login">
          <!-- Ruta al LogIn -->
          <a class="nav-link mr-3 rounded"  href="login.php" role="button">
            <!-- Icono de usuarios -->
            <i class="far fa-user mr-2" style="color:rgb(80, 80, 80);"></i> Iniciar sesión 
          </a>
        </li>
        <li class="nav-item dropdown" id="nav_usuario">
          <a class="nav-link dropdown-toggle rounded" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <!-- avatar del usuario -->
            <img id="avatar_nav" src="" width="30" height="30" class="img-fluid img-circle">
            <span id="usuario_nav" class="ml-2 mr-2" style="color:rgb(80, 80, 80);"> Usuario logueado</span>
          </a>
          <div class="dropdown-menu text-dark rounded" aria-labelledby="navbarDropdownMenuLink" style="background-color: rgb(245,245,245); box-shadow:none;border:none;">
            <?php 
              if($rol !== null ) {
                if ($rol->tipo === 'Repositor') {
                    echo '<a class="dropdown-item text-dark" href="mi_perfil.php"><i class="fas fa-user-cog text-dark"></i> Mi perfil</a>';
                    echo '<a class="dropdown-item text-dark" href="stock.php"><i class="fas fa-cubes text-dark"></i> Stock</a>';
                    echo '<a class="dropdown-item text-dark" href="categoria.php"><i class="fas fa-tags text-dark"></i> Categorías</a>';
                    echo '<a class="dropdown-item text-dark" href="../Controllers/logout.php"><i class="fas fa-user-times" style="color:rgb(80, 80, 80);"></i> Cerrar sesión</a>';
                } else if ($rol->tipo === 'Empleado') {
                    echo '<a class="dropdown-item text-dark" href="mi_perfil.php"><i class="fas fa-user-cog text-dark"></i> Mi perfil</a>';
                    echo '<a class="dropdown-item text-dark" href="pedidoAdmin.php"><i class="fas fa-shopping-basket text-dark"></i> Pedidos</a>';
                    echo '<a class="dropdown-item text-dark" href="../Controllers/logout.php"><i class="fas fa-user-times" style="color:rgb(80, 80, 80);"></i> Cerrar sesión</a>';
                } else if ($rol->tipo === 'Administrador'){
                    echo '<a class="dropdown-item text-dark" href="mi_perfil.php"><i class="fas fa-user-cog text-dark"></i> Mi perfil</a>';
                    echo '<a class="dropdown-item text-dark" href="pedidoAdmin.php"><i class="fas fa-shopping-basket text-dark"></i> Pedidos</a>';
                    echo '<a class="dropdown-item text-dark" href="usuarios.php"><i class="fas fa-users text-dark"></i> Usuarios</a>';
                    echo '<a class="dropdown-item text-dark" href="stock.php"><i class="fas fa-cubes text-dark"></i> Stock</a>';
                    echo '<a class="dropdown-item text-dark" href="categoria.php"><i class="fas fa-tags text-dark"></i> Categorías</a>';
                    echo '<a class="dropdown-item text-dark" href="../Controllers/logout.php"><i class="fas fa-user-times" style="color:rgb(80, 80, 80);"></i> Cerrar sesión</a>';
                } else {
                    echo '<a class="dropdown-item text-dark" href="mi_perfil.php"><i class="fas fa-user-cog text-dark"></i> Mi perfil</a>';
                    echo '<a class="dropdown-item text-dark" href="mis_pedidos.php"><i class="fas fa-shopping-basket text-dark"></i> Mis pedidos</a>';
                    echo '<a class="dropdown-item text-dark" href="../Controllers/logout.php"><i class="fas fa-user-times" style="color:rgb(80, 80, 80);"></i> Cerrar sesión</a>';
                }
              }
            ?>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Footer -->

  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="../Util/Js/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../Util/Js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../Util/Js/adminlte.min.js"></script>
  <!-- Sweetalert2 -->
  <script src="../Util/Js/sweetalert2.min.js"></script>
  <!-- Select2 -->
  <script src="../Util/Js/select2.min.js"></script>
</body>
</html>