<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Bogota');
$doc_root = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
$app_root = str_replace('\\', '/', realpath(__DIR__));
$base_url = ($doc_root !== '' && str_starts_with($app_root, $doc_root))
    ? substr($app_root, strlen($doc_root))
    : '';
$base_url = '/' . trim($base_url, '/') . '/';
if ($base_url === '//') {
    $base_url = '/';
}
define('BASE_URL', $base_url);

// MODELO
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
require_once __DIR__ . '/models/repositories/customer_repository_interface.php';
require_once __DIR__ . '/models/repositories/customer_repository.php';
require_once __DIR__ . '/models/repositories/rentals_repository_interface.php';
require_once __DIR__ . '/models/repositories/rentals_repository.php';
require_once __DIR__ . '/models/config/models_config.php';

// CONTROLADOR
require_once __DIR__ . '/controllers/vehicles/vehicles_controller_interface.php';
require_once __DIR__ . '/controllers/vehicles/vehicles_controller.php';
require_once __DIR__ . '/controllers/customers/customers_controller_interface.php';
require_once __DIR__ . '/controllers/customers/customers_controller.php';
require_once __DIR__ . '/controllers/rentals/rentals_controller_interface.php';
require_once __DIR__ . '/controllers/rentals/rentals_controller.php';
require_once __DIR__ . '/controllers/config/controllers_config.php';

// UTILIDADES
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
    $_SESSION['flash'] = [
        'message' => $message,
        'success' => $success
    ];
}

function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}