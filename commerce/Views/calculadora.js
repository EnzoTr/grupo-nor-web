import { verificar_sesion } from "./sesion.js";

$(document).ready(function() {
    verificar_sesion();
    obtenerCategorias();
});

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

document.getElementById('largo').addEventListener('input', function() {
    validarMedida('largo', 5, 30);
    document.getElementById('tingladoForm').reportValidity();
    calcular();
});

document.getElementById('ancho').addEventListener('input', function() {
    validarMedida('ancho', 5, 30);
    document.getElementById('tingladoForm').reportValidity();
    calcular();
});

function validarMedida(idCampo, minimo, maximo) {
    var campo = document.getElementById(idCampo);
    var valor = parseFloat(campo.value);

    if (isNaN(valor) || valor < minimo || valor > maximo) {
        campo.setCustomValidity('El valor debe estar entre ' + minimo + ' y ' + maximo + '.');
    } else {
        campo.setCustomValidity('');
    }
}

function calcular() {
    var largo = document.getElementById('largo').value;
    var ancho = document.getElementById('ancho').value;
    var precioBase = document.getElementById('precioBase').value;
    var precio_mayor_12 = document.getElementById('precioMayor12').value;
    var precio_mayor_15 = document.getElementById('precioMayor15').value;

    var volumen = largo * ancho;
    var precio;

    // Definir los precios según el ancho del tinglado
    if (ancho <= 12) {
        precio = precioBase;
    } else if (ancho <= 15) {
        precio = precio_mayor_12;
    } else {
        precio = precio_mayor_15;
    }

    var resultado = volumen * precio;
    document.getElementById('resultado').textContent = '$' + resultado;
}

$('#tingladoForm').on('submit', function(e) {
    e.preventDefault();
    var largo = $('#largo').val();
    var ancho = $('#ancho').val();
    var tipo_techo = $('#tipoTecho').val();
    var color = $('#color').val();
    var precio = $('#resultado').text().trim().substring(1);

    $.ajax({
        url: '../Controllers/ProductoController.php',
        method: 'POST',
        data: {
            funcion: 'crear_tinglado',
            largo: largo,
            ancho: ancho,
            tipo_techo: tipo_techo,
            color: color,
            precio: precio
        },
        success: function(response) {
            var data = JSON.parse(response);
            var id_producto = data.producto_id;
            agregar_tinglado(id_producto, precio);
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
});

function agregar_tinglado(id_producto, precio) {
    $.ajax({
        url: '../Controllers/Detalle_PedidoController.php',
        method: 'POST',
        data: {
            funcion: 'agregar_carrito_tinglado',
            id_producto: id_producto,
            cantidad: 1,
            precio: precio
        },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === 'success') {
                swalWithBootstrapButtons.fire({
                    title: "Producto agregado!",
                    text: "Tinglado agregado al carrito",
                    icon: "success"
                });
                //alert(data.message);
            } else {
                swalWithBootstrapButtons.fire({
                    title: "Error!",
                    text: "Hubo un error al agregar el tinglado al carrito",
                    icon: "error"
                });
                //alert('Error al agregar el tinglado al carrito');
            }
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