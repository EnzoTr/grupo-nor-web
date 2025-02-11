<?php
include_once '../Models/Localidad.php';
//esta variable esta siendo instanciada en Usuario.php y a la vez en Conexion.php
$localidad = new Localidad();
//sive para saber cuando el usuario entra en su sesion
session_start();


    if($_POST['funcion']=='llenar_localidad'){
        $id_provincia = $_POST['id_provincia'];
        $localidad->llenar_localidad($id_provincia);
        $json=array();
        foreach($localidad->objetos as $objeto){
            $json[]=array(
                'id'=>$objeto->id,
                'localidad'=>$objeto->localidad
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }


