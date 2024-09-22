<?php
session_start();

if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}
include_once "encabezado.php";
include_once "navbar.php";
$id = $_GET['id'];
if (!$id) {
    echo 'No se ha seleccionado el producto';
    exit;
}
include_once "funciones.php";
$producto = obtenerProductoPorId($id);
?>

<div class="container">
    <h3>Editar producto</h3>
    <form method="post">
        <div class="mb-3">
            <label for="codigo" class="form-label">Código de barras</label>
            <input type="text" name="codigo" class="form-control" value="<?php echo $producto->codigo; ?>" id="codigo" placeholder="Escribe el código de barras del producto">
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $producto->nombre; ?>" id="nombre" placeholder="Ej. Papas">
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" name="descripcion" class="form-control" value="<?php echo $producto->descripcion; ?>" id="descripcion" placeholder="Ej. Brahma lata 710cc">
        </div>
        <div class="mb-3">
            <label for="categoria_id" class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select" id="categoria_id" required>
                <option value="">Selecciona una categoría</option>
                <?php
                try {
                    $categorias = obtenerCategorias();
                    if (count($categorias) > 0) {
                        foreach ($categorias as $categoria) {
                            $selected = ($categoria->id == $producto->categoria_id) ? 'selected' : '';
                            echo '<option value="' . $categoria->id . '" ' . $selected . '>' . $categoria->nombre . '</option>';
                        }
                    } else {
                        echo '<option value="">No hay categorías disponibles</option>';
                    }
                } catch (PDOException $e) {
                    echo '<option value="">Error al obtener categorías</option>';
                }
                ?>
            </select>
        </div>
        <div class="row">
            <div class="col">
                <label for="precio_costo" class="form-label">Precio costo</label>
                <input type="number" name="precio_costo" step="any" value="<?php echo $producto->precio_costo; ?>" id="precio_costo" class="form-control" placeholder="Precio de costo" aria-label="">
            </div>
            <div class="col">
                <label for="precio_venta" class="form-label">Precio venta</label>
                <input type="number" name="precio_venta" step="any" value="<?php echo $producto->precio_venta; ?>" id="precio_venta" class="form-control" placeholder="Precio para la venta" aria-label="">
            </div>
            <div class="col">
                <label for="stock_minimo" class="form-label">Stock Mínimo</label>
                <input type="number" name="stock_minimo" step="any" value="<?php echo $producto->stock_minimo; ?>" id="stock_minimo" class="form-control" placeholder="Stock mínimo" aria-label="">
            </div>
        </div>
        <div class="text-center mt-3">
            <input type="submit" name="registrar" value="Guardar" class="btn btn-outline-primary btn-lg">
            <a href="productos.php" class="btn btn-danger btn-lg">
                <i class="fa fa-times"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>

<?php
if (isset($_POST['registrar'])) {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $categoria_id = $_POST['categoria_id'];
    $precio_costo = $_POST['precio_costo'];
    $precio_venta = $_POST['precio_venta'];
    $stock_minimo = $_POST['stock_minimo'];

    if (empty($codigo) || empty($nombre) || empty($descripcion) || empty($categoria_id) || empty($precio_costo) || empty($precio_venta)) {
        echo '
        <div class="alert alert-danger mt-3" role="alert">
            Debes completar todos los datos.
        </div>';
        return;
    }

    include_once "funciones.php";
    $resultado = editarProducto($codigo, $nombre, $descripcion, $categoria_id, $precio_costo, $precio_venta, $stock_minimo, $id);

    if ($resultado) {
        echo "
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Producto modificado con éxito',
                showConfirmButton: false,
                timer: 1500
            }).then((result) => {
                window.location.href = 'productos.php';
            });
        </script>";
    }
}
?>
