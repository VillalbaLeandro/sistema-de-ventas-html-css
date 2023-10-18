<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";

// Inicializa variables de error
$errors = array();

// Inicializa variables de formulario
$nombre = $apellido = $telefono = $direccion = $fecha_nacimiento = $email = $cuil_cuit = $dni = $categoria = "";

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
        $errors[] = "Debes completar todos los datos.";
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
    }

    if (empty($errors)) {
        $resultado = registrarCliente($nombre, $apellido, $direccion, $fecha_nacimiento, $email, $telefono, $cuil_cuit, $dni, $categoria, $password);

        if ($resultado) {
            echo "
            <script class='alert alert-success mt-3'role='alert'>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Cliente creado con éxito',
                showConfirmButton: false,
                timer: 1500
              }).then((result) => {
                // Redirige a productos.php
                window.location.href = 'clientes.php';
            });
            </script>";
        }
    }
}
?>

<div class="container">
    <h3>Agregar cliente</h3>
    <form method="post">
        <!-- Muestra errores si los hay -->
        <?php foreach ($errors as $error) : ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <div class="row">
            <div class="mb-3 col-md-5">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Escribe el nombre del cliente" value="<?php echo $nombre; ?>" required>
            </div>
            <div class="mb-3 col-md-5">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control" id="apellido" placeholder="Escribe el apellido del cliente" value="<?php echo $apellido; ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-md-5">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" id="telefono" placeholder="Ej. 2111568974" value="<?php echo $telefono; ?>" required>
            </div>
            <div class="mb-3 col-md-5">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Ej. Av Collar 1005 Col Las Cruces" value="<?php echo $direccion; ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-md-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" name="dni" class="form-control" id="dni" placeholder="Ej. 12345678" value="<?php echo $dni; ?>" required>
            </div>
            <div class="mb-3 col-md-4">
                <label for="cuil_cuit" class="form-label">CUIL/CUIT</label>
                <input type="text" name="cuil_cuit" class="form-control" id="cuil_cuit" placeholder="Ej. 20-12345678-9" value="<?php echo $cuil_cuit; ?>" required>
            </div>
            <div class="mb-3 col-md-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control" id="fecha_nacimiento" value="<?php echo $fecha_nacimiento; ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-md-5">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Ej. ejemplo@correo.com" value="<?php echo $email; ?>" required>
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
                                $selected = ($categoria == $categoriaCliente->id) ? 'selected' : '';
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
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-md-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Escribe la contraseña" required>
            </div>
            <div class="mb-3 col-md-3">
                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirma la contraseña" required>
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
include_once "footer.php";
?>
