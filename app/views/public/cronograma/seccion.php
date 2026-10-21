<?php
/**
 * Sección Principal: Cronograma General de Actividades
 * Portal Oficial de las Olimpiadas COSMOL 2026
 * Basado en Docs/cronograma.md
 * Diseño plano corporativo sin gradientes, alineado a cards oficiales.
 * Compatible con PHP 7.3
 */

// Si no vienen los datos desde el controlador, cargarlos desde el modelo
if (!isset($cronogramaDias)) {
    require_once APP_PATH . '/models/Cronograma.php';
    $cronogramaDias = Cronograma::getDias();
}
?>
<!-- SECCIÓN: CRONOGRAMA GENERAL DE ACTIVIDADES -->
<section id="cronograma" class="py-5" style="background-color: #F8FAFC; border-top: 1px solid var(--cosmol-borde); border-bottom: 1px solid var(--cosmol-borde);">
    <div class="container">
        <!-- Encabezado de la Sección -->
        <div class="row align-items-center mb-4">
            <div class="col-lg-6 text-center text-lg-left">
                <h2 class="font-weight-bold mb-1" style="color: var(--cosmol-azul-deep); font-size: 2.1rem;">
                    Cronograma General de Actividades
                </h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">
                    Jornadas Deportivas ANESAPA - COSMOL · Jueves 22 · Viernes 23 · Sábado 24
                </p>
            </div>
            <div class="col-lg-6 text-center text-lg-right mt-3 mt-lg-0">
                <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-admin-login font-weight-bold rounded-pill px-3 py-2 mr-2 mb-2 mb-sm-0 shadow-sm" style="border-width: 2px;">
                    Ver Fixture
                </a>
                <a href="<?php echo BASE_URL; ?>/portal/cronograma" class="btn btn-admin-login font-weight-bold rounded-pill px-4 py-2 shadow-sm">
                    Cronograma Detallado
                </a>
            </div>
        </div>

        <!-- Parrilla de Días del Cronograma -->
        <div class="row">
            <?php foreach ($cronogramaDias as $dia): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="cronograma-day-card">
                        <!-- Cabecera del Día -->
                        <div class="cronograma-day-header">
                            <div>
                                <h3 class="cronograma-day-title">
                                    <?php echo e($dia['dia_nombre']); ?>
                                </h3>
                                <small class="text-muted d-block" style="font-size: 0.78rem;">
                                    <?php echo e($dia['subtitulo']); ?>
                                </small>
                            </div>
                        </div>

                        <!-- Lista de Actividades del Día -->
                        <div class="cronograma-day-body">
                            <?php foreach ($dia['actividades'] as $act): 
                                $catClass = 'cronograma-cat-deportiva';
                                if ($act['categoria'] === 'Recreativa') {
                                    $catClass = 'cronograma-cat-recreativa';
                                } elseif ($act['categoria'] === 'Institucional') {
                                    $catClass = 'cronograma-cat-institucional';
                                } elseif ($act['categoria'] === 'Protocolar') {
                                    $catClass = 'cronograma-cat-protocolar';
                                }
                            ?>
                                <div class="cronograma-activity-item">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="cronograma-time">
                                            <?php echo e($act['horario']); ?>
                                        </span>
                                        <span class="cronograma-badge-category <?php echo $catClass; ?>">
                                            <?php echo e($act['categoria']); ?>
                                        </span>
                                    </div>
                                    <div class="cronograma-act-title">
                                        <?php echo e($act['actividad']); ?>
                                    </div>
                                    <div class="cronograma-venue">
                                        <?php echo e($act['lugar']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pie del Card del Día -->
                        <div class="cronograma-day-footer text-center">
                            <a href="<?php echo BASE_URL; ?>/portal/cronograma?dia=<?php echo urlencode($dia['dia_id']); ?>" class="btn btn-sm btn-admin-login font-weight-bold btn-block" style="border-radius: 8px;">
                                Consultar este día en detalle
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Banner Informativo Complementario sin Gradientes -->
        <div class="mt-2 p-3 p-md-4 rounded border bg-white shadow-sm" style="border-color: #E2E8F0 !important;">
            <div class="row align-items-center">
                <div class="col-md-8 text-center text-md-left mb-3 mb-md-0">
                    <h5 class="font-weight-bold mb-1" style="color: var(--cosmol-azul-deep);">
                        Programa Completo y Recintos Habilitados
                    </h5>
                    <p class="text-muted text-sm mb-0">
                        Consulte las 10 sedes oficiales, direcciones, horarios unificados y distribución de disciplinas para todas las delegaciones participantes.
                    </p>
                </div>
                <div class="col-md-4 text-center text-md-right">
                    <a href="<?php echo BASE_URL; ?>/portal/cronograma" class="btn btn-admin-login btn-sm font-weight-bold px-3 py-2 mr-2">
                        Ver Programa Completo
                    </a>
                    <a href="<?php echo BASE_URL; ?>/#sedes" class="btn btn-light btn-sm font-weight-bold px-3 py-2 border">
                        Ver Sedes GPS
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
