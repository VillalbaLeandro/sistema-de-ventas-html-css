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
$categoria = obtenerCategoriaPorId($id);
if (!$categoria) {
    echo "Categoría no encontrada.";
    exit;
}
?>
<div class="container">
    <h3>Editar Categoría</h3>
    <form method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Categoría</label>
            <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $categoria->nombre; ?>" required>
        </div>
        <div class="text-center mt-3">
            <input type="submit" name="actualizar" value="Actualizar" class="btn btn-outline-primary btn-lg">
        </div>
    </form>
</div>

<?php
include_once "footer.php";
include_once "funciones.php";

if (isset($_POST['actualizar'])) {
    $nombre = $_POST['nombre'];
    if (empty($nombre)) {
        echo '
        <div class="alert alert-danger mt-3" role="alert">
            Debes completar el nombre de la categoría.
        </div>';
        return;
    }

    $resultado = editarCategoria($id, $nombre);
    if ($resultado) {
        echo "
        <script>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Categoría actualizada con éxito',
            showConfirmButton: false,
            timer: 1500
        }).then((result) => {
            window.location.href = 'categorias.php';
        });
        </script>";
    }
}
?>
