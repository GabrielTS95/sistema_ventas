<?php

//Incluimos inicialmente la conexion a la base de datos
require_once "../config/conexion.php";

//Creamos una clase Categoria
class Persona
{

    //Implementamos nuestro constructor vacio, porque 
    //de esa manera podemos crear instancias o funciones  a la clase sin evniar ningun parametro
    public function __construct()
    {
        
    }

    //Implementamos un método para insertar registros, idcategoria no es necesario insertar un valor
    //ya que en la BD esta como auto incrementado
    public function insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email)
    {
        $sql="INSERT INTO persona(tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email)
              VALUES('$tipo_persona','$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email')";
        return ejecutarConsulta($sql); //Al ejecutar este metedo, si retornamos correctamente codigo sql vamos a devolver 1
                                     // caso contrario un 0, eso lo vamos a validar con ajax al momento de realizar las peticiones, mediante los archivos javascritp
    }

    //Implementamos un método  para editar registros, que va recibir 3 parametros
    //aqui se va requerir el idcategoria, ya que con el idcategoria vamos a saber que campo editar, para no editar todos.
    public function editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email)
    {
        $sql="UPDATE persona SET tipo_persona='$tipo_persona',nombre='$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento', direccion='$direccion',telefono='$telefono', email='$email'
              WHERE idpersona='$idpersona'";
             return ejecutarConsulta($sql);
    }

    //Implementamos un metodo parsa elminar las registros tanto proveedores y clientes
    public function eliminar($idpersona)
    {
       $sql = "DELETE FROM persona WHERE idpersona='$idpersona'";
       //echo $sql;
      return ejecutarConsulta($sql); 
    }

    //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idpersona)
    {
        $sql = "SELECT * FROM persona WHERE idpersona='$idpersona'";
        return ejecutarConsultaSimpleFila($sql);
    }

  //Implementamos un metodo para mostrar todos los 
    public function listarp()
    {
        $sql = "SELECT @rownum:=@rownum+1 AS nro,idpersona,tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email FROM persona, (SELECT @rownum:=0) r WHERE tipo_persona='Proveedor' ORDER BY idpersona DESC";
        return ejecutarConsulta($sql);
    }

  //Implementamos un metodo para mostrar todos los clientes
    public function listarc()
    {
        $sql = "SELECT @rownum:=@rownum+1 AS nro,idpersona,tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email FROM persona, (SELECT @rownum:=0) r WHERE tipo_persona='Cliente' ORDER BY idpersona DESC";
        return ejecutarConsulta($sql);
    }


}

?>