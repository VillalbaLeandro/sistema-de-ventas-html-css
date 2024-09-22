<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "funciones.php";

// Verificar si se ha enviado el formulario para agregar producto
if (isset($_POST['agregar'])) {
    if (isset($_POST['codigo'])) {
        $codigo = $_POST['codigo'];
        $producto = obtenerProductoPorCodigo($codigo);

        // Verificar si el producto existe
        if (!$producto) {
            echo "<script type='text/javascript'>
                    alert('No se ha encontrado el producto.');
                    window.location.href='vender.php';
                  </script>";
            exit;
        }

        // Verificar si el producto tiene stock disponible
        if ($producto->stock == 0) {
            echo "<script type='text/javascript'>
                    alert('El producto tiene stock 0 y no puede ser vendido.');
                    window.location.href='vender.php';
                  </script>";
            exit;
        }

        // Agregar producto a la lista de la sesión
        $_SESSION['lista'] = agregarProductoALista($producto, $_SESSION['lista']);
        header("location: vender.php");
        exit;
    } else {
        // Caso en que no se envió un código válido
        echo "<script type='text/javascript'>
                alert('Debe ingresar un código de producto.');
                window.location.href='vender.php';
              </script>";
        exit;
    }
}
