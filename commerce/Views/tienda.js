import { verificar_sesion } from "./sesion.js";

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
    confirmButton: "btn btn-success m-3",
    cancelButton: "btn btn-danger"
    },
    buttonsStyling: false
});

$(document).ready(function() {
    var funcion;
    let limit = 20;

    verificar_sesion();
    obtenerCategorias();

    let urlParams = new URLSearchParams(window.location.search);
    let id_categoria = urlParams.get('id');
    let searchValue = urlParams.get('search');
    let sortValue = urlParams.get('sort') || 'mas_vendido';
    llenar_productos(id_categoria, sortValue, searchValue);

    async function llenar_productos(id_categoria = null, sortValue = null, searchValue = null){
        funcion = "llenar_productos";
        let body = 'funcion=' + funcion + '&limit=' + limit;
        let urlSegments = window.location.href.split('/');
        let urlBase;

        if (urlSegments[urlSegments.length - 2] === 'tienda.php') {
            // If the current URL does not contain a category
            urlBase = urlSegments.slice(0, -3).join('/');
        } else {
            // If the current URL contains a category
            urlBase = urlSegments.slice(0, -2).join('/');
        }

        if (id_categoria !== null) {
            body += '&id_categoria=' + id_categoria;
        }

        if (sortValue !== null) {
            body += '&sortValue=' + sortValue;
        }

        if (searchValue !== null) {
            body += '&searchValue=' + searchValue;
        }

        let url = `${urlBase}/Controllers/ProductoController.php`;
        let data = await fetch(url, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: body
        })
        if(data.ok){
            let response = await data.text();
            try {
                let productos = JSON.parse(response);
                let template = '';
                productos.forEach(producto => {
                    // Si la categoría no es null, agregarla a la URL de la imagen
                    //let fotoUrl = id_categoria !== null ? `../${producto.foto}` : producto.foto;
                    template+= ` 
                    <div class="col-sm-2">
                        <div class="card card-product" onclick="location.href='../Views/descripcion.php?name=${encodeURIComponent(producto.nombre)}&id=${encodeURIComponent(producto.id)}'">
                            <div class="card-body">
                                <div class="row">
                                <div class="col-sm-12 rounded mb-2">
                                    <img src="${producto.foto}" alt="perfil" class="img-fluid rounded" style="width: 100%; height:10em; object-fit:cover;">
                                </div>
                                <div class="col-sm-12" style="gap:0;justify-content:center;align-items:center;">
                                    <span class="card-title float-left text-dark fw-bold fs-4 mb-0" style="font-weight:500; font-size:1.25em; margin-bottom:0;">${producto.nombre}</span></br></br>
                                    <!--<a href="../Views/descripcion.php?name=${encodeURIComponent(producto.nombre)}&id=${encodeURIComponent(producto.id)}" class="float-left descripcion_producto text-dark mb-4" style="font-size: .75em">Descripcion del producto</a></br></br>-->
                                    <h4 class="mb-0 float-left text-info " >$ ${producto.precio}</h4></br></br>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    `;
                });
                $('#productos').html(template);

                if (productos.length < limit) {
                    document.getElementById('loadMoreButton').style.display = 'none';
                } else {
                    document.getElementById('loadMoreButton').style.display = 'block';
                }

            } catch (error) {
                console.error(error);
                console.log(response);
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

    document.getElementById('loadMoreButton').addEventListener('click', function() {
        limit += 20;
        sortValue = document.getElementById('sortSelect').value;
        var searchInput = document.getElementById('inputSearch');
        var searchValue = searchInput ? searchInput.value : null;
        llenar_productos(id_categoria, sortValue, searchValue);
    });

    document.getElementById('sortSelect').addEventListener('change', function() {
        var searchInput = document.getElementById('inputSearch');
        var searchValue = searchInput ? searchInput.value : null;
        llenar_productos(id_categoria, this.value, searchValue);
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
                    id_categoria = $this.data('id');
                    let nombre_categoria = $this.text();
                    limit = 20; // Resetear el límite
    
                    // Cambiar la URL
                    let base_url = window.location.origin + window.location.pathname;
                    let nuevaUrl = new URL(base_url);
                    nuevaUrl.searchParams.set('nombre', nombre_categoria);
                    nuevaUrl.searchParams.set('id', id_categoria);
                    history.pushState({id_categoria: id_categoria}, '', nuevaUrl.toString());
                    llenar_productos(id_categoria, 'mas_vendido');
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

    urlParams = new URLSearchParams(window.location.search);
    id_categoria = urlParams.get('id');
    if(id_categoria){
        searchValue = $(this).find('input[name="search"]').val();
        sortValue = $('#sortSelect').val();
        llenar_productos(id_categoria, sortValue, searchValue);
    }

    // Add event listener to the search form
    $('#searchForm').on('submit', function(e) {
        // Prevent the form from submitting normally
        e.preventDefault();

        // Get the search value
        let searchValue = $(this).find('input[name="search"]').val();
        sortValue = $('#sortSelect').val();
        
        // Redirect to tienda.php with the search value as a query parameter
        window.location.href = `tienda.php?search=${encodeURIComponent(searchValue)}&sort=${encodeURIComponent(sortValue)}`;
    });

});