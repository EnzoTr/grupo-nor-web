$(document).ready(function() {
    var funcion;
    verificar_sesion();
    
    function verificar_sesion() {
        funcion = 'verificar_sesion';
        $.post('../Controllers/UsuarioController.php', {funcion}, (response) => {
            if(response != ''){
                location.href = './index.php';
            }
        });
    }
    
    $.validator.setDefaults({
        submitHandler: function () {
        let username = $('#username').val();
        let pass = $('#pass').val();
        let nombres = $('#nombres').val();
        let apellidos = $('#apellidos').val();
        let dni = $('#dni').val();
        let email = $('#email').val();
        let telefono = $('#telefono').val();
        funcion = "registrar_usuario";
        $.post("../Controllers/UsuarioController.php",{username, pass, nombres, apellidos, dni, email, telefono, funcion},
        (response)=>{
            if(response == "success"){
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Se ha registrado correctamente",
                    showConfirmButton: false,
                    timer: 2500
                }).then(function(){
                    $('#form-register').trigger('reset');
                    location.href = '../Views/login.php';
                });
            }
            else{
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un problema al registrarse, comuniquese con el area de sistemas",
                });
            }
        })
        }
    });
    //funcion sincrona para validar si existe el usuario en la bd
    jQuery.validator.addMethod("usuario_existente",
    function (value, element) {
        let funcion = "verificar_usuario";
        let bandera;
        $.ajax({
            type: "POST",
            url: "../Controllers/UsuarioController.php",
            data: 'funcion='+funcion+'&&value='+value,
            async: false,
            success: function (response) {
                //bandera = JSON.parse(response);
                if(response == "success"){
                    bandera = false;
                }
                else{
                    bandera = true;
                }
            }
        })
        return bandera;
    },
    "El usuario ya existe, ingrese uno diferente");

    jQuery.validator.addMethod("letras",
    function (value, element) {
        let variable = value.replace(/ /g,"");
        return /^[A-Za-z]+$/.test(variable);
    },
    "Este campo solo permite letras");
    
    $('#form-register').validate({
        rules: {
            username:{
                required: true,
                minlength: 6,
                maxlength: 20,
                usuario_existente: true
            },
            pass:{
                required: true,
                minlength: 5,
                maxlength: 20
            },
            pass_repeat:{
                required: true,
                equalTo: '#pass'
            },
            nombres:{
                required: true,
                letras: true
            },
            apellidos:{
                required: true,
                letras: true
            },
            dni:{
                required: true,
                digits: true,
                minlength: 8,
                maxlength: 8
            },
            email: {
                required: true,
                email: true,
            },
            telefono:{
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 16
            },
            terms: {
                required: true
            },
        },
        messages: {
            username:{
                required:  "Este campo es obligatorio",
                minlength: "El nombre de usuario debe tener al menos 5 caracteres",
                maxlength: "El nombre de usuario no debe tener más de 20 caracteres"
            },
            pass:{
                required:  "Este campo es obligatorio",
                minlength: "La contraseña debe tener al menos 5 caracteres",
                maxlength: "La contraseña no debe tener más de 20 caracteres"
            },
            pass_repeat:{
                required: "Este campo es obligatorio",
                equalTo: "La contraseña no coincide con la anterior"
            },
            nombres:{
                required: "Este campo es obligatorio",
            },
            apellidos:{
                required: "Este campo es obligatorio",
            },
            dni:{
                required:  "Este campo es obligatorio",
                minlength: "El DNI debe tener solo 8 caracteres",
                maxlength: "El DNI debe tener solo 8 caracteres",
                digits: "El DNI debe tener solo numeros"
            },
            email: {
                required: "Este campo es obligatorio",
                email: "No es formato email"
            },
            telefono:{
                required:  "Este campo es obligatorio",
                minlength: "El telefono debe tener al menos 10 caracteres",
                maxlength: "El telefono no debe tener mas de 16 caracteres",
                digits: "El telefono debe tener solo numeros"
            },
            terms: "Por favor, acepte los terminos de servicio"
            },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            $(element).removeClass('is-valid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            $(element).addClass('is-valid');
        }
    });

    
})
