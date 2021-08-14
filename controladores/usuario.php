<?php

//iniciamos session 
session_start();

//Incluimos una sola vez(require_once) el modelo Categoria.php 
require_once "../modelos/Usuario.php";

//Como el modelo categoria.php es una clase debemos instanciar la clase
//mediante un objeto que le vamos a llamar $articulo y mediante este objeto
//llamaremos a todos los metodos que tiene nuestra clase.
$usuario = new Usuario();

$idusuario = isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$tipo_documento = isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento = isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion = isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono = isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$cargo = isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
$login = isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$clave = isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
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
                move_uploaded_file($_FILES['imagen']['tmp_name'], "../files/usuarios/" .$imagen);
            }
        }

        //Encriptando la clave a HASH SHA256 
        $claveHash = hash("SHA256",$clave);

        //Rempplazmos el atributo $clave por claveHash 
        if(empty($idusuario))
        {
           $rspta = $usuario->insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$claveHash,$imagen,$_POST['permiso']); 
           echo $rspta ? "Usuario registrado.": "Usuario no se pudo registrar.";
           
           //tenemos que ir a categoria.js 
            

        }else //Si la variable idusuario viene llena, es decir no esta vacia, entonces quiero editar
        {
           $rspta = $usuario->editar($idusuario,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$claveHash,$imagen,$_POST['permiso']);

           //mostrar si rspta es 1? Categoria actualizada y si es 0: Categoria no se puede actualizar
           echo $rspta ? "Usuario actualizado." : "Usuario no se pudo actualizar.";
           
        }

    break;

    case 'desactivar':

            $rspta =$usuario->desactivar($idusuario);
            echo $rspta ? "usuario desactivado" : "usuario no se puede desactivar";

    break;

    case 'activar':
            $rspta =$usuario->activar($idusuario);
            echo $rspta ? "Usuario activado" : "Usuario no se puede activar";

    break;

    
    case 'mostrar':
            $rspta=$usuario->mostrar($idusuario);
            //Codificar el resultado utilizando json
            echo json_encode($rspta);
    break;

    case 'listar':
            $rspta=$usuario->listar();
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
                    "3"=>$reg->num_documento,
                    "4"=>$reg->telefono,
                    "5"=>$reg->email,
                    "6"=>$reg->login,
                    //vamos a escirbir codigo html en comillas
                    "7"=>"<img src='../files/usuarios/".$reg->imagen."' height='50px;' width='50px;'>",
                    "8"=>($reg->condicion) ?'<span class="label bg-green">Activado</span>': '<span class="label bg-red">Desactivado</span>',
                    "9"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-danger" onclick="desactivar('.$reg->idusuario.')"><i class="fa fa-close"></i></button>'
                    :'<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.
                    ' <button class="btn btn-success" onclick="activar('.$reg->idusuario.')"><i class="fa fa-check"></i></button>'

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

     case 'permisos':
            require_once "../modelos/Permiso.php";
            $permiso = new Permiso();
            $rspta=$permiso->listar();

            //Obtener los permisos asiganados al usuario
            $id=$_GET['id']; //id del usuario que quiero obtener sus permisos asignados
            $marcados = $usuario->listarmarcados($id);

            //Declaramos un array para almacenar los permisos marcos
            $valores = array();

            //Almacenar los permisos asiganados al usuario en el array

            while($per = $marcados->fetch_object())
            {
                array_push($valores,$per->idpermiso);
            }

            

            //Mostramos los permisos asiganados al usuario
            while($reg=$rspta->fetch_object())
            {
                $sw = in_array($reg->idpermiso,$valores) ?'checked' :'';
                echo '<li> <input type="checkbox" '.$sw.' name="permiso[]"  value="'.$reg->idpermiso.'"> '.$reg->nombre.'</li>';

            }
    break;

    case 'verificar':

        $logina=$_POST['logina'];
        $clavea=$_POST['clavea']; 

        //Has SHA256 en la contraseña
        $clavehash=hash("SHA256",$clavea);

        $rspta =$usuario->verificar($logina,$clavehash);

        $fetch = $rspta->fetch_object();
        
        if(isset($fetch)) //si la variable fetch viene llena o existe es porque me esta trayendo los datos
        {
            //Declaramos la variable de sesion
            $_SESSION['idusuario'] = $fetch->idusuario;
            $_SESSION['nombre'] = $fetch->nombre;
            $_SESSION['imagen'] = $fetch->imagen;
            $_SESSION['login'] = $fetch->login;

            //obtenemos los permisos del usuario enviando como parametro el idusuario
            $marcados = $usuario->listarmarcados($fetch->idusuario); 

            //declaramos un array para almacenar todos los permisos 
            $valores=array();

            //almacenamos los permisos marcados en el array valores 
            //mediante nuestra ciclo interactivo while 
            while($per = $marcados->fetch_object())
            {
                array_push($valores,$per->idpermiso);
            }

            //Determinamos los accesos del usuario 
            
            //Con la funcion in_array voy haber si 1(id del permiso) escritorio esta dentro del arreglo valores
            //entonces me va crear una nuvea session llamada escritorio  y va ser igual 1 caso contrario 0 
            in_array(1,$valores)?$_SESSION['escritorio']=1:$_SESSION['escritorio']=0;
            in_array(2,$valores)?$_SESSION['almacen']=1:$_SESSION['almacen']=0;
            in_array(3,$valores)?$_SESSION['compras']=1:$_SESSION['compras']=0;
            in_array(4,$valores)?$_SESSION['ventas']=1:$_SESSION['ventas']=0;
            in_array(5,$valores)?$_SESSION['acceso']=1:$_SESSION['acceso']=0;
            in_array(6,$valores)?$_SESSION['consultac']=1:$_SESSION['consultac']=0;
            in_array(7,$valores)?$_SESSION['consultav']=1:$_SESSION['consultav']=0;
        }

        echo json_encode($fetch);
    break;

    case 'salir':
        //Limpiamos las variables de session para limpiar todas las avriables de session
        session_unset();
        //para destruir la session y toda sus variables 
        session_destroy();
        //redireccionamos al login
        header("Location: ../index.php");
    
    break;


}
?>