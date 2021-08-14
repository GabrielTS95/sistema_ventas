<?php

//Incluimos inicialmente la conexion a la base de datos
require_once "../config/conexion.php";

//Creamos una clase Categoria
class Categoria
{

    //Implementamos nuestro constructor vacio, porque 
    //de esa manera podemos crear instancias o funciones  a la clase sin evniar ningun parametro
    public function __construct()
    {
        
    }

    //Implementamos un método para insertar registros, idcategoria no es necesario insertar un valor
    //ya que en la BD esta como auto incrementado
    public function insertar($nombre,$descripcion)
    {
        $sql="INSERT INTO categoria(nombre,descripcion,condicion)
              VALUES('$nombre','$descripcion','1')";
        return ejecutarConsulta($sql); //Al ejecutar este metedo, si retornamos correctamente codigo sql vamos a devolver 1
                                     // caso contrario un 0, eso lo vamos a validar con ajax al momento de realizar las peticiones, mediante los archivos javascritp
    }

    //Implementamos un método  para editar registros, que va recibir 3 parametros
    //aqui se va requerir el idcategoria, ya que con el idcategoria vamos a saber que campo editar, para no editar todos.
    public function editar($idcategoria,$nombre,$descripcion)
    {
        $sql="UPDATE categoria SET nombre='$nombre',descripcion='$descripcion' 
              WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo parsa desactivar las categorias
    public function desactivar($idcategoria)
    {
       $sql = "UPDATE categoria SET condicion='0' WHERE idcategoria='$idcategoria'";
       return ejecutarConsulta($sql); 
    }

 //Implementamos un metodo parsa activar las categorias
    public function activar($idcategoria)
    {
       $sql = "UPDATE categoria SET condicion='1' WHERE idcategoria='$idcategoria'";
       return ejecutarConsulta($sql); 
    }

    //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idcategoria)
    {
        $sql = "SELECT * FROM categoria WHERE idcategoria='$idcategoria'";
        return ejecutarConsultaSimpleFila($sql);
    }

  //Implementamos un metodo para mostrar todos los registros de la tabla categoria
    public function listar()
    {
        $sql = "SELECT @rownum:=@rownum+1 AS nro,idcategoria, nombre, descripcion,condicion FROM categoria, (SELECT @rownum:=0) r ORDER BY idcategoria DESC";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para mostrar todos los registros en el select del formulario articulos.php
    public function select()
    {   //Selecionar las categorias que esten activas es decir que tenga la concion 1
        $sql = "SELECT *FROM categoria WHERE condicion=1";
        return ejecutarConsulta($sql);
    }

}

?>