<?php
session_start();

if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";

$id = $_GET['id'];
if (!$id) {
    echo 'No se ha seleccionado el cliente';
    exit;
}

$cliente = obtenerClientePorId($id);
?>

<div class="container">
    <h3><?php echo isset($cliente->nombre) ? 'Editar cliente' : 'Agregar cliente'; ?></h3>
    <form method="post">
        <div class="row">
            <div class="mb-3 col-md-5">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo isset($cliente->nombre) ? $cliente->nombre : ''; ?>" id="nombre" placeholder="Escribe el nombre del cliente">
            </div>
            <div class="mb-3 col-md-5">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control" value="<?php echo isset($cliente->apellido) ? $cliente->apellido : ''; ?>" id="apellido" placeholder="Escribe el apellido del cliente">
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-md-5">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?php echo isset($cliente->telefono) ? $cliente->telefono : ''; ?>" id="telefono" placeholder="Ej. 2111568974">
            </div>
            <div class="mb-3 col-md-5">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" value="<?php echo isset($cliente->direccion) ? $cliente->direccion : ''; ?>" id="direccion" placeholder="Ej. Av Collar 1005 Col Las Cruces">
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-md-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" name="dni" class="form-control" value="<?php echo isset($cliente->dni) ? $cliente->dni : ''; ?>" id="dni" placeholder="Ej. 12345678">
            </div>
            <div class="mb-3 col-md-4">
                <label for="cuil_cuit" class="form-label">CUIL/CUIT</label>
                <input type="text" name="cuil_cuit" class="form-control" value="<?php echo isset($cliente->cuil_cuit) ? $cliente->cuil_cuit : ''; ?>" id="cuil_cuit" placeholder="Ej. 20-12345678-9">
            </div>
            <div class="mb-3 col-md-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control" value="<?php echo isset($cliente->fechaNacimiento) ? date('Y-m-d', strtotime($cliente->fechaNacimiento)) : ''; ?>" id="fecha_nacimiento">
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-md-5">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo isset($cliente->email) ? $cliente->email : ''; ?>" id="email" placeholder="Ej. ejemplo@correo.com">
            </div>
            <div class="mb-3 col-md-5">
                <label for="categoria" class="form-label">Categoría</label>
                <select name="categoria" class="form-select" id="categoria" required>
                    <option>Selecciona una categoría</option>
                    <?php
                    try {
                        $categoriasCliente = obtenerCategoriasCliente();
                        if (count($categoriasCliente) > 0) {
                            foreach ($categoriasCliente as $categoriaCliente) {
                                $selected = ($categoriaCliente->id == $cliente->categoria_cliente_id) ? 'selected' : '';
                                echo '<option value="' . $categoriaCliente->id . '" ' . $selected . '>' . $categoriaCliente->nombre . '</option>';
                            }
                        } else {
                            echo '<option value="">No hay categorías disponibles</option>';
                        }
                    } catch (PDOException $e) {
                        echo '<option value="">Error al obtener categorías</option>';
                    }
                    ?>
                </select>
                <h1></h1>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-md-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Escribe la contraseña">
            </div>
            <div class="mb-3 col-md-3">
                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirma la contraseña">
            </div>
        </div>
        <div class="text-center mt-3">
            <input type="submit" name="registrar" value="Registrar" class="btn btn-primary btn-lg">
            <a href="clientes.php" class="btn btn-danger btn-lg">
                <i class="fa fa-times"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>
<?php
if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $email = $_POST['email'];
    $cuil_cuit = $_POST['cuil_cuit'];
    $dni = $_POST['dni'];
    $categoria = $_POST['categoria'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (
        empty($nombre)
        || empty($apellido)
        || empty($telefono)
        || empty($direccion)
        || empty($fecha_nacimiento)
        || empty($email)
        || empty($cuil_cuit)
        || empty($categoria)
        || empty($dni)
        || empty($password)
        || empty($confirmPassword)
    ) {
        echo '
        <div class="alert alert-danger mt-3" role="alert">
            Debes completar todos los datos.
        </div>';
        return;
    }
    if ($password !== $confirmPassword) {
        echo '
        <div class="alert alert-danger mt-3" role="alert">
            Las contraseñas no coinciden. Por favor, inténtalo de nuevo.
        </div>';
        return;
    }
    
    if (isset($cliente->nombre)) {
        $resultado = editarCliente($nombre, $apellido, $direccion, $fecha_nacimiento, $email, $telefono, $cuil_cuit, $dni, $categoria, $password, $id);
        
        if ($resultado) {
            echo '
            <div class="alert alert-success mt-3" role="alert">
                Cliente actualizado con éxito.
            </div>';
        }
    } else {
        // Esto es una adición, llamamos a la función para agregar el cliente
        $resultado = registrarCliente($nombre, $apellido,  $direccion, $fecha_nacimiento, $email, $telefono, $cuil_cuit, $dni, $categoria, $password);
        
        if ($resultado) {
            echo '
            <div class="alert alert-success mt-3" role="alert">
                Cliente registrado con éxito.
            </div>';
        }
    }
}
?>
