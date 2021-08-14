
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
                                <button class="btn btn-success form-control" onclick="mostrarform(true);"><i class="fa fa-plus-circle" id="btn_nueva_categoria" name="btn_nueva_categoria"></i> Nuevo Artículo</button>
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
                      <table id="tbllistado" class="display nowrap" style="width: 100%;">
                        <thead>
                          <th style="width:5%;">#</th>
                          <th>Nombre</th>
                          <th>Categoria</th>
                          <th>Código</th>
                          <th>Stock</th>
                          <th>Descripcion</th>
                          <th>Imagen</th>
                          <th>Estado</th>
                          <th style="width: 10%;">Acciones</th>
                        </thead>
                        <tbody></tbody>                      
                      </table>
                    </div>

                    <div class="panel-body" id="formularioregistros">
                      <form method="POST" name="formulario" id="formulario">

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="nombre">Nombre(*):</label>
                          <!-- este input va ser tipo oculta para que me cpature el id del elemento que deseo editar o crear -->
                          <input type="hidden" name="idarticulo" id="idarticulo">
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="text" class="form-control" id="nombre" name="nombre" maxlength="45" placeholder="Nombre" required>
                        </div>
                         <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="nombre">Categoria(*):</label>
                          <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-live-search="true" required></select>
                        </div>

                         <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="stock">Stock(*):</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="number" class="form-control" name="stock" id="stock" required>
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="descripcion">Descripción:</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripción">
                        </div>

                         <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="descripcion">Imagen:</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="file" class="form-control" name="imagen" id="imagen" accept="imagen/jpg,imagen/jpeg,imagen/png">
                          <input type="hidden" name="imagenactual" id="imagenactual">
                          <img src="" alt="" width="150px" height="150px" id="imagenmuestra" name="imagenmuestra">  
                        </div>

                         <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="" class="control-label">Código:</label>
                            <div class="input-group col-xs-12">
                              <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Código de Barras">
                              <div class="input-group-btn">
                                  <button class="btn btn-warning" type="button" onclick="generarbarcode();"><i class="fa fa-barcode" style="width: 5rem;"></i></button>
                                                                </div>
                            </div>
                             <!--este div es para mostrar el codigo de barras-->
                             
                              <div id="print">
                                <svg id="barcode"></svg>
                                <br>
                                <button class="btn btn-info" type="button" id="botonimprimir" onclick="imprimircodigo();"><i class="fa fa-print" style="width: 5rem;"></i></button>
                              </div>
                         </div>
        

                        <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                            <button type="submit"  class="btn btn-primary" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                            <button  class="btn btn-danger" onclick="cancelarform()"><i class="fa fa-close"></i> Cancelar</button>

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
<!-- Libreria JsBarcode para generar codigos de barras, solamente lo vamos a utilziar en ese modulo asi que lo llamamos en la vista articulo. -->
<script type="text/javascript"  src="../public/js/JsBarcode.all.min.js"></script>

<!-- Libreria jquery.PrintArea para imprimir la barra de codigo -->
<script type="text/javascript"  src="../public/js/jquery.PrintArea.js"></script>

<script type="text/javascript"  src="ajax/articulo.js"></script>


<?php
} //cerrar la llave del else

//liberar el espacio del buffer
ob_end_flush();

?>