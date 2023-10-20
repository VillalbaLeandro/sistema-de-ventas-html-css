<?php
session_start();

include_once "encabezado.php";

if (isset($_POST['nombre']) && isset($_POST['password'])) {
    if (empty($_POST['nombre']) || empty($_POST['password'])) {
        echo '
        <div class="alert alert-warning mt-3" role="alert">
            Debes completar todos los datos.
            <a href="login.php">Regresar</a>
        </div>';
        return;
    }

    include_once "funciones.php";

    $usuario = $_POST['nombre'];
    $password = $_POST['password'];

    $datosSesion = iniciarSesion($usuario, $password);

    if (!$datosSesion) {
        echo '
        <script>
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Nombre de usuario y/o contraseña incorrectas"
        }).then(() => {
            window.location.href = "login.php";
        });
        </script>';
        return;
    }

    $_SESSION['nombre'] = $datosSesion->nombre;
    $_SESSION['idUsuario'] = $datosSesion->id;
    header("location: index.php");
    exit;
}
