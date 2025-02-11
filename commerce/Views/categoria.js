import { verificar_sesion } from "./sesion.js";

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
    confirmButton: "btn btn-success m-3",
    cancelButton: "btn btn-danger"
    },
    buttonsStyling: false
});
let categorias = [];

$(document).ready(function() {
    bsCustomFileInput.init();
    verificar_sesion();

    obtenerCategorias();

    function obtenerCategorias() {
        $.ajax({
            url: '../Controllers/CategoriaController.php',
            type: 'POST',
            data: { funcion: 'obtener_categorias'},
            success: function(response) {
                categorias = JSON.parse(response);
                let tbody = '';
                $('#categoryTable').DataTable().destroy();
                categorias.forEach(categoria => {
                    tbody += `
                        <tr class="text-dark">
                            <td class="text-dark">${categoria.nombre ? categoria.nombre : 'N/A'}</td>
                            <td class="text-dark">${categoria.nombre_padre ? categoria.nombre_padre : 'N/A'}</td>
                            <td class="text-dark">${categoria.descripcion ? categoria.descripcion : 'N/A'}</td>
                            <td class="text-dark">${categoria.fecha_creacion ? new Date(new Date(categoria.fecha_creacion).getTime() + new Date().getTimezoneOffset()*60*1000).toLocaleDateString() : 'N/A'}</td>
                            <td class="status-cell text-dark" data-order="${categoria.estado == 'A' ? 1 : 0}">
                                <button class="btn btn-sm ${categoria.estado == 'A' ? 'btn-success' : 'btn-secondary'} toggle-status-button" data-id="${categoria.id}" data-status="${categoria.estado}">
                                    <i class="fas ${categoria.estado == 'A' ? 'fa-toggle-on' : 'fa-toggle-off'}"></i>
                                </button>
                            </td>
                            <td class="text-dark">
                                <button class="btn btn-primary btn-sm btn-edit" data-id="${categoria.id}" data-toggle="modal" data-target="#editCategoryModal"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="${categoria.id}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });

                $('#categoryTable tbody').html(tbody);
                $('#categoryTable').DataTable();

            },
            error: function(error) {
                console.error(error);
            }
        });
    }

    // Controlador de eventos de clic para el botón de edición
    $(document).on('click', '.btn-edit', function() {
        const id = Number($(this).data('id'));
    
        // Busca la categoría en la lista de categorías
        const categoria = categorias.find(categoria => Number(categoria.id) === id);
    
        // Llena los campos del formulario con los datos de la categoría
        $('#editCategoryForm #id').val(categoria.id);
        $('#editCategoryForm #nombre').val(categoria.nombre);
        // Si id_padre es null, establece un valor predeterminado
        const id_padre = categoria.id_padre !== null ? categoria.id_padre : '';
        cargarCategorias('#editCategoryForm #id_padre', categoria.id, function() {
            $('#editCategoryForm #id_padre').val(id_padre);
        });
        $('#editCategoryForm #descripcion').val(categoria.descripcion);
    
        // Muestra el modal
        $('#editCategoryModal').modal('show');
    });

    // Controlador de eventos de clic para el botón de estado
    $(document).on('click', '.toggle-status-button', function() {
        const id = $(this).data('id');
        const currentStatus = $(this).data('status');
        const funcion = currentStatus === 'A' ? 'desactivar_categoria' : 'activar_categoria';
    
        $.ajax({
            url: '../Controllers/CategoriaController.php',
            type: 'POST',
            data: { funcion: funcion, id: id },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.status === 'success') {
                    swalWithBootstrapButtons.fire({
                        title: "Exito!",
                        text: "Se cambio el estado de la categoria",
                        icon: "success"
                    });
                    //alert(data.message);
                    obtenerCategorias();
                } else {
                    swalWithBootstrapButtons.fire({
                        title: "Error!",
                        text: "Hubo un error al cambiar el estado de la categoría",
                        icon: "error"
                    });
                    //alert('Hubo un error al cambiar el estado de la categoría');
                }
            },
            error: function(error) {
                console.error(error);
            }
        });
    });

    // Controlador de eventos de clic para el botón de eliminación
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
    
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Estás seguro de que quieres eliminar esta categoría?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../Controllers/CategoriaController.php',
                    type: 'POST',
                    data: { funcion: 'eliminar_categoria', id: id },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.status === 'success') {
                            swalWithBootstrapButtons.fire({
                                title: "Categoria eliminada!",
                                text: "Tu categoria fue eliminada con exito",
                                icon: "success"
                            });
                            obtenerCategorias();
                        } else {
                            swalWithBootstrapButtons.fire({
                                title: "Error!",
                                text: "Hubo un error al eliminar la categoría",
                                icon: "error"
                            });
                        }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }
        });
    });

    function cargarCategorias(selectId, excludeId, successCallback) {
        $.ajax({
            url: '../Controllers/CategoriaController.php',
            type: 'POST',
            data: { funcion: 'obtener_categorias' },
            success: function(response) {
                const categorias = JSON.parse(response);
                let options = '<option value="">Ninguna</option>'; // Opción base
                categorias.forEach(categoria => {
                    // Si la categoría actual es la misma que la que se excluye, no la agregue como opción
                    if (categoria.id !== excludeId) {
                        options += `<option value="${categoria.id}">${categoria.nombre}</option>`;
                    }
                });
                $(selectId).html(options);
                if (successCallback) {
                    successCallback();
                }
            },
            error: function(error) {
                console.error(error);
            }
        });
    }

    // Llama a la función cuando se muestra el modal de agregar producto
    $('#addCategoryModal').on('shown.bs.modal', function () {
        cargarCategorias('#addCategoryForm #id_padre', null, null);
    });

    $('#addCategoryForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('funcion', 'agregar_categoria');

        $.ajax({
            url: '../Controllers/CategoriaController.php',
            type: 'POST',
            data: formData,
            processData: false, // Indica a jQuery que no procese los datos
            contentType: false, // Indica a jQuery que no establezca el tipo de contenido de la solicitud
            success: function(response) {
                const data = JSON.parse(response);
                if (data.status === 'success') {
                    swalWithBootstrapButtons.fire({
                        title: "Exito!",
                        text: "Categoria agregada",
                        icon: "success"
                    });
                    //alert(data.message);
                    $('#addCategoryModal').modal('hide');
                    obtenerCategorias();
                } else {
                    swalWithBootstrapButtons.fire({
                        title: "Error!",
                        text: "Hubo un error al agregar la categoría",
                        icon: "error"
                    });
                    //alert('Hubo un error al agregar la categoría');
                }
            },
            error: function(error) {
                console.error(error);
            }
        });
    });

    $('#editCategoryForm').on('submit', function(e) {
        e.preventDefault();
    
        const formData = new FormData(this);
        formData.append('funcion', 'editar_categoria');
    
        $.ajax({
            url: '../Controllers/CategoriaController.php',
            type: 'POST',
            data: formData,
            processData: false, // Indica a jQuery que no procese los datos
            contentType: false, // Indica a jQuery que no establezca el tipo de contenido de la solicitud
            success: function(response) {
                const data = JSON.parse(response);
                if (data.status === 'success') {
                    swalWithBootstrapButtons.fire({
                        title: "Exito!",
                        text: "Categoria actualizada",
                        icon: "success"
                    });
                    //alert(data.message);
                    $('#editCategoryModal').modal('hide');
                    obtenerCategorias();
                } else {
                    swalWithBootstrapButtons.fire({
                        title: "Error!",
                        text: "Hubo un error al actualizar la categoría",
                        icon: "error"
                    });
                    //alert('Hubo un error al actualizar la categoría');
                }
            },
            error: function(error) {
                console.error(error);
            }
        });
    });

});