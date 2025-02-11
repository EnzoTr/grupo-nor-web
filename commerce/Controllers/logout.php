<?php
//controlador para cerrar sesion
session_start();
session_destroy();
header('Location: ../Views/index.php');

