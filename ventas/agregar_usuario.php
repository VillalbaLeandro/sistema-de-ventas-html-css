<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}
include_once "encabezado.php";
include_once "navbar.php";

// Inicializa variables de error
$errors = array();

if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validación de campos
    if (empty($nombre) || empty($apellido) || empty($telefono) || empty($direccion) || empty($email) || empty($password) || empty($confirmPassword)) {
        $errors[] = "Debes completar todos los campos.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Las contraseñas no coinciden.";
    }

    // Si no hay errores, registra al usuario
    if (empty($errors)) {
        include_once "funciones.php";
        $resultado = registrarUsuario($nombre, $apellido, $telefono, $direccion, $email, $password);
        if ($resultado) {
            echo "
            <script>
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Usuario creado con éxito',
                    showConfirmButton: false,
                    timer: 1500
                }).then((result) => {
                    // Redirige a usuarios.php
                    window.location.href = 'usuarios.php';
                });
            </script>";
        }
    }
}
?>

<div class="container">
    <h3>Agregar usuario</h3>
    <form method="post">
        <!-- Muestra errores si los hay -->
        <?php foreach ($errors as $error) : ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Escribe el nombre completo del usuario" value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" id="apellido" placeholder="Escribe el apellido del usuario" value="<?php echo isset($_POST['apellido']) ? $_POST['apellido'] : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="number" name="telefono" class="form-control" id="telefono" placeholder="Ej. 2111568974" value="<?php echo isset($_POST['telefono']) ? $_POST['telefono'] : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" id="direccion" placeholder="Ej. Av Collar 1005 Col Las Cruces" value="<?php echo isset($_POST['direccion']) ? $_POST['direccion'] : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" name="email" class="form-control" id="email" placeholder="Ej. ejemplo@ejemplo.com" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Escribe la contraseña">
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
            <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirma la contraseña">
        </div>
        <div class="text-center mt-3">
            <input type="submit" name="registrar" value="Registrar" class="btn btn-primary btn-lg">
            <a href="usuarios.php" class="btn btn-danger btn-lg">
                <i class="fa fa-times"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>
