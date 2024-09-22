<?php
// Función para obtener todas las categorías
function obtenerCategorias()
{
    $conexion = conectarBaseDatos();
    $sql = "SELECT * FROM categoria ORDER BY nombre ASC";
    $sentencia = $conexion->prepare($sql);
    $sentencia->execute();
    return $sentencia->fetchAll(PDO::FETCH_OBJ);
}

// Función para obtener una categoría por su ID
function obtenerCategoriaPorId($id)
{
    $conexion = conectarBaseDatos();
    $sql = "SELECT * FROM categoria WHERE id = ?";
    $sentencia = $conexion->prepare($sql);
    $sentencia->execute([$id]);
    return $sentencia->fetch(PDO::FETCH_OBJ);
}

// Función para agregar una nueva categoría
function agregarCategoria($nombre)
{
    $conexion = conectarBaseDatos();
    $sql = "INSERT INTO categoria (nombre) VALUES (?)";
    $sentencia = $conexion->prepare($sql);
    return $sentencia->execute([$nombre]);
}

// Función para editar una categoría
function editarCategoria($id, $nombre)
{
    $conexion = conectarBaseDatos();
    $sql = "UPDATE categoria SET nombre = ? WHERE id = ?";
    $sentencia = $conexion->prepare($sql);
    return $sentencia->execute([$nombre, $id]);
}

// Función para eliminar una categoría
function eliminarCategoria($id)
{
    $conexion = conectarBaseDatos();
    $sql = "DELETE FROM categoria WHERE id = ?";
    $sentencia = $conexion->prepare($sql);
    return $sentencia->execute([$id]);
}
