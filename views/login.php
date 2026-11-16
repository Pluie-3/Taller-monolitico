<?php
require_once __DIR__ . '/../bootstrap.php';

if (isset($_SESSION['usuario'])) {
    redirect(BASE_URL . 'views/inicio.php');
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion — Gestor de Vehiculos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/animations.css">
</head>
<body>
    <div class="login-pantalla">
        <div class="login-tarjeta">
            <p class="login-subtitulo">Premium Fleet System</p>
            <h2 class="login-titulo">Premium cars.<br>Enjoy the luxury.</h2>
            <p class="login-descripcion">
                Inicia sesion para administrar vehiculos, clientes y reservas.
            </p>

            <?php if ($flash !== null): ?>
                <p class="mensaje <?= $flash['success'] ? 'ok' : 'error' ?>">
                    <?= e($flash['message']) ?>
                </p>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>views/actions/auth_login.php" method="post">
                <div class="campo-grupo">
                    <label for="usuario">Usuario:</label>
                    <input type="text" name="usuario" id="usuario" required placeholder="admin" value="admin">
                </div>

                <div class="campo-grupo-lg">
                    <label for="clave">Contrasena:</label>
                    <input type="password" name="clave" id="clave" required placeholder="••••••••" value="1234">
                </div>

                <button type="submit" class="btn btn-bloque">Get Started — Entrar</button>
            </form>

            <p class="login-nota">
                Acceso demo: <b>admin</b> / <b>1234</b>
            </p>
        </div>
    </div>
</body>
</html>