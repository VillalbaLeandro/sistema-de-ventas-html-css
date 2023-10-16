<?php
define("PASSWORD_PREDETERMINADA", "admin");
define("HOY", date("Y-m-d"));
function select($sentencia, $parametros = [])
{
    $bd = conectarBaseDatos();
    $respuesta = $bd->prepare($sentencia);
    $respuesta->execute($parametros);
    return $respuesta->fetchAll();
}
function obtenerTotalVentas($idUsuario = null)
{
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total), 0) AS total FROM venta";
    if (isset($idUsuario)) {
        $sentencia .= " WHERE usuario_id = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if ($fila) return  number_format($fila[0]->total, 2);
}

function obtenerTotalVentasHoy($idUsuario = null)
{
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total), 0) AS total FROM venta WHERE DATE(fecha) = CURDATE()";
    if (isset($idUsuario)) {
        $sentencia .= " AND usuario_id = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if ($fila) return number_format($fila[0]->total, 2);
}
function obtenerTotalVentasSemana($idUsuario = null)
{
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total), 0) AS total FROM venta WHERE WEEK(fecha) = WEEK(NOW())";
    if (isset($idUsuario)) {
        $sentencia .= " AND usuario_id = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if ($fila) return number_format($fila[0]->total, 2);
}


function obtenerTotalVentasMes($idUsuario = null)
{
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total), 0) AS total FROM venta WHERE MONTH(fecha) = MONTH(CURRENT_DATE()) AND YEAR(fecha) = YEAR(CURRENT_DATE())";
    if (isset($idUsuario)) {
        $sentencia .= " AND usuario_id = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if ($fila) return  number_format($fila[0]->total, 2);
}


function obtenerNumeroProductos()
{
    $sentencia = "SELECT IFNULL(SUM(stock),0) AS total FROM producto";
    $fila = select($sentencia);
    if ($fila) return $fila[0]->total;
}
function obtenerNumeroVentas()
{
    $sentencia = "SELECT IFNULL(COUNT(*),0) AS total FROM venta";
    return select($sentencia)[0]->total;
}

function obtenerNumeroUsuarios()
{
    $sentencia = "SELECT IFNULL(COUNT(*),0) AS total FROM usuario";
    return select($sentencia)[0]->total;
}

function obtenerNumeroClientes()
{
    $sentencia = "SELECT IFNULL(COUNT(*),0) AS total FROM cliente";
    return select($sentencia)[0]->total;
}
function obtenerVentasPorUsuario()
{
    $sentencia = "SELECT usuario.nombre AS usuario, COUNT(*) AS numeroVentas, IFNULL(SUM(detalle_venta.cantidad * producto.precio_venta), 0) AS total
    FROM venta
    INNER JOIN usuario ON usuario.id = venta.usuario_id
    INNER JOIN detalle_venta ON venta.id = detalle_venta.venta_id
    INNER JOIN producto ON detalle_venta.producto_id = producto.id
    GROUP BY venta.usuario_id
    ORDER BY total DESC";
    return select($sentencia);
}

function obtenerVentasPorCliente()
{
    $sentencia = "SELECT IFNULL(cliente.nombre, 'MOSTRADOR') AS cliente, COUNT(*) AS numeroCompras, SUM(venta.total) AS total
    FROM venta
    LEFT JOIN cliente ON cliente.id = venta.cliente_id
    GROUP BY venta.cliente_id
    ORDER BY total DESC";
    return select($sentencia);
}


function obtenerProductosMasVendidos()
{
    $sentencia = "SELECT SUM(detalle_venta.cantidad) AS unidades, SUM(detalle_venta.cantidad * producto.precio_venta) AS total, producto.nombre
    FROM detalle_venta
    INNER JOIN producto ON detalle_venta.producto_id = producto.id
    GROUP BY detalle_venta.producto_id
    ORDER BY total DESC
    LIMIT 10";
    return select($sentencia);
}
function buscarProductos($search)
{
    $pdo = conectarBaseDatos();

    // Consulta SQL para buscar productos por código o nombre
    $sql = "SELECT * FROM producto WHERE nombre LIKE :search OR codigo LIKE :search";
    $statement = $pdo->prepare($sql);
    $statement->execute(array(':search' => '%' . $search . '%'));

    // Recopila los resultados en un arreglo
    $productos = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $productos;
}


function obtenerProductos($busqueda = null)
{
    $parametros = [];
    $sentencia = "SELECT * FROM producto ";
    if (isset($busqueda)) {
        $sentencia .= " WHERE nombre LIKE ? OR codigo LIKE ?";
        array_push($parametros, "%" . $busqueda . "%", "%" . $busqueda . "%");
    }
    return select($sentencia, $parametros);
}

function obtenerTotalInventario()
{
    $sentencia = "SELECT IFNULL(SUM(stock * precio_venta),0) AS total FROM producto";
    $fila = select($sentencia);
    if ($fila) return $fila[0]->total;
}


function calcularGananciaProductos()
{
    $sentencia = "SELECT IFNULL(SUM(stock * precio_venta) - SUM(stock*precio_costo),0) AS total FROM producto";
    $fila = select($sentencia);
    if ($fila) return $fila[0]->total;
}
function insertar($sentencia, $parametros)
{
    $bd = conectarBaseDatos();
    $respuesta = $bd->prepare($sentencia);
    return $respuesta->execute($parametros);
}
function registrarProducto($codigo, $nombre, $descripcion, $categoria, $precio_costo, $precio_venta, $stock)
{
    $sentencia = "INSERT INTO producto(codigo, nombre, descripcion, categoria_id, precio_costo, precio_venta, stock) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $parametros = [$codigo, $nombre, $descripcion, $categoria, $precio_costo, $precio_venta, $stock];
    return insertar($sentencia, $parametros);
}


function obtenerCategorias()
{

    $sentencia = "SELECT id, nombre FROM categoria ORDER BY nombre";

    $parametros = [];

    return select($sentencia, $parametros);
}
function obtenerProductoPorId($id)
{
    $sentencia = "SELECT * FROM producto WHERE id = ?";
    return select($sentencia, [$id])[0];
}
function editar($sentencia, $parametros)
{
    $bd = conectarBaseDatos();
    $respuesta = $bd->prepare($sentencia);
    return $respuesta->execute($parametros);
}
function editarProducto($codigo, $nombre, $descripcion, $categoria_id, $precio_costo, $precio_venta, $stock, $id)
{
    $sentencia = "UPDATE producto SET codigo = ?, nombre = ?, descripcion = ?, categoria_id = ?, precio_costo = ?, precio_venta = ?, stock = ? WHERE id = ?";
    $parametros = [$codigo, $nombre, $descripcion, $categoria_id, $precio_costo, $precio_venta, $stock, $id];
    return editar($sentencia, $parametros);
}
function eliminar($sentencia, $id)
{
    $bd = conectarBaseDatos();
    $respuesta = $bd->prepare($sentencia);
    return $respuesta->execute([$id]);
}
function eliminarProducto($id)
{
    $sentencia = "DELETE FROM producto WHERE id = ?";
    return eliminar($sentencia, $id);
}
function obtenerUsuarios()
{
    $sentencia = "SELECT u.id, u.nombre, u.apellido, u.tel, u.direccion, u.email, r.nombre as rol FROM usuario u
    INNER JOIN rol r ON u.rol_id = r.id";
    return select($sentencia);
}

function registrarUsuario($nombre, $apellido, $telefono, $direccion, $email, $password)
{
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $sentencia = "INSERT INTO usuario (nombre, apellido, tel, direccion, email, pass) VALUES (?,?,?,?,?,?)";
    $parametros = [$nombre, $apellido, $telefono, $direccion, $email, $passwordHash];
    return insertar($sentencia, $parametros);
}
function obtenerUsuarioPorId($id)
{
    $sentencia = "SELECT id, nombre, apellido, tel, direccion, email FROM usuario WHERE id = ?";
    return select($sentencia, [$id])[0];
}
function editarUsuario($nombre, $apellido, $telefono, $direccion, $email, $password, $id)
{
    // Verifica si se proporcionó una nueva contraseña
    if (!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sentencia = "UPDATE usuario SET nombre = ?, apellido = ?, tel = ?, direccion = ?, email = ?, `pass` = ? WHERE id = ?";
        $parametros = [$nombre, $apellido, $telefono, $direccion, $email, $passwordHash, $id];
    } else {
        // Si no se proporciona una nueva contraseña, actualiza sin modificar la contraseña
        $sentencia = "UPDATE usuario SET nombre = ?, apellido = ?, tel = ?, direccion = ?, email = ? WHERE id = ?";
        $parametros = [$nombre, $apellido, $telefono, $direccion, $email, $id];
    }

    return editar($sentencia, $parametros);
}

function eliminarUsuario($id)
{
    $sentencia = "DELETE FROM usuario WHERE id = ?";
    return eliminar($sentencia, $id);
}
function obtenerClientes()
{
    $sentencia = "SELECT c.id, c.nombre, c.apellido, c.telefono, c.direccion, c.fechaNacimiento, c.email, c.cuil_cuit, cc.nombre AS categoria
                FROM cliente AS c
                LEFT JOIN categoria_cliente AS cc ON c.categoria_cliente_id = cc.id";
    return select($sentencia);
}
function registrarCliente($nombre, $apellido,  $direccion, $fecha_nacimiento, $email, $telefono, $cuil_cuit, $dni, $categoria, $password)
{
    $sentencia = "INSERT INTO cliente (nombre, apellido, direccion, fechaNacimiento, email, telefono, cuil_cuit, dni, categoria_cliente_id, pass) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $parametros = [$nombre, $apellido,  $direccion, $fecha_nacimiento, $email, $telefono, $cuil_cuit, $dni, $categoria, $password];
    return insertar($sentencia, $parametros);
}
function obtenerCategoriasCliente()
{
    $sentencia = "SELECT id, nombre FROM categoria_cliente ORDER BY nombre";
    $parametros = [];
    return select($sentencia, $parametros);
}
function obtenerClientePorId($id)
{
    $sentencia = "SELECT * FROM cliente WHERE id = ?";
    $cliente = select($sentencia, [$id]);
    if ($cliente) return $cliente[0];
}

function editarCliente($id, $nombre, $apellido, $direccion, $fecha_nacimiento, $email, $telefono, $cuil_cuit, $dni, $categoria, $password)
{
    $sentencia = "UPDATE cliente SET nombre = ?, apellido = ?, direccion = ?, fechaNacimiento = ?, email = ?, telefono = ?, cuil_cuit = ?, dni = ?, categoria_cliente_id = ?, `pass` = ? WHERE id = ?";
    $parametros = [$id, $nombre, $apellido, $direccion, $fecha_nacimiento, $email, $telefono, $cuil_cuit, $dni, $categoria, $password];
    return editar($sentencia, $parametros);
}
function eliminarCliente($id)
{
    $sentencia = "DELETE FROM cliente WHERE id = ?";
    return eliminar($sentencia, $id);
}
function calcularTotalLista($lista)
{
    $total = 0;
    foreach ($lista as $producto) {
        $total += floatval($producto->precio_venta * $producto->cantidad);
    }
    return $total;
}
function iniciarSesion($usuario, $password)
{
    $sentencia = "SELECT id, nombre, `pass` FROM usuario WHERE nombre = ?";
    $resultado = select($sentencia, [$usuario]);
    if ($resultado) {
        $usuario = $resultado[0];
        $verificaPass = verificarPassword($usuario->id, $password);
        if ($verificaPass) return $usuario;
    }
}
function verificarPassword($idUsuario, $password)
{
    $sentencia = "SELECT `pass` FROM usuario WHERE id = ?";
    $contrasenia = select($sentencia, [$idUsuario])[0]->pass;
    $verifica = password_verify($password, $contrasenia);
    if ($verifica) return true;
}
function cambiarPassword($idUsuario, $password)
{
    $nueva = password_hash($password, PASSWORD_DEFAULT);
    $sentencia = "UPDATE usuario SET pass = ? WHERE id = ?";
    return editar($sentencia, [$nueva, $idUsuario]);
}

function obtenerProductoPorCodigo($codigo)
{
    $sentencia = "SELECT * FROM producto WHERE codigo = ?";
    $producto = select($sentencia, [$codigo]);
    if ($producto) return $producto[0];
    return [];
}
function agregarProductoALista($producto, $listaProductos)
{
    if ($producto->stock < 1) return $listaProductos;

    $existe = verificarSiEstaEnLista($producto->id, $listaProductos);

    if (!$existe) {
        $producto->cantidad = 1;
        array_push($listaProductos, $producto);
    } else {
        $listaProductos = incrementarCantidad($producto->id, $listaProductos);
    }

    return $listaProductos;
}
function incrementarCantidad($productoId, $listaProductos)
{
    foreach ($listaProductos as &$producto) {
        if ($producto->id === $productoId) {
            $producto->cantidad++;
            break;
        }
    }
    return $listaProductos;
}

function verificarExistencia($idProducto, $listaProductos, $existencia)
{
    foreach ($listaProductos as $producto) {
        if ($producto->id == $idProducto) {
            if ($existencia <= $producto->cantidad) return true;
        }
    }
    return false;
}
function verificarSiEstaEnLista($idProducto, $listaProductos)
{
    foreach ($listaProductos as $producto) {
        if ($producto->id == $idProducto) {
            return true;
        }
    }
    return false;
}
function agregarCantidad($idProducto, $listaProductos)
{
    foreach ($listaProductos as $producto) {
        if ($producto->id == $idProducto) {
            $producto->cantidad++;
        }
    }
    return $listaProductos;
}
function registrarVenta($idUsuario, $idCliente, $total, $medioPago, $iva)
{
    $sentencia = "INSERT INTO venta (fecha, medioPago_id, cliente_id, iva_id, usuario_id, total) VALUES (NOW(), ?, ?, ?, ?, ?)";
    $parametros = [$medioPago, $idCliente, $iva, $idUsuario, $total];
    if (insertar($sentencia, $parametros)) {
        return obtenerUltimoIdVenta();
    }
    return false;
}
function actualizarStockProductos($productos)
{
    foreach ($productos as $producto) {
        $sentencia = "UPDATE producto SET stock = stock - ? WHERE id = ?";
        $parametros = [$producto->cantidad, $producto->id];
        editar($sentencia, $parametros);
    }
}

function registrarProductosVenta($productos, $idVenta)
{
    $sentencia = "INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
    foreach ($productos as $producto) {
        $parametros = [$idVenta, $producto->id, $producto->cantidad, $producto->precio_venta];
        insertar($sentencia, $parametros);
        descontarProductos($producto->id, $producto->cantidad);
    }
    return true;
}
function descontarProductos($idProducto, $cantidad)
{
    $sentencia = "UPDATE producto SET stock = stock - ? WHERE id = ?";
    $parametros = [$cantidad, $idProducto];
    return editar($sentencia, $parametros);
}
function obtenerUltimoIdVenta()
{
    $sentencia  = "SELECT id FROM venta ORDER BY id DESC LIMIT 1";
    $resultado = select($sentencia);
    if ($resultado) {
        return $resultado[0]->id;
    } else {
        return null;
    }
}
function obtenerMediosDePago()
{
    $sentencia = "SELECT id, nombre FROM mediopago";
    return select($sentencia);
}
function obtenerIvas()
{
    $sentencia = "SELECT id, nombre FROM iva";
    return select($sentencia);
}
function obtenerVentas($fechaInicio, $fechaFin, $cliente, $usuario)
{
    $parametros = [];
    $sentencia = "SELECT venta.*, usuario.nombre, IFNULL(cliente.nombre, 'MOSTRADOR') AS cliente
    FROM venta
    INNER JOIN usuario ON usuario.id = venta.usuario_id
    LEFT JOIN cliente ON cliente.id = venta.cliente_id
    WHERE 1 = 1"; // Inicio de la consulta

    // Agregar filtro por fecha si es necesario
    if (isset($fechaInicio) && isset($fechaFin)) {
        $sentencia .= " AND DATE(venta.fecha) BETWEEN ? AND ?";
        array_push($parametros, $fechaInicio, $fechaFin);
    }

    // Agregar filtro por cliente si es necesario
    if (isset($cliente)) {
        $sentencia .= " AND venta.cliente_id = ?";
        array_push($parametros, $cliente);
    }

    // Agregar filtro por usuario si es necesario
    if (isset($usuario)) {
        $sentencia .= " AND venta.usuario_id = ?";
        array_push($parametros, $usuario);
    }

    $ventas = select($sentencia, $parametros);

    return agregarProductosVendidos($ventas);
}


function agregarProductosVendidos($ventas)
{
    foreach ($ventas as $venta) {
        $venta->productos = obtenerProductosVendidos($venta->id);
    }
    return $ventas;
}
function calcularTotalVentas($ventas)
{
    $total = 0;
    foreach ($ventas as $venta) {
        $total += $venta->total;
    }
    return $total;
}

function calcularProductosVendidos($ventas)
{
    $total = 0;
    foreach ($ventas as $venta) {
        foreach ($venta->productos as $producto) {
            $total += $producto->cantidad;
        }
    }
    return $total;
}

function obtenerGananciaVentas($ventas)
{
    $total = 0;
    foreach ($ventas as $venta) {
        foreach ($venta->productos as $producto) {
            $total += $producto->cantidad * ($producto->precio_unitario - $producto->precio_costo);
        }
    }
    return $total;
}




function obtenerProductosVendidos($idVenta)
{
    $sentencia = "SELECT detalle_venta.cantidad, detalle_venta.precio_unitario, producto.nombre,
    producto.precio_costo
    FROM detalle_venta
    INNER JOIN producto ON producto.id = detalle_venta.producto_id
    WHERE venta_id  = ? ";
    return select($sentencia, [$idVenta]);
}






function conectarBaseDatos()
{
    $host = "localhost";
    $db   = "drinkstore_db";
    $user = "root";
    $pass = "";
    $charset = 'utf8mb4';

    $options = [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
        \PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    try {
        $pdo = new \PDO($dsn, $user, $pass, $options);
        return $pdo;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}
