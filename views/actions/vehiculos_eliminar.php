<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../vehiculos/lista.php');
}

$result = ControllersConfig::get_vehicles_controller()->vehicle_delete($_POST);

set_flash($result['message'], $result['success']);
redirect('../vehiculos/lista.php');
