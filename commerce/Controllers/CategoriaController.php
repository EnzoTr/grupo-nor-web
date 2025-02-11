<?php
include_once '../Models/Categoria.php';
include '../Util/Config/config.php';
//esta variable esta siendo instanciada en Producto.php y a la vez en Conexion.php
$categoria = new Categoria();
//sirve para saber cuando el usuario entra en su sesion
session_start();

    if($_POST['funcion']=='obtener_categorias'){
        $categorias = $categoria->obtener_categorias();

        $jsonstring = json_encode($categorias);
        echo $jsonstring;
    }

    if ($_POST['funcion'] == 'agregar_categoria') {
        $nombre = $_POST['nombre'];
        $id_padre = is_numeric($_POST['id_padre']) ? $_POST['id_padre'] : null;
        $fecha_creacion = date('Y-m-d H:i:s');
        $descripcion = $_POST['descripcion'];

        // Validaciones
        if(empty($nombre)) {
            echo json_encode(['message' => 'El nombre no puede estar vacío', 'status' => 'error']);
            return;
        }

        $categoria->agregarCategoria($nombre, $id_padre, $fecha_creacion, $descripcion);

        // Envía una respuesta al cliente
        echo json_encode(['status' => 'success', 'message' => 'Categoría creada correctamente']);
    }

    if ($_POST['funcion'] == 'editar_categoria') {
        $id = $_POST['id'];
        if ($categoria->existe_categoria($id)) {
            $nombre = $_POST['nombre'];
            $id_padre = is_numeric($_POST['id_padre']) ? $_POST['id_padre'] : null;
            $descripcion = $_POST['descripcion'];

            // Validaciones
            if(empty($nombre)) {
                echo json_encode(['message' => 'El nombre no puede estar vacío', 'status' => 'error']);
                return;
            }

            $categoria->editar_categoria($id, $nombre, $id_padre, $descripcion);
            echo json_encode(['status' => 'success', 'message' => 'Categoría actualizada correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'La categoría no existe']);
        }
    }

    if ($_POST['funcion'] == 'eliminar_categoria') {
        $id = $_POST['id'];
        if ($categoria->existe_categoria($id)) {
            $categoria->eliminar_categoria($id);
            echo json_encode(['status' => 'success', 'message' => 'Categoría eliminada correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'La categoría no existe']);
        }
    }
    
    if ($_POST['funcion'] == 'activar_categoria') {
        $id = $_POST['id'];
        if ($categoria->existe_categoria($id)) {
            $categoria->activar_categoria($id);
            echo json_encode(['status' => 'success', 'message' => 'Categoría activada correctamente']);;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'La categoría no existe']);
        }
    }
    
    if ($_POST['funcion'] == 'desactivar_categoria') {
        $id = $_POST['id'];
        if ($categoria->existe_categoria($id)) {
            $categoria->desactivar_categoria($id);
            echo json_encode(['status' => 'success', 'message' => 'Categoría desactivada correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'La categoría no existe']);
        }
    }

    if ($_POST['funcion'] == 'obtener_categorias_activas') {
        $categorias = $categoria->obtener_categorias_activas();
        $jsonstring = json_encode($categorias);
        echo $jsonstring;
    }