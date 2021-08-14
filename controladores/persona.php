<?php

//Incluimos una sola vez(require_once) el modelo Categoria.php 
require_once "../modelos/Persona.php";

//Como el modelo categoria.php es una clase debemos instanciar la clase
//mediante un objeto que le vamos a llamar $categoria y mediante este objeto
//llamaremos a todos los metodos que tiene nuestra clase.
$persona = new Persona();

//declaramos las variables que el usuario por ende va tener que registrar y que tambien en 
// en el formulario va estar 

//$idcategoria = si existe este campo idcategoria que recibo por el metodo post 
// si existe el campo(?) validamos con el metodo limpiarcadena 
//si no existe(:) le enviaremos una cadena vacia ""
//De esta manera implementamos una esctructura condicional de solo linea 
//hago lo mismo con nombre y descripcion
$idpersona = isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
$tipo_persona = isset($_POST["tipo_persona"])? limpiarCadena($_POST["tipo_persona"]):"";
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$tipo_documento = isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento = isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";


//vamos hacer peticiones mediante ajax se  utiliza el metodo GET

switch($_GET["op"])
{

    //recordemos que mediante un archivo javascript se va enviar la operacion
    //y se va ejecutar ciertas instrucciones y voy a devolver mediante ajax ciertos valores.

    case 'guardaryeditar':
        
        //para guardar y editar vamos a validar
        //si la variable idpersona esta vacia, entonces yo deseo insertar
        //echo $idpersona;
        if(empty($idpersona))
        {
           //vamos llamar al objeto  que hemos creado arriba y va llamar a un metodo del modelo que es insertar
           //y esto se va almacenar en una variable $rspta 

           $rspta = $persona->insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email); 

           //rspta me va traer 1 o vacio de mi modelo Categoria.php del metodo insertar
           // si se inserto correctamente me va traer 1, si  no se inserto me va traer vacio
           
           //entonces vamos enviar en la variable $rstpa a categoria.js
           // $rspta si se inserto correctamente me muestre un 1, caso contrario me trae vacio 
           
           echo $rspta ? "Persona registrada.": "Persona no se pudo registrar.";
           
           //tenemos que ir a categoria.js 
            

        }else //Si la variable idcategoria viene llena no esta vacia, entonces quiero editar
        {
           $rspta = $persona->editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email);

           //mostrar si rspta es 1? Categoria actualizada y si es 0: Categoria no se puede actualizar
           echo $rspta ? "Persona actualizada." : "Persona no se pudo actualizar.";
           
        }

    break;

    case 'eliminar':

            $rspta =$persona->eliminar($idpersona);
            echo $rspta ? "Persona eliminada" : "Persona no se puede eliminar.";

    break;

    
    case 'mostrar':
            $rspta=$persona->mostrar($idpersona);
            //Codificar el resultado utilizando json
            echo json_encode($rspta);
    break;

    case 'listarp':
            $rspta=$persona->listarp();
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
                    "2"=>$reg->tipo_documento, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "3"=>$reg->num_documento, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "4"=>$reg->telefono, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "5"=>$reg->email, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "6"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
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

    case 'listarc':
            $rspta=$persona->listarc();
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
                    "2"=>$reg->tipo_documento, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "3"=>$reg->num_documento, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "4"=>$reg->telefono, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "5"=>$reg->email, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "6"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
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