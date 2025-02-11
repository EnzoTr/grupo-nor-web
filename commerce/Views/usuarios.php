<?php
    ob_start(); // Inicia el búfer de salida
    include '../Util/Config/config.php';
    // Verificar si el usuario está logueado
    $allowed_roles = ['Administrador'];
    include_once 'Layouts/General/header.php'; // Mover esta línea después de las llamadas a header()
    ob_end_flush(); // Vacía (envía) el búfer de salida
?>

<section class="content">
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-12">
                <!-- Card -->
                <div class="card mb-0" style="box-shadow: none;border:none;">
                    <!-- Card Header -->
                    <div class="card-header mb-0" style="box-shadow: none;border:none;">
                        <h1 class="card-title mb-0" style="font-weight: 700; font-size:1.75em; ">Usuarios</h1>
                        <!-- Botón para agregar empleado -->
                        <div class="d-flex justify-content-end mb-3" style="gap: .5em;">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addEmployeeModal">
                                Agregar Empleado
                            </button>
                            <button id="showClients" class="btn btn-primary">Mostrar Clientes</button>
                            <button id="showEmployees" class="btn btn-secondary">Mostrar Empleados</button>
                        </div>
                        <!-- Botones para cambiar entre tablas -->
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div id="TablesDiv" class="table-responsive">
                            <!-- Tabla de clientes -->
                            <table id="clientsTable" class="table mb-4 pb-4">
                                <thead>
                                    <tr>
                                        <th>DNI</th>
                                        <th>Usuario</th>               
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Email</th>
                                        <th>Telefono</th>
                                        <th>Direccion</th>
                                        <th>Referencia</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Datos de los clientes -->
                                </tbody>
                            </table>
                            <!-- Tabla de empleados -->
                            <table id="employeesTable" class="table border-0">
                                <thead>
                                    <tr>
                                        <th>DNI</th>
                                        <th>Usuario</th>               
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Email</th>
                                        <th>Telefono</th>
                                        <th>Direccion</th>
                                        <th>Referencia</th>
                                        <th>Tipo de empleado</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Datos de los empleados -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para agregar empleado -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeModalLabel">Agregar Empleado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para agregar empleado -->
                    <form id="addEmployeeForm">
                        <!-- Campos del formulario -->
                        <div class="form-group">
                            <label for="user">Usuario</label>
                            <input type="text" class="form-control" id="user" name="user" required>
                        </div>
                        <div class="form-group">
                            <label for="dni">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="tipoEmpleado">Tipo de Empleado</label>
                            <select class="form-control" id="tipoEmpleado" name="tipoEmpleado" required>
                                <option value="1">Administrador</option>
                                <option value="4">Empleado</option>
                                <option value="3">Repositor</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Empleado</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeModalLabel">Editar Empleado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para agregar empleado -->
                    <form id="editEmployeeForm">
                        <!-- Campos del formulario -->
                        <input type="hidden" id="id_usuario" name="id_usuario">
                        <div class="form-group">
                            <label for="user">Usuario</label>
                            <input type="text" class="form-control" id="user" name="user" required>
                        </div>
                        <div class="form-group">
                            <label for="dni">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        <div class="form-group">
                            <label for="referencia">Referencia</label>
                            <input type="text" class="form-control" id="referencia" name="referencia" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div id="selectDiv" class="form-group">
                            <label for="tipoEmpleado">Tipo de Empleado</label>
                            <select class="form-control" id="tipoEmpleado" name="tipoEmpleado">
                                <option value="">Seleccione un tipo de empleado</option>
                                <option value="1">Administrador</option>
                                <option value="4">Empleado</option>
                                <option value="3">Repositor</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Aceptar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section> 

<?php
    include_once 'Layouts/General/footer.php';
?>
<script src="./usuarios.js" type="module"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>