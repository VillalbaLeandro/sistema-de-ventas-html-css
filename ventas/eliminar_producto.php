<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    include_once "funciones.php";

    // Lógica para eliminar el producto
    $resultado = eliminarProducto($id);

    if ($resultado) {
        $mensaje = 'Producto eliminado con éxito';
    } else {
        $mensaje = 'Error al eliminar producto';
    }

    // Redirige de nuevo a la página de productos con el mensaje
    header("Location: productos.php?mensaje=" . urlencode($mensaje));
} else {
    // En caso de que no se haya proporcionado un ID
    header("Location: productos.php?mensaje=" . urlencode('No se ha seleccionado el producto'));
}
