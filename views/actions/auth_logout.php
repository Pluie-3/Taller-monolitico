<?php
require_once __DIR__ . '/../../bootstrap.php';

unset($_SESSION['usuario']);
set_flash('Sesion cerrada correctamente', true);
redirect(BASE_URL . 'views/login.php');
