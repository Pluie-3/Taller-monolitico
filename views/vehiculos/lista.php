<?php
require_once __DIR__ . '/../../bootstrap.php';

use app\controllers\config\ControllersConfig;

$vehicles = ControllersConfig::get_vehicles_controller()->get_vehicles();

require_once __DIR__ . '/../partials/header.php';
?>
<div class="barra-titulo">
    <h1>Vehiculos</h1>
    <a class="btn" href="formulario.php">+ Registrar vehiculo</a>
</div>

<?php if (count($vehicles) === 0): ?>
    <div class="lista-vacia"></div>
<?php else: ?>
    <section class="tarjetas">
        <?php foreach ($vehicles as $item): ?>
            <article class="tarjeta" data-estado="<?= e($item->get_status()) ?>">
                <p><b><?= e($item->get_brand()) ?> <?= e($item->get_model()) ?></b> (<?= (int) $item->get_year() ?>)</p>
                <p>Categoria: <?= e($item->get_category()) ?></p>
                <p class="estado"><?= e($item->get_status()) ?></p>
                <div class="acciones">
                    <a href="formulario.php?id=<?= (int) $item->get_id() ?>">Editar</a>
                    <a href="confirmar_eliminar.php?id=<?= (int) $item->get_id() ?>">Eliminar</a>
                    <a href="../alquileres/historial_vehiculo.php?id=<?= (int) $item->get_id() ?>">Historial</a>
                    <a href="#detalle-vehiculo-<?= (int) $item->get_id() ?>">Ficha rapida</a>
                </div>
            </article>

            <div id="detalle-vehiculo-<?= (int) $item->get_id() ?>" class="modal">
                <div class="modal-contenido">
                    <h2><?= e($item->get_brand() . ' ' . $item->get_model()) ?></h2>
                    <p><b>ID Interno:</b> #<?= (int) $item->get_id() ?></p>
                    <p><b>Año de fabricacion:</b> <?= (int) $item->get_year() ?></p>
                    <p><b>Categoria:</b> <?= e($item->get_category()) ?></p>
                    <p><b>Estado actual:</b> <?= e($item->get_status()) ?></p>
                    <div class="acciones">
                        <a class="btn" href="formulario.php?id=<?= (int) $item->get_id() ?>">Editar vehiculo</a>
                        <a class="btn btn-secundario" href="#">Cerrar</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
