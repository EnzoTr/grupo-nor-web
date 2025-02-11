<?php
//llamamos a la conexion de la bd
    include_once 'Conexion.php';
    class Categoria{
        var $objetos;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }

        function obtener_categorias() {
            $sql = "SELECT c.id, c.nombre, c.id_padre, c.fecha_creacion, c.descripcion, c.estado, cp.nombre as nombre_padre FROM categoria as c LEFT JOIN categoria as cp ON c.id_padre=cp.id";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }

        function obtener_categoria_nombre($nombre) {
            $sql = "SELECT id FROM categoria WHERE nombre=:nombre";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':nombre' => $nombre));
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }
    
        function agregarCategoria($nombre, $id_padre, $fecha_creacion, $descripcion) {
            $sql = "INSERT INTO categoria(nombre, id_padre, fecha_creacion, descripcion) VALUES(:nombre, :id_padre, :fecha_creacion, :descripcion)";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':nombre' => $nombre, ':id_padre' => $id_padre, ':fecha_creacion' => $fecha_creacion, ':descripcion' => $descripcion));
        }
    
        function existe_categoria($id) {
            $sql = "SELECT id FROM categoria WHERE id=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $this->objetos = $query->fetchAll();
            return (bool) $this->objetos;
        }
    
        function editar_categoria($id, $nombre, $id_padre, $descripcion) {
            $sql = "UPDATE categoria SET nombre=:nombre, id_padre=:id_padre, descripcion=:descripcion WHERE id=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id, ':nombre' => $nombre, ':id_padre' => $id_padre, ':descripcion' => $descripcion));
        }
    
        function eliminar_categoria($id) {
            $sql = "DELETE FROM categoria WHERE id=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
        }
    
        function activar_categoria($id) {
            $sql = "UPDATE categoria SET estado='A' WHERE id=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
        }
    
        function desactivar_categoria($id) {
            $sql = "UPDATE categoria SET estado='I' WHERE id=:id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
        }

        function obtener_categorias_activas($id_padre = null) {
            $categorias = [];
        
            // Obtener las categorías (principales o subcategorías) para el id_padre dado
            $sql = "SELECT id, nombre FROM categoria WHERE estado='A' AND id_padre " . ($id_padre ? "= :id_padre" : "IS NULL") . " ORDER BY nombre";
            $query = $this->acceso->prepare($sql);
            if ($id_padre) {
                $query->bindParam(':id_padre', $id_padre);
            }
            $query->execute();
            $categoriasResult = $query->fetchAll(PDO::FETCH_OBJ);
        
            foreach ($categoriasResult as $categoria) {
                // Obtener las subcategorías de la categoría
                $subcategorias = $this->obtener_categorias_activas($categoria->id);
        
                // Añadir la categoría y sus subcategorías al array de categorías
                $categorias[] = [
                    'id' => $categoria->id,
                    'nombre' => $categoria->nombre,
                    'subcategorias' => $subcategorias
                ];
            }
        
            return $categorias;
        }
    }