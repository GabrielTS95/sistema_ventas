<?php

//Incluimos inicialmente la conexion a la base de datos
require_once "../config/conexion.php";

//Creamos una clase Ingreso
class Ingreso
{

    //Implementamos nuestro constructor vacio, porque 
    //de esa manera podemos crear instancias o funciones  a la clase sin evniar ningun parametro
    public function __construct()
    {
        
    }

    //Implementamos un método para insertar registros, idcategoria no es necesario insertar un valor
    //ya que en la BD esta como auto incrementado
    public function insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,
    $impuesto,$total_compra,$idarticulo,$cantidad,$precio_compra,$precio_venta)
    {
        $sql="INSERT INTO ingreso(idproveedor,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_compra,estado)
              VALUES('$idproveedor','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$impuesto','$total_compra','Aceptado')";
        
        $idingresonew = ejecutarConsulta_retornarID($sql); //me devuelve la llama primaria generada
        $num_elementos = 0;
        $sw = true;

        while($num_elementos<count($idarticulo))//contando la cantidad de articulos
        {
          $sql_detalle = "INSERT INTO detalle_ingreso(idingreso,idarticulo,cantidad,precio_compra,precio_venta)
                          VALUES ('$idingresonew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]','$precio_venta[$num_elementos]')";
                          ejecutarConsulta($sql_detalle) or $sw = false;
          $num_elementos=$num_elementos+1;
        }
        return $sw;
    }

 

    //Implementamos un metodo parsa desactivar las categorias
    public function anular($idingreso)
    {
       $sql = "UPDATE ingreso SET estado='Anulado' WHERE idingreso='$idingreso'";
       return ejecutarConsulta($sql); 
    }



    //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idingreso)
    {
        $sql = "SELECT i.idingreso,DATE(i.fecha_hora) as fecha, i.idproveedor as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, i.total_compra, i.impuesto, i.estado 
        FROM ingreso AS i INNER JOIN persona AS p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario
        WHERE  i.idingreso='$idingreso'";
        return ejecutarConsultaSimpleFila($sql);
    }


    public function listarDetalle($idingreso)
    {
      $sql="SELECT di.idingreso,di.idarticulo,a.nombre,di.cantidad,di.precio_compra,di.precio_venta FROM detalle_ingreso di INNER JOIN articulo a on di.idarticulo=a.idarticulo WHERE di.idingreso='$idingreso'";
      return ejecutarConsulta($sql);

    }

  //Implementamos un metodo para mostrar todos los registros de la tabla categoria
    public function listar()
    {
        $sql = "SELECT @rownum:=@rownum+1 AS nro, i.idingreso,DATE(i.fecha_hora) as fecha, p.nombre  as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, i.total_compra, i.impuesto, i.estado 
        FROM ingreso AS i INNER JOIN persona AS  p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario, (SELECT @rownum := 0) r ORDER BY i.idingreso DESC";
        return ejecutarConsulta($sql);
    }



}

?>