<?php
require 'configuracion.php';

// Precios validos (nunca confiar en datos del cliente)
$precios = [
    'Laptop'  => 450,
    'Mouse'   => 25,
    'Teclado' => 45,
    'Monitor' => 180,
];

$nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);

// Validar que el producto existe en el catalogo
if ($nombre !== null && array_key_exists($nombre, $precios)) {
    // Inicializar el carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    // Agregar el producto al array de sesion
    $_SESSION['carrito'][] = [
        'nombre' => $nombre,
        'precio' => $precios[$nombre],
    ];
}

// Patron Post/Redirect/Get: evitar reenvio del formulario
header('Location: index.php');
exit;
