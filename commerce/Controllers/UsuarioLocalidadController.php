<?php
include_once '../Models/UsuarioLocalidad.php';
include_once '../Util/Config/config.php';
//esta variable esta siendo instanciada en Usuario.php y a la vez en Conexion.php
$usuario_localidad = new UsuarioLocalidad();
//sive para saber cuando el usuario entra en su sesion
session_start();


    if($_POST['funcion']=='crear_direccion'){
        $id_usuario = $_SESSION['id'];
        $id_localidades = $_POST['id_localidades'];
        $direccion = $_POST['direccion'];
        $referencia = $_POST['referencia'];
        $usuario_localidad->crear_direccion($id_usuario,$id_localidades, $direccion, $referencia);
        echo 'success';
    }

    
    if($_POST['funcion']=='llenar_direcciones'){
        $id_usuario = $_SESSION['id'];
        $usuario_localidad->llenar_direcciones($id_usuario);
        $json = array();
        foreach($usuario_localidad->objetos as $objeto){
            $json[]=array(
                'id'=>openssl_encrypt($objeto->id,CODE, KEY),
                'direccion'=>$objeto->direccion,
                'referencia'=>$objeto->referencia,
                'localidad'=>$objeto->localidad,
                'provincia'=>$objeto->provincia,
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }

    if($_POST['funcion']=='eliminar_direccion'){
        $id_direccion = openssl_decrypt($_POST['id'], CODE, KEY);
        if(is_numeric($id_direccion)){
            $usuario_localidad->eliminar_direccion($id_direccion);
            echo 'success';
        }else{
            echo 'error';
        }
    }
