<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$id = (int) ($_GET['id'] ?? 0);
$vehicle = ControllersConfig::get_vehicles_controller()->get_vehicle_by_id($id);

if ($vehicle === null) {
    set_flash('El vehiculo no existe', false);
    redirect('../vehiculos/lista.php');
}

$history = ControllersConfig::get_rentals_controller()->get_history_by_vehicle($id);

require_once __DIR__ . '/../partials/header.php';
?>
<h1>Historial del Vehiculo</h1>

<div class="clearfix">
    <aside class="ficha tarjeta" data-estado="<?= e($vehicle->get_status()) ?>">
        <h2><?= e($vehicle->get_brand() . ' ' . $vehicle->get_model()) ?></h2>
        <p><b>Anio:</b> <?= (int) $vehicle->get_year() ?></p>
        <p><b>Categoria:</b> <?= e($vehicle->get_category()) ?></p>
        <p class="estado"><?= e($vehicle->get_status()) ?></p>
    </aside>

    <div class="contenido-historial">
        <?php if (count($history) === 0): ?>
            <p>Este vehiculo aun no tiene reservas registradas en su historial.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $h): ?>
                        <tr>
                            <td><?= e($h->get_customer_name()) ?></td>
                            <td><?= e($h->get_start_date()) ?></td>
                            <td><?= e($h->get_end_date()) ?></td>
                            <td><?= e($h->get_status()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<p class="mt-18"><a class="btn btn-secundario" href="../vehiculos/lista.php">Volver a vehiculos</a></p>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
