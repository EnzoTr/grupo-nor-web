<?php
    ob_start(); // Inicia el búfer de salida
    include '../Util/Config/config.php';

    // Specify the allowed roles for this page
    $allowed_roles = ['Administrador', 'Repositor'];

    include_once 'Layouts/General/header.php'; // Mover esta línea después de las llamadas a header()
    ob_end_flush(); // Vacía (envía) el búfer de salida
?>

<section class="content">
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="box-shadow: none;border:none;">
                    <div class="card-header mb-0" style="box-shadow: none;border:none;">
                        <h1 class="card-title mb-0" style="font-weight: 700; font-size:1.75em; ">Categorías Actuales</h1>
                        <!-- Botón para abrir el modal -->
                        <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#addCategoryModal">
                                Agregar Categoría
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-5">
                            
                            
                        </div>
                        <!-- Agregar la tabla con la información de las categorías -->
                        <table id="categoryTable" class="table table-striped table-hover border-0" style="border:none;box-shadow:none;">
                            <thead>
                                <tr>
                                    <th data-columna="c.nombre"><p style="opacity:.4">Nombre</p></th>
                                    <th data-columna="cp.nombre"><p style="opacity:.4">Categoría Padre</p></th>
                                    <th data-columna="c.descripcion"><p style="opacity:.4">Descripción</p></th>
                                    <th data-columna="c.fecha_creacion"><p style="opacity:.4">Fecha de Registro</p></th>
                                    <th data-columna="c.estado"><p style="opacity:.4">Estado</p></th>
                                    <th><p style="opacity:.4">Acciones</p></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se llenará la tabla con la información de las categorías -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar categoría -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Agregar Categoría</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addCategoryForm">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="id_padre">Categoría Padre:</label>
                            <select class="form-control" id="id_padre" name="id_padre">
                                <!-- Las opciones se llenarán con JavaScript -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>
                            <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Categoría</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar categoría -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Editar Categoría</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCategoryForm">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="id_padre">Categoría Padre:</label>
                            <select class="form-control" id="id_padre" name="id_padre">
                                <!-- Las opciones se llenarán con JavaScript -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>
                            <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    include_once 'Layouts/General/footer.php';
?>
<script src="./categoria.js" type="module"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
