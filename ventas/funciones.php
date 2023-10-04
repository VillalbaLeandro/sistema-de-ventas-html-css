<?php

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
    $sentencia = "SELECT IFNULL(SUM(total),0) AS total FROM venta";
    if (isset($idUsuario)) {
        $sentencia .= " WHERE usuario_id = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if ($fila) return $fila[0]->total;
}
function obtenerTotalVentasHoy($idUsuario = null)
{
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total),0) AS total FROM venta WHERE DATE(fecha) = CURDATE() ";
    if (isset($idUsuario)) {
        $sentencia .= " AND usuario_id = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if ($fila) return $fila[0]->total;
}
function obtenerTotalVentasSemana($idUsuario = null)
{
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total),0) AS total FROM venta  WHERE WEEK(fecha) = WEEK(NOW())";
    if (isset($idUsuario)) {
        $sentencia .= " AND  usuario_id = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if ($fila) return $fila[0]->total;
}

function obtenerTotalVentasMes($idUsuario = null)
{
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total),0) AS total FROM venta  WHERE MONTH(fecha) = MONTH(CURRENT_DATE()) AND YEAR(fecha) = YEAR(CURRENT_DATE())";
    if (isset($idUsuario)) {
        $sentencia .= " AND  usuario_id = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if ($fila) return $fila[0]->total;
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
    $sentencia = "SELECT SUM(venta.cant) AS total, usuario.nombre AS usuario, COUNT(*) AS numeroVentas 
    FROM venta
    INNER JOIN usuario ON usuario.id = venta.usuario_id
    GROUP BY venta.usuario_id
    ORDER BY total DESC";
    return select($sentencia);
}

function obtenerVentasPorCliente()
{
    $sentencia = "SELECT SUM(venta.total) AS total, IFNULL(cliente.nombre, 'MOSTRADOR') AS cliente,
    COUNT(*) AS numeroCompras
    FROM venta
    LEFT JOIN cliente ON cliente.id = venta.cliente_id
    GROUP BY venta.cliente_id
    ORDER BY total DESC";
    return select($sentencia);
}

function obtenerProductosMasVendidos()
{
    $sentencia = "SELECT SUM(detalle_venta.cantidad * producto.precio_venta) AS total, SUM(detalle_venta.cantidad) AS unidades,
    producto.nombre FROM detalle_venta INNER JOIN producto ON producto.id = detalle_venta.producto_id
    GROUP BY detalle_venta.producto_id
    ORDER BY total DESC
    LIMIT 10";
    return select($sentencia);
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
    $sentencia = "UPDATE usuario SET nombre = ?, apellido = ?, tel = ?, direccion = ?, email = ?, `pass` = ? WHERE id = ?";
    $parametros = [$nombre, $apellido, $telefono, $direccion, $email, $password, $id];
    return editar($sentencia, $parametros);
}
function eliminarUsuario($id)
{
    $sentencia = "DELETE FROM usuario WHERE id = ?";
    return eliminar($sentencia, $id);
}
function obtenerClientes(){
    $sentencia = "SELECT c.id, c.nombre, c.apellido, c.telefono, c.direccion, c.fechaNacimiento, c.email, c.cuil_cuit, cc.nombre AS categoria
                FROM cliente AS c
                LEFT JOIN categoria_cliente AS cc ON c.categoria_cliente_id = cc.id";
    return select($sentencia);
}
function registrarCliente($nombre, $apellido,  $direccion, $fecha_nacimiento, $email, $telefono, $cuil_cuit, $dni, $categoria, $password) {
    $sentencia = "INSERT INTO cliente (nombre, apellido, direccion, fechaNacimiento, email, telefono, cuil_cuit, dni, categoria_cliente_id, pass) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $parametros = [$nombre, $apellido,  $direccion, $fecha_nacimiento, $email, $telefono, $cuil_cuit, $dni, $categoria, $password];
    return insertar($sentencia, $parametros);
}
function obtenerCategoriasCliente(){
    $sentencia = "SELECT id, nombre FROM categoria_cliente ORDER BY nombre";
    $parametros = [];
    return select($sentencia, $parametros);
}
function obtenerClientePorId($id){
    $sentencia = "SELECT * FROM cliente WHERE id = ?";
    $cliente = select($sentencia, [$id]);
    if($cliente) return $cliente[0];
}

function editarCliente($id, $nombre, $apellido, $direccion, $fecha_nacimiento, $email, $telefono, $cuil_cuit, $dni, $categoria, $password) {
    $sentencia = "UPDATE cliente SET nombre = ?, apellido = ?, direccion = ?, fechaNacimiento = ?, email = ?, telefono = ?, cuil_cuit = ?, dni = ?, categoria_cliente_id = ?, `pass` = ? WHERE id = ?";
    $parametros = [$id, $nombre, $apellido, $direccion, $fecha_nacimiento, $email, $telefono, $cuil_cuit, $dni, $categoria, $password];
    return editar($sentencia, $parametros);
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
