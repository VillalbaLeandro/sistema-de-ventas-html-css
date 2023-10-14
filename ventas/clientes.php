<?php
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
session_start();

if (empty($_SESSION['nombre'])) header("location: login.php");

$clientes = obtenerClientes();
?>
<div class="container">
    <h1>
        <a class="btn btn-outline-success btn-lg" href="agregar_cliente.php">
            <i class="fa fa-plus"></i>
            Agregar
        </a>
        Clientes
    </h1>

    <div class="card shadow-sm mb-5 border-0">
        <div class="card-body">
            <div class="table-responsive"> <!-- Agregar esta clase para hacer la tabla responsive -->
                <table class="table table-striped" id="clientesLista" style="max-width: 100%; font-size: 14px;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Fecha de Nac.</th>
                            <th>Email</th>
                            <th>cuil/cuit</th>
                            <th>Categoria</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($clientes as $cliente) {
                        ?>
                            <tr>
                                <td><?php echo $cliente->id; ?></td>
                                <td><?php echo $cliente->nombre; ?></td>
                                <td><?php echo $cliente->apellido; ?></td>
                                <td><?php echo $cliente->telefono; ?></td>
                                <td><?php echo $cliente->direccion; ?></td>
                                <td><?php echo $cliente->fechaNacimiento; ?></td>
                                <td><?php echo $cliente->email; ?></td>
                                <td><?php echo $cliente->cuil_cuit; ?></td>
                                <td><?php echo $cliente->categoria; ?></td>
                                <td>
                                    <div class="container d-flex">
                                        <a title="Editar" class="btn btn-outline-secondary" href="editar_cliente.php?id=<?php echo $cliente->id; ?>" #clientesLista
                                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                            <i class="fa fa-edit"></i>
                                        <a title="Eliminar" class="btn btn-outline-danger ms-2" href="eliminar_cliente.php?id=<?php echo $cliente->id; ?>"
                                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center">
                <button id="generarPDF-clientesLista" class="btn btn-outline-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">Generar PDF <i class="far fa-file-pdf"></i></button>
            </div>
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

    new DataTable('#clientesLista', {
        language: spanishTranslations
    });
    document.getElementById('generarPDF-clientesLista').addEventListener('click', function() {
        const {
            jsPDF
        } = window.jspdf;
        var doc = new jsPDF();

        // Establece los márgenes superiores para dar espacio al título

        // Agregar el título
        doc.text('Lista de clientes', 15, 10); // Ajusta la posición (15, 10) según tus necesidades

        doc.autoTable({
            html: '#clientesLista',
            columns: [1, 2, 3, 4, 5, 6, 7, 8], // Esto excluye la última columna "Acciones"

            // Especifica qué columnas deseas incluir en el PDF (0-based index)
        });
        doc.save('Lista_clientes.pdf');
    });
</script>