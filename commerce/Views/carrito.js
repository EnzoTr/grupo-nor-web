import { verificar_sesion } from "./sesion.js";

var noStock;
const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
    confirmButton: "btn btn-success m-3",
    cancelButton: "btn btn-danger"
    },
    buttonsStyling: false
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

$(document).ready(function(){
    // Lógica para inicializar el carrito
    verificar_sesion();
    obtenerCategorias();
    obtenerCarrito();
    obtenerDirecciones();
    $('#pagarButton').addClass('disabled');

    $('#pagarButton').on('click', function() {
        if (!$(this).hasClass('disabled')) {
            window.location.href = './pago.php';
        }
    });

    // Detectar cambios en el dropdown de direcciones
    $('#direccion').change(function() {
        if ($(this).val() !== '') {
            const destino = $(this).val();
            const origen = 'Sáenz Peña, Chaco'; // Cambia esto por la dirección de origen real
            calcularEnvio(origen, destino, function(envio) {
                const subtotal = parseFloat($('#subtotalPrice').text());
                const total = subtotal + envio;
                $('#shippingPrice').text(envio);
                $('#totalPrice').text(total);
            });

            // Habilitar el botón cuando se seleccione una dirección válida
            if (!noStock) {
                $('#pagarButton').removeClass('disabled');
            }
        } else {
            $('#shippingPrice').text(0);
            $('#totalPrice').text(parseFloat($('#subtotalPrice').text()));
            // Deshabilitar el botón si no hay selección
            $('#pagarButton').addClass('disabled');
        }
    }); 
});

function obtenerDirecciones() {
    $.ajax({
        url: '../Controllers/UsuarioLocalidadController.php',
        method: 'POST',
        data: {
            funcion: 'llenar_direcciones'
        },
        success: function(response) {
            var direcciones = JSON.parse(response);
            var direccionesHtml = '<option class="text-dark" value="">Selecciona una dirección</option>';
            
            for (var i = 0; i < direcciones.length; i++) {
                var direccionCompleta = direcciones[i].direccion + ', ' + direcciones[i].localidad + ', ' + direcciones[i].provincia
                direccionesHtml += '<option class="text-dark" value="' + direcciones[i].localidad + ', '+ direcciones[i].provincia + '">' + direccionCompleta + '</option>';
            }
            $('#direccion').html(direccionesHtml);
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

function obtenerCarrito() {
    $.ajax({
        url: '../Controllers/Detalle_PedidoController.php',
        method: 'POST',
        data: {
            funcion: 'obtener_carrito'
        },
        success: function(response) {
            var cartItems = JSON.parse(response);
            var cartItemsHtml = '';
            var subtotal = 0;
            var envio = 0;

            // Reiniciar cantidadCarrito al inicio
            var cantidadCarrito = 0;

            for (var i = 0; i < cartItems.length; i++) {
                cantidadCarrito += 1;
                if (cartItems[i].cantidad > cartItems[i].stock) {
                    noStock = true;
                }
                var itemSubtotal = cartItems[i].precio * cartItems[i].cantidad;
                subtotal += itemSubtotal;
                cartItemsHtml += '<tr>';
                cartItemsHtml += '<td class="text-dark"><img src="' + (cartItems[i].nombre_categoria === 'Tinglados' ? '../Util/Assets/tinglado3.jpeg' : cartItems[i].foto) + '" alt="' + cartItems[i].nombre_producto + '" style="width: 50px; height: 50px;"></td>';
                cartItemsHtml += '<td class="text-dark">' + cartItems[i].nombre_producto + '</td>';
                cartItemsHtml += '<td class="text-dark">' + cartItems[i].precio + '</td>';
                cartItemsHtml += '<td class="text-dark"><input type="number" class="form-control cantidadInput" value="' + cartItems[i].cantidad + '" min="1" max="' + cartItems[i].stock + '" data-id="' + cartItems[i].id + '"></td>';
                cartItemsHtml += '<td class="text-dark">' + itemSubtotal + '</td>';
                if (cartItems[i].cantidad > cartItems[i].stock) {
                    cartItemsHtml += '<td><i class="fas fa-exclamation-triangle text-warning" title="La cantidad en el carrito es mayor que el stock disponible"></i></td>';
                } else {
                    cartItemsHtml += '<td></td>';
                }
                cartItemsHtml += '<td><button class="btn btn-danger delete-button" data-id="' + cartItems[i].id + '"><i class="fas fa-trash-alt"></i></button></td>';
                cartItemsHtml += '</tr>';
            }
            console.log(cantidadCarrito);
            if (cantidadCarrito === 0) {
                document.getElementById('direccion').setAttribute('disabled', 'disabled');
                document.getElementById('pagarButton').setAttribute('disabled', 'disabled');
            } else {
                document.getElementById('direccion').removeAttribute('disabled');
            }

            $('#cart-items').html(cartItemsHtml);
            var total = subtotal + envio;

            document.getElementById('subtotalPrice').innerText = subtotal.toFixed(2);
            document.getElementById('shippingPrice').innerText = envio.toFixed(2);
            document.getElementById('totalPrice').innerText = total.toFixed(2);

            // Agregar eventos a los botones de eliminar y agregar cantidad
            document.querySelectorAll('.cantidadInput').forEach(input => {
                input.addEventListener('change', function() {
                    var input = this;
                    var id = input.getAttribute('data-id');
                    if (this.value <= this.max) {
                        noStock = false;
                        if ($('#direccion').val() !== '') {
                            setTimeout(function() {
                                $('#pagarButton').removeClass('disabled');
                            }, 2000); // 2000 milisegundos = 2 segundos
                        }
                    } else {
                        noStock = true;
                    }
                    cambiarCantidad(id, input.value);
                });
            });

            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function() {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¿Estás seguro de que quieres eliminar este producto del carrito?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            eliminarDelCarrito(this.dataset.id);
                            // Actualizar la tabla del carrito después de eliminar un producto
                            setTimeout(obtenerCarrito, 100); // Espera 500 milisegundos (ajusta según sea necesario)
                        }
                    })
                });
            });
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

var cambiarCantidadTimeout;
function cambiarCantidad(id, cantidad) {
    // Cancelar el temporizador anterior si existe
    if (cambiarCantidadTimeout) {
        clearTimeout(cambiarCantidadTimeout);
    }

    // Establecer un nuevo temporizador
    cambiarCantidadTimeout = setTimeout(function() {
        $.ajax({
            url: '../Controllers/Detalle_PedidoController.php',
            method: 'POST',
            data: {
                funcion: 'cambiar_cantidad',
                id: id,
                cantidad: cantidad
            },
            success: function(response) {
                // Actualizar la tabla del carrito después de cambiar la cantidad
                obtenerCarrito();
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
    }, 2000); // 2000 milisegundos = 3 segundos
}

function eliminarDelCarrito(id_detalle_pedido) {
    $.ajax({
        url: '../Controllers/Detalle_PedidoController.php',
        method: 'POST',
        data: {
            funcion: 'eliminar_carrito',
            id_detalle_pedido: id_detalle_pedido
        },
        success: function(response) {
            var result = JSON.parse(response);
            if (result.status === 'success') {
                swalWithBootstrapButtons.fire({
                    title: "Producto eliminado!",
                    text: "Tu producto fue eliminado del carrito",
                    icon: "success"
                });
            } else {
                swalWithBootstrapButtons.fire({
                    title: "Error!",
                    text: "Hubo un error al eliminar el producto del carrito",
                    icon: "error"
                });
            }
        },
        error: function() {
            swalWithBootstrapButtons.fire({
                title: "Error!",
                text: "Error al realizar la solicitud AJAX'",
                icon: "error"
            });
        }
    });
}

function calcularEnvio(origen, destino, callback) {
    var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix(
        {
            origins: [origen],
            destinations: [destino],
            travelMode: 'DRIVING',
            unitSystem: google.maps.UnitSystem.METRIC,
        }, 
        function(response, status) {
            if (status !== 'OK') {
                swalWithBootstrapButtons.fire({
                    title: "Error!",
                    text: "Hubo un error al calcular el envio " + status,
                    icon: "error"
                });
                //alert('Error al calcular el envío: ' + status);
                return;
            }
            var precio_envio_km = parseFloat(document.getElementById('precio_envio_km').value);
            var distancia = response.rows[0].elements[0].distance.value / 1000; // Convertimos a kilómetros

            // Obtener todos los productos del carrito para calcular el envío total
            $.ajax({
                url: '../Controllers/Detalle_PedidoController.php',
                method: 'POST',
                data: { funcion: 'obtener_carrito' },
                success: function(response) {
                    var cartItems = JSON.parse(response);
                    var costoEnvioTotal = 0;

                    cartItems.forEach(function(item) {
                        if (item.nombre_categoria === 'Tinglados') {
                            costoEnvioTotal += distancia * precio_envio_km;
                        } else {
                            costoEnvioTotal += distancia * item.precio_envio_km;
                        }
                    });

                    var select = document.getElementById('direccion');
                    var direccionCompletaSeleccionada = select.options[select.selectedIndex].text;

                    // Enviar el costo de envío total al servidor
                    $.ajax({
                        url: 'guardar_envio.php',
                        method: 'POST',
                        data: { 
                            envio: costoEnvioTotal,
                            direccion: direccionCompletaSeleccionada
                        },
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data.status === 'success') {
                                callback(Math.trunc(costoEnvioTotal));
                            } else {
                                swalWithBootstrapButtons.fire({
                                    title: "Error!",
                                    text: "Error al obtener el costo de envío del servidor",
                                    icon: "error"
                                });
                                //alert('Error al obtener el costo de envío del servidor');
                            }
                        },
                        error: function() {  
                            swalWithBootstrapButtons.fire({
                                title: "Error!",
                                text: "Error al enviar el costo de envío al servidor",
                                icon: "error"
                            });                        
                            //alert('Error al enviar el costo de envío al servidor');
                        }
                    });
                },
                error: function() {
                    swalWithBootstrapButtons.fire({
                        title: "Error!",
                        text: "Error al enviar el costo de envío al servidor",
                        icon: "error"
                    }); 
                    //alert('Error al realizar la solicitud AJAX para obtener el carrito');
                }
            });
        }
    );
}
