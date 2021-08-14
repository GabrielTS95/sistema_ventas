<?php

//Incluimos inicialmente la conexion a la base de datos
require_once "../config/conexion.php";

//Creamos una clase Categoria
class Articulo
{
    public function __construct()
    {}


    //funcion para insertar registros
    //el mismo orden que tenemos en el PhpMyAdmin
    //el orden que estamos recibiendo los campos. 
    public function insertar($idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen)
    {
        $sql="INSERT INTO articulo (idcategoria,codigo,nombre,stock,descripcion,imagen,condicion)
              VALUES('$idcategoria','$codigo','$nombre','$stock','$descripcion','$imagen','1')";
        return ejecutarConsulta($sql); 
    }

    //funcion para editar registros
    public function editar($idarticulo,$idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen)
    {
        $sql="UPDATE articulo SET idcategoria='$idcategoria', codigo='$codigo', nombre='$nombre',stock='$stock',descripcion='$descripcion',imagen='$imagen' 
              WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo parsa desactivar el registro
    public function desactivar($idarticulo)
    {
       $sql = "UPDATE articulo SET condicion='0' WHERE idarticulo='$idarticulo'";
       return ejecutarConsulta($sql); 
    }

 //Implementamos un metodo parsa activar las categorias
    public function activar($idarticulo)
    {
       $sql = "UPDATE articulo SET condicion='1' WHERE idarticulo='$idarticulo'";
       return ejecutarConsulta($sql); 
    }

    //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idarticulo)
    {
        $sql = "SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
        return ejecutarConsultaSimpleFila($sql);
    }

  //Implementamos un metodo para mostrar todos los registros de la tabla categoria
    public function listar()
    {
        $sql = "SELECT @rownum:=@rownum+1 AS nro, A.idarticulo,C.nombre AS categoria, A.codigo,A.nombre,A.stock, A.descripcion, A.imagen, A.condicion 
                FROM articulo AS A INNER JOIN categoria AS C ON A.idcategoria = C.idcategoria, (SELECT @rownum := 0) r ORDER BY idarticulo ASC";
        return ejecutarConsulta($sql);
    }


    //Implementamos un metodo para listar todos los articulos que se encuentran activos
    public function listarActivos()
    {
        $sql = "SELECT @rownum:=@rownum+1 AS nro, A.idarticulo,C.nombre AS categoria, A.codigo,A.nombre,A.stock, A.descripcion, A.imagen, A.condicion 
                FROM articulo AS A INNER JOIN categoria AS C ON A.idcategoria = C.idcategoria, (SELECT @rownum := 0) r 
                WHERE A.condicion='1' ORDER BY idarticulo ASC";
        return ejecutarConsulta($sql);
    }
}

?>