<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";

// Agrega el código para manejar la búsqueda aquí
$search = isset($_GET['search']) ? $_GET['search'] : '';
$productos = buscarProductos($search); // La función buscarProductos es una función ficticia, reemplázala con tu lógica real

?>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Buscar Productos</h3>
            <form method="get">
                <div class="mb-3">
                    <label for="codigo" class="form-label">Código de barras o Nombre del Producto</label>
                    <input class="form-control form-control-lg" name="search" id="codigo" type="search" placeholder="Código de barras o Nombre del Producto" autocomplete="off" aria-label="codigoBarras">
                </div>
                <div class="text-center mt-3">
                    <input type="submit" id="buscar_productos" name="buscar_productos" value="Buscar Productos" class="btn btn-outline-primary btn-lg">
                </div>
            </form>

            <?php
            // Muestra los resultados en una tabla
            if (!empty($productos)) {
                echo '<div class="mt-3">';
                echo '<h4>Resultados de la búsqueda:</h4>';
                echo '<table class="table table-bordered">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Código</th>';
                echo '<th>Nombre</th>';
                echo '<th>Descripción</th>';
                echo '<th>Precio de Venta</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($productos as $producto) {
                    echo '<tr>';
                    echo '<td>' . $producto['codigo'] . '</td>';
                    echo '<td>' . $producto['nombre'] . '</td>';
                    echo '<td>' . $producto['descripcion'] . '</td>';
                    echo '<td>$' . $producto['precio_venta'] . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-info mt-3" role="alert">No se encontraron resultados.</div>';
            }
            ?>

        </div>
    </div>
</div>

<?php
include_once "footer.php";
?>
