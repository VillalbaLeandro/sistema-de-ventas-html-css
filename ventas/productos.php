<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";

$nombreProducto = (isset($_POST['nombreProducto'])) ? $_POST['nombreProducto'] : null;

$productos = obtenerProductos($nombreProducto);

$cartas = [
    ["titulo" => "No. Productos", "icono" => "fa fa-box", "total" => count($productos), "color" => "#3578FE"],
    ["titulo" => "Total productos", "icono" => "fa fa-shopping-cart", "total" => obtenerNumeroProductos(), "color" => "#4F7DAF"],
    ["titulo" => "Total inventario", "icono" => "fa fa-money-bill", "total" => "$" . obtenerTotalInventario(), "color" => "#1FB824"],
    ["titulo" => "Ganancia", "icono" => "fa fa-wallet", "total" => "$" . calcularGananciaProductos(), "color" => "#D55929"],
];
?>
<div class="container mt-3">
    <h1>
        <a class="btn btn-outline-success btn-lg" href="agregar_producto.php">
            <i class="fa fa-plus"></i>
            Agregar
        </a>
        Productos
    </h1>
    <?php include_once "cartas_totales.php"; ?>

    <div class="container">
        <div class="card  shadow-sm mb-5 border-0 ">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="productsList" style="width:100%">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Precio costo</th>
                                <th>Precio venta</th>
                                <th>Ganancia</th>
                                <th>Stock</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($productos as $producto) {
                            ?>
                                <tr>
                                    <td><?= $producto->codigo; ?></td>
                                    <td><?= $producto->nombre; ?></td>
                                    <td><?= '$' . $producto->precio_costo; ?></td>
                                    <td><?= '$' . $producto->precio_venta; ?></td>
                                    <td><?= '$' . floatval($producto->precio_venta - $producto->precio_costo); ?></td>
                                    <td><?= $producto->stock; ?></td>
                                    <td class="d-flex">

                                        <div class="container d-flex">
                                            <a class="btn btn-outline-secondary" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" href="editar_producto.php?id=<?= $producto->id; ?>">
                                                <i class="fa fa-edit"></i>
                                                Editar
                                            </a>
                                            <a class="btn btn-outline-danger ms-2" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" href="eliminar_producto.php?id=<?= $producto->id; ?>">
                                                <i class="fa fa-trash"></i>
                                                Eliminar
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center">
                    <button id="generarPDF-productsList" class="btn btn-outline-danger " style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">Generar PDF <i class="far fa-file-pdf"></i></button>
                </div>
            </div class="card-body">
        </div>
    </div>
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

    new DataTable('#productsList', {
        language: spanishTranslations
    });
    document.getElementById('generarPDF-productsList').addEventListener('click', function() {
        const {
            jsPDF
        } = window.jspdf;
        var doc = new jsPDF();
        // Establece los márgenes superiores para dar espacio al título

        // Agregar el título
        doc.text('Lista de Producto', 15, 10); // Ajusta la posición (105, 10) según tus necesidades

        doc.autoTable({
            html: '#productsList',
            // Especifica qué columnas deseas incluir en el PDF (0-based index)
            columns: [0, 1, 2, 3, 4, 5], // Esto excluye la última columna "Acciones"
        });
        doc.save('Lista_productos.pdf');
    });
    var table = $('#myTable').DataTable();

    new $.fn.dataTable.Buttons(table, {
        buttons: [
            'copy', 'excel', 'pdf'
        ]
    });

    table.buttons().container()
        .appendTo($('.col-sm-6:eq(0)', table.table().container()));
</script>