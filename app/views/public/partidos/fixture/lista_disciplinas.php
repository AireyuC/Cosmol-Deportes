<?php
/**
 * Submódulo: Lista de Disciplinas y Partidos Ordenados Cronológicamente
 * Renderiza cada disciplina en su propia tarjeta y sus encuentros en una lista ordenada por fechas.
 * Compatible con PHP 7.3
 */

// Agrupar partidos por disciplina conservando el orden de prioridad oficial
$partidosPorDisciplina = [];
foreach ($partidos as $p) {
    $discNombre = $p['disciplina_nombre'];
    $partidosPorDisciplina[$discNombre][] = $p;
}
?>

<div id="contenedor-fixture">
    <?php if (empty($partidos)): ?>
        <?php if ($esFiltroMaraton): ?>
            <!-- Maratón ya se muestra arriba como card independiente -->
        <?php else: ?>
            <div id="empty-todos" class="alert alert-light border text-center py-5 match-empty-state" style="border-radius: 12px; background: #FFFFFF;">
                <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                <h5 class="text-dark font-weight-bold mb-1">No se encontraron encuentros con los filtros seleccionados</h5>
                <p class="text-muted mb-0 text-sm">Pruebe seleccionando otra disciplina para consultar los encuentros programados.</p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <?php foreach ($partidosPorDisciplina as $nombreDisciplina => $listaPartidos): 
            $iconoDisc = obtenerIconoDisciplinaFixture($nombreDisciplina);
        ?>
            <div class="fixture-discipline-card discipline-section mb-4" data-discipline-name="<?php echo e($nombreDisciplina); ?>">
                <!-- Encabezado de la Card de Disciplina -->
                <div class="fixture-card-header">
                    <div class="d-flex align-items-center">
                        <div class="discipline-icon-badge mr-2">
                            <i class="<?php echo $iconoDisc; ?>"></i>
                        </div>
                        <div>
                            <h5 class="fixture-discipline-title mb-0">
                                <?php echo e(mb_strtoupper($nombreDisciplina, 'UTF-8')); ?>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <?php if (mb_strtolower(trim($nombreDisciplina), 'UTF-8') === 'billar'): ?>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill font-weight-bold mr-2 shadow-sm" data-toggle="modal" data-target="#modalLlaveBillar" title="Ver Cuadro y Esquema de Eliminación Directa">
                                Ver Llave de Eliminación
                            </button>
                        <?php elseif (mb_strtolower(trim($nombreDisciplina), 'UTF-8') === 'cacho'): ?>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill font-weight-bold mr-2 shadow-sm" data-toggle="modal" data-target="#modalLlaveCacho" title="Ver Cuadro y Esquema de Eliminación Directa">
                                Ver Llave de Eliminación
                            </button>
                        <?php endif; ?>
                        <span class="discipline-count-badge">
                            <span class="discipline-count-visible"><?php echo count($listaPartidos); ?></span> <?php echo count($listaPartidos) === 1 ? 'partido' : 'partidos'; ?>
                        </span>
                    </div>
                </div>

                <!-- Lista de Partidos Ordenada Cronológicamente -->
                <div class="fixture-match-list">
                    <?php foreach ($listaPartidos as $p): 
                        $fechaSolo = !empty($p['fecha_partido']) ? date('Y-m-d', strtotime($p['fecha_partido'])) : '';
                        $cardDay = 'otro';
                        if ($fechaSolo === $fechaHoy) {
                            $cardDay = 'hoy';
                        } elseif ($fechaSolo === $fechaAyer) {
                            $cardDay = 'ayer';
                        } elseif ($fechaSolo === $fechaManana) {
                            $cardDay = 'manana';
                        }

                        $nLower = mb_strtolower(trim($p['disciplina_nombre']), 'UTF-8');
                        $esRecreativa = in_array($nLower, ['billar', 'cacho', 'karaoke']);
                        $horaFormato  = !empty($p['hora_partido']) ? substr($p['hora_partido'], 0, 5) : '--:--';
                        $isLive       = ($p['estado'] === 'En_Juego');
                        $isFinalizado = ($p['estado'] === 'Finalizado');
                        $fechaTexto   = formatearFechaEspanolFixture($p['fecha_partido']);
                        $canchaNombre = !empty($p['nombre_cancha']) ? $p['nombre_cancha'] : (!empty($p['sede_nombre']) ? $p['sede_nombre'] : 'Cancha por definir');
                        $faseInfo     = !empty($p['fase']) ? str_replace('_', ' ', $p['fase']) : (!empty($p['jornada']) ? 'Jornada ' . $p['jornada'] : '');
                    ?>
                        <div class="fixture-match-row match-card-item" data-match-day="<?php echo $cardDay; ?>" data-match-date="<?php echo $fechaSolo; ?>" onclick="window.location.href='<?php echo BASE_URL; ?>/portal/partido/<?php echo (int)$p['id']; ?>'">
                            <!-- Columna 1: Fecha & Hora -->
                            <div class="fixture-col-date">
                                <div class="fixture-date-val">
                                    <?php echo $fechaTexto; ?>
                                </div>
                                <div class="fixture-time-val">
                                    <i class="far fa-clock mr-1 text-muted"></i>
                                    <span><?php echo $horaFormato; ?></span>
                                    <?php if (!empty($faseInfo)): ?>
                                        <span class="fixture-phase-badge"><?php echo e($faseInfo); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Columna 2: Equipos & Marcador -->
                            <div class="fixture-col-teams">
                                <div class="fixture-team fixture-team-local" title="<?php echo e($p['local_nombre']); ?>">
                                    <span class="fixture-team-name"><?php echo e($p['local_nombre']); ?></span>
                                    <?php if (!empty($p['delegacion_local'])): ?>
                                        <div class="fixture-team-sub">
                                            <?php if (stripos($p['delegacion_local'], 'sorteo') !== false || stripos($p['delegacion_local'], 'definir') !== false): ?>
                                                <span class="badge badge-light border text-muted px-1" style="font-size: 0.72rem; font-weight: normal;"><i class="fas fa-random mr-1"></i>Pendiente Sorteo</span>
                                            <?php else: ?>
                                                <?php echo e($p['delegacion_local']); ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="fixture-score-center">
                                    <?php if ($isFinalizado || ($p['marcador_local'] !== null && $p['marcador_visitante'] !== null)): ?>
                                        <span class="fixture-score-pill">
                                            <?php echo (int)$p['marcador_local']; ?> - <?php echo (int)$p['marcador_visitante']; ?>
                                        </span>
                                    <?php elseif ($isLive): ?>
                                        <span class="fixture-live-pill">
                                            <i class="fas fa-circle mr-1" style="font-size: 0.5rem; animation: pulse 1.5s infinite;"></i> En juego
                                        </span>
                                    <?php elseif ($p['estado'] === 'Suspendido' || $p['estado'] === 'WO'): ?>
                                        <span class="badge badge-warning font-weight-bold px-2 py-1" style="font-size: 0.72rem;">
                                            <?php echo e($p['estado']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="fixture-vs-pill">vs</span>
                                    <?php endif; ?>
                                </div>

                                <div class="fixture-team fixture-team-visit" title="<?php echo e($p['visitante_nombre']); ?>">
                                    <span class="fixture-team-name"><?php echo e($p['visitante_nombre']); ?></span>
                                    <?php if (!empty($p['delegacion_visitante'])): ?>
                                        <div class="fixture-team-sub">
                                            <?php if (stripos($p['delegacion_visitante'], 'sorteo') !== false || stripos($p['delegacion_visitante'], 'definir') !== false): ?>
                                                <span class="badge badge-light border text-muted px-1" style="font-size: 0.72rem; font-weight: normal;"><i class="fas fa-random mr-1"></i>Pendiente Sorteo</span>
                                            <?php else: ?>
                                                <?php echo e($p['delegacion_visitante']); ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Columna 3: Cancha / Sede & GPS -->
                            <div class="fixture-col-venue">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="fixture-venue-info">
                                        <div class="fixture-venue-name" title="<?php echo e($canchaNombre); ?>">
                                            <i class="fas fa-map-marker-alt text-danger mr-1"></i><?php echo e($canchaNombre); ?>
                                        </div>
                                        <?php if (!empty($p['sede_nombre']) && $p['sede_nombre'] !== $canchaNombre): ?>
                                            <div class="fixture-venue-sub" title="<?php echo e($p['sede_nombre']); ?>">
                                                <?php echo e($p['sede_nombre']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($p['sede_latitud']) && !empty($p['sede_longitud'])): ?>
                                        <button type="button" class="btn-gps-flat" onclick="event.stopPropagation(); abrirRutaGps(<?php echo (float)$p['sede_latitud']; ?>, <?php echo (float)$p['sede_longitud']; ?>, '<?php echo addslashes(!empty($p['sede_nombre']) ? $p['sede_nombre'] : $canchaNombre); ?>')" title="Ver ruta GPS en Google Maps">
                                            <i class="fas fa-location-arrow mr-1"></i> GPS
                                        </button>
                                    <?php endif; ?>
                                    <i class="fas fa-chevron-right fixture-arrow-icon"></i>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Estados Vacíos Según Filtro de Fecha -->
    <div id="empty-ayer" class="alert alert-light border text-center py-5 match-empty-state d-none" style="border-radius: 12px; background: #FFFFFF;">
        <i class="far fa-calendar-times fa-2x text-muted mb-2"></i>
        <h5 class="text-dark font-weight-bold mb-1">No se disputaron partidos ayer</h5>
        <p class="text-muted mb-0 text-sm">
            No se registraron encuentros oficiales en la fecha de ayer (<?php echo date('d/m/Y', strtotime($fechaAyer)); ?>).
        </p>
    </div>

    <div id="empty-hoy" class="alert alert-light border text-center py-5 match-empty-state d-none" style="border-radius: 12px; background: #FFFFFF;">
        <i class="far fa-calendar-times fa-2x text-muted mb-2"></i>
        <h5 class="text-dark font-weight-bold mb-1">No hay partidos programados para hoy</h5>
        <p class="text-muted mb-0 text-sm">
            No se registran encuentros a disputarse en la fecha de hoy (<?php echo date('d/m/Y', strtotime($fechaHoy)); ?>).
        </p>
    </div>

    <div id="empty-manana" class="alert alert-light border text-center py-5 match-empty-state d-none" style="border-radius: 12px; background: #FFFFFF;">
        <i class="far fa-calendar-times fa-2x text-muted mb-2"></i>
        <h5 class="text-dark font-weight-bold mb-1">No hay partidos programados para mañana</h5>
        <p class="text-muted mb-0 text-sm">
            Aún no se han publicado encuentros para la fecha de mañana (<?php echo date('d/m/Y', strtotime($fechaManana)); ?>).
        </p>
    </div>
</div>
