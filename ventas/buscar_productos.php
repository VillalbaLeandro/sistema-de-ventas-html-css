<?php
require_once 'funciones.php';

try {
    // Conéctate a tu base de datos utilizando la función conectarBaseDatos
    $pdo = conectarBaseDatos();

    // Obtiene la cadena de búsqueda del parámetro GET
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Consulta SQL para buscar productos por nombre o código
    $sql = "SELECT * FROM producto WHERE nombre LIKE :search_nombre OR codigo LIKE :search_codigo";
    $statement = $pdo->prepare($sql);
    $statement->execute(array(':search_nombre' => '%' . $search . '%', ':search_codigo' => '%' . $search . '%'));

    // Recopila los resultados en un arreglo
    $productos = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Mostrar los resultados en JSON
    header('Content-Type: application/json');
    echo json_encode($productos);
} catch (PDOException $e) {
    echo 'Error en la consulta SQL: ' . $e->getMessage();
}
