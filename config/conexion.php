<?php

//Utilizo require_once  para indicarle a php que si este archivo (global.php) esta incluido, que no me vuelva a incluir otra vez
require_once "global.php";

//Declaramos una variable  que se va llamar $conexion 
//Luego vamos hacer una instancia mysqli, que viene ser el controlador
//mysqli(nombre_del_servidor,nombre_del_usuario_bd,password_dela_bd,nombre_dela_bd)
$conexion = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

//realizamos una consulta a la bd y le indicamos el tipo de caracteres
mysqli_query($conexion,'SET NAMES"'.DB_ENCODE.'"');

//Si tenemos un posible error en la conexion nos va mostrar lo siguiente
if(mysqli_connect_errno()) 
{
echo "Fallo a la conexion a la base de datos: ".mysqli_connect_error();
exit();
}

//Si no existe la funcion ejecutarConsulta
if(!function_exists('ejecutarConsulta'))  
{
    //Creamos la funcion ejecutarConsulta que va recibir un parametro $sql 
   //$sql va ser el codigo o consulta sql que se desea ejecutar
   //Si nosotros deseamos ejecutar codigo SQL vamos a llamar a la funcion ejecutarConsulta
    function ejecutarConsulta($sql) 
    {
        //llamamos a la variable global $conexion 
        global $conexion;
        //luego declaramos una nueva variable $query
        //Esta variable lo único que va ser es de ejecutar el codigo sql
        //Que llega al parametro $sql mediante la funcion ya definida query.
        $query = $conexion->query($sql);   
        //voy a regresar la variable $query es decir la consulta sql
        return $query;
    }

    //La funcion ejecutarConsultaSimpleFila, lo que va ser es recibir un parametro
    //Este parametro $sql es codigo  sql.
    function ejecutarConsultaSimpleFila($sql)
    {

        global $conexion;
        $query = $conexion->query($sql);
        //obtenemos una fila  como resultado en un arreglo y lo almacena en $row
        $row = $query->fetch_assoc();
        //y devolvemos sola una fila 
        return $row;
    }

    function ejecutarConsulta_retornarID($sql)
    {
        global $conexion;
        $query = $conexion->query($sql);
        //nos va devolver haciendo uso de ls sentencia ya definida insert_id
        //el id (o llave primaria) del registro insertado
        //eso nos va servir mucho en otras funciones 
        return $conexion->insert_id;
    }  

    //la funcion limpiarCadena va recibir un parametro $str (que va ser de tipo string)
    //y lo unico que va hacer es  escapar los caracteres especiales de una cadena para usarlo en la sentencia sql
    function limpiarCadena($str)
    {
        global $conexion;
        $str = mysqli_real_escape_string($conexion,trim($str));
        return htmlspecialchars($str);
    }  

}


?>