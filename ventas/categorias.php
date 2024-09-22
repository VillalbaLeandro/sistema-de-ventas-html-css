<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";

// Obtener todas las categorías
$categorias = obtenerCategorias();
?>
<div class="container">
    <h3>Gestión de Categorías</h3>
    <a href="agregar_categoria.php" class="btn btn-outline-primary mb-3">Agregar Nueva Categoría</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $categoria) { ?>
                <tr>
                    <td><?php echo $categoria->id; ?></td>
                    <td><?php echo $categoria->nombre; ?></td>
                    <td>
                        <a href="editar_categoria.php?id=<?php echo $categoria->id; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar_categoria.php?id=<?php echo $categoria->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta categoría?')">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
include_once "footer.php";
?>
