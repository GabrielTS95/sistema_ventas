
<?php

//Activamos el alamaceniento  en el buffer. 
ob_start();

//Iniciamos la session de usuario
session_start();

//vamos  evaluar que la variable de session nombre exista. 
if(!isset($_SESSION['nombre'])) //Si la variable de session nombre no existe, significa que elusuario no se a logueado de manera correcta
{
  header("Location: login2.html");
}else
{
//Requerimos el header.php
require "header.php";
//Si la variable session alamcen es igual a 1  va poder ver todo el contenido
if($_SESSION['acceso']==1)
{
?>

<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        
        <!-- Main content -->
        <section class="content">
        
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border bg-success">
                       <label class="box-title" id="box-title"></label>
                    </div>
                    <br>
                       <div class="form-group" id="btnNC_global_filter">
                       <!--
                            <div class="col-sm-4">
                                <button class="btn btn-success form-control" onclick="mostrarform(true);"><i class="fa fa-plus-circle" id="btn_nueva_categoria" name="btn_nueva_categoria"></i> Nuevo Categoria</button>
                            </div>
                        -->
                              <div class="col-sm-12">
                               <div class="input-group">
                                  <input type="text" placeholder="Buscar" class="global_filter form-control" id="global_filter">
                                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                      </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                      <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover display responsive" style="width: 100%;">
                        <thead>
                          <th>#</th>
                          <th>Nombre</th>
                        </thead>
                        <tbody></tbody>                      
                      </table>
                    </div>

        
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->


  
<?php
} //cerrando el if de la condicion de la session
else
{
  require 'noacceso.php';
}
//Requerimos el header.php
require "footer.php";
?>
<script type="text/javascript"  src="ajax/permiso.js"></script>
<?php
} //cerrar la llave del else

//liberar el espacio del buffer
ob_end_flush();

?>
