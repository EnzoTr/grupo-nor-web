import { verificar_sesion } from "./sesion.js";

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
    confirmButton: "btn btn-success m-3",
    cancelButton: "btn btn-danger"
    },
    buttonsStyling: false
});

function agregar_carrito(id_producto, cantidad, precio) {
    $.ajax({
        url: '../Controllers/Detalle_PedidoController.php',
        method: 'POST',
        data: {
            funcion: 'agregar_carrito',
            id_producto: id_producto,
            cantidad: cantidad,
            precio: precio
        },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status === 'success') {
                swalWithBootstrapButtons.fire({
                    title: "Producto agregado!",
                    text: "Tu producto fue agregado al carrito",
                    icon: "success"
                });
            } else {
                swalWithBootstrapButtons.fire({
                    title: "Error!",
                    text: "Hubo un error al agregar el producto al carrito",
                    icon: "error"
                });
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
    var funcion;
    verificar_sesion();
    obtenerCategorias();
    verificar_productos();

    async function verificar_productos(){
        funcion = "verificar_productos";
        let data = await fetch('../Controllers/ProductoController.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'funcion=' + funcion
        })
        if(data.ok){
            let response = await data.text();
            try {
                let producto = JSON.parse(response);
                let template ='';
                let template2 ='';
                if (producto.imagenes.length > 0) {
                    template += `
                        <div "></div>
                        <div class="col-12">
                            <img class="img-fluid rounded" style="width:100%; height: 35em; object-fit: cover;" id="imagen_principal" src="${producto.foto}">

                        </div>
                        <div class="col-12 product-image-thumbs">
                        <button prod_img="${producto.foto}" class="imagen_pasarelas product-image-thumb">
                            <img src="${producto.foto}">
                        </button>
                        `;
                        producto.imagenes.forEach(imagen => {
                            template +=`
                                <button prod_img="${imagen.nombre}" class="imagen_pasarelas product-image-thumb">
                                    <img src="${imagen.nombre}">
                                </button>
                            ` ;
                        });
                    template += `
                        </div>
                    `;
                }
                else{
                    template += `
                        <div class="col-12">
                            <img class="product-image img-fluid" id="imagen_principal" src="${producto.nombre_categoria === 'Tinglados' ? '../Util/Assets/tinglado3.jpeg' : producto.foto}">
                        </div>
                        `;
                }

                for (let i = 1; i <= producto.stock; i++) {
                    template2 += `
                        <option value='${i}'>${i}</option>
                    `;
                }

                let template3 = ` 
                <div class="input-group mb-3 card-footer">`;

                if (producto.nombre_categoria === "Tinglados") {
                    let tipoTechoDiv = document.createElement('div');
                    tipoTechoDiv.classList.add('text-center');
                    tipoTechoDiv.innerHTML = `
                        <label for="tipoTecho" class="text-dark text-center">Tipo de techo:</label>
                        <select id="tipoTecho" class="custom-select text-dark text-center"> <!-- Agregar la clase text-center aquí -->
                            <option value="a_dos_aguas" class="text-dark">A Dos Aguas</option>
                            <option value="plano" class="text-dark">Plano</option>
                            <option value="parabolico" class="text-dark">Parabólico</option>
                        </select>
                    `;
                    document.getElementById('product_options').appendChild(tipoTechoDiv);
                
                    let colorDiv = document.createElement('div');
                    colorDiv.classList.add('text-center');
                    colorDiv.innerHTML = `
                        <label for="color" class="text-dark text-center">Color:</label>
                        <select id="color" class="custom-select text-dark text-center"> <!-- Agregar la clase text-center aquí -->
                            <option value="gris_metalico" class="text-dark">Gris Metálico</option>
                            <option value="azul" class="text-dark">Azul</option>
                        </select>
                    `;
                    document.getElementById('product_options').appendChild(colorDiv);
                }

                $('#imagenes').html(template);
                $('#id_producto').text(producto.nombre + " #" + producto.id);
                $('#precio_producto').text("$ "+producto.precio);
                $('#product-description-content').text(producto.descripcion);
                $('#nombre_producto').text(producto.nombre);

                $('#product_quantity').attr('max', producto.stock);
                
                if (producto.stock < 20) {
                    $('#warningStock').text('¡Quedan '+ producto.stock + ' unidades!');
                }

            } catch (error) {
                console.error(error);
                console.log("La respuesta del servidor no es un JSON válido:", response);
                if(response == 'error'){
                    location.href = './tienda.php'; 
                }

            }
        }
        else{
            Swal.fire({
                icon: "error",
                title: data.statusText,
                text: "Hubo conflicto de codigo: " + data.status,
            });
        }

        
    }

    $(document).on('click', '.imagen_pasarelas', (e)=>{
        let elemento = $(this)[0].activeElement;
        let img = $(elemento).attr('prod_img');
        $('#imagen_principal').attr('src', img);
    });

    
});

document.querySelector('.agregar-carrito').addEventListener('click', () => {
    const id_producto = document.getElementById('id_producto').textContent;
    const cantidad = document.getElementById('product_quantity').value;
    const precio = document.getElementById('precio_producto').textContent.trim().substring(2);

    agregar_carrito(id_producto, cantidad, precio);
});
