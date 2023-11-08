<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
?>
<div class="container">
    <h3>Caja</h3>

    <!-- Saldo actual de la caja -->
    <div class="card">
        <div class="card-body">
            <h4>Saldo Actual de la Caja</h4>
            <p><?php echo obtenerSaldoCaja(); ?></p>
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
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $historial = obtenerHistorialCaja();
                    foreach ($historial as $transaccion) {
                        echo "<tr>";
                        echo "<td>" . $transaccion['fecha'] . "</td>";
                        echo "<td>" . $transaccion['descripcion'] . "</td>";
                        echo "<td>" . $transaccion['monto'] . "</td>";
                        echo "</tr>";
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
