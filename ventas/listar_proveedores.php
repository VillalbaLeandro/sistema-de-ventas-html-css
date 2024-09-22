<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones_proveedores.php";

$proveedores = obtenerProveedores();
?>

<div class="container mt-3">
    <h2>Lista de Proveedores</h2>
    <a href="agregar_proveedor.php" class="btn btn-primary mb-3">Agregar Proveedor</a>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Categoría</th>
                <th>Dirección</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($proveedores as $proveedor): ?>
                <tr>
                    <td><?php echo $proveedor->nombre; ?></td>
                    <td><?php echo $proveedor->apellido; ?></td>
                    <td><?php echo $proveedor->categoria; ?></td>
                    <td><?php echo $proveedor->direccion; ?></td>
                    <td><?php echo $proveedor->email; ?></td>
                    <td><?php echo $proveedor->telefono; ?></td>
                    <td>
                        <a href="editar_proveedor.php?id=<?php echo $proveedor->id; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar_proveedor.php?id=<?php echo $proveedor->id; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $proveedor->telefono); ?>" target="_blank" class="btn btn-success btn-sm">WhatsApp</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once "footer.php"; ?>
