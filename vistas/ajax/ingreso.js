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

    //cargamios los item del select proveedor
    $.post("../controladores/ingreso.php?op=selectProveedor",function (r)
    {
            $("#idproveedor").html(r);
            $("#idproveedor").selectpicker('refresh');


    })

    


}

/* ############################################################################################################################### */


//funcion limpiar
//los objetos del formulario lo va dejar vacios, lo vamos a poner nombres especificos
/* ############################################################################################################################### */
function limpiar() {
    $("#idproveedor").val("");
    $("#proveedor").val("")
    $("#serie_comprobante").val("");
    $("#num_comprobante").val(""); //el objeto cuyo id tiene nombre le enviaremos un valor vacio
    $("#fecha_hora").val("");  //el objeto cuyo id tiene descripcion le enviaremos un valor vacio
    $("#impuesto").val("0");


    $("total_compra").val("");
    // el punto se pone porque filas es una clase
    $(".filas").remove();
    $("#total").html("0");

    //Obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0"+(now.getMonth()+1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day);
    $('#fecha_hora').val(today);

    //Marcamos el primer tipo_documento
    $("#tipo_comprobante").val("Boleta");
    $("#tipo_comprobante").selectpicker("refresh");



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
        $("#box-title").text("Nuevo Ingreso");
        //mostrar el formulario y ocultar el listado
        $("#btnNC_global_filter").hide();
        //$(".box-header").css("background-color","red"); poner fondo de color al box-header con j-query 


        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //voy a implementar un boton cuyo id va ser btnGuardar y cuando se muestra el formulario y va estar activo.
       // $("#btnGuardar").prop("disabled", false);
        listarArticulos();

        $("btnGuardar").hide();
        $("btnCancelar").show();
        detalles=0;
        $("btnAgregarArt").show();




    } else //si recibo un false(falso)
    {
        //Voy a mostrar el boton crear nueva categoria y el boton buscar
        $("#btnNC_global_filter").show();
        //voy a mostrar el listado de registros y ocultar el  formulario
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#box-title").text("Ingresos");


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
            url: '../controladores/ingreso.php?op=listar', //mediante ajax vamos a obtener valores de la url que hemos descrito
            type: "get", //mediante tipo GET
            dataType: "json", //los datos seran codificados en json 
            error: function (e)  //si tenemos algun error para poder entender y verlo en texto plano median f12 inspeccionar del navegador
            {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, // de 5 en cinco vamos a realizar la paginacion
        "order": [[0, "asc"]] //odenar los datos (idcategoria(0) de manera desenciente, 1 2 3)
    }).DataTable();
}
/* ############################################################################################################################### */


function listarArticulos() {

 
    $tabla = $('#tblarticulos').dataTable({


        responsive: true,
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
       
        //Agregamos un parametro ajax

        "ajax": {
            url: '../controladores/ingreso.php?op=listarArticulos', //mediante ajax vamos a obtener valores de la url que hemos descrito
            type: "get", //mediante tipo GET
            dataType: "json", //los datos seran codificados en json 
            error: function (e)  //si tenemos algun error para poder entender y verlo en texto plano median f12 inspeccionar del navegador
            {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, // de 5 en cinco vamos a realizar la paginacion
        "order": [[0, "asc"]] //odenar los datos (idcategoria(0) de manera desenciente, 1 2 3)
    }).DataTable();
}


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

    //$("#btnGuardar").prop("disabled", true);

    //debajo declaro una variable de nombre formData y voy obtener todos los datos del formulario y se va almacenar en mi variable que he creado.  
    var formData = new FormData($("#formulario")[0]);

    //y por ultimo creo una peticion ajax
    $.ajax({
        //en el parametro url, indico la ruta de mi modelo y hacia que caso voy enviar todos los valores del formulario
        url: "../controladores/ingreso.php?op=guardaryeditar",
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
            listar();

        }


    });
    //y llamo a la funcion limpiar parsa que me limpie los campos del formulario
    limpiar();
    tabla.ajax.reload();

}

//funcion que me muestre los datos de cuyo fila  deseo editar
function mostrar(idingreso) {

    //declarmaos mediante jquery esta linea
    //que voy enviar     que estoy recibieendo
    $.post("../controladores/ingreso.php?op=mostrar", {idingreso: idingreso }, function (data) {
        //todo el registro se almacena en data, vamos a convertir los datos que estamos recibiendo de la url a JSON
        data = JSON.parse(data);
        //Y tengo que mostrar el formulario
        mostrarform(true);
        //con Jqeury busco el objeto(o los id's que deseo mostrar)
        //Poner en el box-title el texto Editar Categoría
        $("#box-title").text("Ver Ingreso");

        $('#idproveedor').val(data.proveedor);
        $('#idproveedor').selectpicker('refresh');
        
        $('#tipo_comprobante').val(data.tipo_comprobante);
        $('#tipo_comprobante').selectpicker('refresh');

        $('#serie_comprobante').val(data.serie_comprobante);
        $('#serie_comprobante').selectpicker('refresh');

        $('#num_comprobante').val(data.num_comprobante);
        $('#fecha_hora').val(data.fecha);
        $('#impuesto').val(data.impuesto);
        $('#idingreso').val(data.idingreso);

        //Ocultar y mostrar los botones
        $("#btnAgregarArt").hide();
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        

    });

    $.post("../controladores/ingreso.php?op=listarDetalle&id="+idingreso,function(r)
    {
        $("#detalles").html(r);    
    });
}

//funcion anular
//Al final no vamos a eliminar ningun registro de la tabla, lo que vamos hacer es que los registros que no vamos a utilizar lo vamos a anular
// cambiando el campo condicion de 1 a 0 de nuestra tabla categorias
function anular(idingreso) {
    var tabla = $('#tbllistado').DataTable();
    Swal.fire({
        title: '¿Estás seguro de anular la artículo?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, deseo anular.'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../controladores/ingreso.php?op=anular", { idingreso: idingreso }, function (e) {
                Swal.fire('Anulado', e, 'success');
                tabla.ajax.reload();

            });
        }
    });
}


//Declaracion de variables necesarias para trabajar con las compras y sus detablles

var impuesto=18; //Variable para almacenar el valor actual del impuesto
var contador=0; //Variable que va ir contanddo cuantos detalles 
var detalles =0; // Variable que va servir la cantidad de detalles que tiene la compra. 

//$("#guardar").hide() //Vamos a ocultar el div con el id=guardar, que contiene el boton guardar y cancelar.  Recien se visualizaran cuando tengas mas de un detalle
$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto); // Cuando el combo tipo_comprobante cambie de valor, voy a llamar a la funcion marcarImpuesto

function marcarImpuesto()
{
    //Creamos una variable que va almacenar, el valor seleccionado del combobox, es decir lo que yo seleccione en el select se va guardar el texto
    var tipo_comprobante = $("#tipo_comprobante option:selected").text();
    if(tipo_comprobante=='Factura')
    {
        //en mi input de id=impuesto me va mostar la variable impuesto que vale 18
        $("#impuesto").val(impuesto);        
    }
    else
    {
        $("#impuesto").val("0");

    }
}

function agregarDetalle(idarticulo,articulo) 
{
    var cantidad = 1;
    var precio_compra=1; 
    var precio_venta=1;
    var cont=0;

    if(idarticulo!="") //Si la variable idarticulo es diferente de vacia
    {
        //entonces si tiene un valor asignado 
        var subtotal=cantidad*precio_compra;
        // la variable fila va contener todo  el codigo html que nos permita crear una fila html para la tabla
        //class filas me va permitir limpiar la tabla  o limpiar todos los items, cada fila tendra un contador y un numero_: filq0,fila2
        var fila='<tr class="filas" id="fila'+cont+'">'+
            '<td> <button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')" style="margin:1px"><i class="fa fa-trash"></i></button></td>'+
                    '<td> <input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
                    '<td> <input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
                    '<td> <input type="number" name="precio_compra[]" id="precio_compra[]" value="'+precio_compra+'"></td>'+
                    '<td> <input type="number" name="precio_venta[]" id="precio_venta[]" value="' +precio_venta+ '"></td>' +
                    '<td> <span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>' +
                    '<td> <button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
                  '</tr>';

                cont++;
                detalles=detalles+1;
                $('#detalles').append(fila);
                modificarSubtotales();

    }else
    {
        Swal.fire('Error al ingresar el detalle.','revisar los datos.', 'success');
    }

}

//Función para 
function modificarSubtotales()
{
    //vamos a crear 3 arrays. 1 array  para almacenar todas las cantidades, otro para almacenar los precios de compra
    //almacenar los subtotatales.

    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_compra[]");
    var sub = document.getElementsByName("subtotal");

    //voy a rrecorrer y calcular sus subotales con un for
    // Si tenemos 5 detalles, la variable cant = 5 
    for (var i = 0; i < cant.length; i++) 
    {
        var inpC = cant[i];    
        var impP = prec[i]; //precio de compra en el array en el indice[i]
        var inpS = sub[i]    

        inpS.value=inpC.value * impP.value;
        document.getElementsByName("subtotal")[i].innerHTML=inpS.value;
    }   
    calcularTotales();
}

function calcularTotales() 
{
    var sub = document.getElementsByName("subtotal");
    var total =0.0;
    
    for (var i = 0; i < sub.length; i++) 
    {
      total += document.getElementsByName("subtotal")[i].value;          
    }
    $("#total").html("S/."+total);
    $("#total_compra").val(total);
    evaluar();
}

function evaluar() 
{
    if(detalles>0)
    {
        $("#btnGuardar").show();
    }else
    {
        $("#btnGuardar").hide();
        cont=0;
    }
}

function eliminarDetalle(indice) 
{
    $("#fila"+indice).remove();
    calcularTotales();
    detalles--;
}

init();

