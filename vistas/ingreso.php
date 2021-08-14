
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
if($_SESSION['compras']==1)
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
                                <button class="btn btn-success form-control" onclick="mostrarform(true);"><i class="fa fa-plus-circle" id="btn_nueva_categoria" name="btn_nueva_categoria"></i> Nuevo Ingreso</button>
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
                          <th>#</th>
                          <th>Fecha</th>
                          <th>Proveedor</th>
                          <th>Usuario</th>
                          <th>Documento</th>
                          <th>Numero</th>
                          <th>Total Compra</th>
                          <th>Estado</th>
                          <th style="width: 15%;">Acciones</th>
                        </thead>
                        <tbody></tbody>                      
                      </table>
                    </div>

                    <div class="panel-body" id="formularioregistros">
                      <form method="POST" name="formulario" id="formulario">

                        <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                          <label for="proveddor">Proveedor(*):</label>
                          <!-- este input va ser tipo oculta para que me cpature el id del elemento que deseo editar o crear -->
                          <input type="hidden" name="idingreso" id="idingreso">
                          <select name="idproveedor" id="idproveedor" class="form-control select-picker" data-live-search="true" required>               
                          </select>
                        </div>

                        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                          <label for="fecha">Fecha(*):</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" maxlength="256" required>
                        </div>


                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="">Tipo Comprobante(*):</label>
                          <!-- este input va ser tipo oculta para que me cpature el id del elemento que deseo editar o crear -->
                          <select name="tipo_comprobante" id="tipo_comprobante" class="form-control select-picker" data-live-search="true" required>               
                          <option value="Boleta">Boleta</option>
                          <option value="Factura">Factura</option>
                          <option value="Ticket">Ticket</option>
                          </select>
                        </div>

                         <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                          <label for="serie">Serie(*):</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="7" placeholder="Serie">
                        </div>

                         <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                          <label for="numero">Numero(*):</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="10" placeholder="Numero" required>
                        </div>

                         <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                          <label for="impuesto">Impuesto(*):</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="text" class="form-control" name="impuesto" id="impuesto" maxlength="10" required>
                        </div>

                        <!-- Ventana modal, vamos a mostrar todos los articulos a ingresar a alamcen -->

                         <div class="form-group col-lg-10 col-md-10 col-sm-10 col-xs-12">
                            <a href="#myModal" data-toggle="modal" >
                                <button id="btnAgregarArt" type="button" class="btn btn-primary"><span class="fa fa-plus"></span> Agregar Artículos</button>
                            </a>
                        </div>

                        
                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <div class="panel-body table-responsive">
                                    <table id="detalles" class="display nowrap" style="width: 100%;">
                                <thead style="background-color:#A9D0F5;">
                                    <th>Acciones </th>
                                    <th>Articulo</th>
                                    <th>Cantidad</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Venta</th>
                                    <th>Subtotal</th>
                                </thead>
                                <tfoot>
                                    <th>Total</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total">S/. 0.00</h4>
                                        <input type="hidden" name="total_compra" id="total_compra">
                                    </th>
                                </tfoot>
                                <tbody>
                                
                                </tbody>
                            </table>
                              </div>
                        </div>


                        <div class="form-group col-md-4 col-lg-4 col-sm-4 col-xs-12">
                            <button type="submit"  class="btn btn-primary" id="btnGuardar" name="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                            <button  id="btnCancelar" name="btnCancelar" class="btn btn-danger" type="button" onclick="cancelarform()"><i class="fa fa-close"></i> Cancelar</button>

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


<!-- ############################################################################## -->

<!-- Modal -->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aira-hidden="true">&times;</button>
                <h4 class="modal-title">Seleccione un Artículo</h4>
            </div>
            <div class="modal-body">
              <div class="panel-body table-responsive">
                      <table id="tblarticulos" class="display nowrap" style="width: 100%;">
                           <thead>
                              <th></th>
                              <th>#</th>
                              <th>Acciones</th>
                              <th>Nombre</th>
                              <th>Categoría</th>
                              <th>Stock</th>
                              <th>Codigo</th>
                              <th>Imagen</th>
                           </thead>

                          <tbody>

                          </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
            
        </div>
    
    </div>
</div>




<!-- ############################################################################## -->

  
<?php
} //cerrando el if de la condicion de la session
else
{
  require 'noacceso.php';
}

//Requerimos el header.php
require "footer.php";
?>
<script type="text/javascript"  src="ajax/ingreso.js"></script>

<?php
} //cerrar la llave del else


//liberar el espacio del buffer
ob_end_flush();

?>