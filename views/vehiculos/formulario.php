<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_vehicles_controller();
$id = (int) ($_GET['id'] ?? 0);
$vehicle = $id > 0 ? $controller->get_vehicle_by_id($id) : null;

if ($id > 0 && $vehicle === null) {
    set_flash('El vehiculo solicitado no existe', false);
    redirect('lista.php');
}

$editing = $vehicle !== null;

require_once __DIR__ . '/../partials/header.php';
?>
<h1><?= $editing ? 'Editar vehiculo' : 'Registrar vehiculo' ?></h1>

<form class="form-2col" action="../actions/vehiculos_guardar.php" method="post">
    <?php if ($editing): ?>
        <input type="hidden" name="id" value="<?= (int) $vehicle->get_id() ?>">
    <?php endif; ?>

    <div>
        <label for="brand">Marca:</label>
        <input type="text" name="brand" id="brand" required maxlength="100"
               placeholder="Ej. Toyota"
               value="<?= $editing ? e($vehicle->get_brand()) : '' ?>">
    </div>

    <div>
        <label for="model">Modelo:</label>
        <input type="text" name="model" id="model" required maxlength="100"
               placeholder="Ej. Corolla"
               value="<?= $editing ? e($vehicle->get_model()) : '' ?>">
    </div>

    <div>
        <label for="year">Anio:</label>
        <input type="number" name="year" id="year" required min="1990" max="<?= (int) date('Y') + 1 ?>"
               value="<?= $editing ? (int) $vehicle->get_year() : (int) date('Y') ?>">
    </div>

    <div>
        <label for="category">Categoria:</label>
        <select name="category" id="category" required>
            <?php foreach (['Economico', 'SUV', 'Camioneta', 'Lujo'] as $cat): ?>
                <option value="<?= $cat ?>" <?= ($editing && $vehicle->get_category() === $cat) ? 'selected' : '' ?>>
                    <?= $cat ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="status">Estado:</label>
        <select name="status" id="status" required>
            <?php foreach (['disponible', 'alquilado', 'mantenimiento'] as $st): ?>
                <option value="<?= $st ?>" <?= ($editing && $vehicle->get_status() === $st) ? 'selected' : '' ?>>
                    <?= $st ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="ancho-completo acciones">
        <button type="submit">Guardar vehiculo</button>
        <a class="btn btn-secundario" href="lista.php">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
