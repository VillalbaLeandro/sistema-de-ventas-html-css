<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";

?>
<div class="container">
    <h3>Agregar Categoría</h3>
    <form method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Categoría</label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Ej. Bebidas" required>
        </div>
        <div class="text-center mt-3">
            <input type="submit" name="registrar" value="Registrar" class="btn btn-outline-success btn-lg">
        </div>
    </form>
</div>

<?php
include_once "footer.php";

if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    if (empty($nombre)) {
        echo '
        <div class="alert alert-danger mt-3" role="alert">
            Debes completar el nombre de la categoría.
        </div>';
        return;
    }

    $resultado = agregarCategoria($nombre);
    if ($resultado) {
        echo "
        <script>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Categoría creada con éxito',
            showConfirmButton: false,
            timer: 1500
        }).then((result) => {
            window.location.href = 'categorias.php';
        });
        </script>";
    }
}