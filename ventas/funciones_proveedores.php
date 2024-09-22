<?php
include_once "funciones.php"; // Asegúrate de tener este archivo para la conexión a la BD
// Función para obtener todos los proveedores
function obtenerProveedores() {
    $conexion = conectarBaseDatos();
    $sql = "SELECT * FROM proveedor ORDER BY nombre ASC";
    $sentencia = $conexion->prepare($sql);
    $sentencia->execute();
    return $sentencia->fetchAll(PDO::FETCH_OBJ);
}

// Función para obtener un proveedor por su ID
function obtenerProveedorPorId($id) {
    $conexion = conectarBaseDatos();
    $sql = "SELECT * FROM proveedor WHERE id = ?";
    $sentencia = $conexion->prepare($sql);
    $sentencia->execute([$id]);
    return $sentencia->fetch(PDO::FETCH_OBJ);
}

// Función para agregar un nuevo proveedor
function agregarProveedor($nombre, $apellido, $categoria, $direccion, $email, $telefono) {
    $conexion = conectarBaseDatos();
    $sql = "INSERT INTO proveedor (nombre, apellido, categoria, direccion, email, telefono) VALUES (?, ?, ?, ?, ?, ?)";
    $sentencia = $conexion->prepare($sql);
    return $sentencia->execute([$nombre, $apellido, $categoria, $direccion, $email, $telefono]);
}

// Función para editar un proveedor
function editarProveedor($id, $nombre, $apellido, $categoria, $direccion, $email, $telefono) {
    $conexion = conectarBaseDatos();
    $sql = "UPDATE proveedor SET nombre = ?, apellido = ?, categoria = ?, direccion = ?, email = ?, telefono = ? WHERE id = ?";
    $sentencia = $conexion->prepare($sql);
    return $sentencia->execute([$nombre, $apellido, $categoria, $direccion, $email, $telefono, $id]);
}

// Función para eliminar un proveedor
function eliminarProveedor($id) {
    $conexion = conectarBaseDatos();
    $sql = "DELETE FROM proveedor WHERE id = ?";
    $sentencia = $conexion->prepare($sql);
    return $sentencia->execute([$id]);
}
?>
