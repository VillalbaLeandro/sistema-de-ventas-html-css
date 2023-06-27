<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$Servidor = "localhost";
$Base_Datos = "drinkstore_db";
$usuario = "root";
$clave = null;

$conexion = new mysqli($Servidor, $usuario, $clave, $Base_Datos);

// Verificar si hay errores de conexión
if ($conexion->connect_errno) {
    die("Error en la conexión a la base de datos: " . $conexion->connect_error);
}

