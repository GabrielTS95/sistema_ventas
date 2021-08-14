<?php

//Incluimos inicialmente la conexion a la base de datos
require_once "../config/conexion.php";

//Creamos una clase Categoria
class Permiso
{

    //Implementamos nuestro constructor vacio, porque 
    //de esa manera podemos crear instancias o funciones  a la clase sin evniar ningun parametro
    public function __construct()
    {
        
    }

   

  //Implementamos un metodo para mostrar todos los registros de la tabla categoria
    public function listar()
    {
        $sql = "SELECT @rownum:=@rownum+1 AS nro, idpermiso, nombre FROM permiso, (SELECT @rownum:=0) r";
        return ejecutarConsulta($sql);
    }


}

?>