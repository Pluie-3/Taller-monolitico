<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../alquileres/lista.php');
}

$result = ControllersConfig::get_rentals_controller()->rental_start($_POST);

set_flash($result['message'], $result['success']);
redirect('../alquileres/lista.php');