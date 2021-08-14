<?php

//Incluimos una sola vez(require_once) el modelo Categoria.php 
require_once "../modelos/Ingreso.php";

//Iniciamos la sesion pero primedo validamos

//si la cantidad de sessiones es menor que 1, no iniciamos ninguna session
if(strlen(session_id()<1))
session_start();


$ingreso = new Ingreso(); //Instanciando el Objeto 


$idingreso = isset($_POST["idingreso"])? limpiarCadena($_POST["idingreso"]):"";
$idproveedor = isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";

//Almacenar la variable de session, del usuario especifico que ingreso al sistema
$idusuario = $_SESSION["idusuario"];

$serie_comprobante = isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante = isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto = isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_compra = isset($_POST["total_compra"])? limpiarCadena($_POST["total_compra"]):"";
$tipo_comprobante = isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";

switch($_GET["op"])
{

    case 'guardaryeditar':
        $tipo;
        //para guardar y editar vamos a validar
        //si la variable icategoria esta vacia, entonces yo deseo insertar
        if(empty($idingreso))
        {
           //vamos llamar al objeto  que hemos creado arriba y va llamar a un metodo del modelo que es insertar
           //y esto se va almacenar en una variable $rspta 

           $rspta = $ingreso->insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_compra,
                                        $_POST['idarticulo'],$_POST['cantidad'],$_POST['precio_compra'],$_POST['precio_venta']); 

           //rspta me va traer 1 o vacio de mi modelo Categoria.php del metodo insertar
           // si se inserto correctamente me va traer 1, si  no se inserto me va traer vacio
           
           //entonces vamos enviar en la variable $rstpa a categoria.js
           // $rspta si se inserto correctamente me muestre un 1, caso contrario me trae vacio 
           
           echo $rspta ? "Ingreso registrado.": "Ingreso no se pudo registrar.";
           
           //tenemos que ir a categoria.js 
            

        }else //Si la variable idcategoria viene llena no esta vacia, entonces quiero editar
        {
           
        }

    break;

    case 'anular':

            $rspta =$ingreso->anular($idingreso);
            echo $rspta ? "Ingreso anulado" : "Ingreso no se pudo anular";

    break;

    
    case 'mostrar':
            $rspta=$ingreso->mostrar($idingreso);
            //Codificar el resultado utilizando json
            echo json_encode($rspta);
    break;

    
    case 'listarDetalle':
            //recibimos el ingreso
            $id=$_GET['id'];
            $total =0;
            echo '    <thead style="background-color:#A9D0F5;">
                                    <th></th>
                                    <th>Articulo</th>
                                    <th>Cantidad</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Venta</th>
                                    <th>Subtotal</th>
                                </thead>';
            $rspta=$ingreso->listarDetalle($id);
            while($reg = $rspta->fetch_object())
            {
                echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->precio_compra.'</td><td>'.$reg->precio_venta.'</td><td>'.$reg->precio_compra*$reg->precio_venta.'</td></tr>';
                $total = $total + ($reg->precio_compra*$reg->precio_venta);
            }
            
            echo '              <tfoot>
                                    <th>Total</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total">S/.'.$total.'</h4>
                                        <input type="hidden" name="total_compra" id="total_compra">
                                    </th>
                                </tfoot>';
    break;

    case 'listar':
            $rspta=$ingreso->listar();
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
                    "1"=>$reg->fecha, //en el registro 0, voy a guardar el campo nombre de mi tabla categoria
                    "2"=>$reg->proveedor, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "3"=>$reg->usuario, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "4"=>$reg->tipo_comprobante, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "5"=>$reg->serie_comprobante.'-'.$reg->num_comprobante, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "6"=>$reg->total_compra, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "7"=>($reg->estado=='Aceptado') ?'<span class="label bg-green">Aceptado</span>': '<span class="label bg-red">Anulado</span>',
                    "8"=>($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>'.
                    ' <button class="btn btn-danger" onclick="anular('.$reg->idingreso.')"><i class="fa fa-close"></i></button>'
                    :'<button class="btn btn-warning" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>',

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



    case 'selectProveedor' :
        
            require_once('../modelos/Persona.php');

            $persona = new Persona();

            $rspta= $persona->listarp();

            while($reg=$rspta->fetch_object())
            {
                echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
            }

    break;


    case 'listarArticulos' :
            
        require_once('../modelos/Articulo.php');
         $articulo = new Articulo();
         $rspta=$articulo->listarActivos();
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
                    "0"=>"",
                    "1"=>$reg->nro, //en el registro 0, voy a guardar el campo nombre de mi tabla categoria
                    "2"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\')"><span class="fa fa-plus"></span> </button>',
                    "3"=>$reg->nombre, //en el registro 0, voy a guardar el campo nombre de mi tabla categoria
                    "4"=>$reg->categoria, //en el registro 1, voy a guardar el campo descripcion de mi tabla categoria
                    "5"=>$reg->stock,
                    "6"=>$reg->codigo,
                    //vamos a escirbir codigo html en comillas
                    "7"=>"<img src='../files/articulos/".$reg->imagen."' height='50px;' width='50px;'>",

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