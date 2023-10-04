<?php
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
session_start();
// if(empty($_SESSION['idUsuario'])) header("location: login.php");

$usuarios = obtenerUsuarios();
?>
<div class="container">
    <h1>
        <a class="btn btn-success btn-lg" href="agregar_usuario.php">
            <i class="fa fa-plus"></i>
            Agregar
        </a>
        Usuarios
    </h1>
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($usuarios as $usuario){
            ?>
                <tr>
                    <td><?php echo $usuario->id; ?></td>
                    <td><?php echo $usuario->nombre; ?></td>
                    <td><?php echo $usuario->apellido; ?></td>
                    <td><?php echo $usuario->tel; ?></td>
                    <td><?php echo $usuario->direccion; ?></td>
                    <td><?php echo $usuario->email; ?></td>
                    <td><?php echo $usuario->rol; ?></td>
                    <td>
                        <a class="btn btn-info" href="editar_usuario.php?id=<?php echo $usuario->id; ?>">
                            <i class="fa fa-edit"></i>
                            Editar
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-danger" href="eliminar_usuario.php?id=<?php echo $usuario->id; ?>">
                            <i class="fa fa-trash"></i>
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>