<?php
/**
 * Sección: Partidos y Resultados Oficiales
 * Portal Oficial de las Olimpiadas COSMOL 2026
 * Compatible con PHP 7.3
 */

$hayPartidosHoy       = !empty($partidosHoy);
$hayUltimosResultados = !empty($ultimosResultados);
$hayProximos          = !empty($proximosPartidos);

// Determinar pestaña activa por defecto
$tabActivo = 'hoy';
if (!$hayPartidosHoy) {
    if ($hayUltimosResultados) {
        $tabActivo = 'resultados';
    } else {
        $tabActivo = 'proximos';
    }
}

if (!function_exists('renderMatchCardPortal')) {
    function renderMatchCardPortal($p) {
        $estado = $p['estado'];
        $hasScores = ($p['marcador_local'] !== null && $p['marcador_visitante'] !== null);
        $isLive = ($estado === 'En_Juego');
        $isFinalizado = ($estado === 'Finalizado') || $hasScores;
        $colorLocal = !empty($p['local_color']) ? $p['local_color'] : '#0066B2';
        $colorVisitante = !empty($p['visitante_color']) ? $p['visitante_color'] : '#28A745';
        $horaFormato = !empty($p['hora_partido']) ? substr($p['hora_partido'], 0, 5) : '--:--';
        $fechaFormato = !empty($p['fecha_partido']) ? date('d/m', strtotime($p['fecha_partido'])) : '';
        $faseJornada = !empty($p['fase']) ? str_replace('_', ' ', $p['fase']) : '';
        if (!empty($p['jornada'])) {
            $faseJornada .= ($faseJornada ? ' • ' : '') . $p['jornada'];
        }

        $localGano = false;
        $visitanteGano = false;
        if ($hasScores) {
            $mL = (int)$p['marcador_local'];
            $mV = (int)$p['marcador_visitante'];
            if ($mL > $mV) { $localGano = true; }
            elseif ($mV > $mL) { $visitanteGano = true; }
        }
        ?>
        <div class="col-lg-6 mb-4 match-card-item" onclick="window.location.href='<?php echo BASE_URL; ?>/portal/partido/<?php echo (int)$p['id']; ?>'" style="cursor: pointer;">
            <div class="match-box h-100 d-flex flex-column justify-content-between">
                <!-- Cabecera del Partido -->
                <div class="match-box-header">
                    <div class="d-flex align-items-center text-truncate mr-2">
                        <span class="font-weight-bold text-dark"><?php echo e($p['disciplina_nombre']); ?></span>
                        <?php if (!empty($p['nombre_grupo'])): ?>
                            <span class="text-muted ml-2 font-weight-bold">(<?php echo e($p['nombre_grupo']); ?>)</span>
                        <?php elseif (!empty($faseJornada)): ?>
                            <span class="badge badge-light border text-muted ml-2 px-2 py-1 font-weight-normal"><?php echo e($faseJornada); ?></span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <?php if ($isLive): ?>
                            <span class="badge-match-live" title="Encuentro en disputa">
                                EN JUEGO (<?php echo $horaFormato; ?>)
                            </span>
                        <?php elseif ($isFinalizado): ?>
                            <span class="badge-match-finished">
                                <i class="fas fa-check-circle mr-1"></i>FINALIZADO
                            </span>
                        <?php elseif ($estado === 'Suspendido' || $estado === 'WO'): ?>
                            <span class="badge badge-warning font-weight-bold px-2 py-1">
                                <?php echo e($estado); ?>
                            </span>
                        <?php else: ?>
                            <span class="badge-match-scheduled">
                                <?php echo $fechaFormato ? $fechaFormato . ' • ' : ''; ?><?php echo $horaFormato; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Cuerpo con Enfrentamiento y Marcador -->
                <div class="match-box-body">
                    <div class="row align-items-center">
                        <!-- Equipo Local -->
                        <div class="col-5 text-center text-md-left d-flex flex-column flex-md-row align-items-center">
                            <div class="text-truncate" style="max-width: 100%;">
                                <h6 class="mb-0 text-truncate <?php echo $localGano ? 'font-weight-bold text-dark' : ($visitanteGano ? 'text-muted font-weight-normal' : 'font-weight-bold text-dark'); ?>" title="<?php echo e($p['local_nombre']); ?>">
                                    <?php if ($localGano): ?>
                                        <i class="fas fa-caret-right text-success mr-1"></i>
                                    <?php endif; ?>
                                    <?php echo e($p['local_nombre']); ?>
                                </h6>
                                <small class="text-muted">
                                    <?php if (!empty($p['delegacion_local']) && stripos($p['delegacion_local'], 'sorteo') === false && stripos($p['delegacion_local'], 'definir') === false): ?>
                                        <?php echo e($p['delegacion_local']); ?>
                                    <?php else: ?>
                                        <span class="text-secondary font-italic"><i class="fas fa-random mr-1"></i>Por Sorteo</span>
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>

                        <!-- Marcador / VS Central -->
                        <div class="col-2 text-center px-0">
                            <?php if ($hasScores): ?>
                                <div class="match-score-pill d-inline-block shadow-sm">
                                    <?php echo (int)$p['marcador_local']; ?> - <?php echo (int)$p['marcador_visitante']; ?>
                                </div>
                                <small class="text-success font-weight-bold d-block text-center mt-1 text-xs">
                                    Final
                                </small>
                            <?php elseif ($isLive): ?>
                                <div class="badge badge-warning text-dark font-weight-bold px-2 py-1">
                                    EN VIVO
                                </div>
                            <?php else: ?>
                                <div class="match-vs-pill d-inline-block">VS</div>
                                <small class="text-muted d-block text-center mt-1 text-xs font-weight-bold">
                                    <?php echo $horaFormato; ?>
                                </small>
                            <?php endif; ?>
                        </div>

                        <!-- Equipo Visitante -->
                        <div class="col-5 text-center text-md-right d-flex flex-column flex-md-row-reverse align-items-center">
                            <div class="text-truncate text-md-right" style="max-width: 100%;">
                                <h6 class="mb-0 text-truncate <?php echo $visitanteGano ? 'font-weight-bold text-dark' : ($localGano ? 'text-muted font-weight-normal' : 'font-weight-bold text-dark'); ?>" title="<?php echo e($p['visitante_nombre']); ?>">
                                    <?php echo e($p['visitante_nombre']); ?>
                                    <?php if ($visitanteGano): ?>
                                        <i class="fas fa-caret-left text-success ml-1"></i>
                                    <?php endif; ?>
                                </h6>
                                <small class="text-muted">
                                    <?php if (!empty($p['delegacion_visitante']) && stripos($p['delegacion_visitante'], 'sorteo') === false && stripos($p['delegacion_visitante'], 'definir') === false): ?>
                                        <?php echo e($p['delegacion_visitante']); ?>
                                    <?php else: ?>
                                        <span class="text-secondary font-italic"><i class="fas fa-random mr-1"></i>Por Sorteo</span>
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie de Tarjeta con Sede, Cancha y Botón GPS -->
                <div class="match-box-footer d-flex justify-content-between align-items-center">
                    <div class="text-truncate mr-2">
                        <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                        <span class="font-weight-bold text-dark"><?php echo !empty($p['cancha_nombre']) ? e($p['cancha_nombre']) : (!empty($p['sede_nombre']) ? e($p['sede_nombre']) : 'Sede por confirmar'); ?></span>
                        <?php if (!empty($p['sede_nombre']) && !empty($p['cancha_nombre']) && $p['sede_nombre'] !== $p['cancha_nombre']): ?>
                            <small class="text-muted ml-1">(<?php echo e($p['sede_nombre']); ?>)</small>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex align-items-center">
                        <?php if (!empty($p['sede_latitud']) && !empty($p['sede_longitud'])): ?>
                            <button type="button" class="btn btn-sm btn-link p-0 text-primary font-weight-bold mr-3" onclick="event.stopPropagation(); abrirRutaGps(<?php echo (float)$p['sede_latitud']; ?>, <?php echo (float)$p['sede_longitud']; ?>, '<?php echo addslashes($p['sede_nombre']); ?>')">
                                <i class="fas fa-location-arrow mr-1"></i> Ruta GPS
                            </button>
                        <?php endif; ?>
                        <span class="text-primary font-weight-bold text-xs">
                            Ver detalle
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>

<!-- SECCIÓN: PARTIDOS Y RESULTADOS DEL TORNEO -->
<section id="fixture" class="py-5 mt-2" style="background: #FFFFFF;">
    <div class="container">
        
        <!-- Encabezado de la sección con Tabs -->
        <div class="row align-items-center mb-4">
            <div class="col-lg-6">
                <h2 class="font-weight-bold mt-2 text-center text-lg-left" style="color: var(--cosmol-azul-deep);">
                    Partidos y Resultados
                </h2>
                <p class="text-muted mb-0 text-center text-lg-left" style="font-size: 0.95rem;">
                    Marcadores oficiales en tiempo real, resultados recientes y cronograma de encuentros.
                </p>
            </div>
            
            <div class="col-lg-6 text-lg-right mt-3 mt-lg-0 text-center">
                <ul class="nav nav-pills d-inline-flex bg-light p-1 rounded-pill shadow-sm mb-2" id="partidosTab" role="tablist">
                    <?php if ($hayPartidosHoy): ?>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill font-weight-bold py-1 px-3 text-xs <?php echo ($tabActivo === 'hoy') ? 'active bg-primary text-white' : 'text-dark'; ?>" id="tab-hoy-link" data-toggle="pill" href="#tab-partidos-hoy" role="tab" aria-controls="tab-partidos-hoy" aria-selected="<?php echo ($tabActivo === 'hoy') ? 'true' : 'false'; ?>">
                                <i class="fas fa-calendar-day mr-1"></i> Hoy (<?php echo count($partidosHoy); ?>)
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link rounded-pill font-weight-bold py-1 px-3 text-xs <?php echo ($tabActivo === 'resultados') ? 'active bg-primary text-white' : 'text-dark'; ?>" id="tab-resultados-link" data-toggle="pill" href="#tab-partidos-resultados" role="tab" aria-controls="tab-partidos-resultados" aria-selected="<?php echo ($tabActivo === 'resultados') ? 'true' : 'false'; ?>">
                            <i class="fas fa-trophy mr-1"></i> Resultados Recientes (<?php echo count($ultimosResultados); ?>)
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link rounded-pill font-weight-bold py-1 px-3 text-xs <?php echo ($tabActivo === 'proximos') ? 'active bg-primary text-white' : 'text-dark'; ?>" id="tab-proximos-link" data-toggle="pill" href="#tab-partidos-proximos" role="tab" aria-controls="tab-partidos-proximos" aria-selected="<?php echo ($tabActivo === 'proximos') ? 'true' : 'false'; ?>">
                            <i class="far fa-clock mr-1"></i> Próximos (<?php echo count($proximosPartidos); ?>)
                        </a>
                    </li>
                </ul>

                <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-outline-primary btn-sm font-weight-bold rounded-pill px-3 shadow-sm ml-lg-2 mb-2">
                    <i class="fas fa-list mr-1"></i> Fixture Completo
                </a>
            </div>
        </div>

        <!-- Contenedores por Pestaña -->
        <div class="tab-content" id="partidosTabContent">
            
            <!-- 1. Pestaña: Partidos de Hoy -->
            <?php if ($hayPartidosHoy): ?>
                <div class="tab-pane fade <?php echo ($tabActivo === 'hoy') ? 'show active' : ''; ?>" id="tab-partidos-hoy" role="tabpanel" aria-labelledby="tab-hoy-link">
                    <div class="row">
                        <?php foreach ($partidosHoy as $pHoy): ?>
                            <?php renderMatchCardPortal($pHoy); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 2. Pestaña: Resultados Recientes (Partidos Finalizados con Marcador) -->
            <div class="tab-pane fade <?php echo ($tabActivo === 'resultados') ? 'show active' : ''; ?>" id="tab-partidos-resultados" role="tabpanel" aria-labelledby="tab-resultados-link">
                <?php if (!empty($ultimosResultados)): ?>
                    <div class="row">
                        <?php foreach ($ultimosResultados as $pRes): ?>
                            <?php renderMatchCardPortal($pRes); ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="p-4 bg-light rounded-lg border d-inline-block shadow-sm" style="max-width: 520px; border-radius: 16px !important;">
                            <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                            <h5 class="font-weight-bold text-dark">Aún no se registran resultados oficiales</h5>
                            <p class="text-muted mb-3 text-sm">
                                Los marcadores se publicarán en esta sección a medida que el comité organizador concluya las actas de cada encuentro.
                            </p>
                            <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-sm btn-primary rounded-pill font-weight-bold px-4 py-2" style="background-color: var(--cosmol-azul);">
                                <i class="fas fa-calendar-alt mr-1"></i> Consultar Fixture
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- 3. Pestaña: Próximos Encuentros -->
            <div class="tab-pane fade <?php echo ($tabActivo === 'proximos') ? 'show active' : ''; ?>" id="tab-partidos-proximos" role="tabpanel" aria-labelledby="tab-proximos-link">
                <?php if (!empty($proximosPartidos)): ?>
                    <div class="row">
                        <?php foreach ($proximosPartidos as $pProx): ?>
                            <?php renderMatchCardPortal($pProx); ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="p-4 bg-light rounded-lg border d-inline-block shadow-sm" style="max-width: 520px; border-radius: 16px !important;">
                            <i class="far fa-calendar-check fa-3x text-muted mb-3"></i>
                            <h5 class="font-weight-bold text-dark">Todos los partidos han sido disputados</h5>
                            <p class="text-muted mb-3 text-sm">
                                No hay encuentros pendientes por jugar en el calendario actual.
                            </p>
                            <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-sm btn-primary rounded-pill font-weight-bold px-4 py-2" style="background-color: var(--cosmol-azul);">
                                <i class="fas fa-calendar-alt mr-1"></i> Ver Historial de Encuentros
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>
</section>
