<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$date = trim($_GET['fecha'] ?? date('Y-m-d'));
$available = ControllersConfig::get_vehicles_controller()->get_available_vehicles($date);

require_once __DIR__ . '/../partials/header.php';
?>
<h1>Disponibilidad por Fecha</h1>

<?php
$fecha_seleccionada = $date;
$url_base_cal = 'disponibles.php';
$param_fecha = 'fecha';
require __DIR__ . '/../partials/calendario.php';
?>

<form class="form-2col mb-22" method="get" action="disponibles.php">
    <div>
        <label for="fecha">O ingrese una fecha exacta:</label>
        <input type="date" name="fecha" id="fecha" required value="<?= e($date) ?>">
    </div>
    <div class="campo-boton-abajo">
        <button type="submit">Consultar disponibles</button>
    </div>
</form>

<h2>Vehiculos libres para el <?= e($date) ?> (<?= count($available) ?> encontrados)</h2>

<?php if (count($available) === 0): ?>
    <p>No hay vehiculos disponibles para la fecha seleccionada.</p>
<?php else: ?>
    <section class="tarjetas">
        <?php foreach ($available as $v): ?>
            <article class="tarjeta" data-estado="<?= e($v->get_status()) ?>">
                <p><b><?= e($v->get_brand()) ?> <?= e($v->get_model()) ?></b> (<?= (int) $v->get_year() ?>)</p>
                <p>Categoria: <?= e($v->get_category()) ?></p>
                <p class="estado">Estado hoy: <?= e($v->get_status()) ?></p>
                <div class="acciones">
                    <a class="btn" href="../alquileres/formulario_reserva.php?vehicle_id=<?= (int) $v->get_id() ?>&start_date=<?= e($date) ?>">
                        Reservar para esta fecha
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
