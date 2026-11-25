<?php
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Alquiler de Vehículos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/animations.css">
    <?php if (isset($extra_css) && is_array($extra_css)): ?>
        <?php foreach ($extra_css as $css_href): ?>
            <link rel="stylesheet" href="<?= e($css_href) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <header class="menu">
        <span class="menu-marca">Fleet Control</span>
        <a href="<?= BASE_URL ?>views/inicio.php">Inicio</a>
        <a href="<?= BASE_URL ?>views/vehiculos/lista.php">Vehículos</a>
        <a href="<?= BASE_URL ?>views/vehiculos/disponibles.php">Disponibilidad</a>
        <a href="<?= BASE_URL ?>views/clientes/lista.php">Clientes</a>
        <a href="<?= BASE_URL ?>views/alquileres/lista.php">Reservas</a>
    </header>
    <main id="contenido">
        <?php if ($flash !== null): ?>
            <p class="mensaje <?= $flash['success'] ? 'ok' : 'error' ?>">
                <?= e($flash['message']) ?>
            </p>
        <?php endif; ?>