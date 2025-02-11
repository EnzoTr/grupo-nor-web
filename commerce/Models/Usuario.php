<?php
//llamamos a la conexion de la bd
    include_once 'Conexion.php';
    class Usuario{
        var $objetos;
        public function __construct(){
            $db = new Conexion();
            $this->acceso = $db->pdo;
        }

        //consultas a la bd
        function loguearse($user, $pass){
            $sql ="SELECT * FROM usuario 
                    WHERE user=:user AND pass=:pass";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':user'=>$user, ':pass'=>$pass));
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }

        //funcion para verificar si el username ya existe
        function verificar_usuario($user){
            $sql ="SELECT * FROM usuario 
                    WHERE user=:user";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':user'=>$user));
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }

        function registrar_usuario($username, $pass, $nombres, $apellidos, $dni, $email, $telefono){
            $sql ="INSERT INTO usuario(user,pass,nombres,apellidos,dni,email,telefono,id_tipo) 
                    VALUES(:user,:pass,:nombres,:apellidos,:dni,:email,:telefono,:id_tipo)";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':user'=>$username,':pass'=>$pass, ':nombres'=>$nombres, ':apellidos'=>$apellidos,
            ':dni'=>$dni, ':email'=>$email, ':telefono'=>$telefono, ':id_tipo'=>2,));
        }

        function registrar_empleado($username, $pass, $nombre, $apellido, $dni, $email, $tipo_empleado){
            $sql ="INSERT INTO usuario(user, pass, nombres, apellidos, dni, email, id_tipo) 
                    VALUES(:user, :pass, :nombres, :apellidos, :dni, :email, :id_tipo)";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':user'=>$username, ':pass'=>$pass, ':nombres'=>$nombre, ':apellidos'=>$apellido,
            ':dni'=>$dni, ':email'=>$email, ':id_tipo'=>$tipo_empleado));
        }

        function modificar_usuario($id_usuario, $username, $nombres, $apellidos, $dni, $email, $direccion, $referencia, $telefono, $tipo_empleado){
            $sql ="UPDATE usuario SET user = :user, nombres = :nombres, apellidos = :apellidos, dni = :dni, email = :email, direccion = :direccion, referencia = :referencia, telefono = :telefono, id_tipo = :id_tipo WHERE id = :id_usuario";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':id_usuario'=>$id_usuario, ':user'=>$username, ':nombres'=>$nombres, ':apellidos'=>$apellidos, ':dni'=>$dni, ':email'=>$email, ':direccion'=>$direccion, ':referencia'=>$referencia, ':telefono'=>$telefono, ':id_tipo'=>$tipo_empleado));
        }

        function obtener_datos($user){
            $sql ="SELECT * FROM usuario 
                    JOIN tipo_usuario ON usuario.id_tipo = tipo_usuario.id
                    WHERE usuario.id=:user";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':user'=>$user));
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }

        function obtener_usuarios() {
            $sql = "SELECT u.id, u.dni, u.user, u.nombres, u.apellidos, u.direccion, u.referencia, u.email, u.telefono, u.estado, u.id_tipo, tu.tipo 
                FROM usuario u 
                JOIN tipo_usuario tu ON u.id_tipo = tu.id";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }

        function editar_datos($id_usuario,$nombres, $apellidos, $dni, $email, $telefono, $nombre){
            if($nombre != ''){
                $sql ="UPDATE usuario SET nombres=:nombres, apellidos=:apellidos, dni=:dni, email=:email, telefono=:telefono, avatar=:avatar
                WHERE  id=:id_usuario";
            $query = $this->acceso->prepare($sql); 
            $variables = array(
            ':id_usuario'=>$id_usuario,
            ':nombres'=>$nombres,
            ':apellidos'=>$apellidos,
            ':dni'=>$dni,
            ':email'=>$email,
            ':telefono'=>$telefono,
            ':avatar'=>$nombre
            );
            $query->execute($variables);
            }
            else{   
                $sql ="UPDATE usuario SET nombres=:nombres, apellidos=:apellidos, dni=:dni, email=:email, telefono=:telefono
                    WHERE  id=:id_usuario";
                $query = $this->acceso->prepare($sql); 
                $variables = array(
                ':id_usuario'=>$id_usuario,
                ':nombres'=>$nombres,
                ':apellidos'=>$apellidos,
                ':dni'=>$dni,
                ':email'=>$email,
                ':telefono'=>$telefono
            );
                $query->execute($variables);
            }
        }

        function modificar_estado_usuario($id, $estado){
            $sql ="UPDATE usuario SET estado = :estado WHERE id = :id";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':estado'=>$estado, ':id'=>$id));
        }

        function eliminar_usuario($id){
            $sql ="DELETE FROM usuario WHERE id = :id";
            $query = $this->acceso->prepare($sql); 
            $query->execute(array(':id'=>$id));
        }

        function obtener_payer($id) {
            $sql = "SELECT nombres, apellidos, email FROM usuario WHERE id = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $this->objetos = $query->fetchAll();
            return $this->objetos;
        }

        public function obtener_rol($id) {
            $sql = "SELECT tp.tipo FROM usuario u
                    JOIN tipo_usuario tp ON u.id_tipo = tp.id 
                    WHERE u.id = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));
            $this->objetos = $query->fetchAll(PDO::FETCH_OBJ);
            return $this->objetos;
        }

    }
