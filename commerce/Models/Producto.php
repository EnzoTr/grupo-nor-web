<?php

//llamamos a la conexion de la bd

    include_once 'Conexion.php';
    class Producto{
        var $objetos;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }

        // Funcion para mostrar los productos activados en el ecommerce
        function llenar_productos($limit = 20, $id_categoria = null, $sortValue = null, $searchValue = null){
            $orderBy = ' ORDER BY producto.vendido DESC';

            if ($sortValue === 'precio_ascendente') {
                $orderBy = ' ORDER BY producto.precio_unitario ASC';
            } elseif ($sortValue === 'precio_descendente') {
                $orderBy = ' ORDER BY producto.precio_unitario DESC';
            } elseif ($sortValue === 'nombre_ascendente') {
                $orderBy = ' ORDER BY producto.nombre ASC';
            } elseif ($sortValue === 'nombre_descendiente') {
                $orderBy = ' ORDER BY producto.nombre DESC';
            } elseif ($sortValue === 'mas_vendido') {
                $orderBy = ' ORDER BY producto.vendido DESC';
            } elseif ($sortValue === 'nuevo') {
                $orderBy = ' ORDER BY producto.fecha_registro DESC';
            } elseif ($sortValue === 'viejo') {
                $orderBy = ' ORDER BY producto.fecha_registro ASC';
            }

            // Agregar cláusula WHERE para búsqueda
            $whereSearch = '';
            if ($searchValue !== null) {
                $whereSearch = " AND producto.nombre LIKE :searchValue";
            }

            if ($id_categoria !== null) {
                $sql = "WITH RECURSIVE subcategorias AS (
                    SELECT id FROM categoria WHERE id = :id_categoria
                    UNION ALL
                    SELECT c.id FROM categoria c
                    INNER JOIN subcategorias s ON c.id_padre = s.id
                )
                SELECT producto.id as id,
                producto.nombre as nombre,
                producto.cantidad_disponible as stock,
                producto.precio_unitario as precio,
                producto.foto as foto,
                producto.descripcion as descripcion
                FROM producto
                INNER JOIN subcategorias ON producto.id_categoria = subcategorias.id
                WHERE producto.estado = 'A' AND producto.cantidad_disponible > 0"
                . $whereSearch
                . $orderBy .
                " LIMIT :limit";
            } else {
                $sql = "SELECT producto.id as id,
                producto.nombre as nombre,
                producto.cantidad_disponible as stock,
                producto.precio_unitario as precio,
                producto.foto as foto,
                producto.descripcion as descripcion
                FROM producto
                WHERE producto.estado = 'A' AND producto.cantidad_disponible > 0"
                . $whereSearch
                . $orderBy .
                " LIMIT :limit";
            }

            $query = $this->acceso->prepare($sql); 
            $query->bindValue(':limit', (int) trim($limit), PDO::PARAM_INT);

            if ($id_categoria !== null) {
                $query->bindValue(':id_categoria', trim($id_categoria), PDO::PARAM_INT);
            }

            // Agregar valor de búsqueda a la consulta
            if ($searchValue !== null) {
                $query->bindValue(':searchValue', '%' . trim($searchValue) . '%', PDO::PARAM_STR);
            }

            $query->execute();
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }

        //funcion para traer las imagenes del producto 
        function capturar_imagenes($id_producto){
            $sql ="SELECT *
                FROM imagen
                WHERE imagen.id_producto = :id_producto
                AND imagen.estado = 'A'";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':id_producto'=>$id_producto));
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }

        //consultas a la bd
        public function crear_producto($nombre, $id_categoria, $descripcion, $precio_unitario, $cantidad_disponible, $fecha_registro, $sector, $costo_unidad, $foto = null, $precio_envio_km) {
            $this->acceso->beginTransaction();
            try {
                if ($foto) {
                    $sql = "INSERT INTO producto(nombre, id_categoria, descripcion, precio_unitario, cantidad_disponible, fecha_registro, sector, costo_unidad, foto, precio_envio_km) 
                            VALUES(:nombre, :id_categoria, :descripcion, :precio_unitario, :cantidad_disponible, :fecha_registro, :sector, :costo_unidad, :foto, :precio_envio_km)";
                    $query = $this->acceso->prepare($sql); 
                    $query->execute(array(':nombre'=>$nombre, ':id_categoria'=>$id_categoria, ':descripcion'=>$descripcion, ':precio_unitario'=>$precio_unitario, ':cantidad_disponible'=>$cantidad_disponible, ':fecha_registro'=>$fecha_registro, ':sector'=>$sector, ':costo_unidad'=>$costo_unidad, ':foto'=>$foto, ':precio_envio_km'=>$precio_envio_km));
                } else {
                    $sql = "INSERT INTO producto(nombre, id_categoria, descripcion, precio_unitario, cantidad_disponible, fecha_registro, sector, costo_unidad, precio_envio_km) 
                            VALUES(:nombre, :id_categoria, :descripcion, :precio_unitario, :cantidad_disponible, :fecha_registro, :sector, :costo_unidad, :precio_envio_km)";
                    $query = $this->acceso->prepare($sql); 
                    $query->execute(array(':nombre'=>$nombre, ':id_categoria'=>$id_categoria, ':descripcion'=>$descripcion, ':precio_unitario'=>$precio_unitario, ':cantidad_disponible'=>$cantidad_disponible, ':fecha_registro'=>$fecha_registro, ':sector'=>$sector, ':costo_unidad'=>$costo_unidad, ':precio_envio_km'=>$precio_envio_km));
                }
                $id_producto = $this->acceso->lastInsertId();
                $this->acceso->commit();
                return $id_producto;
            } catch (Exception $e) {
                $this->acceso->rollBack();
                throw $e;
            }
        }

        public function crear_tinglado($nombre, $id_categoria, $precio_unitario, $fecha_registro, $largo, $ancho, $tipo_techo, $color, $estado, $cantidad) {
            $this->acceso->beginTransaction();
            try {
                // Verificar si el valor de descripción es necesario
                $descripcion = 'Tinglado personalizado';
        
                $sql = "INSERT INTO producto(nombre, id_categoria, descripcion, precio_unitario, fecha_registro, largo, ancho, tipo_techo, color, estado, cantidad_disponible) 
                        VALUES(:nombre, :id_categoria, :descripcion, :precio_unitario, :fecha_registro, :largo, :ancho, :tipo_techo, :color, :estado, :cantidad)";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(
                    ':nombre' => $nombre,
                    ':id_categoria' => $id_categoria,
                    ':descripcion' => $descripcion,
                    ':precio_unitario' => $precio_unitario,
                    ':fecha_registro' => $fecha_registro,
                    ':largo' => $largo,
                    ':ancho' => $ancho,
                    ':tipo_techo' => $tipo_techo,
                    ':color' => $color,
                    ':estado' => $estado,
                    ':cantidad' => $cantidad
                ));
                
                // Obtener el ID del producto recién insertado
                $id_producto = $this->acceso->lastInsertId();
                $this->acceso->commit();
                return $id_producto;
            } catch (Exception $e) {
                $this->acceso->rollBack();
                error_log("Error al crear el tinglado: " . $e->getMessage()); // Log del error
                throw $e;
            }
        }

        function obtener_productos() {
            $sql = "SELECT p.id, p.nombre, p.descripcion, p.precio_unitario, p.fecha_registro, p.fecha_actualizacion, p.costo_unidad, p.cantidad_disponible, p.sector, p.estado, p.precio_envio_km, c.nombre as nombre_categoria 
                    FROM producto p 
                    INNER JOIN categoria c ON p.id_categoria = c.id
                    WHERE p.estado <> 'T'";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }

        public function obtener_producto($id){
            $sql ="SELECT p.*, c.nombre as nombre_categoria FROM producto
            p INNER JOIN categoria c ON p.id_categoria = c.id
            WHERE p.id = :id";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':id'=>$id));
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }

        public function modificar_cantidad_disponible($id, $cantidad) {
            $sql = "UPDATE producto SET cantidad_disponible = :cantidad WHERE id = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id, ':cantidad'=>$cantidad));
        }
        
        public function editar_producto($id, $nombre, $id_categoria, $descripcion, $cantidad_disponible, $precio_unitario, $fecha_actualizacion, $sector, $costo_unidad, $foto = null) {
            if ($foto) {
                $sql = "UPDATE producto SET nombre = :nombre, id_categoria = :id_categoria, descripcion = :descripcion, cantidad_disponible = :cantidad_disponible, precio_unitario = :precio_unitario, fecha_actualizacion = :fecha_actualizacion, sector = :sector, costo_unidad = :costo_unidad, foto = :foto WHERE id = :id";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':id' => $id, ':nombre' => $nombre, ':id_categoria' => $id_categoria, ':descripcion' => $descripcion, ':cantidad_disponible' => $cantidad_disponible, ':precio_unitario' => $precio_unitario, ':fecha_actualizacion' => $fecha_actualizacion, ':sector' => $sector, ':costo_unidad' => $costo_unidad, ':foto' => $foto));
            } else {
                $sql = "UPDATE producto SET nombre = :nombre, id_categoria = :id_categoria, descripcion = :descripcion, cantidad_disponible = :cantidad_disponible, precio_unitario = :precio_unitario, fecha_actualizacion = :fecha_actualizacion, sector = :sector, costo_unidad = :costo_unidad WHERE id = :id";
                $query = $this->acceso->prepare($sql);
                $query->execute(array(':id' => $id, ':nombre' => $nombre, ':id_categoria' => $id_categoria, ':descripcion' => $descripcion, ':cantidad_disponible' => $cantidad_disponible, ':precio_unitario' => $precio_unitario, ':fecha_actualizacion' => $fecha_actualizacion, ':sector' => $sector, ':costo_unidad' => $costo_unidad));
            }
        }

        public function eliminar_producto($id) {
            $sql = "DELETE FROM producto WHERE id = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
        }

        public function existe($id) {
            $sql = "SELECT COUNT(*) FROM producto WHERE id = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            return $query->fetchColumn() > 0;
        }

        public function obtener_fotos($id_producto) {
            // Prepara la consulta SQL
            $sql = "SELECT * FROM imagen WHERE id_producto = :id_producto";
            $query = $this->acceso->prepare($sql);
            // Ejecuta la consulta con el id_producto como parámetro
            $query->execute([':id_producto' => $id_producto]);
            // Obtiene todas las filas que coinciden con la consulta
            $fotos = $query->fetchAll(PDO::FETCH_ASSOC);
            // Devuelve las fotos
            return $fotos;
        }

        public function agregar_imagen($id_producto, $nombre) {
            $sql = "INSERT INTO imagen (id_producto, nombre) VALUES (:id_producto, :nombre)";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_producto' => $id_producto, ':nombre' => $nombre));
        }

        public function eliminar_foto($id) {
            $sql = "DELETE FROM imagen WHERE id = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
        }

        public function obtener_ruta($id) {
            $sql = "SELECT nombre FROM imagen WHERE id = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            return $query->fetchColumn();
        }

        public function existe_imagen($id) {
            $sql = "SELECT COUNT(*) FROM imagen WHERE id = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            return $query->fetchColumn() > 0;
        }

        public function activar_producto($id) {
            $sql = "UPDATE producto SET estado = 'A' WHERE id = :id";
            $stmt = $this->acceso->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        public function desactivar_producto($id) {
            $sql = "UPDATE producto SET estado = 'I' WHERE id = :id";
            $stmt = $this->acceso->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }

        public function obtener_foto($id) {
            $sql = "SELECT foto FROM producto WHERE id = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute([':id' => $id]);
            return $query->fetchColumn();
        }

        function actualizarStock($id_producto, $cantidad){
            $sql = "UPDATE producto SET cantidad_disponible = cantidad_disponible - :cantidad WHERE id = :id_producto";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':cantidad' => $cantidad, ':id_producto' => $id_producto));
        }
        
        function verificarStock($id_producto, $cantidad){
            $sql = "SELECT cantidad_disponible FROM producto WHERE id = :id_producto";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_producto' => $id_producto));
            $stock = $query->fetchColumn();
            return $stock >= $cantidad;
        }

        function reestablecerStock($id_producto, $cantidad){
            $sql = "UPDATE producto SET cantidad_disponible = cantidad_disponible + :cantidad WHERE id = :id_producto";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':cantidad' => $cantidad, ':id_producto' => $id_producto));
        }

    }
