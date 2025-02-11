import { verificar_sesion } from './sesion.js'

//aqui vamos a aplicar js al index 
$(document).ready(function() {
    var funcion;
    bsCustomFileInput.init();

    //funcion para verificar si existe una sesion abierta
    verificar_sesion();
    obtener_datos();
    llenar_provincia();
    llenar_direcciones();


    $('#provincia').select2({
        placeholder: 'Seleccione una provincia',
        language: {
            noResults: function(){
                return "No hay resultados"
            },
            searching: function(){
                return "Buscando..."
            }
        }
    });
    $('#localidad').select2({
        placeholder: 'Seleccione una localidad',
        language: {
            noResults: function(){
                return "No hay resultados"
            },
            searching: function(){
                return "Buscando..."
            }
        }
    });

    function llenar_direcciones(){
        funcion = "llenar_direcciones";
        $.post('../Controllers/UsuarioLocalidadController.php', {funcion}, (response)=>{
            let direcciones = JSON.parse(response);
            let contador = 0;
            let template = '';
            direcciones.forEach(direccion=>{
                contador ++;
                template += `
                    <div class="callout callout-info">
                        <div class="card-header">
                            <strong>direccion ${contador}</strong>
                            <div class="card-tools">
                                <button dir_id="${direccion.id}" type="button" class="eliminar_direccion btn btn-tool">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h2 class="lead"><b>${direccion.direccion}</b></h2>
                            <p class="text-muted text-sm"><b>Referencia: ${direccion.referencia}</p>
                            <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span>
                                ${direccion.provincia}, ${direccion.localidad}
                            </li>
                            </ul>
                        </div>
                    </div>
                `;
            });
            $('#direcciones').html(template);
        
        })
    }

    $(document).on('click', '.eliminar_direccion', (e)=>{
        let elemento = $(this)[0].activeElement;
        let id = $(elemento).attr('dir_id');

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
            confirmButton: "btn btn-success m-3",
            cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
            title: "Desea borrar esta direccion?",
            text: "Esta accion puede traer consecuencias!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Si, borra esto!",
            cancelButtonText: "No, deseo cancelar!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
            funcion = "eliminar_direccion";
            $.post('../Controllers/UsuarioLocalidadController.php', {funcion, id}, (response)=>{
                if(response === "success"){
                    swalWithBootstrapButtons.fire({
                        title: "Borrado!",
                        text: "Tu direccion fue borrada!",
                        icon: "success"
                    });
                    llenar_direcciones();
                }
                else if(response === "error"){
                    swalWithBootstrapButtons.fire({
                        title: "No se borro!",
                        text: "Hubo alteraciones en la integridad de datos",
                        icon: "error"
                    });
                }
                else{
                    swalWithBootstrapButtons.fire({
                        title: "No se borro!",
                        text: "Tenemos problemas en el sistema",
                        icon: "error"
                    });
                }
            })         
            }
        });
    });

    function llenar_provincia(){
        funcion = "llenar_provincia";
        $.post('../Controllers/ProvinciaController.php', {funcion}, (response)=>{
            let provincias = JSON.parse(response);
            let template = '';
            provincias.forEach(provincia=>{
                template += `
                    <option value="${provincia.id}">${provincia.provincia}</option>
                `;
            });
            $('#provincia').html(template);
            $('#provincia').val('').trigger('change');
        })
    }
    $('#provincia').change(function(){
        let id_provincia = $('#provincia').val();
        funcion = "llenar_localidad";
        $.post('../Controllers/LocalidadController.php', {funcion, id_provincia}, (response)=>{
            let localidades = JSON.parse(response);
            let template = '';
            localidades.forEach(localidad=>{
                template += `
                    <option value="${localidad.id}">${localidad.localidad}</option>
                `;
            });
            $('#localidad').html(template);
        })
    });

    /*function verificar_sesion() {
        funcion = 'verificar_sesion';
        $.post('../Controllers/UsuarioController.php', {funcion}, (response) => {
            console.log(response);
            if(response != ''){
                let sesion = JSON.parse(response);
                $('#nav_login').hide();
                $('#nav_register').hide();
                $('#usuario_nav').text(sesion.user + ' #'+ sesion.id);
                $('#avatar_nav').attr('src', '../Util/Img/Users/' + sesion.avatar);
                $('#avatar_menu').attr('src', '../Util/Img/Users/' + sesion.avatar);
                $('#usuario_menu').text(sesion.user);
            }
            else{
                $('#nav_usuario').hide();
                location. href = 'login.php';
            }
        });
    }*/

    function obtener_datos() {
        funcion = 'obtener_datos';
        $.post('../Controllers/UsuarioController.php', {funcion}, (response) => {
            let usuario = JSON.parse(response);
            $('#username').text(usuario.username);
            $('#tipo_usuari').text(usuario.tipo_usuario);
            $('#nombres').text(usuario.nombres + " " + usuario.apellidos);
            $('#avatar_perfil').attr('src', '../Util/Img/Users/' + usuario.avatar);
            $('#dni').text(usuario.dni);
            $('#email').text(usuario.email);
            $('#telefono').text(usuario.telefono);

            
        });
    }

    $('#form-direccion').submit(e=>{
        funcion = 'crear_direccion';
        //agarramos a traves del id, lo que contiene el user y la contraseña
        let id_localidades = $('#localidad').val();
        let direccion = $('#direccion').val();
        let referencia = $('#referencia').val();
        $.post('../Controllers/UsuarioLocalidadController.php', {id_localidades,direccion,referencia,funcion}, (response)=>{
            if(response == 'success'){
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Se ha registrado su direccion",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function(){
                    $('#form-direccion').trigger('reset');
                    $('#provincia').trigger('change');
                });
            }
            else{
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un problema al crear su direccion, comuniquese con el area de sistemas",
                });
            }
        })

        //previene que se reinicie la pagina
        e.preventDefault();

    });

    $(document).on('click', '.editar_datos', (e)=>{
        funcion = "obtener_datos";
        $.post('../Controllers/UsuarioController.php', {funcion}, (response)=>{
            let usuario = JSON.parse(response);
            $('#nombres_mod').val(usuario.nombres);
            $('#apellidos_mod').val(usuario.apellidos);
            $('#dni_mod').val(usuario.dni);
            $('#email_mod').val(usuario.email);
            $('#telefono_mod').val(usuario.telefono);
        })
    })

    $.validator.setDefaults({
        submitHandler: function () {
            funcion = "editar_datos";
            let datos = new FormData($('#form-datos')[0]);
            datos.append("funcion", funcion);
            $.ajax({
                type: "POST",
                url: "../Controllers/UsuarioController.php",
                data: datos,
                cache: false,
                processData: false,
                contentType: false,
                success: function (response){
                    console.log(response);
                    if(response == "success"){
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Se ha editado sus datos",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function(){
                            verificar_sesion();
                            obtener_datos();
                        });
                    }
                    else{
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Hubo un problema al editar sus datos, comuniquese con el area de sistemas",
                        });
                    }
                    
                }
            })
        }
    });

    jQuery.validator.addMethod("letras",
    function (value, element) {
        let variable = value.replace(/ /g,"");
        return /^[A-Za-z]+$/.test(variable);
    },
    "Este campo solo permite letras");
    
    $('#form-datos').validate({
        rules: {
            nombres_mod:{
                required: true,
                letras: true
            },
            apellidos_mod:{
                required: true,
                letras: true
            },
            dni_mod:{
                required: true,
                digits: true,
                minlength: 8,
                maxlength: 8
            },
            email_mod: {
                required: true,
                email: true,
            },
            telefono_mod:{
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 16
            },
        },
        messages: {
            nombres_mod:{
                required: "Este campo es obligatorio",
            },
            apellidos_mod:{
                required: "Este campo es obligatorio",
            },
            dni_mod:{
                required:  "Este campo es obligatorio",
                minlength: "El DNI debe tener solo 8 caracteres",
                maxlength: "El DNI debe tener solo 8 caracteres",
                digits: "El DNI debe tener solo numeros"
            },
            email_mod: {
                required: "Este campo es obligatorio",
                email: "No es formato email"
            },
            telefono_mod:{
                required:  "Este campo es obligatorio",
                minlength: "El telefono debe tener al menos 10 caracteres",
                maxlength: "El telefono no debe tener mas de 16 caracteres",
                digits: "El telefono debe tener solo numeros"
            }
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

    //comprobar contraseña para actualizar
    $.validator.setDefaults({
        submitHandler: function () {
            alert("se valido todo");
        }
    });
    

    jQuery.validator.addMethod("letras",
    function (value, element) {
        let variable = value.replace(/ /g,"");
        return /^[A-Za-z]+$/.test(variable);
    },
    "Este campo solo permite letras");
    
    $('#form-contra').validate({
        rules: {
            pass_old:{
                required: true,
                minlength: 5,
                maxlength: 20
            },
            pass_new:{
                required: true,
                minlength: 5,
                maxlength: 20
            },
            pass_repeat:{
                required: true,
                equalTo: '#pass_new'
            }
        },
        messages: {
            pass_old:{
                required:  "Este campo es obligatorio",
                minlength: "La contraseña debe tener al menos 5 caracteres",
                maxlength: "La contraseña no debe tener más de 20 caracteres"
            },
            pass_new:{
                required:  "Este campo es obligatorio",
                minlength: "La contraseña debe tener al menos 5 caracteres",
                maxlength: "La contraseña no debe tener más de 20 caracteres"
            },
            pass_repeat:{
                required: "Este campo es obligatorio",
                equalTo: "La contraseña no coincide con la anterior"
            }
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