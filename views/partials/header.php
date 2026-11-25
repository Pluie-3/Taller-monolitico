<?php
if (!isset($_SESSION['usuario'])) {
    redirect(BASE_URL . 'views/login.php');
}

$flash = get_flash();
$nombre_usuario = is_array($_SESSION['usuario'])
    ? ($_SESSION['usuario']['nombre'] ?? $_SESSION['usuario']['user'] ?? 'Usuario')
    : (string) $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Alquiler de Vehiculos</title>

    <?php if (isset($extra_css) && is_array($extra_css)): ?>
        <?php foreach ($extra_css as $css_href): ?>
            <?php
            $href = (str_starts_with($css_href, '/') || str_starts_with($css_href, 'http'))
                ? $css_href
                : BASE_URL . ltrim($css_href, '/');
            ?>
            <link rel="stylesheet" href="<?= e($href) ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/animations.css">
</head>
<body>
    <header class="menu">
        <span class="menu-marca">Fleet Control</span>
        <a href="<?= BASE_URL ?>views/inicio.php">Inicio</a>
        <a href="<?= BASE_URL ?>views/vehiculos/lista.php">Vehiculos</a>
        <a href="<?= BASE_URL ?>views/vehiculos/disponibles.php">Disponibilidad</a>
        <a href="<?= BASE_URL ?>views/clientes/lista.php">Clientes</a>
        <a href="<?= BASE_URL ?>views/alquileres/lista.php">Reservas</a>

        <div class="menu-usuario">
            <span class="usuario-nombre">Usuario: <b><?= e($nombre_usuario) ?></b></span>
            <form class="form-inline" action="<?= BASE_URL ?>views/actions/auth_logout.php" method="post">
                <button type="submit" class="btn-secundario btn-sm">Salir</button>
            </form>
        </div>
    </header>

    <main id="contenido">
        <?php if ($flash !== null): ?>
            <p class="mensaje <?= $flash['success'] ? 'ok' : 'error' ?>">
                <?= e($flash['message']) ?>
            </p>
        <?php endif; ?>s
