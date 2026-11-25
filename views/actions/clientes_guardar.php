<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../clientes/lista.php');
}

$controller = ControllersConfig::get_customers_controller();
$id = (int) ($_POST['id'] ?? 0);

$result = $id > 0
    ? $controller->customer_update($_POST)
    : $controller->customer_create($_POST);

set_flash($result['message'], $result['success']);

if (!$result['success']) {
    $query = $id > 0 ? '?id=' . $id : '';
    redirect('../clientes/formulario.php' . $query);
}

redirect('../clientes/lista.php');
