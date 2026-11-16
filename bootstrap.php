<?php
date_default_timezone_set('America/Bogota');
session_start();
define('BASE_URL', '/Taller-monolitico/');
// Cambia a false cuando tu companera termine de llenar models/ y controllers/
$MODO_DISENO = true;

if ($MODO_DISENO) {
    require_once __DIR__ . '/views/partials/mock_backend.php';
} else {
    // MODELO (Persona A)
    require_once __DIR__ . '/models/database/rental_db_interface.php';
    require_once __DIR__ . '/models/database/rental_db.php';
    require_once __DIR__ . '/models/entities/vehicle.php';
    require_once __DIR__ . '/models/entities/customer.php';
    require_once __DIR__ . '/models/entities/rental.php';
    require_once __DIR__ . '/models/mappers/vehicles_mappers.php';
    require_once __DIR__ . '/models/mappers/customers_mappers.php';
    require_once __DIR__ . '/models/mappers/rentals_mappers.php';
    require_once __DIR__ . '/models/repositories/vehicles_repository_interface.php';
    require_once __DIR__ . '/models/repositories/vehicles_repository.php';
    require_once __DIR__ . '/models/config/models_config.php';

    // CONTROLADOR (Persona A)
    require_once __DIR__ . '/controllers/vehicles/vehicles_controller_interface.php';
    require_once __DIR__ . '/controllers/vehicles/vehicles_controller.php';
    require_once __DIR__ . '/controllers/customers/customers_controller_interface.php';
    require_once __DIR__ . '/controllers/customers/customers_controller.php';
    require_once __DIR__ . '/controllers/rentals/rentals_controller_interface.php';
    require_once __DIR__ . '/controllers/rentals/rentals_controller.php';
    require_once __DIR__ . '/controllers/config/controllers_config.php';
}

// UTILIDADES (Persona B)
function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function set_flash(string $message, bool $success = true): void
{
    $_SESSION['flash'] = ['message' => $message, 'success' => $success];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}