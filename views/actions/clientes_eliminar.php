<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../clientes/lista.php');
}

$result = ControllersConfig::get_customers_controller()->customer_delete($_POST);

set_flash($result['message'], $result['success']);
redirect('../clientes/lista.php');
