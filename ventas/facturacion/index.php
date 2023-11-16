<?php
include_once "../funciones.php";

// Verificar si se proporciona un ID de venta o compra
$idVenta = isset($_GET['idVenta']) ? $_GET['idVenta'] : null;
$idCompra = isset($_GET['idCompra']) ? $_GET['idCompra'] : null;

if (empty($idVenta) && empty($idCompra)) {
  echo "No se ha proporcionado un ID válido.";
  exit;
}

if (!empty($idVenta)) {
  // Obtener información de la venta
  $venta = obtenerVentaPorId($idVenta);
  $cliente = obtenerClientePorId($venta->cliente_id);
  $categorias = obtenerCategoriasCliente();
  $categoriaIVA = obtenerCategoriaIVADeCliente($cliente->categoria_cliente_id);
  $productosVendidos = obtenerProductosVendidos($idVenta);

  // Resto del código de la vista para ventas...
} elseif (!empty($idCompra)) {
  // Obtener información de la compra
  $compra = obtenerCompraPorId($idCompra);
  $proveedor = obtenerProveedorPorId($compra->proveedor_id);
  $categorias = obtenerCategoriasProveedor();
  $productosComprados = obtenerProductosComprados($idCompra);
}

// $numeroFacturaActual = obtenerNumeroFactura();

// $nuevoNumeroFactura = incrementarNumeroFactura();



$productosVendidos = obtenerProductosVendidos($idVenta);
// echo "Productos vendidos";
// return $productosVendidos;
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Drinkstore-Facturacion</title>
  <link rel="stylesheet" href="style.css" media="all" />
</head>

<body>
  <header class="clearfix">
    <button id="printButton" class="no-print">Imprimir</button>
    <button class="no-print"><a href="../index.php" class="no-print">Volver al inicio</a></button>

    <div id="logo">
      <img src="logo.png">
    </div>
    <h1>FACTURA</h1>
    <div id="company" class="clearfix">
      <div>DrinkStore</div>
      <div>Av. Blas Parera,<br /> 6155, Posadas, Misiones.</div>
      <div>(54) 3765 - 230488</div>
      <div><a href="mailto:drinkstore@24hs.com">drinkstore@24hs.com</a></div>
    </div>
    <div id="project">
      <?php if (!empty($venta)) { ?>
        <!-- Información específica para ventas -->
        <div><span>Señor(es): </span> <?php echo $cliente->nombre; ?></div>
        <div><span>Domicilio: </span> <?php echo $cliente->direccion; ?></div>
        <div><span>Localidad: </span> <?php echo $cliente->email; ?></div>
        <?php foreach ($categorias as $key => $categoria) { ?>
          <?php if ($categoria->id === $categoriaIVA->id) { ?>
            <div><span>IVA</span> <?php echo $categoria->nombre; ?></div>
          <?php } ?>
        <?php } ?>
        <div><span>FECHA</span> <?php echo $venta->fecha; ?></div>
      <?php } elseif (!empty($compra)) { ?>
        <!-- Información específica para compras -->
        <div><span>Proveedor: </span> <?php echo $proveedor->nombre; ?></div>
        <div><span>Dirección: </span> <?php echo $proveedor->direccion; ?></div>
        <div><span>Teléfono: </span> <?php echo $proveedor->telefono; ?></div>
        <div><span>Email: </span> <?php echo $proveedor->email; ?></div>
        <!-- Agrega aquí más detalles de la compra según sea necesario -->
      <?php } else { ?>
        <!-- Manejar el caso donde no se proporciona un ID válido -->
        <div>No se ha proporcionado un ID válido.</div>
      <?php } ?>
    </div>
  </header>
  <main>
    <table>
      <thead>
        <tr>
          <th class="service">Cantidad</th>
          <th class="desc">Descripción</th>
          <th>P. Unitario</th>
          <th>Importe</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($venta)) { ?>
          <!-- Detalles de productos para ventas -->
          <?php
          $subtotal = 0;
          foreach ($productosVendidos as $producto) :
            $importe = $producto->precio_unitario * $producto->cantidad;
            $subtotal += $importe;
          ?>
            <tr>
              <td class="service"><?php echo $producto->cantidad; ?></td>
              <td class="desc"><?php echo $producto->nombre; ?></td>
              <td class="unit">$<?php echo $producto->precio_unitario; ?></td>
              <td class="total">$<?php echo $importe; ?></td>
            </tr>
          <?php endforeach;
          $iva = $subtotal * 0.21;
          $total = $subtotal + $iva;
          ?>
          <tr>
            <td class="blank" colspan="2"></td>
            <td class="total-line">Subtotal</td>
            <td class="total-value">$<?php echo $subtotal; ?></td>
          </tr>
          <tr>
            <td class="blank" colspan="2"></td>
            <td class="total-line">IVA (21%)</td>
            <td class="total-value">$<?php echo $iva; ?></td>
          </tr>
          <tr>
            <td class="blank" colspan="2"></td>
            <td class="total-line">Total</td>
            <td class="total-value">$<?php echo $total; ?></td>
          </tr>
        <?php } elseif (!empty($compra)) { ?>
          <!-- Detalles de productos para compras -->
          <?php
          $productosComprados = obtenerProductosComprados($idCompra); // Asume que tienes una función para obtener los productos comprados
          $subtotalCompra = 0;
          foreach ($productosComprados as $producto) :
            $importeCompra = $producto->precio_unitario * $producto->cantidad;
            $subtotalCompra += $importeCompra;
          ?>
            <tr>
              <td class="service"><?php echo $producto->cantidad; ?></td>
              <td class="desc"><?php echo $producto->nombre; ?></td>
              <td class="unit">$<?php echo $producto->precio_unitario; ?></td>
              <td class="total">$<?php echo $importeCompra; ?></td>
            </tr>
          <?php endforeach;
          $ivaCompra = $subtotalCompra * 0.21;
          $totalCompra = $subtotalCompra + $ivaCompra;
          ?>
          <tr>
            <td class="blank" colspan="2"></td>
            <td class="total-line">Subtotal</td>
            <td class="total-value">$<?php echo $subtotalCompra; ?></td>
          </tr>
          <tr>
            <td class="blank" colspan="2"></td>
            <td class="total-line">IVA (21%)</td>
            <td class="total-value">$<?php echo $ivaCompra; ?></td>
          </tr>
          <tr>
            <td class="blank" colspan="2"></td>
            <td class="total-line">Total</td>
            <td class="total-value">$<?php echo $totalCompra; ?></td>
          </tr>
          <?php
          // Agrega aquí la lógica para mostrar los productos comprados
          ?>
        <?php } ?>
      </tbody>
    </table>
    <div id="notices">
      <div>Este documento no tiene validez como factura</div>
    </div>
  </main>
  <footer>
    Creado con ❤ por Emiliano balanda y Villalba Leandro
  </footer>
</body>
<script>
  document.getElementById("printButton").addEventListener("click", function() {
    window.print();
  });
</script>

</html>