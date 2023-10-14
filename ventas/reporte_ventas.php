<?php
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
session_start();
if (empty($_SESSION['nombre'])) header("location: login.php");

if (isset($_POST['buscar'])) {
    if (empty($_POST['inicio']) || empty($_POST['fin'])) header("location: reporte_ventas.php");
}

if (isset($_POST['buscarPorUsuario'])) {
    if (empty($_POST['idUsuario'])) header("location: reporte_ventas.php");
}

if (isset($_POST['buscarPorCliente'])) {
    if (empty($_POST['idCliente'])) header("location: reporte_ventas.php");
}

$fechaInicio = (isset($_POST['inicio'])) ? $_POST['inicio'] : null;
$fechaFin = (isset($_POST['fin'])) ? $_POST['fin'] : null;
$usuario = (isset($_POST['idUsuario'])) ? $_POST['idUsuario'] : null;
$cliente = (isset($_POST['idCliente'])) ? $_POST['idCliente'] : null;

$ventas = obtenerVentas($fechaInicio, $fechaFin, $cliente, $usuario);

$cartas = [
    ["titulo" => "No. ventas", "icono" => "fa fa-shopping-cart", "total" => count($ventas), "color" => "#A71D45"],
    ["titulo" => "Total ventas", "icono" => "fa fa-money-bill", "total" => "$" . calcularTotalVentas($ventas), "color" => "#2A8D22"],
    ["titulo" => "Productos vendidos", "icono" => "fa fa-box", "total" => calcularProductosVendidos($ventas), "color" => "#223D8D"],
    ["titulo" => "Ganancia", "icono" => "fa fa-wallet", "total" => "$" . obtenerGananciaVentas($ventas), "color" => "#D55929"],
];

$clientes = obtenerClientes();
$usuarios = obtenerUsuarios();
?>

<div class="container">
    <h2 class="my-4">Reporte de ventas :
        <?php
        if (empty($fechaInicio)) echo HOY;
        if (isset($fechaInicio) && isset($fechaFin)) echo $fechaInicio . " al " . $fechaFin;
        ?>
    </h2>

    <?php include_once "cartas_totales.php" ?>


    <form class="row mb-2" method="post">
        <div class="col-5">
            <label for="inicio" class="form-label">Fecha busqueda inicial</label>
            <input type="date" name="inicio" class="form-control" id="inicio">
        </div>
        <div class="col-5">
            <label for="fin" class="form-label">Fecha busqueda final</label>
            <input type="date" name="fin" class="form-control" id="fin">
        </div>
        <div class="col">
            <input type="submit" name="buscar" value="Buscar" class="btn btn-primary mt-4">
        </div>
    </form>
    <div class="row mb-2">
        <div class="col">
            <form action="" method="post" class="row my-3">
                <div class="col-6">
                    <select class="form-select" aria-label="Default select example" name="idUsuario">
                        <option selected value="">Selecciona un usuario</option>
                        <?php foreach ($usuarios as $usuario) { ?>
                            <option value="<?= $usuario->id ?>"><?= $usuario->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-1">
                    <input type="submit" name="buscarPorUsuario" value="Buscar por usuario" class="btn btn-secondary">
                </div>
            </form>
        </div>
        <div class="col">
            <form action="" method="post" class="row my-3">
                <div class="col-6">
                    <select class="form-select" aria-label="Default select example" name="idCliente">
                        <option selected value="">Selecciona un cliente</option>
                        <?php foreach ($clientes as $cliente) { ?>
                            <option value="<?= $cliente->id ?>"><?= $cliente->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-1">
                    <input type="submit" name="buscarPorCliente" value="Buscar por cliente" class="btn btn-secondary">
                </div>
            </form>
        </div>
    </div>

    <?php if (count($ventas) > 0) { ?>
        <div class="card shadow-sm mb-5 border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="reportesVentas" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Usuario</th>
                                <th>Detalle</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ventas as $venta) { ?>
                                <tr>
                                    <td><?= $venta->id; ?></td>
                                    <td><?= $venta->fecha; ?></td>
                                    <td><?= $venta->cliente; ?></td>
                                    <td>$<?= $venta->total; ?></td>
                                    <td><?= $venta->usuario_id; ?></td>
                                    <td>
                                        <button  style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
                                         type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal<?= $venta->id; ?>">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <div class="container d-flex">
                                            <a title="Editar venta" class="btn btn-outline-secondary" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" href="editar_producto.php?id=<?= $producto->id; ?>">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a title="Eliminar venta" class="btn btn-outline-danger mx-2" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" href="eliminar_producto.php?id=<?= $producto->id; ?>">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <div class="text-center">
                                                <button title="Generar PDF" class="btn btn-outline-warning" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="generatePDF(<?= $venta->id; ?>)">
                                                     <i class="far fa-file-pdf"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center">
                    <button id="generarPDF-reportesVentas" class="btn btn-outline-danger " style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">Generar PDF de la tabla actual <i class="far fa-file-pdf"></i></button>
                </div>
            </div>
        <?php } ?>

        <?php if (count($ventas) < 1) { ?>
            <div class="alert alert-warning mt-3" role="alert">
                <h1>No se han encontrado ventas</h1>
            </div>
        <?php } ?>

        <!-- Modal -->
        <?php foreach ($ventas as $venta) { ?>
            <div class="modal fade" id="modal<?= $venta->id; ?>" tabindex="-1" aria-labelledby="modalLabel<?= $venta->id; ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel<?= $venta->id; ?>">Detalle de Venta #<?= $venta->id; ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($venta->productos as $producto) { ?>
                                        <tr>
                                            <td><?= $producto->nombre; ?></td>
                                            <td><?= $producto->cantidad; ?></td>
                                            <td>$<?= $producto->precio_unitario; ?></td>
                                            <td>$<?= $producto->cantidad * $producto->precio_unitario; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="generatePDF(<?= $venta->id; ?>)">Generar PDF</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>

        <?php
        include_once "footer.php"
        ?>

        <script>
            const spanishTranslations = {
                lengthMenu: "Mostrar _MENU_ registros",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                infoFiltered: "(filtrado de un total de _MAX_ registros)",
                sSearch: "Buscar:",
                oPaginate: {
                    sFirst: "Primero",
                    sLast: "Último",
                    sNext: "Siguiente",
                    sPrevious: "Anterior"
                },
                sProcessing: "Procesando...",
            }

            new DataTable('#reportesVentas', {
                language: spanishTranslations
            });

            function generatePDF(ventaId) {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF();

                const venta = <?php echo json_encode($ventas); ?>.find(v => v.id === ventaId);

                if (!venta) return;

                // Crear una matriz para los detalles de la venta
                const ventaData = [
                    ["Fecha", "Cliente", "Usuario", "Total"],
                    [venta.fecha, venta.cliente, venta.usuario_id, `$${venta.total}`],
                ];

                // Crear una matriz para los detalles de los productos vendidos
                const productosData = [
                    ["Producto", "Cantidad", "Precio Unitario", "Total"]
                ];
                venta.productos.forEach(producto => {
                    productosData.push([
                        producto.nombre,
                        producto.cantidad,
                        `$${producto.precio_unitario}`,
                        `$${producto.cantidad * producto.precio_unitario}`,
                    ]);
                });

                doc.text(`Detalle de Venta #${venta.id}`, 15, 10);

                // Agregar una tabla para los detalles de la venta
                doc.autoTable({
                    head: [ventaData[0]], // Títulos de las columnas
                    body: [ventaData[1]], // Datos de la venta
                    startY: 20,
                });

                // Agregar una tabla para los detalles de los productos vendidos
                doc.autoTable({
                    head: [productosData[0]], // Títulos de las columnas
                    body: productosData.slice(1), // Datos de los productos vendidos (sin el título)
                    startY: doc.autoTable.previous.finalY + 10,
                });

                doc.save(`Venta_${venta.id}.pdf`);
            }


            document.getElementById('generarPDF-reportesVentas').addEventListener('click', function() {
                const {
                    jsPDF
                } = window.jspdf;
                var doc = new jsPDF();
                // Establece los márgenes superiores para dar espacio al título

                // Agregar el título
                doc.text('Lista de ventas', 15, 10); // Ajusta la posición (105, 10) según tus necesidades

                doc.autoTable({
                    html: '#reportesVentas',
                    // Especifica qué columnas deseas incluir en el PDF (0-based index)
                    columns: [0, 1, 2, 3, 4, 5], // Esto excluye la última columna "Acciones"
                });
                doc.save('Lista_ventas.pdf');
            });
        </script>