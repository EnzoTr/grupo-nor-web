<?php
    $require_login = false;  // No requiere iniciar sesión
    $allowed_roles = ['Administrador', 'Repositor', 'Empleado', 'Cliente', null];

    include_once('Layouts/Tienda/header.php');
    include '..\Util\Config\config.php';
?>
<input type="hidden" id="precioBase" value="<?php echo PRECIO_BASE; ?>">
<input type="hidden" id="precioMayor12" value="<?php echo PRECIO_MAYOR_12; ?>">
<input type="hidden" id="precioMayor15" value="<?php echo PRECIO_MAYOR_15; ?>">

<section>
    <div class="container mt-5 mb-5">
        <h1 class="text-dark mb-5">Calculadora de Tinglado</h1>
        <form id="tingladoForm">
            <div class="form-group mb-4">
                <input type="number" id="largo" name="largo" class="form-control" placeholder="Largo" style="background:none; border:none; border-radius: 0; border-bottom: 3px solid rgba(0, 0, 0, 0.15)" required>
            </div>
            <div class="form-group mb-4">
                <input type="number" id="ancho" name="ancho" class="form-control" placeholder="Ancho" style="background:none; border:none; border-radius: 0; border-bottom: 3px solid rgba(0, 0, 0, 0.15)" required>
            </div>
            <label for="tipoTecho" class="text-dark">Tipo de Techo:</label>
            <select id="tipoTecho" name="tipoTecho" class="text-dark rounded mr-5"style="padding: .25em 1em; background:none; border: 1px solid rgba(0, 0, 0, 0.15); ">
                <option value="a_dos_aguas" class="text-dark rounded" >A Dos Aguas</option>
                <option value="plano" class="text-dark">Plano</option>
                <option value="parabolico" class="text-dark">Parabólico</option>
            </select>
            <label for="color" class="text-dark">Color:</label>
            <select id="color" name="color" class="text-dark rounded"style="padding: .25em 1em; background:none; border: 1px solid rgba(0, 0, 0, 0.15); ">
                <option value="gris_metalico" class="text-dark">Gris Metálico</option>
                <option value="azul" class="text-dark">Azul</option>
            </select>
            <div class="form-group mb-4">
                <label style="display:flex;" for="resultado" class="text-dark">Precio: <div class="text-dark" style="padding-left:10px;font-weight:600;" id="resultado" name="resultado" class="mt-3">$0</div></label>
                
            </div>
            <button type="submit" id="addToCart" class="btn btn-block" style="background: #ac2e32; color: #ffff; padding:.75em 0">Agregar al carrito</button>
        </form>
    </div>
</section>

<?php
    include_once('Layouts/Tienda/footer.php');
?>

<script src="./calculadora.js" type="module"></script>