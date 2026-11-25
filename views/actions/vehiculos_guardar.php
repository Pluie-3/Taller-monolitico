<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../vehiculos/lista.php');
}

$controller = ControllersConfig::get_vehicles_controller();
$id = (int) ($_POST['id'] ?? 0);

$result = $id > 0
    ? $controller->vehicle_update($_POST)
    : $controller->vehicle_create($_POST);

set_flash($result['message'], $result['success']);

if (!$result['success']) {
    $query = $id > 0 ? '?id=' . $id : '';
    redirect('../vehiculos/formulario.php' . $query);
}

redirect('../vehiculos/lista.php');
