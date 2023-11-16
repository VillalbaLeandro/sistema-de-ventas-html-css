<?php
// estadisticas.php

session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";

$productos = obtenerProductosConDetalles();

?>

<div class="container mt-3">
    <h1>Estadísticas Mensuales de Movimientos por Producto</h1>

    <?php if (empty($productos)) { ?>
        <div class="alert alert-info" role="alert">
            No hay productos registrados.
        </div>
    <?php } else { ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <?php for ($mes = 1; $mes <= 12; $mes++) { ?>
                        <th><?= obtenerNombreMes($mes); ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto) { ?>
                    <tr>
                        <td><?= $producto->nombre; ?></td>
                        <?php
                        for ($mes = 1; $mes <= 12; $mes++) {
                            $compras = obtenerCantidadMovimientosPorMes($producto->id, $mes, 'Compra');
                            $ventas = obtenerCantidadMovimientosPorMes($producto->id, $mes, 'Venta');
                            echo "<td>Compras: $compras<br>Ventas: $ventas</td>";
                        }
                        ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>
<?php
// Funciones adicionales para consultar la base de datos

function obtenerProductosConDetalles()
{
    try {
        $conexion = conectarBaseDatos();
        $stmt = $conexion->query("SELECT * FROM producto");
        $productos = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($productos as &$producto) {
            $producto->movimientos = obtenerDetallesPorTipo($producto->id);
        }

        return $productos;
    } catch (PDOException $e) {
        echo "Error al obtener productos: " . $e->getMessage();
        return [];
    } finally {
        if ($conexion) {
            $conexion = null;
        }
    }
}

function obtenerDetallesPorTipo($productoId)
{
    try {
        $conexion = conectarBaseDatos();
        $stmt = $conexion->prepare("SELECT * FROM movimiento_producto WHERE producto_id = :producto_id");
        $stmt->bindParam(':producto_id', $productoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        echo "Error al obtener detalles por tipo: " . $e->getMessage();
        return [];
    } finally {
        if ($conexion) {
            $conexion = null;
        }
    }
}

function obtenerCantidadMovimientosPorMes($productoId, $mes, $tipo)
{
    $movimientos = obtenerDetallesPorTipo($productoId);
    $cantidad = 0;

    foreach ($movimientos as $movimiento) {
        $fechaMes = date('n', strtotime($movimiento->fecha));
        if ($fechaMes == $mes && $movimiento->tipo == $tipo) {
            $cantidad += $movimiento->cantidad;
        }
    }

    return $cantidad;
}

?>

<?php include_once "footer.php"; ?>