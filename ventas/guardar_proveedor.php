<?php
session_start();
include_once "funciones_proveedores.php";

$accion = $_POST['accion'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$categoria = $_POST['categoria'];
$direccion = $_POST['direccion'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];

if ($accion === 'agregar') {
    $resultado = agregarProveedor($nombre, $apellido, $categoria, $direccion, $email, $telefono);
} elseif ($accion === 'editar') {
    $id = $_POST['id'];
    $resultado = editarProveedor($id, $nombre, $apellido, $categoria, $direccion, $email, $telefono);
}

if ($resultado) {
    header("Location: listar_proveedores.php");
} else {
    echo "Error al guardar el proveedor.";
}
