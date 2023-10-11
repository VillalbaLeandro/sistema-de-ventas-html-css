<?php
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
session_start();

if(empty($_SESSION['nombre'])) header("location: login.php");

$clientes = obtenerClientes();
?>
<div class="container">
    <h1>
        <a class="btn btn-success btn-lg" href="agregar_cliente.php">
            <i class="fa fa-plus"></i>
            Agregar
        </a>
        Clientes
    </h1>
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Fecha de Nac.</th>
                <th>Email</th>
                <th>cuil/cuit</th>
                <th>Categoria</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($clientes as $cliente){
            ?>
                <tr>
                    <td><?php echo $cliente->id; ?></td>
                    <td><?php echo $cliente->nombre; ?></td>
                    <td><?php echo $cliente->apellido; ?></td>
                    <td><?php echo $cliente->telefono; ?></td>
                    <td><?php echo $cliente->direccion; ?></td>
                    <td><?php echo $cliente->fechaNacimiento; ?></td>
                    <td><?php echo $cliente->email; ?></td>
                    <td><?php echo $cliente->cuil_cuit; ?></td>
                    <td><?php echo $cliente->categoria; ?></td>
                    <td>
                        <a class="btn btn-info" href="editar_cliente.php?id=<?php echo $cliente->id;?>">
                            <i class="fa fa-edit"></i>
                            Editar
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-danger" href="eliminar_cliente.php?id=<?php echo $cliente->id;?>">
                            <i class="fa fa-trash"></i>
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>