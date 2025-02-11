import { verificar_sesion } from './sesion.js';

$(document).ready(function() {
    verificar_sesion();
    obtenerPedidos();
});

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
    confirmButton: "btn btn-success m-3",
    cancelButton: "btn btn-danger"
    },
    buttonsStyling: false
});

function obtenerPedidos() {
    $.ajax({
        url: '../Controllers/PedidoController.php', // Reemplaza esto con la ruta a tu archivo PHP
        method: 'POST',
        data: { funcion: 'obtener_pedidos' },
        success: function(response) {
            var pedidos = JSON.parse(response);
            var tbody = $('#ordersTable tbody');
            tbody.empty(); // Limpiar la tabla antes de agregar los nuevos datos

            pedidos.forEach(function(pedido) {
                var row = $('<tr>');
                row.append($('<td class="text-dark">').text(pedido.id || 'N/A'));
                row.append($('<td class="text-dark">').text((pedido.nombres || 'N/A') + ' ' + (pedido.apellidos || 'N/A'))); // Reemplaza esto con el nombre del cliente
                row.append($('<td class="text-dark">').text(pedido.dni || 'N/A')); // Reemplaza esto con el DNI del cliente
                row.append($('<td class="text-dark">').text(pedido.fecha ? new Date(pedido.fecha).toLocaleString() : 'N/A'));
                row.append($('<td class="text-dark">').text(pedido.envio ? Math.trunc(pedido.envio) : 0));
                row.append($('<td class="text-dark">').text(pedido.total ? Math.trunc(pedido.total) : 0));
                row.append($('<td class="text-dark">').text(pedido.direccion_envio || 'N/A'));
                row.append($('<td class="text-dark">').text(pedido.metodo_pago || 'N/A'));
                row.append($('<td class="text-dark">').text(pedido.estado || 'N/A'));

                var acciones = $('<td>');
                var viewDetailsButton = $('<button>').attr('id', 'viewDetails-' + pedido.id).addClass('btn btn-info').append($('<i>').addClass('fas fa-eye'));
                var editOrderButton = $('<button>').attr('id', 'editOrder-' + pedido.id).addClass('btn btn-success').append($('<i>').addClass('fas fa-edit'));
                var deleteOrderButton = $('<button>').attr('id', 'deleteOrder-' + pedido.id).addClass('btn btn-danger').append($('<i>').addClass('fas fa-trash-alt'));
                var viewInvoiceButton = $('<button>').attr('id', 'viewInvoice-' + pedido.id).addClass('btn').append($('<i>').addClass('fas fa-file-invoice'));

                viewDetailsButton.on('click', function() {
                    // Aquí puedes redirigir a la vista de detalles del pedido
                    window.location.href = './resumen_pedido.php?id=' + encodeURIComponent(pedido.id);
                });

                editOrderButton.on('click', function() {
                    // Aquí puedes abrir el modal de edición
                    $('#orderId').val(pedido.id);
                    $('#editOrderModal').modal('show');
                });

                deleteOrderButton.on('click', function() {
                    // Aquí puedes eliminar el pedido
                    // Asegúrate de confirmar la acción antes de eliminar el pedido
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¿Estás seguro de que quieres eliminar este pedido?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            eliminarPedido(pedido.id);
                        }
                    });
                });

                if (pedido.ruta_pdf) {
                    viewInvoiceButton.addClass('btn-success');
                    viewInvoiceButton.on('click', function() {
                        // Abrir el PDF en una nueva ventana usando una URL relativa
                        window.open('./serve_pdf.php?file=' + encodeURIComponent(pedido.ruta_pdf.split('/').pop()));
                    });
                } else {
                    viewInvoiceButton.addClass('btn-secondary').prop('disabled', true);
                }

                acciones.append(viewDetailsButton, editOrderButton, deleteOrderButton, viewInvoiceButton);

                row.append(acciones);

                tbody.append(row);
            });

            $('#ordersTable').DataTable(); // Inicializar el plugin DataTable
        },
        error: function() {
            swalWithBootstrapButtons.fire({
                title: "Error!",
                text: "Error al obtener los pedidos del servidor",
                icon: "error"
            });
            //alert('Error al obtener los pedidos del servidor');
        }
    });
}

function eliminarPedido(id) {
    $.ajax({
        url: '../Controllers/PedidoController.php',
        method: 'POST',
        data: {
            funcion: 'eliminar_pedido',
            id: id
        },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === 'success') {
                obtenerPedidos();
                swalWithBootstrapButtons.fire({
                    title: "Exito!",
                    text: "Pedido eliminado",
                    icon: "success"
                });
            } else {
                swalWithBootstrapButtons.fire({
                    title: "Error!",
                    text: "Error al eliminar el pedido",
                    icon: "error"
                });
                //alert('Error al eliminar el pedido');
            }
        },
        error: function() {
            swalWithBootstrapButtons.fire({
                title: "Error!",
                text: "Error al eliminar el pedido",
                icon: "error"
            });
            //alert('Error al eliminar el pedido');
        }
    });
}

$('#saveChanges').on('click', function() {
    var orderId = $('#orderId').val();
    var orderStatus = $('#orderStatus').val();
    var orderInvoice = $('#orderInvoice')[0].files[0];

    var formData = new FormData();
    formData.append('funcion', 'modificar_pedido');
    formData.append('id', orderId);
    formData.append('estado', orderStatus);
    formData.append('factura', orderInvoice);

    $.ajax({
        url: '../Controllers/PedidoController.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === 'success') {
                $('#editOrderModal').modal('hide');
                obtenerPedidos();
                swalWithBootstrapButtons.fire({
                    title: "Exito!",
                    text: "Pedido editado",
                    icon: "success"
                });
            } else {
                swalWithBootstrapButtons.fire({
                    title: "Error!",
                    text: "Error al editar el pedido",
                    icon: "error"
                });
                //alert('Error al editar el pedido');
            }
        },
        error: function() {
            swalWithBootstrapButtons.fire({
                title: "Error!",
                text: "Error al editar el pedido",
                icon: "error"
            });
            //alert('Error al editar el pedido');
        }
    });
});