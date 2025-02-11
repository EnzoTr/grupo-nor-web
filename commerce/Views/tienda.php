<?php
  $require_login = false;  // No requiere iniciar sesión
  $allowed_roles = ['Administrador', 'Repositor', 'Empleado', 'Cliente', null];

    include_once('Layouts/Tienda/header.php');
?>

    <!-- Main content -->
    <section class="content border-0 " style="border:none;">

      <!-- Default box -->
      <div class="card mt-3 mb-3 border-0" style="padding:1em; background:none; border:none; box-shadow:none;">
        <div class="card-header mb-0" style="border:none;">
          <h3 class="card-title mb-0" style="color: #505050; font-weight:600; font-size:1.5em">Productos</h3>
        </div>
        <select id="sortSelect" class="form-control " style="color: #505050; margin: 1em; background:none;">
            <option style="color: #505050" value="">Ordenar por</option>
            <option style="color: #505050" value="precio_ascendente">Precio: menor a mayor</option>
            <option style="color: #505050" value="precio_descendente">Precio: mayor a menor</option>
            <option style="color: #505050" value="nombre_ascendente">Nombre: A - Z</option>
            <option style="color: #505050" value="nombre_descendiente">Nombre: Z - A</option>
            <option style="color: #505050" value="mas_vendido">Más vendido</option>
            <option style="color: #505050" value="nuevo">Más nuevo a más viejo</option>
            <option style="color: #505050" value="viejo">Más viejo a más nuevo</option>
        </select>
        <div class="card-body">
          <div id="productos" class="row">
            <!-- cards-->
            <div class="col-sm-2">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <img src="../Util/Img/perfil_negro.jpg" alt="perfil" class="img-fluid">
                    </div>
                    <div class="col-sm-12">
                      <!-- Se añaden los productos mediante JS -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- end cards -->
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer" style="background-color: transparent;">
          <button id="loadMoreButton" class="btn btn-primary btn-lg btn-block" style="background-color: #ac2e32; border-color: #ac2e32;">Cargar más</button>
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
<?php
    include_once('Layouts/Tienda/footer.php');
?>

<!-- Js del index -->
<script src="./tienda.js" type="module"></script>
