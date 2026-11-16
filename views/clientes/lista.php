<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$customers = ControllersConfig::get_customers_controller()->get_customers();

$extra_css = [BASE_URL . 'css/vendor/bootstrap.min.css'];

require_once __DIR__ . '/../partials/header.php';
?>
<div class="barra-titulo">
    <h1>Clientes</h1>
    <a class="btn" href="formulario.php">+ Registrar cliente</a>
</div>

<table class="table table-dark table-striped table-hover">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Telefono</th>
            <th>Correo</th>
            <th>Numero de Licencia</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($customers as $c): ?>
            <tr>
                <td><?= e($c->get_name()) ?></td>
                <td><?= e($c->get_phone()) ?></td>
                <td><?= e($c->get_email()) ?></td>
                <td><span class="badge text-bg-warning"><?= e($c->get_license_number()) ?></span></td>
                <td class="acciones">
                    <a href="formulario.php?id=<?= (int) $c->get_id() ?>">Editar</a>
                    <a href="confirmar_eliminar.php?id=<?= (int) $c->get_id() ?>">Eliminar</a>
                    <a href="../alquileres/historial_cliente.php?id=<?= (int) $c->get_id() ?>">Historial</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
