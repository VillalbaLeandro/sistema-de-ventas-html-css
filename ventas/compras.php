<?php
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}

include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
include_once "funciones_proveedores.php";

// Obtener la lista de proveedores
$proveedores = obtenerProveedores();

?>
<div class="container">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Registrar Compra</h3>
            <form method="post">
                <div class="mb-3">
                    <label for="producto" class="form-label">Selecciona el Producto</label>
                    <!-- Campo de búsqueda dinámica de productos -->
                    <input type="text" class="form-control" id="producto" placeholder="Código de barras o nombre del producto" autocomplete="off">
                    <div id="results" class="position-relative"></div>
                    <input type="hidden" name="producto_id" id="producto_id">
                </div>
                <div class="mb-3">
                    <label for="proveedor" class="form-label">Proveedor</label>
                    <select class="form-select" name="proveedor" id="proveedor" required>
                        <?php
                        foreach ($proveedores as $proveedor) {
                            echo "<option value='{$proveedor->id}'>{$proveedor->nombre}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha de Compra (opcional)</label>
                    <input type="date" name="fecha" class="form-control" id="fecha" placeholder="Fecha de compra">
                </div>
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad Comprada (Stock)</label>
                    <input type="number" name="cantidad" class="form-control" id="cantidad" placeholder="Cantidad comprada (Stock)" required>
                </div>
                <div class="mb-3">
                    <label for="precio_compra" class="form-label">Precio de Compra</label>
                    <input type="number" name="precio_compra" step="any" id="precio_compra" class="form-control" placeholder="Precio de compra" required>
                </div>
                <div class="mb-3">
                    <label for="precio_venta" class="form-label">Precio de Venta</label>
                    <input type="number" name="precio_venta" step="any" id="precio_venta" class="form-control" placeholder="Precio de venta" required>
                    <small id="precio_sugerido" class="form-text text-muted"></small>
                </div>
                <div class="text-center mt-3">
                    <input type="submit" id="registrar_compra" name="registrar_compra" value="Registrar Compra" class="btn btn-outline-success btn-lg">
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var productoInput = document.getElementById("producto");
        var resultsDiv = document.getElementById("results");
        var productoIdInput = document.getElementById("producto_id");
        var precioCompraInput = document.getElementById("precio_compra");
        var precioVentaInput = document.getElementById("precio_venta");
        var precioSugeridoText = document.getElementById("precio_sugerido");

        // Búsqueda dinámica de productos
        productoInput.addEventListener("input", function() {
            var searchValue = productoInput.value.trim();

            if (searchValue === "") {
                resultsDiv.innerHTML = "";
                productoIdInput.value = "";
                return;
            }

            // AJAX para buscar productos
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "buscar_productos.php?search=" + encodeURIComponent(searchValue), true);
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
                resultList.setAttribute("class", "list-group position-absolute");
                resultList.style.width = "100%";

                data.forEach(function(product) {
                    var resultItem = document.createElement("li");
                    resultItem.textContent = product.codigo + " - " + product.nombre + " - " + product.descripcion + " - $" + product.precio_venta;
                    resultItem.setAttribute("class", "list-group-item border border-dark-subtle");

                    // Evento click para seleccionar un producto
                    resultItem.addEventListener("click", function() {
                        productoInput.value = product.codigo + " - " + product.nombre;
                        productoIdInput.value = product.id;
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

        // Evento para calcular el precio sugerido cuando cambia el precio de compra
        precioCompraInput.addEventListener("input", function() {
            var precioCompra = parseFloat(precioCompraInput.value);
            if (!isNaN(precioCompra)) {
                var precioSugerido = (precioCompra * 1.4).toFixed(2);
                precioSugeridoText.textContent = "Precio sugerido +40%: $" + precioSugerido;
                precioVentaInput.value = precioSugerido; // Mostrar el precio sugerido en el campo de venta
            } else {
                precioSugeridoText.textContent = "";
                precioVentaInput.value = ""; // Limpiar si no hay un número válido
            }
        });

        // Ocultar los resultados cuando se hace clic fuera
        document.addEventListener("click", function(event) {
            if (event.target !== productoInput && event.target !== resultsDiv) {
                resultsDiv.innerHTML = "";
            }
        });
    });
</script>

<?php
include_once "footer.php";

if (isset($_POST['registrar_compra'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $proveedor_id = $_POST['proveedor'];

    if (empty($producto_id) || empty($cantidad) || empty($precio_compra) || empty($precio_venta) || empty($proveedor_id)) {
        echo '
        <div class="alert alert-danger mt-3" role="alert">
            Debes completar todos los datos.
        </div>';
    } else {
        $producto = obtenerProductoPorId($producto_id);
        if (!$producto) {
            echo '
            <div class="alert alert-danger mt-3" role="alert">
                Producto no encontrado. Verifica la selección del producto.
            </div>';
        } else {
            $idProducto = $producto->id;
            $compra_id = obtenerUltimoIdCompra();
            $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d H:i:s');
            $totalCompra = $cantidad * $precio_compra;
            $tipo_movimiento = 'Entrada'; // Esto se interpreta como entrada de dinero al registrar una compra.
            $tipo_transaccion = 'Compra';
            $saldo = obtenerSaldoActual($fecha, $fecha);

            if (registrarCompra($producto->codigo, $cantidad, $precio_compra, $precio_venta, $idProducto, $proveedor_id, $totalCompra)) {
                registrarMovimientoProducto($idProducto, 'Compra', $compra_id, null, $cantidad, $fecha);
                registrarEfectivoCaja($fecha, $totalCompra, 'Compra', null, null, 2, $compra_id);

                echo '
                <script>
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Compra registrada con éxito",
                        showConfirmButton: false,
                        timer: 1500
                    }).then((result) => {
                        window.location.href = "productos.php";
                    });
                </script>';
            } else {
                echo '
                <div class="alert alert-danger mt-3" role="alert">
                    Error al registrar la compra.
                </div>';
            }
        }
    }
}
