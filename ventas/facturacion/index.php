<?php

include_once "../funciones.php";
// Obtener el idVenta desde la URL
$idVenta = isset($_GET['idVenta']) ? $_GET['idVenta'] : null;

// Verificar que el idVenta no esté vacío
if (empty($idVenta)) {
  // Manejar el caso en que el idVenta no esté presente
  echo "No se ha proporcionado un ID de venta válido.";
  exit;
}

// Consultar la base de datos para obtener los datos de la venta y el cliente
$venta = obtenerVentaPorId($idVenta);
$cliente = obtenerClientePorId($venta->cliente_id);
$categorias = obtenerCategoriasCliente();
$categoriaIVA = obtenerCategoriaIVADeCliente($cliente->categoria_cliente_id);

$numeroFacturaActual = obtenerNumeroFactura();

$nuevoNumeroFactura = incrementarNumeroFactura();


// Verificar que se haya encontrado la venta y el cliente
if (!$venta || !$cliente) {
  // Manejar el caso en que no se encuentre la venta o el cliente
  echo "No se ha encontrado la venta o el cliente correspondiente.";
  exit;
}

// Consultar los productos vendidos en esta venta
$productosVendidos = obtenerProductosVendidos($idVenta);

// Ahora puedes usar $venta, $cliente y $productosVendidos para llenar la factura
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
    <center>
      <!-- <h2><b>A</b></h2> -->
      <!-- <h4> Nº 00001 - <?php echo sprintf('%06d', $numeroFacturaActual); ?></h4> -->
    </center>
    <div id="company" class="clearfix">
      <div>DrinkStore</div>
      <div>Av. Blas Parera,<br /> 6155, Posadas, Misiones.</div>
      <div>(54) 3765 - 230488</div>
      <div><a href="mailto:drinkstore@24hs.com">drinkstore@24hs.com</a></div>
    </div>
    <div id="project">
      <div><span>Señor(es): </span> <?php echo $cliente->nombre; ?></div>
      <div><span>Domicilio: </span> <?php echo $cliente->direccion; ?></div>
      <div><span>Localidad: </span> <?php echo $cliente->email; ?></div>
      <?php foreach ($categorias as $key => $categoria) { ?>
        <?php if ($categoria->id === $categoriaIVA->id) { ?>
          <div><span>IVA</span> <?php echo $categoria->nombre; ?></div>
        <?php } ?>
      <?php } ?>
      <div><span>FECHA</span> <?php echo $venta->fecha; ?></div>
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