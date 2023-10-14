<?php

session_start();
if (empty($_SESSION['nombre'])) {
    header("location: login.php");
    exit;
}
$_SESSION['lista'] = [];
header("location: vender.php");
?>