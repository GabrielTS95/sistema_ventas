
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
                            <div class="col-sm-4">
                                <button class="btn btn-success form-control" onclick="mostrarform(true);"><i class="fa fa-plus-circle" id="btn_nueva_categoria" name="btn_nueva_categoria"></i> Nuevo Usuario</button>
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
                          <th style="width:5%;">#</th>
                          <th>Nombre</th>
                          <th>Tipo Documento</th>
                          <th>Numero Documento</th>
                          <th>Teléfono</th>
                          <th>Email</th>
                          <th>Login</th>
                          <th>Imagen</th>
                          <th>Estado</th>
                          <th style="width: 10%;">Acciones</th>
                        </thead>
                        <tbody></tbody>                      
                      </table>
                    </div>

                    <div class="panel-body" id="formularioregistros">
                      <form method="POST" name="formulario" id="formulario">

                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <label for="nombre">Nombre(*):</label>
                          <!-- este input va ser tipo oculta para que me cpature el id del elemento que deseo editar o crear -->
                          <input type="hidden" name="idusuario" id="idusuario">
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="text" class="form-control" id="nombre" name="nombre" maxlength="100" placeholder="Nombre" required>
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="nombre">Tipo Documento(*):</label>
                          <select name="tipo_documento" id="tipo_documento" class="form-control selectpicker" data-live-search="true" required>
                                <option value="DNI">DNI</option>
                                <option value="RUC">RUC</option>
                                <option value="CEDULA">CEDULA</option>

                          </select>
                        </div>

                         <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="stock">Numero(*):</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="text" class="form-control" name="num_documento" id="num_documento" maxlength="20" required>
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="">Dirección:</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="text" class="form-control" name="direccion" id="direccion" maxlength="256" placeholder="Dirección">
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="">Teléfono:</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="text" class="form-control" name="telefono" id="telefono" maxlength="10" placeholder="Teléfono">
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="">Email:</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="email" class="form-control" name="email" id="email" maxlength="50" placeholder="Email">
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="">Cargo:</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="text" class="form-control" name="cargo" id="cargo" maxlength="20" placeholder="Cargo">
                        </div>
                        
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="">Login(*):</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="text" class="form-control" name="login" id="login" maxlength="20" placeholder="Login" required>
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="">Clave(*):</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="password" class="form-control" name="clave" id="clave" maxlength="20" placeholder="Clave" required>
                        </div>

                         <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="">Permisos(*):</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <ul style="list-style:none;" id="permisos" name="permisos"></ul>
                        </div>

                         <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="">Imagen:</label>
                          <!-- maxlength es por que nombre en mi base de datos es un varchar de 50 caracteres -->
                          <input type="file" class="form-control" name="imagen" id="imagen" accept="imagen/jpg,imagen/jpeg,imagen/png">
                          <input type="hidden" name="imagenactual" id="imagenactual">
                          <img src="" alt="" width="150px" height="150px" id="imagenmuestra" name="imagenmuestra">  
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
//Requerimos el footer.php
require "footer.php";
?>

<script type="text/javascript"  src="ajax/usuario.js"></script>

<?php
} //cerrar la llave del else

//liberar el espacio del buffer
ob_end_flush();

?>
