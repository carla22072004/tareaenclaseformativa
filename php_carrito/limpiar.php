<?php
require 'configuracion.php';

// Opcion A: solo vaciar el carrito (mantiene la sesion)
$_SESSION['carrito'] = [];

// Opcion B: destruir toda la sesion (cerrar sesion completo)
// session_unset();
// session_destroy();
// setcookie(session_name(), '', time()-3600, '/');

header('Location: index.php');
exit;
