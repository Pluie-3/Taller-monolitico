<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$vehicles = ControllersConfig::get_vehicles_controller()->get_vehicles();
$customers = ControllersConfig::get_customers_controller()->get_customers();

$pre_vehicle_id = (int) ($_GET['vehicle_id'] ?? 0);
$pre_start_date = trim($_GET['start_date'] ?? date('Y-m-d'));

require_once __DIR__ . '/../partials/header.php';
?>
<h1>Nueva Reserva</h1>

<?php
$fecha_seleccionada = $pre_start_date;
$url_base_cal = 'formulario_reserva.php';
$param_fecha = 'start_date';
$extra_query = $pre_vehicle_id > 0 ? '&vehicle_id=' . $pre_vehicle_id : '';
require __DIR__ . '/../partials/calendario.php';
?>

<form class="form-2col" action="../actions/alquileres_crear.php" method="post">
    <div>
        <label for="customer_id">Cliente:</label>
        <select name="customer_id" id="customer_id" required>
            <option value="">-- Seleccione un cliente --</option>
            <?php foreach ($customers as $c): ?>
                <option value="<?= (int) $c->get_id() ?>">
                    <?= e($c->get_name()) ?> (Lic: <?= e($c->get_license_number()) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="vehicle_id">Vehiculo:</label>
        <select name="vehicle_id" id="vehicle_id" required>
            <option value="">-- Seleccione un vehiculo --</option>
            <?php foreach ($vehicles as $v): ?>
                <?php if ($v->get_status() !== 'mantenimiento'): ?>
                    <option value="<?= (int) $v->get_id() ?>" <?= $pre_vehicle_id === $v->get_id() ? 'selected' : '' ?>>
                        <?= e($v->get_brand() . ' ' . $v->get_model()) ?> (<?= (int) $v->get_year() ?> — <?= e($v->get_status()) ?>)
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="start_date">Fecha de inicio:</label>
        <input type="date" name="start_date" id="start_date" required
               min="<?= e(date('Y-m-d')) ?>" value="<?= e($pre_start_date) ?>">
    </div>

    <div>
        <label for="end_date">Fecha de fin:</label>
        <input type="date" name="end_date" id="end_date" required
               min="<?= e(date('Y-m-d')) ?>" value="<?= e($pre_start_date) ?>">
    </div>

    <div class="ancho-completo acciones">
        <button type="submit">Registrar reserva</button>
        <a class="btn btn-secundario" href="lista.php">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>