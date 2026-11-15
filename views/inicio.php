<?php
require_once __DIR__ . '/../bootstrap.php';

use app\controllers\config\ControllersConfig;

$counts = ControllersConfig::get_vehicles_controller()->get_summary_counts();
$active_rentals = ControllersConfig::get_rentals_controller()->get_active_count();

require_once __DIR__ . '/partials/header.php';
?>
<h1>Panel de Inicio</h1>
<p class="intro-texto">Bienvenido al sistema central de administracion de flota, clientes y reservas.</p>

<section class="panel">
    <article class="tarjeta" data-estado="disponible">
        <h2>Vehiculos Disponibles</h2>
        <p class="numero-grande"><?= (int) $counts['disponible'] ?></p>
    </article>
    <article class="tarjeta" data-estado="alquilado">
        <h2>Vehiculos Alquilados</h2>
        <p class="numero-grande"><?= (int) $counts['alquilado'] ?></p>
    </article>
    <article class="tarjeta" data-estado="mantenimiento">
        <h2>En Mantenimiento</h2>
        <p class="numero-grande"><?= (int) $counts['mantenimiento'] ?></p>
    </article>
    <article class="tarjeta">
        <h2>Reservas Activas</h2>
        <p class="numero-grande"><?= (int) $active_rentals ?></p>
    </article>
</section>

<div class="acciones">
    <a class="btn" href="<?= BASE_URL ?>views/alquileres/formulario_reserva.php">Nueva reserva</a>
    <a class="btn btn-secundario" href="<?= BASE_URL ?>views/vehiculos/disponibles.php">Consultar disponibilidad</a>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
