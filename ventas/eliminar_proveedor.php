<?php
session_start();
include_once "funciones_proveedores.php";

$id = $_GET['id'];
$proveedor = obtenerProveedorPorId($id);

if (!$proveedor) {
    echo "Proveedor no encontrado";
    exit;
}

$resultado = eliminarProveedor($id);

if ($resultado) {
    header("Location: listar_proveedores.php");
} else {
    echo "Error al eliminar el proveedor.";
}