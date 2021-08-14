<?php

//Incluimos una sola vez(require_once) el modelo Categoria.php 
require_once "../modelos/Categoria.php";

//Como el modelo categoria.php es una clase debemos instanciar la clase
//mediante un objeto que le vamos a llamar $categoria y mediante este objeto
//llamaremos a todos los metodos que tiene nuestra clase.
$categoria = new Categoria();

//declaramos las variables que el usuario por ende va tener que registrar y que tambien en 
// en el formulario va estar 

//$idcategoria = si existe este campo idcategoria que recibo por el metodo post 
// si existe el campo(?) validamos con el metodo limpiarcadena 
//si no existe(:) le enviaremos una cadena vacia ""
//De esta manera implementamos una esctructura condicional de solo linea 
//hago lo mismo con nombre y descripcion

$idcategoria = isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";


//vamos hacer peticiones mediante ajax se  utiliza el metodo GET

switch($_GET["op"])
{

    //recordemos que mediante un archivo javascript se va enviar la operacion
    //y se va ejecutar ciertas instrucciones y voy a devolver mediante ajax ciertos valores.

    case 'guardaryeditar':
        $tipo;
        //para guardar y editar vamos a validar
        //si la variable icategoria esta vacia, entonces yo deseo insertar
        if(empty($idcategoria))
        {
           //vamos llamar al objeto  que hemos creado arriba y va llamar a un metodo del modelo que es insertar
           //y esto se va almacenar en una variable $rspta 

           $rspta = $categoria->insertar($nombre, $descripcion); 

           //rspta me va traer 1 o vacio de mi modelo Categoria.php del metodo insertar
           // si se inserto correctamente me va traer 1, si  no se inserto me va traer vacio
           
           //entonces vamos enviar en la variable $rstpa a categoria.js
           // $rspta si se inserto correctamente me muestre un 1, caso contrario me trae vacio 
           
           echo $rspta ? "Categoria registrada.": "Categoria no se pudo registrar.";
           
           //tenemos que ir a categoria.js 
            

        }else //Si la variable idcategoria viene llena no esta vacia, entonces quiero editar
        {
           $rspta = $categoria->editar($idcategoria,$nombre, $descripcion);

           //mostrar si rspta es 1? Categoria actualizada y si es 0: Categoria no se puede actualizar
           echo $rspta ? "Categoria actualizada." : "Categoria no se pudo actualizar.";
           
        }

    break;

    case 'desactivar':

            $rspta =$categoria->desactivar($idcategoria);
            echo $rspta ? "Categoria desactivada" : "Categoria no se puede desactivar";

    break;

    case 'activar':
            $rspta =$categoria->activar($idcategoria);
            echo $rspta ? "Categoria activada" : "Categoria no se puede activar";

    break;

    
    case 'mostrar':
            $rspta=$categoria->mostrar($idcategoria);
            //Codificar el resultado utilizando json
            echo json_encode($rspta);
    break;

    case 'listar':
            $rspta=$categoria->listar();
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
                    "0"=>$reg->nro,
                    "1"=>$reg->nombre, //en el registro 0, voy a guardar el campo nombre de mi tabla categoria
                    "2"=>$reg->descripcion, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "3"=>($reg->condicion) ?'<span class="label bg-green">Activado</span>': '<span class="label bg-red">Desactivado</span>',
                    "4"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idcategoria.')"><i class="fa fa-close"></i></button>'
                    :'<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-success" onclick="activar('.$reg->idcategoria.')"><i class="fa fa-check"></i></button>'

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