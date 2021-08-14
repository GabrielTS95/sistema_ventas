//Inicialmente declaramos una variable global la cual va almacenar todos
//los datos que vamos a ir actulizando en la datatable.
var tabla;
//Funcion que se va ejeuctar siempre al inicio 
//La funcion init va ser referencia a otros funciones que iremos implementando
/* ############################################################################################################################### */
function init() {
    mostrarform(false); //voy a hacer que no se muestre el formulario
    listar(); //mostrar el listado de todas las categorias
    ocultarBuscador();

    //mediante jquery voy a decir si hago click en el botonque tenga el  id del formulario y es de tipo submit
    $("#formulario").on("submit", function (e) {
        //voy a llamar a la funcion guardaryeditar
        guardaryeditar(e);

    });

    $.post("../controladores/usuario.php?op=permisos&id=",function (r) 
    {
        $("#permisos").html(r);
    });
    $("#imagenmuestra").hide();
}

/* ############################################################################################################################### */


//funcion limpiar
//los objetos del formulario lo va dejar vacios, lo vamos a poner nombres especificos
/* ############################################################################################################################### */
function limpiar() {
    $("#nombre").val("");
    $("#num_documento").val("")
    $("#direccion").val("");
    $("#telefono").val(""); //el objeto cuyo id tiene nombre le enviaremos un valor vacio
    $("#email").val("");  //el objeto cuyo id tiene descripcion le enviaremos un valor vacio
    $("#cargo").val("");
    $("#login").val("");
    $("#clave").val("");
    $("#imagenmuestra").hide();
    $("#imagenactual").val("");
    $("#idusuario").val("");
    $('input:checkbox').each(function () { this.checked = false; });
}
/* ############################################################################################################################### */


//funcion mostar formulario
//esta funcion nos va servir, en una sola pagina html se va tener tanto el listado como
//el formulario donde voy a ir dando mantenimiento a  mis registros de mi tabla categoria

function mostrarform(flag) {
    limpiar(); //para siempre mantener limpias las cajas de textos;
    if (flag)  //si el flag viene true(verdadero)
    {
        //Poner el box-tiele el texto Nueva Categoría
        $("#box-title").text("Nuevo Usuario");
        //mostrar el formulario y ocultar el listado
        $("#btnNC_global_filter").hide();
        //$(".box-header").css("background-color","red"); poner fondo de color al box-header con j-query 


        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //voy a implementar un boton cuyo id va ser btnGuardar y cuando se muestra el formulario y va estar activo.
        $("#btnGuardar").prop("disabled", false);

        


    } else //si recibo un false(falso)
    {
        //Voy a mostrar el boton crear nueva categoria y el boton buscar
        $("#btnNC_global_filter").show();
        //voy a mostrar el listado de registros y ocultar el  formulario
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#box-title").text("Usuarios");


    }
}


//Cancelar form para que nos permita ocultaar un formulario o cerrarlo
/* ############################################################################################################################### */
function cancelarform() {
    limpiar();
    //llamo a la funcion monstrarform la cual lo voy enviar un false
    // para indicarle que no esta visible
    mostrarform(false);

}
/* ############################################################################################################################### */


//funcion listar
/* ############################################################################################################################### */
function listar() {
    $tabla = $('#tbllistado').dataTable({

        responsive: true, //para que mi tabla al momento de hacerle mas pequeña me salga un boton + para mostar mis columnas


        language: {
            "decimal": "",
            "emptyTable": "No hay datos",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(Filtro de _MAX_ total registros)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron coincidencias",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            },
        },

        "aProcessing": true, //Activamos el procesamiento del datatables
        "aServerSide": true, //Paginacion y filtrado realizados por el servidor
        dom: 'Bfrtip',       //Definimos los elementos del control de la tabla
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        //Agregamos un parametro ajax

        "ajax": {
            url: '../controladores/usuario.php?op=listar', //mediante ajax vamos a obtener valores de la url que hemos descrito
            type: "get", //mediante tipo GET
            dataType: "json", //los datos seran codificados en json 
            error: function (e)  //si tenemos algun error para poder entender y verlo en texto plano median f12 inspeccionar del navegador
            {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 5, // de 5 en cinco vamos a realizar la paginacion
        "order": [[0, "asc"]] //odenar los datos (idcategoria(0) de manera desenciente, 1 2 3)
    }).DataTable();
}
/* ############################################################################################################################### */

//función que oculta el buscador(input) que por defecto viene con datatable.
//lo inicializamos al incio. en la funcion init
//creamos la funcion que se va llamar ocultarBuscador
/* ############################################################################################################################### */
function ocultarBuscador() {
    //Vamos a crear una variable buscador y con el metodo getElement lo vamos a capturar y se va almacenar en nuestra variable. 
    var buscador = document.getElementById('tbllistado_filter');
    //Una vez capturado tblistado_filter que es el id del input que por defecto viene con datatable, 
    //a nuestra variable le vamos a darle un estilo de ninguno, style.display="none" es decir nos va ocultar
    buscador.style.display = 'none';
    //luego llamamos a la funcion FilterGlobal para que el buscador que hemos creado funcione como el buscador de datatable
    //esto lo hacemos desde el incio ya que al momento de listar automaticamente oculta y el buscador que hicimos se convierte el de datable  
    filterGlobal();
}
/* ############################################################################################################################### */


/* ############################################################################################################################### */
//funcion que Filtra la tabla según una entrada personalizada y vuelva a dibujar:
//creamos una funcion que se va llamar filterGlobal
/* ############################################################################################################################### */
function filterGlobal() {
    //declaramos una variable que se va llamar table, la cual va capturar el id de la nuestra tabla 
    var tabla = $('#tbllistado').DataTable();
    //luego con codigo jquery, le vamos a decir a que la clase del input que hemos creado como buscados,
    // la cual con punto y comillas simples hacemos referencia '.global_filter', con keyup (cuando hago escribro en el teclado )
    // se activa una funcion,  que hace referenia a mi table la cuald me va buscar el valor que ponga y con un draw me vuelva dibujar en la tabla 
    $('.global_filter').on('keyup', function () {
        tabla
            .search(this.value)
            .draw();
    });
}
/* ############################################################################################################################### */

//funcion para guardar y editar

function guardaryeditar(e) {
    var tabla = $('#tbllistado').DataTable();
    //esto es una instruccion j-query para indicar que no se activará la acción predeterminada del evento
    //ya que el boton btnGuardar del formulario es de tipo submit, no se va responder ese evento si no se va ejecutar las intrucciones en el orden que aparece.
    e.preventDefault();
    //una vez que yo haga en el boton guardar lo voy a deshabilitar 
    $("#btnGuardar").prop("disabled", true);
    //debajo declaro una variable de nombre formData y voy obtener todos los datos del formulario y se va almacenar en mi variable que he creado.  
    var formData = new FormData($("#formulario")[0]);

    //y por ultimo creo una peticion ajax
    $.ajax({
        //en el parametro url, indico la ruta de mi modelo y hacia que caso voy enviar todos los valores del formulario
        url: "../controladores/usuario.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        //si la funcion se ejecuta de manera correcta lo que va hacer es:
        success: function (datos) {
            //voy enviar una alerta y voy a recibir los datos que me esta enviando el controlador
            //$rspta = datos, entonces digo si datos=="true" me va mostrar una alerta de exito, 
            // caso contrario un error.s

            //console.log(datos);
            //console.log('Mozilla is ' + datos.length);

            if (datos.length <= 22) {

                Swal.fire('Mensaje de confirmación.', datos, 'success'
                )

            }
            else {
                Swal.fire('Mensaje de error.', datos, 'error')

            }

            //una vez mostrado la alerta me oculta el formulario y me muestra el listado
            mostrarform(false);
            //y la tabla lo voy a recargar
            tabla.ajax.reload();

        }


    });
    //y llamo a la funcion limpiar parsa que me limpie los campos del formulario
    limpiar();
    tabla.ajax.reload();

}

//funcion que me muestre los datos de cuyo fila  deseo editar
function mostrar(idusuario) {

    //declarmaos mediante jquery esta linea
    //que voy enviar     que estoy recibieendo
    $.post("../controladores/usuario.php?op=mostrar", { idusuario: idusuario }, function (data) {
        //todo el registro se almacena en data, vamos a convertir los datos que estamos recibiendo de la url a JSON
        data = JSON.parse(data);
        //Y tengo que mostrar el formulario
        mostrarform(true);
        //con Jqeury busco el objeto(o los id's que deseo mostrar)
        //Poner en el box-title el texto Editar Categoría
        $("#box-title").text("Editar Usuario");
      
        $('#nombre').val(data.nombre);
        $('#tipo_documento').val(data.tipo_documento);
        $('#tipo_documento').selectpicker('refresh'); //como es un select con la funcion select pocker lo refresco
        $('#num_documento').val(data.num_documento);
        $('#direccion').val(data.direccion);
        $('#telefono').val(data.telefono);
        $('#email').val(data.email);
        $('#cargo').val(data.cargo);
        $('#login').val(data.login);
        $('#clave').val(data.clave);
        $('#imagenmuestra').show();
        $('#imagenmuestra').attr("src","../files/usuarios/"+data.imagen);
        $("#imagenactual").val(data.imagen);
        $('#idusuario').val(data.idusuario);
        //$('#permisos').val(data.);
   

    });
     $.post("../controladores/usuario.php?op=permisos&id="+idusuario,function (r) 
    {
        $("#permisos").html(r);
    });
}

//funcion desactivar
//Al final no vamos a eliminar ningun registro de la tabla, lo que vamos hacer es que los registros que no vamos a utilizar lo vamos a desactivar
// cambiando el campo condicion de 1 a 0 de nuestra tabla categorias
function desactivar(idusuario) {
    var tabla = $('#tbllistado').DataTable();
    Swal.fire({
        title: '¿Estás seguro de desactivar el usuario?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, deseo desactivar.'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../controladores/usuario.php?op=desactivar", {idusuario: idusuario }, function (e) {
                Swal.fire('Desactivado', e, 'success');
                tabla.ajax.reload();

            });
        }
    });
}

//funcion para activar
function activar(idusuario) 
{
    var tabla = $('#tbllistado').DataTable();
    Swal.fire({
        title: '¿Estás seguro de activar el usuario?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Por Favor.',
        cancelButtonText:  "No, Cancelar."
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../controladores/usuario.php?op=activar", {idusuario:idusuario }, function (e) {
                Swal.fire('Activado',e, 'success');
                tabla.ajax.reload();

            });
        }
    });
}


init();

