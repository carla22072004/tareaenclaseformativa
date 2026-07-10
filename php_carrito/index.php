<?php require 'configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito PHP</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 2rem auto }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem }
        th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: left }
        th { background: #4a90d9; color: white }
        .btn { padding: 6px 14px; border: none; border-radius: 4px; cursor: pointer }
        .btn-add { background: #27ae60; color: white }
        .btn-del { background: #e74c3c; color: white }
        .btn-clear { background: #7f8c8d; color: white }
        .total { font-weight: bold; font-size: 1.2rem; margin-top: 1rem }
    </style>
</head>
<body>
    <h1>Tienda - Sesion PHP</h1>

    <!-- Catalogo de productos -->
    <h2>Catalogo</h2>
    <form method="POST" action="agregar.php">
        <select name="nombre">
            <option value="Laptop">Laptop - $450</option>
            <option value="Mouse">Mouse - $25</option>
            <option value="Teclado">Teclado - $45</option>
            <option value="Monitor">Monitor - $180</option>
        </select>
        <button type="submit" class="btn btn-add">Agregar al carrito</button>
    </form>

    <!-- Carrito actual -->
    <h2>Carrito</h2>
    <?php
    $carrito = $_SESSION['carrito'] ?? [];
    $precios = ['Laptop' => 450, 'Mouse' => 25, 'Teclado' => 45, 'Monitor' => 180];

    if (empty($carrito)): ?>
        <p>El carrito esta vacio.</p>
    <?php else: ?>
        <table>
            <tr><th>Producto</th><th>Precio</th><th>Accion</th></tr>
            <?php foreach ($carrito as $i => $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td>$<?= $item['precio'] ?></td>
                    <td>
                        <form method="POST" action="eliminar.php" style="margin:0">
                            <input type="hidden" name="indice" value="<?= $i ?>">
                            <button class="btn btn-del">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p class="total">
            Total: $<?= array_sum(array_column($carrito, 'precio')) ?>
        </p>
        <form method="POST" action="limpiar.php">
            <button class="btn btn-clear">Vaciar carrito</button>
        </form>
    <?php endif; ?>

    <!-- Debug: mostrar ID de sesion (solo en desarrollo) -->
    <p style="color:#999;font-size:0.8rem">
        Session ID: <?= session_id() ?>
    </p>
</body>
</html>
