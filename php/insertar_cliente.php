<?php
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $mail = $_POST["mail"];
    $cuil_cuit = $_POST["cuil-cuit"];
    $tel = $_POST["telNum"];
    $direccion = $_POST["direccion"];
    $tipoCliente = $_POST["tipo_cliente"];
    $fechaNacimiento = $_POST["fechaNacimiento"]; 
    $dni = $_POST["dni"];
    $pass = $_POST["pass"];

    // Insertar los datos en la tabla "cliente"
    $query = "INSERT INTO cliente (nombre, apellido, direccion, fechaNacimiento, email, telefono, cuil_cuit, dni, categoria_cliente_id, pass) 
            VALUES ('$nombre', '$apellido', '$direccion', '$fechaNacimiento', '$mail', '$tel', '$cuil_cuit', '$dni', $tipoCliente, '$pass')";

    if ($conexion->query($query) === TRUE) {
        
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error al registrar el cliente: " . $conexion->error;
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
}
