<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "funciones.php";

$id = $_GET['id'];
$resultado = eliminarCategoria($id);
if ($resultado) {
    echo "
    <script>
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Categoría eliminada con éxito',
        showConfirmButton: false,
        timer: 1500
    }).then((result) => {
        window.location.href = 'categorias.php';
    });
    </script>";
} else {
    echo '
    <div class="alert alert-danger mt-3" role="alert">
        Error al eliminar la categoría.
    </div>';
}
