<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$id = (int) ($_GET['id'] ?? 0);
$customer = $id > 0 ? ControllersConfig::get_customers_controller()->get_customer_by_id($id) : null;

if ($customer === null) {
    set_flash('El cliente no existe', false);
    redirect('lista.php');
}

require_once __DIR__ . '/../partials/header.php';
?>
<h1>Confirmar eliminacion</h1>

<div class="tarjeta">
    <p>¿Seguro que deseas eliminar al cliente <b><?= e($customer->get_name()) ?></b>
       (Licencia: <?= e($customer->get_license_number()) ?>)?</p>

    <form class="acciones" action="../actions/clientes_eliminar.php" method="post">
        <input type="hidden" name="id" value="<?= (int) $customer->get_id() ?>">
        <button class="btn-peligro" type="submit">Si, eliminar</button>
        <a class="btn btn-secundario" href="lista.php">Cancelar</a>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$id = (int) ($_GET['id'] ?? 0);
$customer = $id > 0 ? ControllersConfig::get_customers_controller()->get_customer_by_id($id) : null;

if ($customer === null) {
    set_flash('El cliente no existe', false);
    redirect('lista.php');
}

require_once __DIR__ . '/../partials/header.php';
?>
<h1>Confirmar eliminacion</h1>

<div class="tarjeta">
    <p>¿Seguro que deseas eliminar al cliente <b><?= e($customer->get_name()) ?></b>
       (Licencia: <?= e($customer->get_license_number()) ?>)?</p>

    <form class="acciones" action="../actions/clientes_eliminar.php" method="post">
        <input type="hidden" name="id" value="<?= (int) $customer->get_id() ?>">
        <button class="btn-peligro" type="submit">Si, eliminar</button>
        <a class="btn btn-secundario" href="lista.php">Cancelar</a>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
