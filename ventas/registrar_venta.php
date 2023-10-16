<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}
include_once "funciones.php";

$productos = $_SESSION['lista'];
$idUsuario = $_SESSION['idUsuario'];
$total = calcularTotalLista($productos);
$idCliente = isset($_SESSION['clienteVenta']) ? $_SESSION['clienteVenta'] : 11;
$medioPago = isset($_POST['mediopago']) ? $_POST['mediopago'] : 1;
$iva = isset($_POST['iva']) ? $_POST['iva'] : 2;


if (count($productos) === 0) {
    header("location: vender.php");
    return;
}

$idVenta = registrarVenta($idUsuario, $idCliente, $total, $medioPago, $iva);

if (!$idVenta) {
    echo "Error al registrar la venta";
    return;
}

$resultado = registrarProductosVenta($productos, $idVenta);

if (!$resultado) {
    echo "Error al registrar los productos vendidos";
    return;
}


$_SESSION['lista'] = [];
$_SESSION['clienteVenta'] = "";

echo "
<script type='text/javascript'>
    // Abre el modal
    $('#confirmarVentaModal').modal('show');
</script>";
?>

<!-- Modal para confirmar la venta e impresión de factura -->
<div class="modal fade" id="confirmarVentaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Deseas imprimir la factura?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Contenido del modal -->
                <!-- Agrega aquí la información de la factura que deseas mostrar -->

                <p>Total: $<?php echo $total; ?></p>
                <!-- Agrega más información de la factura si es necesario -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, gracias</button>
                <a href="imprimir_factura.php?id=<?php echo $idVenta; ?>" class="btn btn-primary">Sí, imprimir factura</a>
                <!-- El enlace anterior debe redirigir a la página que imprimirá la factura -->
            </div>
        </div>
    </div>
</div>