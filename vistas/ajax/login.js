$('#formAcceso').on('submit',function(e)  //en el form con id formAcceso, en el boton de tipo submit 
{
    e.preventDefault(); //con preventDefaul hacemos que el boton envia los datos si no que se cumpla la siguientes acciones;
    //capturamos los valores que ingresamos tanto el input login y clave en el formulario de nuestra vista login 
    logina=$("#logina").val();
    clavea=$("#clavea").val();

    //por metodo POST lo enviamos a nuestro controlador los valores 
    $.post("../controladores/usuario.php?op=verificar",
    {"logina":logina,
    "clavea":clavea},
    //todo lo quer me trae de usuario.php deñ case verificar lo voy a guardar en data
    function(data) 
    {
        //si data viene llena va ser diferencte de nulo
        if(data!="null") // si el objeto data es diferente de nulo es decir si data esta lleno y por ende no esta null porque esta llena
        {
        //voy a direccionar a una contenido dentro de mi sistema
        
            Swal.fire
                (
                    'Mensaje de Confirmación',
                    'Usuario Encontrado',
                    'success'
                )
        $(location).attr("href","categoria.php");
        }
        else
        {
            Swal.fire
                (
                    'Alerta',
                    'Usuario o Contraseña Incorrecta',
                    'warning'
                )
            LimpiarFormularioLogin();

            
        }
    });
});

function LimpiarFormularioLogin() 
{
    $("#logina").val("");
    $("#clavea").val("");
    
}