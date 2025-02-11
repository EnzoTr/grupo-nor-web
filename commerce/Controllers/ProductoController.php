<?php
include_once '../Models/Producto.php';
include_once '../Models/Categoria.php';
include '../Util/Config/config.php';
//esta variable esta siendo instanciada en Producto.php y a la vez en Conexion.php
$producto = new Producto();
$categoria = new Categoria();
//sirve para saber cuando el usuario entra en su sesion
session_start();

    if($_POST['funcion']=='llenar_productos'){
        $limit = isset($_POST['limit']) ? $_POST['limit'] : 20;
        $id_categoria = isset($_POST['id_categoria']) ? $_POST['id_categoria'] : null;
        $sortValue = isset($_POST['sortValue']) ? $_POST['sortValue'] : null;
        $searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] : null;
        $producto->llenar_productos($limit, $id_categoria, $sortValue, $searchValue);
        $json=array();
        foreach($producto->objetos as $objeto){
            $json[]=array(
                'id'=>openssl_encrypt($objeto->id,CODE,KEY),
                'nombre'=>$objeto->nombre,
                'stock'=>$objeto->stock,
                'precio'=>intval($objeto->precio),
                'foto'=>$objeto->foto,
                'descripcion'=>$objeto->descripcion,
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

    if($_POST['funcion']=='verificar_productos'){
        $id_prod = openssl_decrypt($_SESSION['product-verification'], CODE, KEY);
        if (is_numeric($id_prod)) {
            $producto->obtener_producto($id_prod);
            $id_producto = $producto->objetos[0]->id;
            $nombre = $producto->objetos[0]->nombre;
            $stock = $producto->objetos[0]->cantidad_disponible;
            $precio = $producto->objetos[0]->precio_unitario;
            $foto = $producto->objetos[0]->foto;
            $descripcion = $producto->objetos[0]->descripcion;
            $nombre_categoria = $producto->objetos[0]->nombre_categoria;
            $producto->capturar_imagenes($id_producto);
            $imagenes =  array();
            foreach($producto->objetos as $objeto){
                $imagenes[]=array(
                    'id'=>$objeto->id,
                    'nombre'=>$objeto->nombre
                );
            }
            $json=array(
                'id'=>$id_prod,
                'nombre'=>$nombre,
                'stock'=>$stock,
                'precio'=>intval($precio),
                'foto'=>$foto,
                'descripcion'=>$descripcion,
                'nombre_categoria'=>$nombre_categoria,
                'imagenes'=>$imagenes
            );
            
            $jsonstring = json_encode($json);
            echo $jsonstring; 
        }
        else {
            echo "error";
        }
        
    }

    if($_POST['funcion']=='eliminar_producto'){
        $id = $_POST['id'];
        // Verifica si el producto existe
        if (!$producto->existe($id)) {
            // Si el producto no existe, maneja este caso de error (por ejemplo, mostrando un mensaje de error al usuario)
            echo json_encode(['message' => 'El producto no existe', 'status' => 'error']);
            return;
        }

        // Si el producto existe, procede a eliminarlo
        $producto->eliminar_producto($id);

        // Devuelve un mensaje de éxito
        echo json_encode(['message' => 'Producto eliminado', 'status' => 'success']);
    }

    if($_POST['funcion']=='obtener_productos'){
        $productos = $producto->obtener_productos();
    
        $jsonstring = json_encode($productos);
        echo $jsonstring;
    }

    if($_POST['funcion']=='crear_tinglado'){
        $nombre = 'Tinglado Personalizado';
        $id_categoria = $categoria->obtener_categoria_nombre('Tinglados')[0]->id;
        $precio_unitario = $_POST['precio'];
        $ancho = $_POST['ancho'];
        $largo = $_POST['largo'];
        $cantidad = 1;
        $tipo_techo = '';

        // Mapear los valores del tipo de techo
        switch ($_POST['tipo_techo']) {
            case 'a_dos_aguas':
                $tipo_techo = 'A dos aguas';
                break;
            case 'plano':
                $tipo_techo = 'Plano';
                break;
            case 'parabolico':
                $tipo_techo = 'Parabólico';
                break;
            default:
                $tipo_techo = ''; // Valor por defecto o manejo de errores
                break;
        }

        $color = '';

        // Mapear los valores del color
        switch ($_POST['color']) {
            case 'gris_metalico':
                $color = 'Gris Metálico';
                break;
            case 'azul':
                $color = 'Azul';
                break;
            default:
                $color = ''; // Valor por defecto o manejo de errores
                break;
        }
        $fecha_registro = date('Y-m-d H:i:s');
        $estado = 'T';
        $nuevo_tinglado_id = $producto->crear_tinglado($nombre, $id_categoria, $precio_unitario, $fecha_registro, $largo, $ancho, $tipo_techo, $color, $estado, $cantidad);
        
        // Verificar si se creó correctamente el nuevo producto
        if ($nuevo_tinglado_id) {
            // Si se creó correctamente, enviar el ID del nuevo producto en la respuesta JSON
            echo json_encode(['message' => 'Tinglado creado', 'status' => 'success', 'producto_id' => $nuevo_tinglado_id]);
        } else {
            // Si hubo algún error al crear el producto, enviar un mensaje de error en la respuesta JSON
            echo json_encode(['message' => 'Error al crear el tinglado', 'status' => 'error']);
        }
    }

    if($_POST['funcion']=='crear_producto'){
        $nombre = $_POST['nombre'];
        $id_categoria = $_POST['id_categoria'];
        $descripcion = $_POST['descripcion'];
        $precio_unitario = $_POST['precio_unitario'];
        $cantidad_disponible = $_POST['cantidad_disponible'];
        $fecha_registro = date('Y-m-d H:i:s');
        $sector = $_POST['sector'];
        $costo_unidad = $_POST['costo_unidad'];
        $precio_envio_km = $_POST['precio_envio'];
        $foto = $_FILES['foto']['name'] ? $_FILES['foto']['name'] : null;

        // Validaciones
        if(empty($nombre)) {
            echo json_encode(['message' => 'El nombre no puede estar vacío', 'status' => 'error']);
            return;
        }

        if(!is_numeric($precio_unitario) || !is_numeric($cantidad_disponible) || !is_numeric($costo_unidad)) {
            echo json_encode(['message' => 'Los campos numéricos deben ser números', 'status' => 'error']);
            return;
        }
    
        if ($foto) {
            $ruta = '../Util/Img/Producto/' . $foto;
            move_uploaded_file($_FILES['foto']['tmp_name'], $ruta);
        }

        $id_producto = $producto->crear_producto($nombre, $id_categoria, $descripcion, $precio_unitario, $cantidad_disponible, $fecha_registro, $sector, $costo_unidad, $ruta, $precio_envio_km);
    
        if (isset($_FILES['fotos'])) {
            foreach ($_FILES['fotos']['name'] as $key => $nombre_foto) {
                // Verificar si se ha subido una foto
                if ($nombre_foto) {
                    $ruta = '../Util/Img/Producto/' . $nombre_foto;
                    $producto->agregar_imagen($id_producto, $ruta);
                    move_uploaded_file($_FILES['fotos']['tmp_name'][$key], $ruta);
                }
            }
        }
    
        echo json_encode(['message' => 'Producto creado', 'status' => 'success']);
    }
    
    if($_POST['funcion']=='editar_producto'){
        $id = $_POST['id'];
        if ($producto->existe($id)) {
            $nombre = $_POST['nombre'];
            $id_categoria = $_POST['id_categoria'];
            $descripcion = $_POST['descripcion'];
            $cantidad_disponible = $_POST['cantidad_disponible'];
            $precio_unitario = $_POST['precio_unitario'];
            $fecha_actualizacion = date('Y-m-d H:i:s');
            $sector = $_POST['sector'];
            $costo_unidad = $_POST['costo_unidad'];
            $foto = $_FILES['foto']['name'] ? $_FILES['foto']['name'] : null;
        
            // Validaciones
            if(empty($nombre)) {
                echo json_encode(['message' => 'El nombre no puede estar vacío', 'status' => 'error']);
                return;
            }

            if(!is_numeric($precio_unitario) || !is_numeric($cantidad_disponible) || !is_numeric($costo_unidad)) {
                echo json_encode(['message' => 'Los campos numéricos deben ser números', 'status' => 'error']);
                return;
            }

            if ($foto) {
                // Obtén la ruta de la foto antigua
                $ruta_antigua = $producto->obtener_foto($id);

                $ruta = '../Util/Img/Producto/' . $foto;
                move_uploaded_file($_FILES['foto']['tmp_name'], $ruta);

                // Si la foto antigua existe, elimínala
                if ($ruta_antigua && file_exists($ruta_antigua)) {
                    unlink($ruta_antigua);
                }
            }

            $producto->editar_producto($id, $nombre, $id_categoria, $descripcion, $cantidad_disponible, $precio_unitario, $fecha_actualizacion, $sector, $costo_unidad, $ruta);
        
            if (isset($_FILES['fotos'])) {
                foreach ($_FILES['fotos']['name'] as $key => $nombre_foto) {
                    // Verificar si se ha subido una foto
                    if ($nombre_foto) {
                        $ruta = '../Util/Img/Producto/' . $nombre_foto;
                        $producto->agregar_imagen($id, $ruta);
                        move_uploaded_file($_FILES['fotos']['tmp_name'][$key], $ruta);
                    }
                }
            }
        
            echo json_encode(['message' => 'Producto editado', 'status' => 'success']);
        } else {
            echo json_encode(['message' => 'El producto no existe', 'status' => 'error']);
        }
    }
    
    if($_POST['funcion']=='obtener_producto'){
        $id = $_POST['id'];
        $productoData = $producto->obtener_producto($id);

        // Obtener las fotos del producto
        $fotos = $producto->obtener_fotos($id);

        // Agregar las fotos al producto
        $productoData[0]->fotos = $fotos;

        $jsonstring = json_encode($productoData);
        echo $jsonstring;
    }
    
    if($_POST['funcion']=='modificar_cantidad_disponible'){
        $id = $_POST['id'];
        $cantidad = $_POST['cantidad'];

        // Validaciones
        if(!is_numeric($cantidad)) {
            echo json_encode(['message' => 'La cantidad debe ser un número', 'status' => 'error']);
            return;
        }

        $producto->modificar_cantidad_disponible($id, $cantidad);
        echo json_encode(['message' => 'Cantidad modificada', 'status' => 'success']);
    }
    
    if($_POST['funcion']=='agregar_imagen'){
        $id_producto = $_POST['id_producto'];
        $nombre = $_FILES['imagen']['name'];
    
        $producto->agregar_imagen($id_producto, $nombre);
        echo json_encode(['message' => 'Imagen agregada', 'status' => 'success']);
    }
    
    if($_POST['funcion']=='eliminar_foto'){
        $id = $_POST['id'];
        $ruta = $producto->obtener_ruta($id);
        $producto->eliminar_foto($id);
        // Si la foto existe en el sistema de archivos, elimínala
        if (file_exists($ruta)) {
            unlink($ruta);
        }
        echo json_encode(['message' => 'Foto eliminada', 'status' => 'success']);
    }

    if ($_POST['funcion'] == 'modificar_estado_producto') {
        $id = $_POST['id'];
        $estado = $_POST['estado'];
        if ($producto->existe($id)) {
            if ($estado == 'A') {
                $producto->desactivar_producto($id);
                echo json_encode(['status' => 'success', 'message' => 'Producto desactivado correctamente']);
            } else if ($estado == 'I') {
                $producto->activar_producto($id);
                echo json_encode(['status' => 'success', 'message' => 'Producto activado correctamente']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Estado inválido']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'El producto no existe']);
        }
    }
