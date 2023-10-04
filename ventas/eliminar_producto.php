<?php
$id = $_GET['id'];
if (!$id) {
    echo 'No se ha seleccionado el producto';
    exit;
}
include_once "funciones.php";
$resultado = eliminarProducto($id);
if ($resultado) {
    $mensaje = 'Producto eliminado con éxito';
} else {
    $mensaje = 'Error al eliminar producto';
}
echo '<div class="alert alert-success mt-3" role="alert">' . $mensaje . '</div>';
header("Location: productos.php");

