<?php
session_start();
include '../Util/Config/config.php';
include '../Models/Usuario.php';

// Verificar si el usuario tiene permisos de administrador
$usuario = new Usuario();
$rol = null;

// Verificar si el usuario está logueado
if (isset($_SESSION['id'])) {
    $rolUsuario = $usuario->obtener_rol($_SESSION['id']);
    // Verificar si obtener_rol() devolvió un resultado
    if (isset($rolUsuario[0])) {
        $rol = $rolUsuario[0];
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Util/Css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">

    <link rel="icon" type="image/png" href="../Util/Assets/Grupo Nor Logo 2 [Recuperado].svg">
    <title>GRUPO NOR | Home</title>
</head>



<body>

    <!--<div class="popup-background-news"></div> -->

    <nav class="nav-bar">

        <section id="nav">

            <div class="logo-container">
                <div class="logo-img-container">
                    <img src="../Util/Assets/Grupo Nor Logo 3[Recuperado].png" class="logo-img" />
                </div>
                <ul class="nav-list">
                    <a class="nav-link" href="./tienda.php"> TIENDA </a>
                    <a class="nav-link" href="#quienes-somos"> NOSOTROS </a>
                    <a class="nav-link" href="#servicio"> SERVICIOS </a>
                    <a class="nav-link" href="#proyects"> PROYECTOS </a>
                    <?php if($rol !== null && $rol->tipo !== 'Cliente') : ?>
                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#">ACCESOS</a>
                            <ul class="dropdown-menu">
                                <?php
                                    if($rol !== null) {
                                        if ($rol->tipo === 'Administrador') {
                                            echo '<li><a class="dropdown-item" href="./categoria.php">Categorias</a></li>
                                            <li><a class="dropdown-item" href="./pedidoAdmin.php">Pedidos de clientes</a></li>
                                            <li><a class="dropdown-item" href="./stock.php">Stock</a></li>
                                            <li><a class="dropdown-item" href="./usuarios.php">Lista usuarios</a></li>';
                                        } elseif ($rol->tipo === 'Repositor') {
                                            echo '<li><a class="dropdown-item" href="./categoria.php">Categorias</a></li>
                                            <li><a class="dropdown-item" href="./stock.php">Stock</a></li>';
                                        } elseif ($rol->tipo === 'Empleado') {
                                            echo ' <li><a class="dropdown-item" href="./categoria.php">Categorias</a></li>
                                            <li><a class="dropdown-item" href="./pedidoAdmin.php">Pedidos de clientes</a></li>
                                            <li><a class="dropdown-item" href="./stock.php">Stock</a></li>';
                                        }
                                    } 
                                ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                    <?php
                        if (empty($rol->tipo)) {
                            echo '<a class="nav-link-mobile" href="./login.php"> INICIAR SESIÓN </a>';
                            echo '<a class="nav-link-login" href="./login.php"> INICIAR SESION </a>';
                        }else{
                            echo '<a class="nav-link-mobile" href="../Controllers/logout.php"> CERRAR SESIÓN </a>';
                            echo '<a class="nav-link-login" href="../Controllers/logout.php"> CERRAR SESIÓN </a>';
                        }
                    ?>
                
                <!-- <p class="nav-spacer">I</p> -->
                
                <div class="ham">
                    <img src="../Util/Assets/menu.svg" class="ham-img" />
                </div>

            </div>

        </section>

    </nav>



    <main>

        <!--home-->

        <img src="../Util/Assets/fondomain.jpeg" class="back-image">
        <div class="back-image-2"></div>


        <!--<section id="popup-news">
            
            <div class="popup-center-news">
                <div class="popup-container-news">
                    <div class="marni-container-news">
                        <img src="../Util/Assets/workeranimated.png" class="marni">
                    </div>
                    <a href="#" class="close-btn">
                        <img src="../Util/Assets/close-button.svg" alt="">
                    </a>
                    <div class="popup-text-news">
                        <h2>Bienvenido!</h2>
                        <p>¿Quieres recibir las últimas novedades? </p>
                    </div>
                    <form action="" class="popup-log-news">
                        <input type="text" class="popup-input-news" placeholder="Nombre Completo" required>
                        <input type="email" class="popup-input-news" placeholder="Dirección Email" required>
                    </form>
                    <button href="#" class="btn-popup-news">Suscribirse</button>
                </div>
            </div>
        </section> -->


        <section id="popup-empleo">

            <div class="popup-center">

                <div class="popup-container">

                    <a href="#" class="close-btn">
                        <img src="../Util/Assets/close-button.svg" alt="">
                    </a>

                    <div class="popup-column-1">

                        <div class="pp-clm1-div">

                            <h1>Únete a<br>Nosotros!</h1>

                            <p class="pp-desc">Inspiramos, desafiamos y empoderamos a todos nuestros alumnos a
                                ser miembros comprometidos y éticos de una comunidad global.</p>

                            <form action="" class="popup-log">
                                <input type="text" class="popup-input" placeholder="Nombre Completo" required>
                                <input type="email" class="popup-input" placeholder="Email" required>
                                <input type="text" class="popup-input" placeholder="Teléfono" required>
                                <input type="text" class="popup-input" placeholder="Dirección" required>
                            </form>

                            <button href="#" class="btn-popup">Enviar</button>

                        </div>

                    </div>

                    <div class="popup-column-2">

                        <div class="pp-clm2-div1">
                            <div class="pp-img-div">
                                <img src="../Util/Assets/Logo_TPI.png" alt="">
                            </div>
                        </div>

                        <div class="pp-clm2-div2"></div>

                        <h3>CONTÁCTANOS</h3>
                        <p>+54 9 362 480 3822</p>
                        <p>+54 9 362 474 7543</p>
                        <p></p>
                        <h3>DIRECCIÓN</h3>
                        <p>Carlos Pellegrini 777,
                            H3500 Resistencia, Chaco</p>

                        <div class="pp-redes">
                            <a href=""><img src="../Util/Assets/linkedin.svg" alt=""></a>
                            <a href=""><img src="../Util/Assets/instagram.svg" alt=""></a>
                            <a href=""><img src="../Util/Assets/facebook.svg" alt=""></a>
                            <a href=""><img src="../Util/Assets/mail.svg" alt=""></a>
                        </div>
                    </div>

                </div>

            </div>

            </div>

        </section>

        <section id="popup-comment">

            <div class="popup-center-cm">

                <div class="popup-container-cm">

                    <a href="#" class="close-btn-cm">
                        <img src="../Util/Assets/close-button.svg" alt="">
                    </a>

                    <div class="popup-column-1-cm">

                        <div class="pp-cm-img">
                            <img src="../Util/Assets/secundaria.png" alt="">
                        </div>

                    </div>

                    <div class="popup-column-2-cm">

                        <div class="pp-clm1-div">

                            <h1>Deja tu comentario!</h1>

                            <p class="pp-desc">Escríbelo aquí y todos lo verán.</p>

                            <form action="" class="popup-log">
                                <input type="text" class="popup-input" placeholder="Nombre Completo" required>
                                <input type="text" class="popup-input" placeholder="Comentario" required>
                            </form>

                            <button href="#" class="btn-popup-cm">Enviar</button>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <section id="home">



            <div class='landing-container'>
                <div class='LandingText'>
                    <h1>Construcción de Tinglados & Distribución de Aceros</h1>
                    <p class="landing-desc">Descubra la excelencia en construcción de tinglados, galpones y distribución
                        de acero en Argentina con nuestra empresa chaqueña.
                        Ofrecemos la mejor calidad a precios competitivos y estamos listos para instalar
                        en cualquier punto del país. Confíe en nosotros para cumplir sus proyectos con
                        profesionalismo y eficiencia.
                    </p>
                    <div class="landing-buttons">
                        <Button class='btn-cotizar' onclick="window.location.href='./calculadora.php';">COTIZAR</Button>
                        <Button class='btn-contact' onclick="window.location.href='#end';">CONTÁCTANOS</Button>
                    </div>
                </div>

                <div class='landing-img-container'>
                    <img src="../Util/Assets/worker no background.png" alt="">
                </div>

            </div>

        </section>

        <section id="quienes-somos">

            <div class="qs-main-div">
                <div class="qs-img-div">
                    <div class="qs-img-background">
                        <img src="../Util/Assets/worker.jpg" alt="Trabajador" class="img-worker">
                        <div class="qs-list">
                            ✓ Presupuesto instantaneo.<br>
                            ✓ Servicio de instlación.<br>
                            ✓ Múltiples modos de pago.<br>
                            ✓ Excelencia garantizada.
                        </div>

                    </div>
                </div>

                <div class="qs-text-div">
                    <h4 class="top-section-name">
                        <svg xmlns="http://www.w3.org/2000/svg" width="47.5" height="3" viewBox="0 0 47.5 2">
                            <line id="Línea_2" data-name="Línea 2" x2="45.5" transform="translate(1 1)" fill="none"
                                stroke="#ac2e32" stroke-linecap="round" stroke-width="4" />
                        </svg>
                        SOBRE NOSOTROS
                    </h4>
                    <h2>
                        Construyendo Confianza
                        en cada Proyecto
                    </h2>
                    <p class="qs-special-p">
                        Descubra la excelencia en construcción de tinglados, galpones
                        y distribución de acero en Argentina con nuestra empresa
                        chaqueña.
                        <br>
                    </p>
                    <p>
                        Ofrecemos la mejor calidad a precios competitivos y estamos
                        listos para instalar en cualquier punto del país. Confíe en nosotros
                        para cumplir sus proyectos con profesionalismo y eficiencia.
                    </p>
                    <a href="./tienda.php"><button id="btn-vermas">VER MÁS</button></a>
                </div>

            </div>

        </section>

        <section id="servicio">

            <div class="serv-main-div">
                <!-- <div class="serv-back-img">
                        <img src="Assets/lines warehouse.png" alt="">
                    </div> -->
                <div class="serv-text-div">
                    <h4 class="top-section-name">
                        <svg xmlns="http://www.w3.org/2000/svg" width="47.5" height="3" viewBox="0 0 47.5 2">
                            <line id="Línea_2" data-name="Línea 2" x2="45.5" transform="translate(1 1)" fill="none"
                                stroke="#ac2e32" stroke-linecap="round" stroke-width="4" />
                        </svg>
                        NUESTRO SERVICIO
                    </h4>
                    <div class="serv-text">
                        <h2>
                            Ofrecemos un Servicio de Alta Calidad
                        </h2>
                        <p>
                            Ofrecemos la mejor calidad a precios competitivos y estamos
                            listos para instalar en cualquier punto del país. Confíe en nosotros
                            para cumplir sus proyectos con profesionalismo y eficiencia
                        </p>
                    </div>
                </div>
                <div class="serv-cards-container">

                    <div class="serv-card">

                        <svg xmlns="http://www.w3.org/2000/svg" width="77.999" height="78" viewBox="0 0 77.999 78">
                            <path id="Sustracción_1" data-name="Sustracción 1"
                                d="M54,78A39.01,39.01,0,0,1,38.82,3.065,39.01,39.01,0,0,1,69.18,74.935,38.755,38.755,0,0,1,54,78ZM48.541,26.8h0L35.722,39.563a5.384,5.384,0,0,0-1.188,1.8,5.632,5.632,0,0,0-.414,2.127,5.45,5.45,0,0,0,1.326,3.647,5.282,5.282,0,0,0,3.426,1.823,5.641,5.641,0,0,0,1.519,3.122,5.267,5.267,0,0,0,3.122,1.574,5.433,5.433,0,0,0,4.7,4.7,5.294,5.294,0,0,0,1.851,3.4A5.412,5.412,0,0,0,53.68,63.1a5.328,5.328,0,0,0,2.128-.443,5.751,5.751,0,0,0,1.8-1.215L75.726,43.376a8.742,8.742,0,0,0,1.933-2.9,8.836,8.836,0,0,0,.663-3.343,9.049,9.049,0,0,0-.663-3.37,8.687,8.687,0,0,0-1.933-2.929L66.388,21.5a8.743,8.743,0,0,0-2.9-1.934,8.765,8.765,0,0,0-6.685,0A9.365,9.365,0,0,0,53.845,21.5l-.607.608-.608-.608a8.743,8.743,0,0,0-2.9-1.934,8.765,8.765,0,0,0-6.685,0A9.378,9.378,0,0,0,40.087,21.5l-7.79,7.79a8.9,8.9,0,0,0-1.823,2.625,8.785,8.785,0,0,0,.94,8.868l3.2-3.2a4.03,4.03,0,0,1-.443-1.3,4.244,4.244,0,0,1,0-1.354,4.7,4.7,0,0,1,.443-1.326,4.432,4.432,0,0,1,.828-1.16l7.791-7.846a4.1,4.1,0,0,1,1.491-.967,4.792,4.792,0,0,1,1.658-.3,4.722,4.722,0,0,1,1.686.3,3.666,3.666,0,0,1,1.409.967l7.9,7.9a.671.671,0,0,1,.249.331,1.269,1.269,0,0,1,.083.443,1.038,1.038,0,0,1-.332.8,1.184,1.184,0,0,1-.829.3,1.247,1.247,0,0,1-.442-.083.662.662,0,0,1-.331-.248L48.541,26.8Zm5.194,31.881a1.16,1.16,0,0,1-.8-.331,1.029,1.029,0,0,1-.359-.774,1.049,1.049,0,0,1,.083-.414,1.178,1.178,0,0,1,.248-.359l7.515-7.515-3.094-3.094-7.515,7.459a1.145,1.145,0,0,1-.359.249,1.049,1.049,0,0,1-.414.083,1.133,1.133,0,0,1-1.105-1.105,1.259,1.259,0,0,1,.083-.442.674.674,0,0,1,.248-.332l7.459-7.514L52.629,41.5l-7.514,7.459a1.3,1.3,0,0,1-.331.221.984.984,0,0,1-.442.11,1.132,1.132,0,0,1-1.105-1.1,1.036,1.036,0,0,1,.083-.415,1.147,1.147,0,0,1,.248-.359L51.027,39.9,47.933,36.8l-7.514,7.514a1.294,1.294,0,0,1-.332.221.979.979,0,0,1-.442.11,1.025,1.025,0,0,1-.773-.359,1.157,1.157,0,0,1-.332-.8,1.049,1.049,0,0,1,.083-.414,1.166,1.166,0,0,1,.249-.359l9.669-9.67,4.145,4.089a5.044,5.044,0,0,0,1.8,1.16,5.979,5.979,0,0,0,2.128.387,5.38,5.38,0,0,0,5.47-5.47,5.779,5.779,0,0,0-.387-2.1,4.793,4.793,0,0,0-1.216-1.768L56.387,25.2l.608-.608a4.111,4.111,0,0,1,1.492-.967,4.792,4.792,0,0,1,1.658-.3,4.712,4.712,0,0,1,1.685.3,3.663,3.663,0,0,1,1.409.967l9.393,9.393a3.666,3.666,0,0,1,.967,1.409,4.741,4.741,0,0,1,.3,1.686,4.8,4.8,0,0,1-.3,1.657,4.1,4.1,0,0,1-.967,1.492L54.508,58.35a1.27,1.27,0,0,1-.332.221A.98.98,0,0,1,53.735,58.681Z"
                                transform="translate(-15)" fill="#ac2e32" />
                        </svg>

                        <h3>
                            Calidad & Satisfacción
                        </h3>

                        <p>
                            Ofrecemos la mejor calidad a
                            precios competitivos y
                            estamos listos para instalar
                            en cualquier punto del país.
                        </p>

                    </div>

                    <div class="serv-card">

                        <svg xmlns="http://www.w3.org/2000/svg" width="77.999" height="78" viewBox="0 0 77.999 78">
                            <path id="Sustracción_1" data-name="Sustracción 1"
                                d="M54,78A39.01,39.01,0,0,1,38.82,3.065,39.01,39.01,0,0,1,69.18,74.935,38.755,38.755,0,0,1,54,78ZM48.541,26.8h0L35.722,39.563a5.384,5.384,0,0,0-1.188,1.8,5.632,5.632,0,0,0-.414,2.127,5.45,5.45,0,0,0,1.326,3.647,5.282,5.282,0,0,0,3.426,1.823,5.641,5.641,0,0,0,1.519,3.122,5.267,5.267,0,0,0,3.122,1.574,5.433,5.433,0,0,0,4.7,4.7,5.294,5.294,0,0,0,1.851,3.4A5.412,5.412,0,0,0,53.68,63.1a5.328,5.328,0,0,0,2.128-.443,5.751,5.751,0,0,0,1.8-1.215L75.726,43.376a8.742,8.742,0,0,0,1.933-2.9,8.836,8.836,0,0,0,.663-3.343,9.049,9.049,0,0,0-.663-3.37,8.687,8.687,0,0,0-1.933-2.929L66.388,21.5a8.743,8.743,0,0,0-2.9-1.934,8.765,8.765,0,0,0-6.685,0A9.365,9.365,0,0,0,53.845,21.5l-.607.608-.608-.608a8.743,8.743,0,0,0-2.9-1.934,8.765,8.765,0,0,0-6.685,0A9.378,9.378,0,0,0,40.087,21.5l-7.79,7.79a8.9,8.9,0,0,0-1.823,2.625,8.785,8.785,0,0,0,.94,8.868l3.2-3.2a4.03,4.03,0,0,1-.443-1.3,4.244,4.244,0,0,1,0-1.354,4.7,4.7,0,0,1,.443-1.326,4.432,4.432,0,0,1,.828-1.16l7.791-7.846a4.1,4.1,0,0,1,1.491-.967,4.792,4.792,0,0,1,1.658-.3,4.722,4.722,0,0,1,1.686.3,3.666,3.666,0,0,1,1.409.967l7.9,7.9a.671.671,0,0,1,.249.331,1.269,1.269,0,0,1,.083.443,1.038,1.038,0,0,1-.332.8,1.184,1.184,0,0,1-.829.3,1.247,1.247,0,0,1-.442-.083.662.662,0,0,1-.331-.248L48.541,26.8Zm5.194,31.881a1.16,1.16,0,0,1-.8-.331,1.029,1.029,0,0,1-.359-.774,1.049,1.049,0,0,1,.083-.414,1.178,1.178,0,0,1,.248-.359l7.515-7.515-3.094-3.094-7.515,7.459a1.145,1.145,0,0,1-.359.249,1.049,1.049,0,0,1-.414.083,1.133,1.133,0,0,1-1.105-1.105,1.259,1.259,0,0,1,.083-.442.674.674,0,0,1,.248-.332l7.459-7.514L52.629,41.5l-7.514,7.459a1.3,1.3,0,0,1-.331.221.984.984,0,0,1-.442.11,1.132,1.132,0,0,1-1.105-1.1,1.036,1.036,0,0,1,.083-.415,1.147,1.147,0,0,1,.248-.359L51.027,39.9,47.933,36.8l-7.514,7.514a1.294,1.294,0,0,1-.332.221.979.979,0,0,1-.442.11,1.025,1.025,0,0,1-.773-.359,1.157,1.157,0,0,1-.332-.8,1.049,1.049,0,0,1,.083-.414,1.166,1.166,0,0,1,.249-.359l9.669-9.67,4.145,4.089a5.044,5.044,0,0,0,1.8,1.16,5.979,5.979,0,0,0,2.128.387,5.38,5.38,0,0,0,5.47-5.47,5.779,5.779,0,0,0-.387-2.1,4.793,4.793,0,0,0-1.216-1.768L56.387,25.2l.608-.608a4.111,4.111,0,0,1,1.492-.967,4.792,4.792,0,0,1,1.658-.3,4.712,4.712,0,0,1,1.685.3,3.663,3.663,0,0,1,1.409.967l9.393,9.393a3.666,3.666,0,0,1,.967,1.409,4.741,4.741,0,0,1,.3,1.686,4.8,4.8,0,0,1-.3,1.657,4.1,4.1,0,0,1-.967,1.492L54.508,58.35a1.27,1.27,0,0,1-.332.221A.98.98,0,0,1,53.735,58.681Z"
                                transform="translate(-15)" fill="#ac2e32" />
                        </svg>

                        <h3>
                            Calidad & Satisfacción
                        </h3>

                        <p>
                            Ofrecemos la mejor calidad a
                            precios competitivos y
                            estamos listos para instalar
                            en cualquier punto del país.
                        </p>

                    </div>

                    <div class="serv-card">

                        <svg xmlns="http://www.w3.org/2000/svg" width="77.999" height="78" viewBox="0 0 77.999 78">
                            <path id="Sustracción_1" data-name="Sustracción 1"
                                d="M54,78A39.01,39.01,0,0,1,38.82,3.065,39.01,39.01,0,0,1,69.18,74.935,38.755,38.755,0,0,1,54,78ZM48.541,26.8h0L35.722,39.563a5.384,5.384,0,0,0-1.188,1.8,5.632,5.632,0,0,0-.414,2.127,5.45,5.45,0,0,0,1.326,3.647,5.282,5.282,0,0,0,3.426,1.823,5.641,5.641,0,0,0,1.519,3.122,5.267,5.267,0,0,0,3.122,1.574,5.433,5.433,0,0,0,4.7,4.7,5.294,5.294,0,0,0,1.851,3.4A5.412,5.412,0,0,0,53.68,63.1a5.328,5.328,0,0,0,2.128-.443,5.751,5.751,0,0,0,1.8-1.215L75.726,43.376a8.742,8.742,0,0,0,1.933-2.9,8.836,8.836,0,0,0,.663-3.343,9.049,9.049,0,0,0-.663-3.37,8.687,8.687,0,0,0-1.933-2.929L66.388,21.5a8.743,8.743,0,0,0-2.9-1.934,8.765,8.765,0,0,0-6.685,0A9.365,9.365,0,0,0,53.845,21.5l-.607.608-.608-.608a8.743,8.743,0,0,0-2.9-1.934,8.765,8.765,0,0,0-6.685,0A9.378,9.378,0,0,0,40.087,21.5l-7.79,7.79a8.9,8.9,0,0,0-1.823,2.625,8.785,8.785,0,0,0,.94,8.868l3.2-3.2a4.03,4.03,0,0,1-.443-1.3,4.244,4.244,0,0,1,0-1.354,4.7,4.7,0,0,1,.443-1.326,4.432,4.432,0,0,1,.828-1.16l7.791-7.846a4.1,4.1,0,0,1,1.491-.967,4.792,4.792,0,0,1,1.658-.3,4.722,4.722,0,0,1,1.686.3,3.666,3.666,0,0,1,1.409.967l7.9,7.9a.671.671,0,0,1,.249.331,1.269,1.269,0,0,1,.083.443,1.038,1.038,0,0,1-.332.8,1.184,1.184,0,0,1-.829.3,1.247,1.247,0,0,1-.442-.083.662.662,0,0,1-.331-.248L48.541,26.8Zm5.194,31.881a1.16,1.16,0,0,1-.8-.331,1.029,1.029,0,0,1-.359-.774,1.049,1.049,0,0,1,.083-.414,1.178,1.178,0,0,1,.248-.359l7.515-7.515-3.094-3.094-7.515,7.459a1.145,1.145,0,0,1-.359.249,1.049,1.049,0,0,1-.414.083,1.133,1.133,0,0,1-1.105-1.105,1.259,1.259,0,0,1,.083-.442.674.674,0,0,1,.248-.332l7.459-7.514L52.629,41.5l-7.514,7.459a1.3,1.3,0,0,1-.331.221.984.984,0,0,1-.442.11,1.132,1.132,0,0,1-1.105-1.1,1.036,1.036,0,0,1,.083-.415,1.147,1.147,0,0,1,.248-.359L51.027,39.9,47.933,36.8l-7.514,7.514a1.294,1.294,0,0,1-.332.221.979.979,0,0,1-.442.11,1.025,1.025,0,0,1-.773-.359,1.157,1.157,0,0,1-.332-.8,1.049,1.049,0,0,1,.083-.414,1.166,1.166,0,0,1,.249-.359l9.669-9.67,4.145,4.089a5.044,5.044,0,0,0,1.8,1.16,5.979,5.979,0,0,0,2.128.387,5.38,5.38,0,0,0,5.47-5.47,5.779,5.779,0,0,0-.387-2.1,4.793,4.793,0,0,0-1.216-1.768L56.387,25.2l.608-.608a4.111,4.111,0,0,1,1.492-.967,4.792,4.792,0,0,1,1.658-.3,4.712,4.712,0,0,1,1.685.3,3.663,3.663,0,0,1,1.409.967l9.393,9.393a3.666,3.666,0,0,1,.967,1.409,4.741,4.741,0,0,1,.3,1.686,4.8,4.8,0,0,1-.3,1.657,4.1,4.1,0,0,1-.967,1.492L54.508,58.35a1.27,1.27,0,0,1-.332.221A.98.98,0,0,1,53.735,58.681Z"
                                transform="translate(-15)" fill="#ac2e32" />
                        </svg>

                        <h3>
                            Calidad & Satisfacción
                        </h3>

                        <p>
                            Ofrecemos la mejor calidad a
                            precios competitivos y
                            estamos listos para instalar
                            en cualquier punto del país.
                        </p>

                    </div>

                    <div class="serv-card">

                        <svg xmlns="http://www.w3.org/2000/svg" width="77.999" height="78" viewBox="0 0 77.999 78">
                            <path id="Sustracción_1" data-name="Sustracción 1"
                                d="M54,78A39.01,39.01,0,0,1,38.82,3.065,39.01,39.01,0,0,1,69.18,74.935,38.755,38.755,0,0,1,54,78ZM48.541,26.8h0L35.722,39.563a5.384,5.384,0,0,0-1.188,1.8,5.632,5.632,0,0,0-.414,2.127,5.45,5.45,0,0,0,1.326,3.647,5.282,5.282,0,0,0,3.426,1.823,5.641,5.641,0,0,0,1.519,3.122,5.267,5.267,0,0,0,3.122,1.574,5.433,5.433,0,0,0,4.7,4.7,5.294,5.294,0,0,0,1.851,3.4A5.412,5.412,0,0,0,53.68,63.1a5.328,5.328,0,0,0,2.128-.443,5.751,5.751,0,0,0,1.8-1.215L75.726,43.376a8.742,8.742,0,0,0,1.933-2.9,8.836,8.836,0,0,0,.663-3.343,9.049,9.049,0,0,0-.663-3.37,8.687,8.687,0,0,0-1.933-2.929L66.388,21.5a8.743,8.743,0,0,0-2.9-1.934,8.765,8.765,0,0,0-6.685,0A9.365,9.365,0,0,0,53.845,21.5l-.607.608-.608-.608a8.743,8.743,0,0,0-2.9-1.934,8.765,8.765,0,0,0-6.685,0A9.378,9.378,0,0,0,40.087,21.5l-7.79,7.79a8.9,8.9,0,0,0-1.823,2.625,8.785,8.785,0,0,0,.94,8.868l3.2-3.2a4.03,4.03,0,0,1-.443-1.3,4.244,4.244,0,0,1,0-1.354,4.7,4.7,0,0,1,.443-1.326,4.432,4.432,0,0,1,.828-1.16l7.791-7.846a4.1,4.1,0,0,1,1.491-.967,4.792,4.792,0,0,1,1.658-.3,4.722,4.722,0,0,1,1.686.3,3.666,3.666,0,0,1,1.409.967l7.9,7.9a.671.671,0,0,1,.249.331,1.269,1.269,0,0,1,.083.443,1.038,1.038,0,0,1-.332.8,1.184,1.184,0,0,1-.829.3,1.247,1.247,0,0,1-.442-.083.662.662,0,0,1-.331-.248L48.541,26.8Zm5.194,31.881a1.16,1.16,0,0,1-.8-.331,1.029,1.029,0,0,1-.359-.774,1.049,1.049,0,0,1,.083-.414,1.178,1.178,0,0,1,.248-.359l7.515-7.515-3.094-3.094-7.515,7.459a1.145,1.145,0,0,1-.359.249,1.049,1.049,0,0,1-.414.083,1.133,1.133,0,0,1-1.105-1.105,1.259,1.259,0,0,1,.083-.442.674.674,0,0,1,.248-.332l7.459-7.514L52.629,41.5l-7.514,7.459a1.3,1.3,0,0,1-.331.221.984.984,0,0,1-.442.11,1.132,1.132,0,0,1-1.105-1.1,1.036,1.036,0,0,1,.083-.415,1.147,1.147,0,0,1,.248-.359L51.027,39.9,47.933,36.8l-7.514,7.514a1.294,1.294,0,0,1-.332.221.979.979,0,0,1-.442.11,1.025,1.025,0,0,1-.773-.359,1.157,1.157,0,0,1-.332-.8,1.049,1.049,0,0,1,.083-.414,1.166,1.166,0,0,1,.249-.359l9.669-9.67,4.145,4.089a5.044,5.044,0,0,0,1.8,1.16,5.979,5.979,0,0,0,2.128.387,5.38,5.38,0,0,0,5.47-5.47,5.779,5.779,0,0,0-.387-2.1,4.793,4.793,0,0,0-1.216-1.768L56.387,25.2l.608-.608a4.111,4.111,0,0,1,1.492-.967,4.792,4.792,0,0,1,1.658-.3,4.712,4.712,0,0,1,1.685.3,3.663,3.663,0,0,1,1.409.967l9.393,9.393a3.666,3.666,0,0,1,.967,1.409,4.741,4.741,0,0,1,.3,1.686,4.8,4.8,0,0,1-.3,1.657,4.1,4.1,0,0,1-.967,1.492L54.508,58.35a1.27,1.27,0,0,1-.332.221A.98.98,0,0,1,53.735,58.681Z"
                                transform="translate(-15)" fill="#ac2e32" />
                        </svg>

                        <h3>
                            Calidad & Satisfacción
                        </h3>

                        <p>
                            Ofrecemos la mejor calidad a
                            precios competitivos y
                            estamos listos para instalar
                            en cualquier punto del país.
                        </p>

                    </div>
                </div>
            </div>
        </section>

        <section id="infocard">
            <div class="info-main-div">
                <div class="info-container">
                    <div class="info">
                        <h3>
                            90+
                        </h3>
                        <p>
                            Proyectos Finalizados
                        </p>
                    </div>
                    <div class="info">
                        <h3>
                            6+
                        </h3>
                        <p>
                            Años de Experiencia
                        </p>
                    </div>
                    <div class="info">
                        <h3>
                            25+
                        </h3>
                        <p>
                            Profesionales
                        </p>
                    </div>
                    <div class="info">
                        <h3>
                            400+
                        </h3>
                        <p>
                            Clientes Satisfechos
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="proyects">
            <div class="proyects-main-div">
                <div class="proyects-text-div">
                    <h4 class="top-section-name">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="2" viewBox="0 0 25 2">
                            <line id="Línea_2" data-name="Línea 2" x2="25" transform="translate(1 1)" fill="none"
                                stroke="#fff" stroke-linecap="round" stroke-width="2" />
                        </svg>

                        NUESTRO TRABAJO

                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="2" viewBox="0 0 25 2">
                            <line id="Línea_2" data-name="Línea 2" x2="25" transform="translate(1 1)" fill="none"
                                stroke="#fff" stroke-linecap="round" stroke-width="2" />
                        </svg>

                    </h4>
                    <h1>
                        Proyectos Recientes
                    </h1>
                    <p>
                        Descubra la excelencia en construcción de tinglados, galpones y distribución
                        de acero en Argentina con nuestra empresa chaqueña.
                    </p>
                </div>
                <div class="proyects-carousel-container">
                    <div class="carousel" id="carousel">

                        


                        <div class="proyects-card" onclick="selectItem(0)">

                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="62" viewBox="0 0 6 62">
                                <line id="Línea_5" data-name="Línea 5" y1="56" transform="translate(3 3)" fill="none"
                                    stroke="#ac2e32" stroke-linecap="round" stroke-width="6" opacity="0.9" />
                            </svg>

                            <div class="proyects-card-text">
                                <h3>
                                    Tinglado parabólico amurado
                                </h3>
                                <p>
                                    Clorinda, Formosa
                                </p>
                            </div>


                        </div>

                        <div class="proyects-card" onclick="selectItem(1)">

                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="62" viewBox="0 0 6 62">
                                <line id="Línea_5" data-name="Línea 5" y1="56" transform="translate(3 3)" fill="none"
                                    stroke="#ac2e32" stroke-linecap="round" stroke-width="6" opacity="0.9" />
                            </svg>

                            <div class="proyects-card-text">
                                <h3>
                                    Tinglado parabólico amurado
                                </h3>
                                <p>
                                    Clorinda, Formosa
                                </p>
                            </div>


                        </div>

                        <div class="proyects-card selected" onclick="selectItem(2)">

                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="62" viewBox="0 0 6 62">
                                <line id="Línea_5" data-name="Línea 5" y1="56" transform="translate(3 3)" fill="none"
                                    stroke="#ac2e32" stroke-linecap="round" stroke-width="6" opacity="0.9" />
                            </svg>

                            <div class="proyects-card-text">
                                <h3>
                                    Tinglado parabólico amurado
                                </h3>
                                <p>
                                    Clorinda, Formosa
                                </p>
                            </div>


                        </div>

                        <div class="proyects-card" onclick="selectItem(3)">

                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="62" viewBox="0 0 6 62">
                                <line id="Línea_5" data-name="Línea 5" y1="56" transform="translate(3 3)" fill="none"
                                    stroke="#ac2e32" stroke-linecap="round" stroke-width="6" opacity="0.9" />
                            </svg>

                            <div class="proyects-card-text">
                                <h3>
                                    Tinglado parabólico amurado
                                </h3>
                                <p>
                                    Clorinda, Formosa
                                </p>
                            </div>


                        </div>

                        <div class="proyects-card" onclick="selectItem(4)">

                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="62" viewBox="0 0 6 62">
                                <line id="Línea_5" data-name="Línea 5" y1="56" transform="translate(3 3)" fill="none"
                                    stroke="#ac2e32" stroke-linecap="round" stroke-width="6" opacity="0.9" />
                            </svg>

                            <div class="proyects-card-text">
                                <h3>
                                    Tinglado parabólico amurado
                                </h3>
                                <p>
                                    Clorinda, Formosa
                                </p>
                            </div>


                        </div>


                    </div>
                </div>
            </div>
        </section>


        <!-- FOOTER -->


        <section id="end">

            <div class="end-container-1">
                <h1>
                    Coméntanos sobre
                    tu Proyecto
                </h1>
                
            </div>

            <div class="end-container-2">

                <div class="end-list-1">
                    <div class="end-logo-container">
                        <img src="../Util/Assets/Grupo Nor Logo 3[Recuperado].png" alt="">
                    </div>
                    
                    <p>
                        Ofrecemos la mejor calidad a precios
                        competitivos y estamos listos para instalar en
                        cualquier punto del país. Confíe en nosotros para
                        cumplir sus proyectos con profesionalismo.
                    </p>
                </div>

                <div class="end-list-2">
                    <iframe class="end-map"
                        src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d890.3007895832679!2d-60.453807943699296!3d-26.801659107565058!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sColectora%20Sur%20y%20Calle%2022!5e0!3m2!1ses!2sar!4v1717992181737!5m2!1ses!2sar"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <div class="end-list-3">
                    <h4 class="end-list-item-name" href="">Contacto</h4>
                    <p class="end-list-item" href="">gruponor.sas.chaco@gmail.com</p>
                    <p class="end-list-item" href="">+54 343 5310475</p>
                    <p class="end-list-item" href="">Colectora Sur y Calle 22, Presidencia Roque Sáenz Peña, Argentina</p>
                </div>

                <div class="end-list-4">
                    <h4 class="end-list-item-name" href="">Redes</h4>
                    <a class="end-list-item" href="https://www.instagram.com/gruponor.ventas/">Instagram</a>
                    <a class="end-list-item" href="https://www.facebook.com/p/Grupo-NOR-100088112688254/">Facebook</a>
                    <a class="end-list-item" href="https://api.whatsapp.com/send?phone=%2B5493435310475&context=ARAefVPWNQyy32tq9e77YBJ4a7dWC4LeJL5J_wrwh6ThnrcQ2f3Tz-Aupn1kF7dDcJ2Q6MqjHVxRiL5OgDKtwkkkEEB_5Ei_TaGlAXXAlTU27EiumLk2Tnm-jSZWLunE9Cg1xFEZUBWZo-JacNhDc1w9Mg&source=FB_Page&app=facebook&entry_point=page_cta&fbclid=IwZXh0bgNhZW0CMTAAAR0P7ZYwqD28R9TTuC5vtI8dCsaAEG5OJ01B2nRY3uHq01u5EujxhZKt9KA_aem_AbKquTN6CnBkGSWAmfgIyrNZhgjYyL00cX6Svb3v8QsInw6KaXZuxv785fYnuqFzGoXMZexC_OAwrCvApoEDplXC">WhatsApp</a>
                </div>

            </div>

            <div class="end-container-3">
                <p class="end-copyright">© 2023 GRUPO NOR.</p>
                <div class="end-links">
                    <a class="end-link" href="">Privacy policy</a>
                    <a class="end-link" href="">Terms of service</a>
                    <a class="end-link" href="">Security</a>
                </div>
            </div>

        </section>
    </main>

    <script src="https://www.gstatic.com/firebasejs/10.3.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.3.1/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.3.1/firebase-database.js"></script>

    <script src="./index.js"></script>
    <!-- <script src="index.js"></script> -->
    <!--<script src="functions.js"></script> -->

</body>

</html>