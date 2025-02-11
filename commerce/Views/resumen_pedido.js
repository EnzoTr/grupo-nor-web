import { verificar_sesion } from "./sesion.js";

$(document).ready(function() {
    verificar_sesion();
    var id_pedido = new URLSearchParams(window.location.search).get('id');
    if (id_pedido) {
        obtenerDetallesPedido(id_pedido);
    } else {
        alert('No se ha proporcionado un ID de pedido');
    }
});

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
    confirmButton: "btn btn-success m-3",
    cancelButton: "btn btn-danger"
    },
    buttonsStyling: false
});

function obtenerDetallesPedido($id_pedido) {
    $.ajax({
        url: '../Controllers/Detalle_PedidoController.php',
        method: 'POST',
        data: {
            funcion: 'obtener_Detalle_Pedido_Id',
            id_pedido: $id_pedido
        },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.length > 0) {

                document.getElementById('pedido-id').textContent = $id_pedido;

                // Rellenar la lista de productos
                var listaProductos = document.getElementById('lista-productos');
                // Limpiar la lista de productos existente
                listaProductos.innerHTML = '';
                data.forEach(function(producto) {
                    var productoDiv = document.createElement('div');
                    productoDiv.className = 'producto-item'; // Añadir una clase para estilizar

                    // Crear un contenedor para los detalles del producto
                    var detallesProducto = document.createElement('div');
                    detallesProducto.className = 'detalles-producto';

                    // Agregar la foto al inicio
                    var img = document.createElement('img');
                    if (producto.nombre_producto === 'Tinglado Personalizado') {
                        producto.producto_foto = '../Util/Assets/tinglado3.jpeg';
                    }
                    img.src = producto.producto_foto;
                    img.className = 'producto-img';
                    detallesProducto.appendChild(img);

                    // Agregar los detalles del producto
                    var detallesTexto = document.createElement('div');
                    detallesTexto.className = 'detalles-texto';

                    var nombreProducto = document.createElement('p');
                    nombreProducto.textContent = 'Nombre: ' + producto.nombre_producto;
                    detallesTexto.appendChild(nombreProducto);

                    var precioUnitario = document.createElement('p');
                    precioUnitario.textContent = 'Precio unitario: ' + producto.precio_unitario;
                    detallesTexto.appendChild(precioUnitario);

                    var cantidad = document.createElement('p');
                    cantidad.textContent = 'Cantidad: ' + producto.cantidad;
                    detallesTexto.appendChild(cantidad);

                    var subtotal = document.createElement('p');
                    subtotal.textContent = 'Subtotal: ' + producto.precio_unitario * producto.cantidad;
                    detallesTexto.appendChild(subtotal);

                    // Si el producto es un tinglado, añadir tipo de techo y color
                    if (producto.tipo_techo && producto.color) {
                        var tipoTecho = document.createElement('p');
                        tipoTecho.textContent = 'Tipo de techo: ' + producto.tipo_techo;
                        detallesTexto.appendChild(tipoTecho);

                        var color = document.createElement('p');
                        color.textContent = 'Color: ' + producto.color;
                        detallesTexto.appendChild(color);
                    }

                    detallesProducto.appendChild(detallesTexto);
                    productoDiv.appendChild(detallesProducto);
                    listaProductos.appendChild(productoDiv);
                });
            } else {
                swalWithBootstrapButtons.fire({
                    title: "Error!",
                    text: "Error al obtener los detalles del pedido",
                    icon: "error"
                });
                //alert('Error al obtener los detalles del pedido');
            }
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