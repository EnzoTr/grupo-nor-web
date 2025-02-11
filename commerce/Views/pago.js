import { verificar_sesion } from "./sesion.js";

$(document).ready(function() {
    verificar_sesion();
    obtenerDatosCliente();
});

function obtenerDatosCliente() {
    $.ajax({
        url: '../Controllers/UsuarioController.php',
        method: 'POST',
        data: {
            funcion: 'obtener_payer'
        },
        success: function(response) {
            var data = JSON.parse(response);
            $('#nombre').val(data.nombre);
            $('#apellido').val(data.apellido);
            $('#email').val(data.email);
        },
        error: function() {
            swalWithBootstrapButtons.fire({
                title: "Error!",
                text: "Error al realizar la solicitud AJAX'",
                icon: "error"
            });
            //alert('Error al realizar la solicitud AJAX');
        }
    });

}