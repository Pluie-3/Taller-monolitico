<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$id = (int) ($_GET['id'] ?? 0);
$customer = ControllersConfig::get_customers_controller()->get_customer_by_id($id);

if ($customer === null) {
    set_flash('El cliente no existe', false);
    redirect('../clientes/lista.php');
}

$history = ControllersConfig::get_rentals_controller()->get_history_by_customer($id);

require_once __DIR__ . '/../partials/header.php';
?>
<h1>Historial del Cliente</h1>

<div class="clearfix">
    <aside class="ficha tarjeta">
        <h2><?= e($customer->get_name()) ?></h2>
        <p><b>Licencia:</b> <?= e($customer->get_license_number()) ?></p>
        <p><b>Telefono:</b> <?= e($customer->get_phone()) ?></p>
        <p><b>Correo:</b> <?= e($customer->get_email()) ?></p>
    </aside>

    <div class="contenido-historial">
        <?php if (count($history) === 0): ?>
            <p>Este cliente aun no tiene reservas registradas en su historial.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Vehiculo</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $h): ?>
                        <tr>
                            <td><?= e($h->get_vehicle_label()) ?></td>
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

<p class="mt-18"><a class="btn btn-secundario" href="../clientes/lista.php">Volver a clientes</a></p>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
