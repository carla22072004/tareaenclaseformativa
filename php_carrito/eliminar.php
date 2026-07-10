<?php
require 'configuracion.php';

$indice = filter_input(INPUT_POST, 'indice', FILTER_VALIDATE_INT);

if ($indice !== false && $indice !== null && isset($_SESSION['carrito'][$indice])) {
    // Eliminar el elemento por indice
    array_splice($_SESSION['carrito'], $indice, 1);
}

header('Location: index.php');
exit;
