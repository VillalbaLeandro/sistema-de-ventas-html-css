<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge los datos enviados por el formulario
    $fecha = date('Y-m-d H:i:s');
    $descripcion = (isset($_POST['descripcion'])) ? $_POST['descripcion'] : null;
    $entradaSalidaId = (isset($_POST['entradaSalidaId'])) ? $_POST['entradaSalidaId'] : null;
    $ventaId = (isset($_POST['ventaId'])) ? $_POST['ventaId'] : null;
    $compraId = (isset($_POST['compraId'])) ? $_POST['compraId'] : null;

    // Determina el tipo de transacción (1 para agregar, 2 para quitar)
    $tipo = ($entradaSalidaId == 1) ? 1 : 2;

    // Verifica si al menos una fecha está presente antes de registrar la transacción
    if (isset($_POST['fechaInicio']) || isset($_POST['fechaFin'])) {
        // Llama a la función para registrar en la caja
        // registrarEfectivoCaja($fecha, $descripcion, $entradaSalidaId, $ventaId, $tipo, $compraId);
    }
}

// Inicializa las fechas con el formato correcto
$fechaInicio = date('Y-m-d');
$fechaFin = date('Y-m-d');

// Filtrar por rango de fechas si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fechaInicio']) && isset($_POST['fechaFin'])) {
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
}

// Obtener el historial de transacciones
$historial = obtenerHistorialCajaPorFecha($fechaInicio, $fechaFin);

?>


<div class="container">
    <h3>Caja</h3>

    <!-- Tarjetas con montos de la caja -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Saldo Anterior</h5>
                    <p class="card-text"><?php echo obtenerSaldoAnterior($fechaInicio); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Compras</h5>
                    <p class="card-text"><?php echo obtenerTotalCompras($fechaInicio, $fechaFin); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ventas</h5>
                    <p class="card-text"><?php echo obtenerTotalVentasCaja($fechaInicio, $fechaFin); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Saldo Actual</h5>
                    <p class="card-text"><?php echo obtenerSaldoActual($fechaInicio, $fechaFin); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h4 class="mb-4">Filtrar por Rango de Fechas</h4>
            <form method="post" action="">
                <div class="form-row mb-3">
                    <div class="form-group col-md-4">
                        <label for="fechaInicio">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="fechaFin">Fecha de Fin</label>
                        <input type="datetime-local" class="form-control" id="fechaFin" name="fechaFin" value="<?php echo date('Y-m-d') . 'T23:59'; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Buscar</button>
                </div>
            </form>
        </div>

    </div>
    <!-- Historial de transacciones -->
    <div class="card mt-4">
        <div class="card-body">
            <h4>Historial de Transacciones</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                        <th>Acciones</th> <!-- Nueva columna para el botón Ver Factura -->
                    </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($historial)) {
                    foreach ($historial as $transaccion) {
                        echo "<tr>";
                        echo "<td>" . $transaccion['fecha'] . "</td>";
                        echo "<td>" . $transaccion['descripcion'] . "</td>";
                        echo "<td>$" . $transaccion['monto'] . "</td>";
                    
                        // Agrega un enlace para ver la factura si hay un venta_id o compra_id
                        if (!empty($transaccion['venta_id'])) {
                            echo "<td><a title='Ver Factura' href='facturacion/index.php?idVenta=" . $transaccion['venta_id'] . "' class='btn btn-outline-info'  style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;'><i class='fas fa-receipt'></i></a></td>";
                        } elseif (!empty($transaccion['compra_id'])) {
                            echo "<td><a title='Ver Factura' href='facturacion/index.php?idCompra=" . $transaccion['compra_id'] . "' class='btn btn-outline-info' style='--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;'><i class='fas fa-receipt'></i></a></td>";
                        } else {
                            echo "<td></td>";
                        }
                    
                        echo "</tr>";
                    }
                    
                } else {
                    echo "<tr><td colspan='4'>No hay transacciones en el rango de fechas seleccionado.</td></tr>";
                }
                ?>
            </tbody>
            </table>
        </div>
    </div>
</div>

</div>
<script>
    // Función para redirigir a la factura
    function redirectToInvoice(id, tipo) {
        if (tipo === 'venta') {
            window.location.href = './facturacion/index.php?idVenta=' + id;
        } else if (tipo === 'compra') {
            window.location.href = './facturacion/index.php?idCompra=' + id;
        }
    }
</script>

<?php
include_once "footer.php";
?>