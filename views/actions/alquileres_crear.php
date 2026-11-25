<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../alquileres/lista.php');
}

$result = ControllersConfig::get_rentals_controller()->rental_create($_POST);

set_flash($result['message'], $result['success']);

if (!$result['success']) {
    redirect('../alquileres/formulario_reserva.php');
}

redirect('../alquileres/lista.php');
