<?php   
session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}
    include_once "funciones.php";
    if(isset($_POST['agregar'])){
        echo "agregar";
        if(isset($_POST['codigo'])) {
            $codigo = $_POST['codigo'];
            $producto = obtenerProductoPorCodigo($codigo);
            var_dump($producto);
            if(!$producto) {
                echo "
                <script type='text/javascript'>
                    window.location.href='vender.php'
                    alert('No se ha encontrado el producto')
                </script>";
                return;
            }
            
            print_r($producto);
            $_SESSION['lista'] = agregarProductoALista($producto,  $_SESSION['lista']);
            unset($_POST['codigo']);
            header("location: vender.php");
        }
    }
