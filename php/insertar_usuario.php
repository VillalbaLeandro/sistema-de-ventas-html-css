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
<<<<<<< HEAD

    // Insertar los datos en la base de datos. se coloca 3 porque va a ser por defecto ese rol de usuario (cliente)
    $query = "INSERT INTO usuario (nombre, apellido, cuit_cuil, tel, direccion, rol_id, tipo_cliente_id) 
              VALUES ('$nombre', '$apellido', '$cuil_cuit', '$tel', '$direccion', 3, $tipoCliente)";
=======
    $email = $_POST["mail"];
    $pass = $_POST["pass"];
    

    // Insertar los datos en la base de datos. se coloca 3 porque va a ser por defecto ese rol de usuario (cliente)
    $query = "INSERT INTO usuario (nombre, apellido, cuit_cuil, tel, direccion, rol_id, tipo_cliente_id, email, pass) 
            VALUES ('$nombre', '$apellido', '$cuil_cuit', '$tel', '$direccion', 3, $tipoCliente, '$email' , '$pass')";
>>>>>>> c8323a941639b175d390048752c8a686bcc1cf0a

    if ($conexion->query($query) === TRUE) {
        echo "Usuario registrado exitosamente.";
    } else {
<<<<<<< HEAD
        echo "Error al registrar el usuario: " . $conexion->error;
=======
        echo "Error al registrar el usuario: ";
>>>>>>> c8323a941639b175d390048752c8a686bcc1cf0a
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
}
<<<<<<< HEAD
?>
=======
>>>>>>> c8323a941639b175d390048752c8a686bcc1cf0a
