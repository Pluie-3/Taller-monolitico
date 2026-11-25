<?php
$fecha_sel = $fecha_seleccionada ?? date('Y-m-d');
$url_cal = $url_base_cal ?? 'disponibles.php';
$param_name = $param_fecha ?? 'fecha';
$extra_q = $extra_query ?? '';

$ts_sel = strtotime($fecha_sel) ?: time();
$year = (int) date('Y', $ts_sel);
$month = (int) date('m', $ts_sel);

$primer_dia_ts = mktime(0, 0, 0, $month, 1, $year);
$dias_en_mes = (int) date('t', $primer_dia_ts);
$dia_semana_inicio = (int) date('N', $primer_dia_ts); // 1 (Lun) a 7 (Dom)

$prev_month_ts = strtotime('-1 month', $primer_dia_ts);
$next_month_ts = strtotime('+1 month', $primer_dia_ts);
$prev_date = date('Y-m-01', $prev_month_ts);
$next_date = date('Y-m-01', $next_month_ts);

$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$hoy_str = date('Y-m-d');
?>
<div class="calendario-contenedor">
    <div class="calendario-cabecera">
        <h3 class="calendario-titulo"><?= $meses[$month] . ' ' . $year ?></h3>
        <div class="calendario-nav">
            <a class="btn btn-secundario btn-xs" href="<?= e($url_cal . '?' . $param_name . '=' . $prev_date . $extra_q) ?>">&laquo; Mes anterior</a>
            <a class="btn btn-secundario btn-xs" href="<?= e($url_cal . '?' . $param_name . '=' . $hoy_str . $extra_q) ?>">Hoy</a>
            <a class="btn btn-secundario btn-xs" href="<?= e($url_cal . '?' . $param_name . '=' . $next_date . $extra_q) ?>">Mes siguiente &raquo;</a>
        </div>
    </div>

    <div class="calendario-dias-semana">
        <span>Lun</span><span>Mar</span><span>Mie</span><span>Jue</span><span>Vie</span><span>Sab</span><span>Dom</span>
    </div>

    <div class="calendario-grid">
        <?php for ($i = 1; $i < $dia_semana_inicio; $i++): ?>
            <div class="calendario-celda otro-mes"></div>
        <?php endfor; ?>

        <?php for ($dia = 1; $dia <= $dias_en_mes; $dia++): ?>
            <?php
            $fecha_actual = sprintf('%04d-%02d-%02d', $year, $month, $dia);
            $clases = 'calendario-celda';
            if ($fecha_actual === $fecha_sel) {
                $clases .= ' seleccionado';
            }
            if ($fecha_actual === $hoy_str) {
                $clases .= ' hoy';
            }
            ?>
            <a class="<?= $clases ?>" href="<?= e($url_cal . '?' . $param_name . '=' . $fecha_actual . $extra_q) ?>">
                <span class="dia-numero"><?= $dia ?></span>
            </a>
        <?php endfor; ?>
    </div>
</div>