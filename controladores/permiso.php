<?php

//Incluimos una sola vez(require_once) el modelo Categoria.php 
require_once "../modelos/Permiso.php";

$permiso = new Permiso();


switch($_GET["op"])
{

    
    case 'listar':
            $rspta=$permiso->listar();
            //Vamos a declarar un array  que se va llamar $data
            //para almacenar todos los registros que voy a listar
            $data = Array();

            //mediante una estructura interactiva while voy ha recorrer
            //todos los registros que voy a obtener de mi tabla categorias
            
            while($reg=$rspta->fetch_object())
            //con el $reg voy a ir recorriendo una a uno todos los metodos
            // y lo voy a ir trabajando de manera independiente.
            {
                //voy a especificar que todos los datos obteneidos lo voy almacenar
                //en el array $data[], dentro de los parentesis voy a indicarle uno a uno
                //los valores, mediante indices
                $data[]=array
                (
                    //en el indice "0" => quiero que me muestre de mi tabla categorias el campo 
                    
                    "0"=>$reg->nro, //en el registro 0, voy a guardar el campo nombre de mi tabla categoria
                    "1"=>$reg->nombre, //en el registro 0, voy a guardar el campo nombre de mi tabla categoria

                );

                //voy a recorrer entonces todos los registros lo voy a ir almacenando una a uno en $reg y voy a ir almacenando segun los indices
                //"0,1,2,3" los campos(igual el nombre) que estan en mi tabla categoria de mi base de datos 
            }
            //fuera del bluque while voy a declarar un nuevo array que se va llamar $results
            //y le voy a indicar las siguientes instrucciones. 
             $results = array
             (
                "sEcho"=>1, //Informacion para el datables
                "iTotalRecords" =>count($data), //enviamso el total de registros al datable
                "iTotalDisplayRecords" =>count($data), //enviamos el total de registros a visuaizar
                "aaData"=>$data //el array que alamcena todos los registros
            );
            echo json_encode($results); //este array que estamos mostrando va ser utilizado por datable

    break;
}
?>