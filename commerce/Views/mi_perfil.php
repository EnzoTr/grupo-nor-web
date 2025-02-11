<?php
    ob_start(); // Inicia el búfer de salida
    include '../Util/Config/config.php';
    // Specify the allowed roles for this page
    $allowed_roles = ['Administrador', 'Repositor', 'Cliente', 'Empleado'];

    include_once 'Layouts/General/header.php'; // Mover esta línea después de las llamadas a header()
    ob_end_flush(); // Vacía (envía) el búfer de salida
?>

<!-- Modal para cambiar contraseña -->
<div class="modal fade" id="modal_contra" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cambiar contraseña</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-contra">

          <div class="form-group">
            <label for="pass_old">Ingrese su contraseña actual</label>
            <input type="password" name="pass_old" class="form-control" id="pass_old" placeholder="Ingrese contraseña">
          </div>
          <div class="form-group">
            <label for="pass_new">Ingrese su nueva contraseña</label>
            <input type="password" name="pass_new" class="form-control" id="pass_new"
              placeholder="Ingrese contraseña nueva">
          </div>
          <div class="form-group">
            <label for="pass_repeat">Repita su nueva contraseña</label>
            <input type="password" name="pass_repeat" class="form-control" id="pass_repeat"
              placeholder="Repita su contraseña nueva">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal datos personales-->
<div class="modal fade" id="modal_datos" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar datos personales</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-datos" enctype="multipart/form-data">

          <div class="form-group">
            <label for="nombres_mod">Nombre</label>
            <input type="text" name="nombres_mod" class="form-control" id="nombres_mod" placeholder="Ingrese su nombre">
          </div>
          <div class="form-group">
            <label for="apellidos_mod">Apellido</label>
            <input type="text" name="apellidos_mod" class="form-control" id="apellidos_mod"
              placeholder="Ingrese su apellido">
          </div>
          <div class="form-group">
            <label for="dni_mod">DNI</label>
            <input type="text" name="dni_mod" class="form-control" id="dni_mod" placeholder="Ingrese su dni">
          </div>
          <div class="form-group">
            <label for="email_mod">Email</label>
            <input type="text" name="email_mod" class="form-control" id="email_mod" placeholder="Ingrese su email">
          </div>
          <div class="form-group">
            <label for="telefono_mod">Telefono</label>
            <input type="text" name="telefono_mod" class="form-control" id="telefono_mod"
              placeholder="Ingrese su telefono">
          </div>
          <div class="form-group">
            <label for="exampleInputFile">Avatar</label>
            <div class="input-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="avatar_mod" id="avatar_mod">
                <label class="custom-file-label" for="exampleInputFile">Seleccione una imagen como avatar</label>
              </div>
            </div>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal Direcciones-->
<div class="modal fade" id="modal_direcciones" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar direccion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-direccion">
          <div class="form-group">
            <label>Provincia: </label>
            <select id="provincia" class="form-control" style="width:100%" required></select>
          </div>
          <div class="form-group">
            <label>Localidad: </label>
            <select id="localidad" class="form-control" style="width:100%" required></select>
          </div>
          <div class="form-group">
            <label>Direccion: </label>
            <input id="direccion" class="form-control" placeholder="Ingrese su direccion" required>
          </div>
          <div class="form-group">
            <label>Referencia: </label>
            <input id="referencia" class="form-control" placeholder="Ingrese alguna referencia">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
      </form>
    </div>
  </div>
</div>


<title>Mi perfil</title>
<section class="content">
  <div class="container-fluid">
    <div class="col-md-3">

      <div class="card card-widget widget-user">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header bg-info">
          <h3 id="username" class="widget-user-username"></h3>
          <h5 id="tipo_usuario" class="widget-user-desc"></h5>
        </div>
        <div class="widget-user-image">
          <img id="avatar_perfil" class="img-circle elevation-2" src="" alt="User Avatar">
        </div>
        <div class="card-footer">
          <!-- /.row -->
        </div>
      </div>
      <!-- carts sobre mi -->
      <div class="card card-light d-flex flex-fill">
        <div class="card-header text-muted border-bottom-0">
          <strong>Mis datos personales</strong>
          <div class="card-tools">
            <button type="button" class="editar_datos btn btn-tool" data-toggle="modal" data-target="#modal_datos">
              <i class="fas fa-pencil-alt"></i>
            </button>
          </div>
        </div>
        <div class="card-body pt-0 mt-3">
          <div class="row">
            <div class="col-8">
              <h2 id="nombres" class="lead"><b></b></h2>
              <ul class="ml-4 mb-0 fa-ul text-muted">
                <li class="small"><span class="fa-li"><i class="fas fa-address-card"></i></span>DNI: <span
                    id="dni"></span></li>
                <li class="small"><span class="fa-li"><i class="fas fa-at"></i></span>EMAIL: <span id="email"></span>
                </li>
                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span>TELEFONO: <span
                    id="telefono"></span></li>
              </ul>
            </div>
            <div class="col-4 text-center">
              <img src="../Util/Img/datospersonales.png" alt="user-avatar" class="img-circle img-fluid">
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button class="btn btn-warning btn-block" data-toggle="modal" data-target="#modal_contra">Cambiar
            contraseña</button>
        </div>
      </div>

      <div class="card card-light d-flex flex-fill">
        <div class="card-header text-muted border-bottom-0">
          <strong>Mis direcciones de envio</strong>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#modal_direcciones">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div id="direcciones" class="card-body pt-0 mt-3">
        </div>
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div><!-- /.container-fluid -->
</section>
<?php
    include_once 'Layouts/General/footer.php';
?>
<script src="mi_perfil.js" type="module"></script>