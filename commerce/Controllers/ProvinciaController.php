<?php
include_once '../Models/Provincia.php';
//esta variable esta siendo instanciada en Usuario.php y a la vez en Conexion.php
$provincia = new Provincia();
//sive para saber cuando el usuario entra en su sesion
session_start();


    if($_POST['funcion']=='llenar_provincia'){
        $provincia->llenar_provincia();
        foreach($provincia->objetos as $objeto){
            $json[]=array(
                'id'=>$objeto->id,
                'provincia'=>$objeto->provincia
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }


