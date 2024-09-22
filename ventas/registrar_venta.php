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
$idCliente = isset($_SESSION['clienteVenta']) && !empty($_SESSION['clienteVenta']) ? $_SESSION['clienteVenta'] : 11;
$medioPago = isset($_POST['mediopago']) ? $_POST['mediopago'] : 1;
$iva = isset($_POST['iva']) ? $_POST['iva'] : 2;

if (count($productos) === 0) {
    header("location: vender.php");
    return;
}

$idVenta = registrarVenta($idUsuario, $idCliente, $total, $medioPago, $iva);

if (!$idVenta) {
    echo "Error al registrar la venta";
    return;
}

$resultado = registrarProductosVenta($productos, $idVenta);

if (!$resultado) {
    echo "Error al registrar los productos vendidos";
    return;
}

foreach ($productos as $producto) {
    registrarMovimientoProducto($producto->id, 'Venta', null, $idVenta, $producto->cantidad, date('Y-m-d H:i:s'));
}

$fechaRegistro = date('Y-m-d H:i:s');
registrarEfectivoCaja($fechaRegistro, $total, 'Venta', null, $idVenta, 1, null);


$_SESSION['lista'] = [];
$_SESSION['clienteVenta'] = "";

header("Location: vender.php");
