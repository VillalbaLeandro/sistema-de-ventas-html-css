<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";

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
    ["titulo" => "No. ventas", "icono" => "fa fa-shopping-cart", "total" => count($ventas), "color" => "#ce4f74"],
    ["titulo" => "Total ventas", "icono" => "fa fa-money-bill", "total" => "$" . calcularTotalVentas($ventas), "color" => "#4fab47"],
    ["titulo" => "Productos vendidos", "icono" => "fa fa-box", "total" => calcularProductosVendidos($ventas), "color" => "#40579b"],
    ["titulo" => "Ganancia", "icono" => "fa fa-wallet", "total" => "$" . obtenerGananciaVentas($ventas), "color" => "#f67644"],
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
                                        <button style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal<?= $venta->id; ?>">
                                            <i class="far fa-eye"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <div class="container d-flex">
                                            <a title="Editar venta" class="btn btn-outline-secondary mx-2" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" href="editar_producto.php?id=<?= $producto->id; ?>">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <!-- <a title="Eliminar venta" class="btn btn-outline-danger mx-2" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" href="eliminar_producto.php?id=<?= $producto->id; ?>">
                                                <i class="fa fa-trash"></i>
                                            </a> -->
                                            <div class="text-center">
                                                <button title="Ver Factura" class="btn btn-outline-info" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="redirectToInvoice(<?= $venta->id; ?>)">
                                                    <i class="fas fa-receipt"></i>
                                                </button>

                                                <!-- <button title="Generar PDF" class="btn btn-outline-warning" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="generatePDF(<?= $venta->id; ?>)">
                                                    <i class="far fa-file-pdf"></i>
                                                </button> -->
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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>


        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

        <script>
            function redirectToInvoice(idVenta) {
                window.location.href = './facturacion/index.php?idVenta=' + idVenta;
            }
        </script>

        <script>
            // const spanishTranslations = {
            //     lengthMenu: "Mostrar _MENU_ registros",
            //     zeroRecords: "No se encontraron resultados",
            //     info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            //     infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
            //     infoFiltered: "(filtrado de un total de _MAX_ registros)",
            //     sSearch: "Buscar:",
            //     oPaginate: {
            //         sFirst: "Primero",
            //         sLast: "Último",
            //         sNext: "Siguiente",
            //         sPrevious: "Anterior"
            //     },
            //     sProcessing: "Procesando...",
            // }

            // new DataTable('#reportesVentas', {
            //     language: spanishTranslations
            // });



            let datatable;
            let dataTableIsInitialized = false;
            let dataTableOptions = {
                dom: 'Bfrtilp',
                lengthMenu: [10, 15, 50],
                buttons: [{
                        extend: 'pdfHtml5',
                        text: '<i class="far fa-file-pdf"></i>',
                        titleAttr: 'Exportar a pdf.',
                        className: "btn btn-outline-danger",
                        title: 'Lista de Productos',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },


                    },
                    {
                        extend: 'copyHtml5',
                        text: '<i class="far fa-copy"></i>',
                        titleAttr: 'Copiar Tabla.',
                        className: "btn btn-outline-dark",
                        title: 'Lista de Productos',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },

                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="far fa-file-excel"></i>',
                        titleAttr: 'Exportar a Excel.',
                        className: "btn btn-outline-success",
                        title: 'Lista de Productos',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },

                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i>',
                        titleAttr: 'Imprimir',
                        className: "btn btn-outline-info",
                        title: 'Lista de Productos',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        },

                    }
                ],
                columnDefs: [{
                        //deshabilitar el ordenamiento para una columnna
                        orderable: false,
                        target: [6, 0]
                    },
                    {
                        //deshabilitar que el buscador para las columnas en target
                        searcheable: false,
                        target: [6]
                    },

                    {
                        //especificar un tamaño para una o mas columnas por su posicion
                        width: '5%',
                        target: [6]
                    }
                ],
                language: {
                    "processing": "Procesando...",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "zeroRecords": "No se encontraron resultados",
                    "emptyTable": "Ningún dato disponible en esta tabla",
                    "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "search": "Buscar:",
                    "loadingRecords": "Cargando...",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sortDescending": ": Activar para ordenar la columna de manera descendente"
                    },
                    "buttons": {
                        "copy": "Copiar",
                        "colvis": "Visibilidad",
                        "collection": "Colección",
                        "colvisRestore": "Restaurar visibilidad",
                        "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
                        "copySuccess": {
                            "1": "Copiada 1 fila al portapapeles",
                            "_": "Copiadas %ds fila al portapapeles"
                        },
                        "copyTitle": "Copiar al portapapeles",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Mostrar todas las filas",
                            "_": "Mostrar %d filas"
                        },
                        "pdf": "PDF",
                        "print": "Imprimir",
                        "renameState": "Cambiar nombre",
                        "updateState": "Actualizar",
                        "createState": "Crear Estado",
                        "removeAllStates": "Remover Estados",
                        "removeState": "Remover",
                        "savedStates": "Estados Guardados",
                        "stateRestore": "Estado %d"
                    },
                    "autoFill": {
                        "cancel": "Cancelar",
                        "fill": "Rellene todas las celdas con <i>%d<\/i>",
                        "fillHorizontal": "Rellenar celdas horizontalmente",
                        "fillVertical": "Rellenar celdas verticalmente"
                    },
                    "decimal": ",",
                    "searchBuilder": {
                        "add": "Añadir condición",
                        "button": {
                            "0": "Constructor de búsqueda",
                            "_": "Constructor de búsqueda (%d)"
                        },
                        "clearAll": "Borrar todo",
                        "condition": "Condición",
                        "conditions": {
                            "date": {
                                "before": "Antes",
                                "between": "Entre",
                                "empty": "Vacío",
                                "equals": "Igual a",
                                "notBetween": "No entre",
                                "not": "Diferente de",
                                "after": "Después",
                                "notEmpty": "No Vacío"
                            },
                            "number": {
                                "between": "Entre",
                                "equals": "Igual a",
                                "gt": "Mayor a",
                                "gte": "Mayor o igual a",
                                "lt": "Menor que",
                                "lte": "Menor o igual que",
                                "notBetween": "No entre",
                                "notEmpty": "No vacío",
                                "not": "Diferente de",
                                "empty": "Vacío"
                            },
                            "string": {
                                "contains": "Contiene",
                                "empty": "Vacío",
                                "endsWith": "Termina en",
                                "equals": "Igual a",
                                "startsWith": "Empieza con",
                                "not": "Diferente de",
                                "notContains": "No Contiene",
                                "notStartsWith": "No empieza con",
                                "notEndsWith": "No termina con",
                                "notEmpty": "No Vacío"
                            },
                            "array": {
                                "not": "Diferente de",
                                "equals": "Igual",
                                "empty": "Vacío",
                                "contains": "Contiene",
                                "notEmpty": "No Vacío",
                                "without": "Sin"
                            }
                        },
                        "data": "Data",
                        "deleteTitle": "Eliminar regla de filtrado",
                        "leftTitle": "Criterios anulados",
                        "logicAnd": "Y",
                        "logicOr": "O",
                        "rightTitle": "Criterios de sangría",
                        "title": {
                            "0": "Constructor de búsqueda",
                            "_": "Constructor de búsqueda (%d)"
                        },
                        "value": "Valor"
                    },
                    "searchPanes": {
                        "clearMessage": "Borrar todo",
                        "collapse": {
                            "0": "Paneles de búsqueda",
                            "_": "Paneles de búsqueda (%d)"
                        },
                        "count": "{total}",
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Sin paneles de búsqueda",
                        "loadMessage": "Cargando paneles de búsqueda",
                        "title": "Filtros Activos - %d",
                        "showMessage": "Mostrar Todo",
                        "collapseMessage": "Colapsar Todo"
                    },
                    "select": {
                        "cells": {
                            "1": "1 celda seleccionada",
                            "_": "%d celdas seleccionadas"
                        },
                        "columns": {
                            "1": "1 columna seleccionada",
                            "_": "%d columnas seleccionadas"
                        },
                        "rows": {
                            "1": "1 fila seleccionada",
                            "_": "%d filas seleccionadas"
                        }
                    },
                    "thousands": ".",
                    "datetime": {
                        "previous": "Anterior",
                        "hours": "Horas",
                        "minutes": "Minutos",
                        "seconds": "Segundos",
                        "unknown": "-",
                        "amPm": [
                            "AM",
                            "PM"
                        ],
                        "months": {
                            "0": "Enero",
                            "1": "Febrero",
                            "10": "Noviembre",
                            "11": "Diciembre",
                            "2": "Marzo",
                            "3": "Abril",
                            "4": "Mayo",
                            "5": "Junio",
                            "6": "Julio",
                            "7": "Agosto",
                            "8": "Septiembre",
                            "9": "Octubre"
                        },
                        "weekdays": {
                            "0": "Dom",
                            "1": "Lun",
                            "2": "Mar",
                            "4": "Jue",
                            "5": "Vie",
                            "3": "Mié",
                            "6": "Sáb"
                        },
                        "next": "Próximo"
                    },
                    "editor": {
                        "close": "Cerrar",
                        "create": {
                            "button": "Nuevo",
                            "title": "Crear Nuevo Registro",
                            "submit": "Crear"
                        },
                        "edit": {
                            "button": "Editar",
                            "title": "Editar Registro",
                            "submit": "Actualizar"
                        },
                        "remove": {
                            "button": "Eliminar",
                            "title": "Eliminar Registro",
                            "submit": "Eliminar",
                            "confirm": {
                                "_": "¿Está seguro de que desea eliminar %d filas?",
                                "1": "¿Está seguro de que desea eliminar 1 fila?"
                            }
                        },
                        "error": {
                            "system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\/a&gt;).<\/a>"
                        },
                        "multi": {
                            "title": "Múltiples Valores",
                            "restore": "Deshacer Cambios",
                            "noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo.",
                            "info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, haga clic o pulse aquí, de lo contrario conservarán sus valores individuales."
                        }
                    },
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "stateRestore": {
                        "creationModal": {
                            "button": "Crear",
                            "name": "Nombre:",
                            "order": "Clasificación",
                            "paging": "Paginación",
                            "select": "Seleccionar",
                            "columns": {
                                "search": "Búsqueda de Columna",
                                "visible": "Visibilidad de Columna"
                            },
                            "title": "Crear Nuevo Estado",
                            "toggleLabel": "Incluir:",
                            "scroller": "Posición de desplazamiento",
                            "search": "Búsqueda",
                            "searchBuilder": "Búsqueda avanzada"
                        },
                        "removeJoiner": "y",
                        "removeSubmit": "Eliminar",
                        "renameButton": "Cambiar Nombre",
                        "duplicateError": "Ya existe un Estado con este nombre.",
                        "emptyStates": "No hay Estados guardados",
                        "removeTitle": "Remover Estado",
                        "renameTitle": "Cambiar Nombre Estado",
                        "emptyError": "El nombre no puede estar vacío.",
                        "removeConfirm": "¿Seguro que quiere eliminar %s?",
                        "removeError": "Error al eliminar el Estado",
                        "renameLabel": "Nuevo nombre para %s:"
                    },
                    "infoThousands": "."
                }


            }


            $(document).ready(function() {
                $('#reportesVentas').DataTable(dataTableOptions);
            });

            // reubica el menu de cantidad de registros
            $(document).ready(function() {
                var lengthSelect = $('#reportesVentas_length');
                var dtButtons = $('.dt-buttons');

                lengthSelect.detach(); // Elimina el elemento del DOM original
                lengthSelect.insertAfter(dtButtons); // Agrega el elemento al comienzo del div.dt-buttons
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