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
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Registrar Compra</h3>
            <form method="post">
                <div class="mb-3">
                    <label for="codigo" class="form-label">Código de barras</label>
                    <input class="form-control form-control-lg" name="codigo" id="codigo" type="search" placeholder="Código de barras" autocomplete="off" aria-label="codigoBarras">
                    <div id="results"></div>
                    <div id="error" class="text-danger"></div>
                </div>
                <div class="mb-3">
                    <label for="proveedor" class="form-label">Proveedor</label>
                    <select class="form-select" name="proveedor" id="proveedor" required>
                        <?php
                        $proveedores = obtenerProveedores();
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
                </div>
                <div class="text-center mt-3">
                    <input type="submit" id="registrar_compra" name="registrar_compra" value="Registrar Compra" class="btn btn-outline-success btn-lg">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var codigoInput = document.getElementById("codigo");
        var resultsDiv = document.getElementById("results");
        var errorDiv = document.getElementById("error");
        var registrarButton = document.getElementById("registrar_compra");

        codigoInput.addEventListener("input", function() {
            var searchValue = codigoInput.value.trim();

            if (searchValue === "") {
                resultsDiv.innerHTML = "";
                errorDiv.innerHTML = "";
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
                        errorDiv.innerHTML = "";
                    });

                    resultList.appendChild(resultItem);
                });
                resultsDiv.appendChild(resultList);
                registrarButton.disabled = false; // Habilitar el botón
            } else {
                errorDiv.innerHTML = "Producto no encontrado. Verifica el código de barras o crea el producto antes de continuar";
                resultsDiv.innerHTML = "";
                registrarButton.disabled = true; // Deshabilitar el botón
            }
        }

        document.addEventListener("click", function(event) {
            if (event.target !== codigoInput && event.target !== resultsDiv) {
                resultsDiv.innerHTML = "";
            }
        });
    });
</script>

<?php
include_once "footer.php";

if (isset($_POST['registrar_compra'])) {
    $codigo = $_POST['codigo'];
    $cantidad = $_POST['cantidad'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $proveedor_id = $_POST['proveedor'];

    if (empty($codigo) || empty($cantidad) || empty($precio_compra) || empty($precio_venta) || empty($proveedor_id)) {
        echo '
        <div class="alert alert-danger mt-3" role="alert">
            Debes completar todos los datos.
        </div>';
    } else {
        $producto = obtenerProductoPorCodigo($codigo);
        if (!$producto) {
            echo '
            <div class="alert alert-danger mt-3" role="alert">
                Producto no encontrado. Verifica el código de barras o crea el producto antes de continuar.
            </div>';
        } else {
            $idProducto = $producto->id;
            $compra_id = obtenerUltimoIdCompra();
            $venta_id = null;
            $cantidad = $cantidad;
            $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d H:i:s'); // Si se proporciona una fecha, úsala; de lo contrario, usa la fecha actual.

            // Modificación para obtener el total de la compra
            $totalCompra = $cantidad * $precio_compra;

            if (registrarCompra($codigo, $cantidad, $precio_compra, $precio_venta, $idProducto, $proveedor_id, $totalCompra)) {

                $tipoMovimiento = 'Compra';
                $compraId = obtenerUltimoIdCompra();
                registrarMovimientoProducto($idProducto, 'Compra', $compraId, null, $cantidad, $fecha);
                registrarEfectivoCaja($fecha, $totalCompra, 'Compra', null, null, 2, $compraId); // Ajusta el valor del tipo según tu esquema

                echo '
                <script>
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Compra registrada con éxito",
                        showConfirmButton: false,
                        timer: 1500
                    }).then((result) => {
                        // Redirige a la página de resumen de la compra o a donde desees.
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
?>