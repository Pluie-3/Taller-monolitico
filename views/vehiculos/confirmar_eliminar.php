<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$id = (int) ($_GET['id'] ?? 0);
$vehicle = $id > 0 ? ControllersConfig::get_vehicles_controller()->get_vehicle_by_id($id) : null;

if ($vehicle === null) {
    set_flash('El vehiculo no existe', false);
    redirect('lista.php');
}

require_once __DIR__ . '/../partials/header.php';
?>
<h1>Confirmar eliminacion</h1>

<div class="tarjeta" data-estado="<?= e($vehicle->get_status()) ?>">
    <p>¿Estas seguro de que deseas eliminar permanentemente el vehiculo
       <b><?= e($vehicle->get_brand() . ' ' . $vehicle->get_model()) ?></b> (<?= (int) $vehicle->get_year() ?>)?</p>
    <p>Nota: Si el vehiculo tiene reservas asociadas en el historial, el sistema impedira su eliminacion.</p>

    <form class="acciones" action="../actions/vehiculos_eliminar.php" method="post">
        <input type="hidden" name="id" value="<?= (int) $vehicle->get_id() ?>">
        <button class="btn-peligro" type="submit">Si, eliminar</button>
        <a class="btn btn-secundario" href="lista.php">Cancelar</a>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
