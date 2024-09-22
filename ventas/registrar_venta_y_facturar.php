<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "funciones.php";

$productos = $_SESSION['lista'];
$idUsuario = $_SESSION['idUsuario'];
$total = calcularTotalLista($productos);

// Verificar que $idCliente tenga un valor válido; asignar 11 si está vacío
$idCliente = isset($_SESSION['clienteVenta']) && !empty($_SESSION['clienteVenta']) ? $_SESSION['clienteVenta'] : 11;

$medioPago = isset($_POST['mediopago']) ? $_POST['mediopago'] : 1;
$iva = isset($_POST['iva']) ? $_POST['iva'] : 2;

if (count($productos) === 0) {
    header("location: vender.php");
    return;
}

// Registrar la venta y obtener el ID de la venta registrada
$idVenta = registrarVenta($idUsuario, $idCliente, $total, $medioPago, $iva);

if (!$idVenta) {
    echo "Error al registrar la venta";
    return;
}

// Registrar los productos de la venta
$resultado = registrarProductosVenta($productos, $idVenta);

if (!$resultado) {
    echo "Error al registrar los productos vendidos";
    return;
}

// Registrar movimientos de productos
foreach ($productos as $producto) {
    registrarMovimientoProducto($producto->id, 'Venta', null, $idVenta, $producto->cantidad, date('Y-m-d H:i:s'));
}

// Registrar el movimiento en la caja
$fechaRegistro = date('Y-m-d H:i:s');
registrarEfectivoCaja($fechaRegistro, $total, 'Venta', null, $idVenta, 1, null);

// Limpiar la lista de productos y el cliente seleccionado en la sesión
$_SESSION['lista'] = [];
$_SESSION['clienteVenta'] = null; // Cambia esto a null en lugar de cadena vacía

header("Location: vender.php");
