<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
$cartas = [
	["titulo" => "Total ventas", "icono" => "fa fa-money-bill", "total" => "$" . obtenerTotalVentas(), "color" => "#ce4f74 "],
	["titulo" => "Ventas hoy", "icono" => "fa fa-calendar-day", "total" => "$" . obtenerTotalVentasHoy(), "color" => "#4fab47 "],
	["titulo" => "Ventas semana", "icono" => "fa fa-calendar-week", "total" => "$" . obtenerTotalVentasSemana(), "color" => "#40579b "],
	["titulo" => "Ventas mes", "icono" => "fa fa-calendar-alt", "total" => "$" . obtenerTotalVentasMes(), "color" => "#f67644 "],
];

$totales = [
	["nombre" => "Total productos", "total" => obtenerNumeroProductos(), "imagen" => "../public/images/index/productos.png"],
	["nombre" => "Ventas registradas", "total" => obtenerNumeroVentas(), "imagen" => "../public/images/index/ventas.png"],
	["nombre" => "Usuarios registrados", "total" => obtenerNumeroUsuarios(), "imagen" => "../public/images/index/usuarios.png"],
	["nombre" => "Clientes registrados", "total" => obtenerNumeroClientes(), "imagen" => "../public/images/index/clientes.png"],
];

$ventasUsuarios = obtenerVentasPorUsuario();
$ventasClientes = obtenerVentasPorCliente();
$productosMasVendidos = obtenerProductosMasVendidos();
?>

<div class="container">
	<div class="alert alert-info" role="alert">
		<h1>
			Hola, <?= $_SESSION['nombre'] ?>
		</h1>
	</div>
	<?php include_once "cartas_totales.php" ?>

	<div class="card-deck row mb-2">
		<?php foreach ($totales as $total) { ?>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<div class="card text-center shadow-sm mb-5 border-0 " style="height: 82%">
					<div class="card-body">
						<img class="img-thumbnail" style="height: 40%" src="<?= $total['imagen'] ?>" alt="">
						<h4 class="card-title">
							<?= $total['nombre'] ?>
						</h4>
						<h2><?= $total['total'] ?></h2>

					</div>

				</div>
			</div>
		<?php } ?>
	</div>


	<div class="row mt-2">
		<div class="col">
			<h4>Ventas por usuarios</h4>
			<div class="card shadow-sm mb-5 border-0 min-h-30">
				<div class="card-body">

					<table class="table table-striped " id="ventaPorUsuarios" style="width:100%">
						<thead>
							<tr>
								<th>Nombre usuario</th>
								<th>Número ventas</th>
								<th>Total ventas</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($ventasUsuarios as $usuario) { ?>
								<tr>
									<td><?= $usuario->usuario ?></td>
									<td><?= $usuario->numeroVentas ?></td>
									<td>$<?= $usuario->total ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<div class="text-center mt-2">
						<button id="generarPDF-ventaPorUsuarios" class="btn btn-outline-danger " style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">Generar PDF <i class="far fa-file-pdf"></i></button>
					</div>

				</div>
			</div>
		</div>
		<div class="col">
			<h4>Ventas por clientes</h4>
			<div class="card  shadow-sm mb-5 border-0 ">
				<div class="card-body">
					<table class="table table-striped" id="ventaPorClientes" style="width:100%">
						<thead>
							<tr>
								<th>Nombre cliente</th>
								<th>Número compras</th>
								<th>Total ventas</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($ventasClientes as $cliente) { ?>
								<tr>
									<td><?= $cliente->cliente ?></td>
									<td><?= $cliente->numeroCompras ?></td>
									<td>$<?= $cliente->total ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<div class="text-center mt-2">
						<button id="generarPDF-ventaPorClientes" class="btn btn-outline-danger " style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">Generar PDF <i class="far fa-file-pdf"></i></button>
					</div>

				</div>
			</div>
		</div>
	</div>

	<h4 class="mb-1">10 Productos más vendidos</h4>
	<div class="card  shadow-sm mb-5 border-0 ">
		<div class="card-body">
			<table class="table table-striped" id="productosMasVendidos" style="width:100%">
				<thead>
					<tr class="table-primary ">
						<th>Producto</th>
						<th>Unidades vendidas</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($productosMasVendidos as $producto) { ?>
						<tr>
							<td><?= $producto->nombre ?></td>
							<td><?= $producto->unidades ?></td>
							<td>$<?= $producto->total ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="text-center mt-2">
				<button id="generarPDF-productosMasVendidos" class="btn btn-outline-danger " style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">Generar PDF <i class="far fa-file-pdf"></i></button>
			</div>

		</div>
	</div>
	<?php
	include_once "footer.php"
	?>
</div>

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
    sPrevious: "Anterior",
  },
  sProcessing: "Procesando...",
};

new DataTable("#ventaPorUsuarios", {
  language: spanishTranslations,
  lengthMenu: [5, 10, 50]
});

// Evento de clic en el botón
document
  .getElementById("generarPDF-ventaPorUsuarios")
  .addEventListener("click", function () {
    // Crear un nuevo documento PDF
    const { jsPDF } = window.jspdf;
    var doc = new jsPDF();
    doc.text("Ventas por Usuario", 15, 10);

    // Agregar la tabla al documento PDF
    doc.autoTable({
      html: "#ventaPorUsuarios", // ID de la tabla en tu HTML
    });
    // Guardar o descargar el PDF
    doc.save("reporte_ventas_por_usuario.pdf");
  });

new DataTable("#ventaPorClientes", {
  language: spanishTranslations,
  lengthMenu: [5, 10, 50]

});
document
  .getElementById("generarPDF-ventaPorClientes")
  .addEventListener("click", function () {
    const { jsPDF } = window.jspdf;
    var doc = new jsPDF();
    doc.text("Ventas por Cliente", 15, 10);

    doc.autoTable({
      html: "#ventaPorClientes",
    });
    doc.save("reporte_ventas_por_clientes.pdf");
  });

new DataTable("#productosMasVendidos", {
  language: spanishTranslations,
  lengthMenu: [5, 10, 50]

});
document
  .getElementById("generarPDF-productosMasVendidos")
  .addEventListener("click", function () {
    const { jsPDF } = window.jspdf;
    var doc = new jsPDF();
    doc.text("Productos mas vendidos", 15, 10);
    doc.autoTable({
      html: "#productosMasVendidos",
    });
    doc.save("reporte_productos_mas_vendidos.pdf");
  });

</script>