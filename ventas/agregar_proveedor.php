<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "encabezado.php";
?>

<div class="container mt-3">
    <h2>Agregar Proveedor</h2>
    <form action="guardar_proveedor.php" method="POST">
        <input type="hidden" name="accion" value="agregar">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" name="apellido" required>
        </div>
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoría</label>
            <input type="text" class="form-control" name="categoria" required>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" name="direccion">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" name="telefono" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="listar_proveedores.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include_once "footer.php"; ?>
