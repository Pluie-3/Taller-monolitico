<?php
// Cargar el archivo de arranque unificado
require_once __DIR__ . '/bootstrap.php';

use app\controllers\config\ControllersConfig;
// Si ya tienes la configuración de controladores para clientes y alquileres, 
// o puedes instanciar los repositorios directo para la prueba:
use app\models\config\ModelsConfig;

echo "<h1>Prueba Integral del Sistema - Taller Monolítico</h1>";

try {
    // 1. Probar Repositorio de Vehículos
    $veh_repo = ModelsConfig::get_vehicles_repository();
    $vehiculos = $veh_repo->get_vehicles_all();
    echo "<h2>Vehículos registrados: " . count($vehiculos) . "</h2>";

    // 2. Probar Repositorio de Clientes (Si ya creaste su ModelsConfig, úsalo; si no, directo con la BD)
    $db = ModelsConfig::get_rental_db();
    $clientes = $db->exc_select("select * from clientes");
    echo "<h2>Clientes registrados: " . count($clientes) . "</h2>";

    // 3. Probar Repositorio de Alquileres (Rentals)
    require_once __DIR__ . '/models/repositories/rentals_repository.php';
    $rental_repo = new \app\models\repositories\RentalsRepository($db);
    $alquileres = $rental_repo->get_rentals_all();
    
    echo "<h2>Alquileres / Historial encontrados: " . count($alquileres) . "</h2>";

    if (count($alquileres) > 0) {
        echo "<ul>";
        foreach ($alquileres as $alq) {
            echo "<li>";
            echo "<b>Vehículo:</b> " . e($alq->get_vehicle_label()) . " | ";
            echo "<b>Cliente:</b> " . e($alq->get_customer_name()) . " | ";
            echo "<b>Del:</b> " . e($alq->get_start_date()) . " <b>al</b> " . e($alq->get_end_date()) . " | ";
            echo "<b>Estado:</b> " . e($alq->get_status());
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>No hay alquileres registrados todavía, pero la consulta JOIN funcionó correctamente.</p>";
    }

    echo "<p style='color: green; font-weight: bold; font-size: 18px;'>¡Excelente! Las consultas con JOIN, entidades y mappers de todo el sistema responden sin errores.</p>";

} catch (\Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>Error detectado: " . $e->getMessage() . "</p>";
}