<?php
require_once __DIR__ . '/../../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . 'views/login.php');
}

$usuario = trim($_POST['usuario'] ?? '');
$clave = trim($_POST['clave'] ?? '');

if ($usuario === 'admin' && $clave === '1234') {
    $_SESSION['usuario'] = 'admin';
    redirect(BASE_URL . 'views/inicio.php');
}

set_flash('Usuario o contrasena incorrectos', false);
redirect(BASE_URL . 'views/login.php');
