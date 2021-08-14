
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
if($_SESSION['almacen']==1)
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
                            <div class="col-sm-4">
                                <button class="btn btn-success form-control" onclick="mostrarform(true);"><i class="fa fa-plus-circle" id="btn_nueva_categoria" name="btn_nueva_categoria"></i> Nuevo Categoria</button>
                            </div>
                            
                              <div class="col-sm-8">
                               <div class="input-group">
                                  <input type="text" placeholder="Buscar" class="global_filter form-control" id="global_filter">
                                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                      </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                      <table id="tbllistado" class="table table-striped table-bordered dt-responsive nowrap" style="width: 100%;">
                        <thead>
                          <th>#</th>
                          <th>Nombre</th>
                          <th>Descripcion</th>
                          <th>Estado</th>
                          <th style="width: 15%;">Acciones</th>
                        </thead>
                        <tbody></tbody>                      
                      </table>
                    </div>

                    <div class="panel-body" id="formularioregistros">
                      <form method="POST" name="formulario" id="formulario">

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="nombre">Nombre:</label>
                          <!-- este input va ser tipo oculta para que me cpature el id del elemento que deseo editar o crear -->
                          <input type="hidden" name="idcategoria" id="idcategoria">
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="text" class="form-control" id="nombre" name="nombre" maxlength="50" placeholder="Nombre" required>
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="descripcion">Descripción:</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripción">
                        </div>

                        <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <button type="submit"  class="btn btn-primary" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                            <button  class="btn btn-danger" type="button" onclick="cancelarform()"><i class="fa fa-close"></i> Cancelar</button>

                        </div>

                      </form>
                        
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
<script type="text/javascript"  src="ajax/categoria.js"></script>

<?php
} //cerrar la llave del else


//liberar el espacio del buffer
ob_end_flush();

?>