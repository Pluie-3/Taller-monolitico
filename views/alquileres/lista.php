<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$rentals = ControllersConfig::get_rentals_controller()->get_rentals();

require_once __DIR__ . '/../partials/header.php';
?>
<div class="barra-titulo">
    <h1>Reservas y Alquileres</h1>
    <a class="btn" href="formulario_reserva.php">+ Nueva reserva</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Vehiculo</th>
            <th>Cliente</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rentals as $r): ?>
            <tr>
                <td>#<?= (int) $r->get_id() ?></td>
                <td><?= e($r->get_vehicle_label()) ?></td>
                <td><?= e($r->get_customer_name()) ?></td>
                <td><?= e($r->get_start_date()) ?></td>
                <td><?= e($r->get_end_date()) ?></td>
                <td><b><?= e($r->get_status()) ?></b></td>
                <td class="acciones">
                    <?php if ($r->get_status() === 'activa'): ?>
                        <form action="../actions/alquileres_entregar.php" method="post">
                            <input type="hidden" name="id" value="<?= (int) $r->get_id() ?>">
                            <button type="submit">Entregar</button>
                        </form>
                        <form action="../actions/alquileres_devolver.php" method="post">
                            <input type="hidden" name="id" value="<?= (int) $r->get_id() ?>">
                            <button type="submit">Devolver</button>
                        </form>
                        <form action="../actions/alquileres_cancelar.php" method="post">
                            <input type="hidden" name="id" value="<?= (int) $r->get_id() ?>">
                            <button class="btn-peligro" type="submit">Cancelar</button>
                        </form>
                    <?php else: ?>
                        <span class="texto-apagado">Sin acciones</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
