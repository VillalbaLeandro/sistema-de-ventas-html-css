<?php
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
session_start();
if (empty($_SESSION['idUsuario'])) header("location: login.php");

$usuarios = obtenerUsuarios();
?>
<div class="container">
    <h1>
        <a class="btn btn-outline-success btn-lg" href="agregar_usuario.php">
            <i class="fa fa-plus"></i>
            Agregar
        </a>
        Usuarios
    </h1>
    <div class="card  shadow-sm mb-5 border-0 ">
        <div class="card-body">
            <table class="table table-striped" id="usersLists" style="width:100%">

                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($usuarios as $usuario) {
                    ?>
                        <tr>
                            <td><?php echo $usuario->id; ?></td>
                            <td><?php echo $usuario->nombre; ?></td>
                            <td><?php echo $usuario->apellido; ?></td>
                            <td><?php echo $usuario->tel; ?></td>
                            <td><?php echo $usuario->direccion; ?></td>
                            <td><?php echo $usuario->email; ?></td>
                            <td><?php echo $usuario->rol; ?></td>
                            <td>
                                <a class="btn btn-outline-secondary " style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" href="editar_usuario.php?id=<?php echo $usuario->id; ?>">
                                    <i class="fa fa-edit"></i>
                                    Editar
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-outline-danger " style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" href="eliminar_usuario.php?id=<?php echo $usuario->id; ?>">
                                    <i class="fa fa-trash"></i>
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="text-center">
                <button id="generarPDF-usersLists" class="btn btn-outline-danger " style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">Generar PDF <i class="far fa-file-pdf"></i></button>
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

    new DataTable('#usersLists', {
        language: spanishTranslations
    });
    document.getElementById('generarPDF-usersLists').addEventListener('click', function() {
        const {
            jsPDF
        } = window.jspdf;
        var doc = new jsPDF();
        // Establece los márgenes superiores para dar espacio al título

        // Agregar el título
        doc.text('Lista de Usuarios', 15, 10); // Ajusta la posición (105, 10) según tus necesidades

        doc.autoTable({
            html: '#usersLists',
            // Especifica qué columnas deseas incluir en el PDF (0-based index)
            columns: [0, 1, 2, 3, 4, 5, 6], // Esto excluye la última columna "Acciones"
        });
        doc.save('Lista_usuarios.pdf');
    });
</script>