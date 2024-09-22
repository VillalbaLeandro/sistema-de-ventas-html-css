<?php
session_start();

if (isset($_POST['index']) && isset($_POST['cantidad'])) {
    $index = intval($_POST['index']);
    $cantidad = intval($_POST['cantidad']);

    // Verificar que la cantidad no supere el stock
    if (isset($_SESSION['lista'][$index]) && $cantidad <= $_SESSION['lista'][$index]->stock) {
        $_SESSION['lista'][$index]->cantidad = $cantidad;
        echo json_encode(['status' => 'success', 'message' => 'Cantidad actualizada']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'La cantidad excede el stock disponible.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}