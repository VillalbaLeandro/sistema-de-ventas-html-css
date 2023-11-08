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
    <h3>Agregar producto</h3>
    <form method="post">
        <div class="mb-3">
            <label for="codigo" class="form-label">Código de barras</label>
            <input type="number" name="codigo" class="form-control" id="codigo" placeholder="Escribe el código de barras del producto" required>
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Ej. Brahma" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" name="descripcion" class="form-control" id="descripcion" placeholder="Ej. Brahma lata 710cc" required>
        </div>
        <div class="mb-3">
            <label for="categoria_id" class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select" id="categoria_id" required>
                <option>Selecciona una categoría</option>
                <?php
                try {
                    $categorias = obtenerCategorias();
                    if (count($categorias) > 0) {
                        foreach ($categorias as $categoria) {
                            echo '<option value="' . $categoria->id . '">' . $categoria->nombre . '</option>';
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
        <div class="text-center mt-3">
            <input type="submit" name="registrar" value="Registrar" class="btn btn-outline-success btn-lg">
        </div>
    </form>
</div>

<?php
include_once "footer.php";

if (isset($_POST['registrar'])) {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria_id'];
    if (
        empty($codigo)
        || empty($nombre)
        || empty($descripcion)
        || empty($categoria)
    ) {
        echo '
        <div class="alert alert-danger mt-3" role="alert">
            Debes completar todos los datos.
        </div>';
        return;
    }
    include_once "funciones.php";
    $resultado = registrarProducto($codigo, $nombre, $descripcion, $categoria);
    if ($resultado) {
        echo "
        <script class='alert alert-success mt-3' role='alert'>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Producto creado con éxito',
            showConfirmButton: false,
            timer: 1500
          }).then((result) => {
            // Redirige a productos.php
            window.location.href = 'productos.php';
        });
        </script>";
    }
}
?>
