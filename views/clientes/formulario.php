<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_customers_controller();
$id = (int) ($_GET['id'] ?? 0);
$customer = $id > 0 ? $controller->get_customer_by_id($id) : null;

if ($id > 0 && $customer === null) {
    set_flash('El cliente solicitado no existe', false);
    redirect('lista.php');
}

$editing = $customer !== null;

require_once __DIR__ . '/../partials/header.php';
?>
<h1><?= $editing ? 'Editar cliente' : 'Registrar cliente' ?></h1>

<form class="form-2col" action="../actions/clientes_guardar.php" method="post">
    <?php if ($editing): ?>
        <input type="hidden" name="id" value="<?= (int) $customer->get_id() ?>">
    <?php endif; ?>

    <div>
        <label for="name">Nombre completo:</label>
        <input type="text" name="name" id="name" required maxlength="150"
               placeholder="Ej. Laura Gomez"
               value="<?= $editing ? e($customer->get_name()) : '' ?>">
    </div>

    <div>
        <label for="phone">Telefono (solo digitos):</label>
        <input type="text" name="phone" id="phone" required maxlength="50"
               pattern="[0-9]{7,15}" placeholder="Ej. 3001112233"
               value="<?= $editing ? e($customer->get_phone()) : '' ?>">
    </div>

    <div>
        <label for="email">Correo electronico:</label>
        <input type="email" name="email" id="email" required maxlength="100"
               placeholder="cliente@correo.com"
               value="<?= $editing ? e($customer->get_email()) : '' ?>">
    </div>

    <div>
        <label for="license_number">Numero de licencia:</label>
        <input type="text" name="license_number" id="license_number" required maxlength="50"
               placeholder="Ej. LIC-1001"
               value="<?= $editing ? e($customer->get_license_number()) : '' ?>">
    </div>

    <div class="ancho-completo acciones">
        <button type="submit">Guardar cliente</button>
        <a class="btn btn-secundario" href="lista.php">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
