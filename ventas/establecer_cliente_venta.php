<?php
session_start();

// Obtener el ID del cliente, asignar a 1 por defecto (Cliente Genérico)
$cliente = isset($_POST['idCliente']) && !empty($_POST['idCliente']) ? $_POST['idCliente'] : 1;

// Guardar el cliente en la sesión
$_SESSION['clienteVenta'] = $cliente;

// Redirigir a la página de venta
header("location: vender.php");
