<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";

$_SESSION['lista'] = (isset($_SESSION['lista'])) ?  $_SESSION['lista'] : [];
$total = calcularTotalLista($_SESSION['lista']);
$clientes = obtenerClientes();
$clienteSeleccionado = (isset($_SESSION['clienteVenta'])) ? obtenerClientePorId($_SESSION['clienteVenta']) : null;

$fechaInicio = (isset($_POST['inicio'])) ? $_POST['inicio'] : null;
$fechaFin = (isset($_POST['fin'])) ? $_POST['fin'] : null;
$usuario = (isset($_POST['idUsuario'])) ? $_POST['idUsuario'] : null;
$cliente = (isset($_POST['idCliente'])) ? $_POST['idCliente'] : null;

$ventas = obtenerVentas($fechaInicio, $fechaFin, $cliente, $usuario);
?>
<div class="container mt-3">
    <form action="agregar_producto_venta.php" method="post" class="row" role="search">
        <div class="col-10">
            <input class="form-control form-control-lg" name="codigo" id="codigo" type="search" placeholder="Código de barras o nombre del producto" autocomplete="off" aria-label="codigoBarras">
            <div id="results"></div>
        </div>
        <div class="col">
            <input type="submit" value="Agregar" name="agregar" class="btn btn-success mt-2">
        </div>
    </form>
    <div>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Quitar</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($_SESSION['lista']) { ?>
                    <?php foreach ($_SESSION['lista'] as $key => $lista) { ?>
                        <tr>
                            <td><?php echo $lista->codigo; ?></td>
                            <td><?php echo $lista->nombre; ?></td>
                            <td><?php echo $lista->descripcion; ?></td>
                            <td>$<?php echo $lista->precio_venta; ?></td>
                            <td>
                                <div>
                                    <!-- <button type="button" class="btn border-0 btn-sm btn-outline-secondary shadow-sm  bg-body rounded text-dark" onclick="decrementarCantidad(<?php echo $key; ?>)">-</button> -->
                                    <input class="border-0 text-center" type="text" id="cantidad<?php echo $key; ?>" value="<?php echo $lista->cantidad; ?>">
                                    <!-- <button type="button" class="btn border-0 btn-sm btn-outline-secondary shadow-sm  bg-body rounded text-dark" onclick="incrementarCantidad(<?php echo $key; ?>)">+</button> -->
                                </div>
                            </td>
                            <td>$<?php echo floatval($lista->cantidad * $lista->precio_venta); ?></td>
                            <td>
                                <a href="quitar_producto_venta.php?id=<?php echo $lista->id ?>" class="btn btn-danger">
                                    <i class="fa fa-times"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

            </tbody>
        </table>
        <?php if (empty($_SESSION['lista'])) { ?>
            <div class="alert alert-info" role="alert">
                Todavía no has agregado ningún producto.
            </div>
        <?php } ?>
        <div class="row">

            <form class="row" method="post" action="establecer_cliente_venta.php">
                <div class="col-10">
                    <select class="form-select" aria-label="Default select example" name="idCliente">
                        <option selected value="">Selecciona el cliente</option>
                        <?php foreach ($clientes as $cliente) { ?>
                            <option value="<?php echo $cliente->id ?>"><?php echo $cliente->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-auto">
                    <input class="btn btn-info" type="submit" value="Seleccionar cliente">
                    </input>
                </div>
            </form>

            <?php if ($clienteSeleccionado) { ?>
                <div class="alert alert-primary mt-3" role="alert">
                    <b>Cliente seleccionado: </b>
                    <br>
                    <b>Nombre: </b> <?php echo $clienteSeleccionado->nombre ?><br>
                    <b>Teléfono: </b> <?php echo $clienteSeleccionado->telefono ?><br>
                    <b>Dirección: </b> <?php echo $clienteSeleccionado->direccion ?><br>
                    <a href="quitar_cliente_venta.php" class="btn btn-warning">Quitar</a>
                </div>
            <?php } ?>
            <div class="col-6 my-3">
                <label for="mediopago">Selecciona el medio de pago:</label>
                <select class="form-select" name="mediopago" id="mediopPago">
                    <?php
                    $mediosDePago = obtenerMediosDePago();
                    foreach ($mediosDePago as $medioPago) {
                        echo '<option value="' . $medioPago->id . '">' . $medioPago->nombre . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-6 my-3">
                <label for="iva">Selecciona el IVA:</label>
                <select class="form-select" name="iva" id="iva">
                    <?php
                    $ivas = obtenerIvas();
                    foreach ($ivas as $iva) {
                        echo '<option value="' . $iva->id . '">' . $iva->nombre . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="text-center mt-3">
                <h1>Total: $<?php echo $total; ?></h1>
                <a class="btn btn-primary btn-lg" href="javascript:void(0);" onclick="confirmarFactura()">
                    <i class="fa fa-check"></i>
                    Terminar venta
                </a>
                <a class="btn btn-danger btn-lg" href="cancelar_venta.php">
                    <i class="fa fa-times"></i>
                    Cancelar
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmarFactura() {
        Swal.fire({
            title: '¿Desea imprimir la factura?',
            text: 'Si lo desea, podrá imprimirla más tarde desde "Reporte Ventas"',
            icon: 'info',
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: 'Imprimir factura',
            cancelButtonText: 'No, gracias',
            confirmButtonColor: '#198754',
            cancelButtonColor: '#0b5ed7',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Usuario elige imprimir factura, redirige a registrar_venta.php con imprimir_factura=1
                window.location.href = "registrar_venta_y_facturar.php";
            } else {
                // Usuario elige no imprimir factura, redirige a donde desees
                window.location.href = "registrar_venta.php"; // Cambia "otra_pagina.php" por la URL deseada
            }
        });
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var codigoInput = document.getElementById("codigo");
        var resultsDiv = document.getElementById("results");

        codigoInput.addEventListener("input", function() {
            var searchValue = codigoInput.value.trim();

            if (searchValue === "") {
                resultsDiv.innerHTML = "";
                return;
            }

            var xhr = new XMLHttpRequest();

            xhr.open("GET", "buscar_productos.php?search=" + searchValue, true);

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    var data = JSON.parse(xhr.responseText);
                    showResults(data);
                } else {
                    console.error("Error en la solicitud AJAX: " + xhr.status);
                }
            };

            xhr.send();
        });

        function showResults(data) {
            resultsDiv.innerHTML = "";
            if (data.length > 0) {
                var resultList = document.createElement("ul");
                resultList.setAttribute("class", "list-group");
                resultList.style.position = "absolute";
                resultList.style.zIndex = "1000";

                data.forEach(function(product) {
                    var resultItem = document.createElement("li");
                    resultItem.textContent = product.codigo + " - " + product.nombre + " - " + product.descripcion + " - $" + product.precio_venta;
                    resultItem.setAttribute("class", "list-group-item border border-dark-subtle");

                    resultItem.addEventListener("click", function() {
                        codigoInput.value = product.codigo;
                        resultsDiv.innerHTML = "";
                    });

                    resultList.appendChild(resultItem);
                });
                resultsDiv.appendChild(resultList);
            } else {
                var noResultsItem = document.createElement("div");
                noResultsItem.textContent = "No se encontraron productos.";
                resultsDiv.appendChild(noResultsItem);
            }
        }
        document.addEventListener("click", function(event) {
            if (event.target !== codigoInput && event.target !== resultsDiv) {
                resultsDiv.innerHTML = "";
            }
        });
    });
</script>

<script>
    function incrementarCantidad(index) {
        var cantidadInput = document.getElementById('cantidad' + index);
        var cantidad = parseInt(cantidadInput.value);
        cantidad++;
        cantidadInput.value = cantidad;
        actualizarSubtotal(index);
        actualizarTotal();
    }

    function decrementarCantidad(index) {
        var cantidadInput = document.getElementById('cantidad' + index);
        var cantidad = parseInt(cantidadInput.value);
        if (cantidad > 1) {
            cantidad--;
            cantidadInput.value = cantidad;
            actualizarSubtotal(index);
            actualizarTotal();
        }
    }

    function actualizarSubtotal(index) {
        var cantidadInput = document.getElementById('cantidad' + index);
        var cantidad = parseInt(cantidadInput.value);
        var precio = parseFloat(<?php echo $_SESSION['lista'][0]->precio_venta; ?>); // Obtén el precio del producto desde la primera fila (puedes cambiar esto)
        var subtotal = cantidad * precio;
        var subtotalTd = document.querySelectorAll('td:nth-child(6)')[index];
        subtotalTd.textContent = '$' + subtotal.toFixed(2);
    }

    function actualizarTotal() {
        var subtotales = document.querySelectorAll('td:nth-child(6)');
        var total = 0;
        for (var i = 0; i < subtotales.length; i++) {
            total += parseFloat(subtotales[i].textContent.replace('$', ''));
        }
        document.querySelector('h1').textContent = 'Total: $' + total.toFixed(2);
    }
</script>