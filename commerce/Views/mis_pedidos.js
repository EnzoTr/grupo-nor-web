import { verificar_sesion } from './sesion.js';

$(document).ready(function() {
    verificar_sesion();
    obtenerCategorias();
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
        data: { funcion: 'obtener_pedidos_usuario' , id_usuario: localStorage.getItem('id_usuario')},
        success: function(response) {
            var pedidos = JSON.parse(response);
            var tbody = $('#ordersTable tbody');
            tbody.empty(); // Limpiar la tabla antes de agregar los nuevos datos

            pedidos.forEach(function(pedido) {
                var row = $('<tr>');
                row.append($('<td class="text-dark">').text(pedido.id || 'N/A'));
                row.append($('<td class="text-dark">').text(pedido.fecha ? new Date(pedido.fecha).toLocaleString() : 'N/A'));
                row.append($('<td class="text-dark">').text(pedido.envio ? Math.trunc(pedido.envio) : 0));
                row.append($('<td class="text-dark">').text(pedido.total ? Math.trunc(pedido.total) : 0));
                row.append($('<td class="text-dark">').text(pedido.direccion_envio || 'N/A'));
                row.append($('<td class="text-dark">').text(pedido.metodo_pago || 'N/A'));
                row.append($('<td class="text-dark">').text(pedido.estado || 'N/A'));

                var acciones = $('<td class="text-dark">');
                var viewDetailsButton = $('<button>').attr('id', 'viewDetails-' + pedido.id).addClass('btn btn-info').append($('<i>').addClass('fas fa-eye'));
                var viewInvoiceButton = $('<button>').attr('id', 'viewInvoice-' + pedido.id).addClass('btn').append($('<i>').addClass('fas fa-file-invoice'));

                viewDetailsButton.on('click', function() {
                    // Aquí puedes redirigir a la vista de detalles del pedido
                    window.location.href = './resumen_pedido.php?id=' + encodeURIComponent(pedido.id);
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

                acciones.append(viewDetailsButton, viewInvoiceButton);

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
                    text: "Se edito el pedido",
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

function obtenerCategorias() {
    $.ajax({
        url: '../Controllers/CategoriaController.php',
        method: 'POST',
        data: {
            funcion: 'obtener_categorias_activas'
        },
        success: function(response) {
            var categorias = JSON.parse(response);
            var navbarHtml = '';

            navbarHtml += '<li class="nav-item text-dark"><a href="./calculadora.php" class="nav-link text-dark">Cotización</a></li>';
            navbarHtml += '<li class="nav-item text-dark"><a href="./tienda.php" class="nav-link text-dark">Inicio</a></li>';

            function generateCategoryHtml(categoria, isSubcategory = false) {
                var html = '';
                if (isSubcategory) {
                    html += '<li id="dropdown-submenu" class="dropdown-submenu text-dark"><a href="#" class="dropdown-item text-dark subcategoria" data-id="' + categoria.id + '">' + categoria.nombre + '</a>';
                } else {
                    html += '<li id="dropdown" class="nav-item dropdown text-dark">';
                    html += '<a href="#" class="nav-link text-dark categoria ' + ((categoria.subcategorias && categoria.subcategorias.length > 0) ? 'dropdown-toggle' : '') + '" role="button" aria-haspopup="true" aria-expanded="false" data-id="' + categoria.id + '">';
                    html += categoria.nombre;
                    html += '</a>';
                }

                if (categoria.subcategorias && categoria.subcategorias.length > 0) {
                    html += '<ul id="dropdown-menu" class="dropdown-menu text-dark rounded" style="background-color: rgb(230,230,230); box-shadow:none;border:none;">';
                    categoria.subcategorias.forEach(function(subcategoria) {
                        html += generateCategoryHtml(subcategoria, true);
                    });
                    html += '</ul>';
                }
                html += '</li>';
                return html;
            }

            categorias.forEach(function(categoria) {
                navbarHtml += generateCategoryHtml(categoria, false);
            });

            $('#categorias').html(navbarHtml);

            function handleItemClick(event) {
                event.preventDefault();
                let $this = $(this);
                let id_categoria = $this.data('id');
                let nombre_categoria = $this.text();

                // Cambiar la URL
                window.location.href = './tienda.php?nombre=' + encodeURIComponent(nombre_categoria) + '&id=' + encodeURIComponent(id_categoria);
            }

            function handleMouseEnter() {
                $(this).children('.dropdown-menu').stop(true, true).slideDown();
            }
            
            function handleMouseLeave() {
                $(this).children('.dropdown-menu').stop(true, true).slideUp();
            }
            
            $('#categorias').off('click', '.categoria, .subcategoria', handleItemClick);
            $('#categorias').off('mouseenter', '.nav-item', handleMouseEnter);
            $('#categorias').off('mouseleave', '.nav-item', handleMouseLeave);

            $('#categorias').on('click', '.categoria, .subcategoria', handleItemClick);
            $('#categorias').on('mouseenter', '.nav-item', handleMouseEnter);
            $('#categorias').on('mouseleave', '.nav-item', handleMouseLeave);
        },
        error: function() {
            swalWithBootstrapButtons.fire({
                title: "Error!",
                text: "Error al realizar la solicitud AJAX",
                icon: "error"
            });
            //alert('Error al realizar la solicitud AJAX');
        }
    });
}
