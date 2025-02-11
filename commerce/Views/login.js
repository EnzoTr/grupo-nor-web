//aqui vamos a capturar los datos del formulario
$(document).ready(function() {
    var funcion;

    //funcion para verificar si existe una sesion abierta
    verificar_sesion();
    function verificar_sesion() {
        funcion = 'verificar_sesion';
        $.post('../Controllers/UsuarioController.php', {funcion}, (response) => {
            if(response != ''){
                location.href = './index.php';
            }
        });
    }


    $('#form-login').submit(e=>{
        funcion = 'login';
        //agarramos a traves del id, lo que contiene el user y la contraseña
        let user = $('#user').val();
        let pass = $('#pass').val();
        $.post('../Controllers/UsuarioController.php', {user,pass,funcion}, (response)=>{
            if(response === 'logueado'){
                location.href = './index.php';
            }
            else{
                toastr.error('Usuario o contraseña incorrectas!');
            }
        })

        //previene que se reinicie la pagina
        e.preventDefault();

    });
});