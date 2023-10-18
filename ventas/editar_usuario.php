<?php
session_start();

if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}
include_once "encabezado.php";
include_once "navbar.php";

$id = $_GET['id'];
if (!$id) {
    echo 'No se ha seleccionado el usuario';
    exit;
}
include_once "funciones.php";
$usuario = obtenerUsuarioPorId($id);

$errors = array();

if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['tel'];
    $direccion = $_POST['direccion'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($nombre) || empty($apellido) || empty($telefono) || empty($direccion) || empty($email)) {
        $errors[] = "Debes completar todos los campos.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Las contraseñas no coinciden.";
    }

    if (empty($errors)) {
        $resultado = editarUsuario($nombre, $apellido, $telefono, $direccion, $email, $password, $id);
        if ($resultado) {
            echo "
            <script>
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Usuario modificado con éxito',
                    showConfirmButton: false,
                    timer: 1500
                }).then((result) => {
                    window.location.href = 'usuarios.php';
                });
            </script>";
        }
    }
}
?>

<div class="container">
    <h3>Editar usuario</h3>
    <form method="post">
        <?php foreach ($errors as $error) : ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $usuario->nombre; ?>" id="nombre" placeholder="Escribe el nombre del usuario">
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" value="<?php echo $usuario->apellido; ?>" id="apellido" placeholder="Escribe el apellido del usuario">
        </div>
        <div class="mb-3">
            <label for="tel" class="form-label">Teléfono</label>
            <input type="text" name="tel" class="form-control" value="<?php echo $usuario->tel; ?>" id="tel" placeholder="Ej. 2111568974">
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="<?php echo $usuario->direccion; ?>" id="direccion" placeholder="Ej. Av Collar 1005 Col Las Cruces">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" name="email" class="form-control" value="<?php echo $usuario->email; ?>" id="email" placeholder="ejemplo@ejemplo.com">
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
            <input type="submit" name="registrar" value="Guardar Cambios" class="btn btn-primary btn-lg">
            <a href="usuarios.php" class="btn btn-danger btn-lg">
                <i class="fa fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>
