<?php
date_default_timezone_set('America/Bogota');
//MODELO
require_once __DIR__ . '/models/entities/vehicle.php';
require_once __DIR__ . '/models/entities/customer.php';
require_once __DIR__ . '/models/entities/rental.php';
require_once __DIR__ . '/models/mappers/vehicles_mappers.php';
require_once __DIR__ . '/models/mappers/customers_mappers.php';
require_once __DIR__ . '/models/mappers/rentals_mappers.php';
require_once __DIR__ . '/models/database/rental_db_interface.php';
require_once __DIR__ . '/models/database/rental_db.php';
require_once __DIR__ . '/models/repositories/vehicles_repository_interface.php';
require_once __DIR__ . '/models/repositories/vehicles_repository.php';
require_once __DIR__ . '/models/config/models_config.php';
//CONTROLADOR
require_once __DIR__ . '/controllers/vehicles/vehicles_controller_interface.php';
require_once __DIR__ . '/controllers/vehicles/vehicles_controller.php';
require_once __DIR__ . '/controllers/config/controllers_config.php';
require_once __DIR__ . '/controllers/customers/customers_controller_interface.php';
require_once __DIR__ . '/controllers/customers/customers_controller.php';
require_once __DIR__ . '/controllers/rentals/rentals_controller_interface.php';
require_once __DIR__ . '/controllers/rentals/rentals_controller.php';
// UTILIDADES
function e(string $text): string { // escapa texto antes de imprimirlo en HTML
 return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
function redirect(string $url): void { // redirige y termina el script
 header('Location: ' . $url);
 exit;
}
