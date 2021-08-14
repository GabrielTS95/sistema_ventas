<?php

//Incluimos una sola vez(require_once) el modelo Categoria.php 
require_once "../modelos/Articulo.php";

//Como el modelo categoria.php es una clase debemos instanciar la clase
//mediante un objeto que le vamos a llamar $articulo y mediante este objeto
//llamaremos a todos los metodos que tiene nuestra clase.
$articulo = new Articulo();

//declaramos las variables que el usuario por ende va tener que registrar y que tambien en 
// en el formulario va estar 

//$idcategoria = si existe este campo idcategoria que recibo por el metodo post 
// si existe el campo(?) validamos con el metodo limpiarcadena 
//si no existe(:) le enviaremos una cadena vacia ""
//De esta manera implementamos una esctructura condicional de solo linea 
//hago lo mismo con nombre y descripcion

$idarticulo = isset($_POST["idarticulo"])? limpiarCadena($_POST["idarticulo"]):"";
$idcategoria = isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$codigo = isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$stock = isset($_POST["stock"])? limpiarCadena($_POST["stock"]):"";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$imagen = isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";

switch($_GET["op"])
{

    case 'guardaryeditar':
        //Si el usuario no a seleccionado ningun archivo en el objeto fornulario imgen,
        //Si no existe ningun archivo seleccionado  o si no ha sido cargado ningun archivo en el campo imagen del fornulario
        if(!file_exists($_FILES['imagen']['tmp_name']) ||  !is_uploaded_file($_FILES['imagen']['tmp_name'])) 
        {
            //la variable imagen va ser igual a vacia. Al momento de editar vamo a tener un inconveniente pero lo vamos arreglar 
            $imagen =$_POST["imagenactual"];
            //caso contrario por seguridad para que los usuarios no suban codigo malicioso
        }else
        {
            //vamos obtener la extension de nuestro archivo
            $ext = explode(".", $_FILES['imagen']['name']);
            //validar el tipo de extension que vamos a permitir
            //si mi objeto imagen es igual a tipo jpg
            if($_FILES['imagen']['type'] =='image/jpg' || $_FILES['imagen']['type'] =='image/jpeg' || $_FILES['imagen']['type'] =='image/png')
            {
                //se va cargar en unca carpeta dentro de nuestro sistema
                //en nombre d ela variable se va renombrar urtilizando la funcion microtime(con un formato de tiempo)
                $imagen=round(microtime(true)).'.'.end($ext); 

                //subimos el archivo
                move_uploaded_file($_FILES['imagen']['tmp_name'], "../files/articulos/" .$imagen);
            }
        }
        if(empty($idarticulo))
        {
           $rspta = $articulo->insertar($idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen); 
           echo $rspta ? "Articulo registrado.": "Articulo no se pudo registrar.";
           
           //tenemos que ir a categoria.js 
            

        }else //Si la variable idcategoria viene llena no esta vacia, entonces quiero editar
        {
           $rspta = $articulo->editar($idarticulo,$idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen);

           //mostrar si rspta es 1? Categoria actualizada y si es 0: Categoria no se puede actualizar
           echo $rspta ? "Aritculo actualizado." : "Aritculo no se pudo actualizar.";
           
        }

    break;

    case 'desactivar':

            $rspta =$articulo->desactivar($idarticulo);
            echo $rspta ? "Articulo desactivado" : "Articulo no se puede desactivar";

    break;

    case 'activar':
            $rspta =$articulo->activar($idarticulo);
            echo $rspta ? "Articulo activado" : "Articulo no se puede activar";

    break;

    
    case 'mostrar':
            $rspta=$articulo->mostrar($idarticulo);
            //Codificar el resultado utilizando json
            echo json_encode($rspta);
    break;

    case 'listar':
            $rspta=$articulo->listar();
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
                    "2"=>$reg->categoria, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "3"=>$reg->codigo,
                    "4"=>$reg->stock,
                    "5"=>$reg->descripcion,
                    //vamos a escirbir codigo html en comillas
                    "6"=>"<img src='../files/articulos/".$reg->imagen."' height='50px;' width='50px;'>",
                    "7"=>($reg->condicion) ?'<span class="label bg-green">Activado</span>': '<span class="label bg-red">Desactivado</span>',
                    "8"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>'
                    :'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-success" onclick="activar('.$reg->idarticulo.')"><i class="fa fa-check"></i></button>'

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

    case 'selectCategoria':
            require_once "../modelos/Categoria.php";
            $categoria = new Categoria;
            $rspta=$categoria->select();
            //implmentamos un bucle while, con el objeto reg va rrecorrer de manera independien cada registro almacenado en rspta
            while($reg=$rspta->fetch_object())
            {
                echo '<option value='.$reg->idcategoria.'>'.$reg->nombre.'</option>';
            }

    break;


}
?>