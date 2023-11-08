<?php
// ver_movimientos.php

session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
$fechaInicio = (isset($_POST['inicio'])) ? $_POST['inicio'] : null;
$fechaFin = (isset($_POST['fin'])) ? $_POST['fin'] : null;
$usuario = (isset($_POST['idUsuario'])) ? $_POST['idUsuario'] : null;
$cliente = (isset($_POST['idCliente'])) ? $_POST['idCliente'] : null;
$ventas = obtenerVentas($fechaInicio, $fechaFin, $cliente, $usuario);

if (isset($_GET['id'])) {
    $idProducto = $_GET['id'];
    $producto = obtenerProductoPorId($idProducto);
    $movimientos = obtenerMovimientosPorProducto($idProducto);
}
?>

<div class="container mt-3">
    <?php if (!empty($producto)) { ?>
        <h1>Movimientos del Producto: <?php echo $producto->nombre; ?></h1>
        <p>Stock actual: <?php echo obtenerStockProducto($idProducto); ?></p>
        <div class="container mt-3">
            <h3>Filtrar Movimientos</h3>
            <form method="post d-flex">
                <div class="form-group col-3">
                    <label for="inicio">Fecha de inicio:</label>
                    <input type="date" name="inicio" id="inicio" class="form-control">
                </div>
                <div class="form-group col-3">
                    <label for="fin">Fecha de fin:</label>
                    <input type="date" name="fin" id="fin" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
        </div>
        <?php if (empty($movimientos)) { ?>
            <div class="alert alert-info" role="alert">
                No hay movimientos registrados para este producto.
            </div>
        <?php } else { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Precio Compra Unitario</th>
                        <th>Precio Venta Unitario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movimientos as $movimiento) { ?>
                        <tr>
                            <td><?= $movimiento->fecha; ?></td>
                            <td><?= $movimiento->tipo; ?></td>
                            <td><?= $movimiento->cantidad; ?></td>
                            <td><?= isset($movimiento->precio_compra) ? '$' . $movimiento->precio_compra : ''; ?></td>
                            <td><?= isset($movimiento->precio_venta) ? '$' . $movimiento->precio_venta : ''; ?></td>
                            <td>
                                <?php foreach ($ventas as $venta) { ?>
                                    <?php if ($idProducto == $venta->id) { ?>
                                        <button title="Ver Factura" class="btn btn-outline-info" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="redirectToInvoice(this.dataset.idVenta)">
                                            <i class="fas fa-receipt"></i>
                                        </button>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    <?php } else { ?>
        <div class="alert alert-danger" role="alert">
            Producto no encontrado.
        </div>
    <?php } ?>
</div>
<?php include_once "footer.php"; ?>
<script>
    function redirectToInvoice(idVenta) {
        console.log("funciona")
        window.location.href = './facturacion/index.php?idVenta=' + idVenta;
    }
</script>