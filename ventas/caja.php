<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";

// Inicializa las fechas con el formato correcto
$fechaInicio = date('Y-m-d');
$fechaFin = date('Y-m-d');

// Manejo de la solicitud POST para registrar movimientos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = date('Y-m-d H:i:s');
    $monto = isset($_POST['monto']) ? (float)$_POST['monto'] : 0;
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : 'sin detallar';
    $tipoMovimiento = isset($_POST['tipo_movimiento']) ? $_POST['tipo_movimiento'] : 'Entrada';
    $tipoTransaccion = isset($_POST['tipo_transaccion']) ? $_POST['tipo_transaccion'] : 'Ajuste Manual';

    // Validar y registrar el saldo inicial
    if (isset($_POST['registrar_saldo_inicial'])) {
        $saldoInicial = obtenerSaldoAnterior($fechaInicio); // Verificar si ya existe un saldo inicial
        if ($saldoInicial == 0) {
            registrarMovimientoManualCaja($fecha, $monto, $descripcion, $tipoMovimiento, $tipoTransaccion);
        } else {
            echo '<div class="alert alert-danger mt-3" role="alert">Ya existe un saldo inicial registrado.</div>';
        }
    }

    // Registrar entrada o salida manual
    if (isset($_POST['registrar_movimiento'])) {
        registrarMovimientoManualCaja($fecha, $monto, $descripcion, $tipoMovimiento, $tipoTransaccion);
    }

    // Actualizar el rango de fechas si se envía desde el formulario
    if (isset($_POST['fechaInicio']) && isset($_POST['fechaFin'])) {
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFin = $_POST['fechaFin'];
    }
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

    <!-- Formulario para establecer saldo inicial -->
    <!-- <div class="card mt-4">
        <div class="card-body">
            <h4 class="mb-4">Establecer Saldo Inicial</h4>
            <form method="post" action="">
                <div class="form-group mb-3">
                    <label for="monto_inicial">Monto Inicial</label>
                    <input type="number" class="form-control" id="monto_inicial" name="monto" placeholder="Ingrese el monto inicial" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="registrar_saldo_inicial" class="btn btn-success btn-block">Registrar Saldo Inicial</button>
                </div>
            </form>
        </div>
    </div> -->

    <!-- Formulario para registrar movimiento manual -->
    <div class="card mt-4">
        <div class="card-body">
            <h4 class="mb-4">Registrar Movimiento Manual</h4>
            <form method="post" action="">
                <div class="form-group mb-3">
                    <label for="monto">Monto</label>
                    <input type="number" class="form-control" id="monto" name="monto" placeholder="Ingrese el monto" required>
                </div>
                <div class="form-group mb-3">
                    <label for="descripcion">Descripción</label>
                    <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción del movimiento">
                </div>
                <div class="form-group mb-3">
                    <label for="tipo_movimiento">Tipo de Movimiento</label>
                    <select class="form-select" id="tipo_movimiento" name="tipo_movimiento" required>
                        <option value="Entrada">Entrada</option>
                        <option value="Salida">Salida</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="tipo_transaccion">Tipo de Transacción</label>
                    <input type="text" class="form-control" id="tipo_transaccion" name="tipo_transaccion" placeholder="Ej. Ajuste Manual" value="Ajuste Manual">
                </div>
                <div class="form-group">
                    <button type="submit" name="registrar_movimiento" class="btn btn-primary btn-block">Registrar Movimiento</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Formulario para filtrar por fechas -->
    <div class="card mt-4">
        <div class="card-body">
            <h4 class="mb-4">Filtrar por Rango de Fechas</h4>
            <form method="post" action="">
                <div class="form-row mb-3">
                    <div class="form-group col-md-4">
                        <label for="fechaInicio">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" value="<?php echo $fechaInicio; ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="fechaFin">Fecha de Fin</label>
                        <input type="datetime-local" class="form-control" id="fechaFin" name="fechaFin" value="<?php echo $fechaFin . 'T23:59'; ?>" required>
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
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($historial)) {
                        foreach ($historial as $transaccion) {
                            echo "<tr>";
                            echo "<td>" . $transaccion['fecha'] . "</td>";
                            echo "<td>" .  $transaccion['descripcion'] . "</td>";
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
                        echo "<tr><td colspan='6'>No hay transacciones en el rango de fechas seleccionado.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php
include_once "footer.php";
?>