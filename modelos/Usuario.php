<?php

//Incluimos inicialmente la conexion a la base de datos
require_once "../config/conexion.php";

//Creamos una clase Categoria
class Usuario
{

    //Implementamos nuestro constructor vacio, porque 
    //de esa manera podemos crear instancias o funciones  a la clase sin evniar ningun parametro
    public function __construct()
    {
        
    }

    //Implementamos un método para insertar registros, idcategoria no es necesario insertar un valor
    //ya que en la BD esta como auto incrementado
    public function insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos)
    {
        $sql="INSERT INTO usuario(nombre,tipo_documento,num_documento,direccion,telefono,email,cargo,login,clave,imagen,condicion)
              VALUES('$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email','$cargo','$login','$clave','$imagen','1')";
        
        $idUsuarionew = ejecutarConsulta_retornarID($sql);

        $num_elementos = 0;

        $sw = true;

        while($num_elementos<count($permisos))
        {
          $sql_detalle = "INSERT INTO usuario_permiso(idusuario,idpermiso)
                          VALUES ('$idUsuarionew','$permisos[$num_elementos]')";
                          ejecutarConsulta($sql_detalle) or $sw = false;
          $num_elementos=$num_elementos+1;
        }
        return $sw;


        //return ejecutarConsulta($sql); //Al ejecutar este metedo, si retornamos correctamente codigo sql vamos a devolver 1
                                     // caso contrario un 0, eso lo vamos a validar con ajax al momento de realizar las peticiones, mediante los archivos javascritp
    }

    //Implementamos un método  para editar registros, que va recibir 3 parametros
    //aqui se va requerir el idcategoria, ya que con el idcategoria vamos a saber que campo editar, para no editar todos.
    public function editar($idusuario,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos)
    {
        $sql="UPDATE usuario SET nombre='$nombre',tipo_documento='$tipo_documento', num_documento='$num_documento',direccion='$direccion',telefono='$telefono',email='$email',cargo='$cargo',login='$login',clave='$clave',imagen='$imagen'
              WHERE idusuario='$idusuario'";
         ejecutarConsulta($sql);

         //eliminamos todos los permisos asignados para volverlos a registrar
         $sqldel ="DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";

         ejecutarConsulta($sqldel);

            $num_elementos = 0;

            $sw = true;

            while($num_elementos<count($permisos))
            {
            $sql_detalle = "INSERT INTO usuario_permiso(idusuario,idpermiso)
                            VALUES ('$idusuario','$permisos[$num_elementos]')";
                            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos=$num_elementos+1;
            }
            return $sw;


    }

    //Implementamos un metodo parsa desactivar las categorias
    public function desactivar($idusuario)
    {
       $sql = "UPDATE usuario SET condicion='0' WHERE idusuario='$idusuario'";
       return ejecutarConsulta($sql); 
    }

 //Implementamos un metodo parsa activar las categorias
    public function activar($idusuario) //vamos a recibir como parametro el idusuario
    {
       $sql = "UPDATE usuario SET condicion='1' WHERE idusuario='$idusuario'";
       return ejecutarConsulta($sql); 
    }

    //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idusuario)
    {
        $sql = "SELECT * FROM usuario WHERE idusuario='$idusuario'";
        return ejecutarConsultaSimpleFila($sql);
    }

  //Implementamos un metodo para mostrar todos los registros de la tabla categoria
    public function listar()
    {
        $sql = "SELECT @rownum:=@rownum+1 AS nro,idusuario,nombre,tipo_documento,num_documento,direccion,telefono,email,cargo,login,clave,imagen,condicion FROM usuario, (SELECT @rownum:=0) r ORDER BY idusuario DESC";
        return ejecutarConsulta($sql);
    }

    //funcion para listar permisos marcados
    public function listarmarcados($idusuario)
    {
        $sql="SELECT *FROM usuario_permiso WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    //función para verificar el acceso al sistema

    public function verificar($login,$clave)
    {
        $sql = "SELECT idusuario,nombre,tipo_documento,num_documento,telefono,email,cargo,imagen,login FROM usuario WHERE login='$login' AND
        clave ='$clave' AND condicion='1'";
        return ejecutarConsulta($sql);
    }


}

?>