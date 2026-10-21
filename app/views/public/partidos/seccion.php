<?php
/**
 * Sección: Partidos del Día (Juegos de Hoy)
 * Portal Oficial de las Olimpiadas COSMOL 2026
 * Compatible con PHP 7.3
 */

// Partidos oficiales exclusivamente de la jornada de hoy (muestra aviso si no hay encuentros hoy)
$hayPartidosHoy   = !empty($partidosHoy);
$partidosMostrar  = $hayPartidosHoy ? $partidosHoy : [];
$tituloSeccion    = 'Partidos de la Jornada de Hoy';
$subtituloSeccion = 'Consulte los enfrentamientos oficiales programados para la fecha de hoy (' . (!empty($fechaHoy) ? date('d/m/Y', strtotime($fechaHoy)) : date('d/m/Y')) . ').';
?>
<!-- 4. PRIMERA SECCIÓN: PARTIDOS DEL DÍA / PRÓXIMOS ENCUENTROS -->
<section id="fixture" class="py-5 mt-2" style="background: #FFFFFF;">
    <div class="container">
        <!-- Encabezado de la sección -->
        <div class="row align-items-center mb-4">
            <div class="col-md-7">
                <h2 class="font-weight-bold mt-2 text-center text-md-left" style="color: var(--cosmol-azul-deep);">
                    <?php echo e($tituloSeccion); ?>
                </h2>
                <p class="text-muted mb-0 text-center text-md-left">
                    <?php echo e($subtituloSeccion); ?>
                </p>
            </div>
            <div class="col-md-5 text-md-right mt-3 mt-md-0 text-center">
                <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-admin-login font-weight-bold rounded-pill px-4 shadow-sm" style="border-width: 2px;">
                    Ver Fixture Completo
                </a>
            </div>
        </div>

        <!-- Listado Dinámico de Partidos (Todas las Disciplinas) -->
        <div class="row" id="contenedor-partidos">

            <?php if (!empty($partidosMostrar)): ?>
                <?php foreach ($partidosMostrar as $p): 
                    $estado = $p['estado'];
                    $isLive = ($estado === 'En_Juego');
                    $isFinalizado = ($estado === 'Finalizado');
                    $colorLocal = !empty($p['local_color']) ? $p['local_color'] : '#0066B2';
                    $colorVisitante = !empty($p['visitante_color']) ? $p['visitante_color'] : '#28A745';
                    
                    // Diferenciación de disciplinas recreativas (Billar, Cacho, Karaoke)
                    $nombreDiscNorm = mb_strtolower(trim($p['disciplina_nombre']), 'UTF-8');
                    $esRecreativa   = in_array($nombreDiscNorm, ['billar', 'cacho', 'karaoke']);
                    $horaFormato    = !empty($p['hora_partido']) ? substr($p['hora_partido'], 0, 5) : '--:--';
                ?>
                    <div class="col-lg-6 mb-4 match-card-item" data-match-day="hoy" onclick="window.location.href='<?php echo BASE_URL; ?>/portal/partido/<?php echo (int)$p['id']; ?>'" style="cursor: pointer;">
                        <div class="match-box h-100 d-flex flex-column justify-content-between">
                            <!-- Cabecera del Partido -->
                            <div class="match-box-header">
                                <div class="d-flex align-items-center text-truncate mr-2">
                                    <span class="font-weight-bold text-dark"><?php echo e($p['disciplina_nombre']); ?></span>
                                    <span class="text-muted text-truncate ml-2 font-weight-bold"><?php echo !empty($p['nombre_grupo']) ? '('.e($p['nombre_grupo']).')' : ''; ?></span>
                                </div>
                                <div>
                                    <?php if ($isLive): ?>
                                        <span class="badge-match-live" title="Encuentro en disputa iniciado a la hora establecida">
                                            EN JUEGO (<?php echo $horaFormato; ?>)
                                        </span>
                                    <?php elseif ($isFinalizado): ?>
                                        <span class="badge-match-finished">
                                            FINALIZADO
                                        </span>
                                    <?php elseif ($estado === 'Suspendido' || $estado === 'WO'): ?>
                                        <span class="badge badge-warning font-weight-bold px-2 py-1">
                                            <?php echo e($estado); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-match-scheduled">
                                            <?php echo $horaFormato; ?>
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
                                            <h6 class="font-weight-bold text-dark mb-0 text-truncate" title="<?php echo e($p['local_nombre']); ?>">
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
                                        <?php if ($esRecreativa): ?>
                                            <div class="match-vs-pill d-inline-block">VS</div>
                                            <small class="text-muted d-block text-center mt-1 text-xs">
                                                <?php echo $horaFormato; ?>
                                            </small>
                                        <?php elseif ($isFinalizado): ?>
                                            <div class="match-score-pill d-inline-block">
                                                <?php echo (int)$p['marcador_local']; ?> - <?php echo (int)$p['marcador_visitante']; ?>
                                            </div>
                                        <?php elseif ($isLive): ?>
                                            <div class="match-vs-pill d-inline-block">VS</div>
                                        <?php else: ?>
                                            <div class="match-vs-pill d-inline-block">VS</div>
                                            <small class="text-muted d-block text-center mt-1 text-xs">
                                                <?php echo $horaFormato; ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Equipo Visitante -->
                                    <div class="col-5 text-center text-md-right d-flex flex-column flex-md-row-reverse align-items-center">
                                        <div class="text-truncate text-md-right" style="max-width: 100%;">
                                            <h6 class="font-weight-bold text-dark mb-0 text-truncate" title="<?php echo e($p['visitante_nombre']); ?>">
                                                <?php echo e($p['visitante_nombre']); ?>
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
                                    <span class="font-weight-bold text-dark"><?php echo !empty($p['sede_nombre']) ? e($p['sede_nombre']) : 'Sede por confirmar'; ?></span>
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
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Alerta Universal de Ausencia de Partidos para Hoy -->
                <div class="col-12 text-center py-5">
                    <div class="p-4 bg-light rounded-lg border d-inline-block shadow-sm" style="max-width: 520px; border-radius: 16px !important;">
                        <i class="far fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="font-weight-bold text-dark">No hay partidos programados para hoy</h5>
                        <p class="text-muted mb-3 text-sm">
                            No se registran encuentros a disputarse en la fecha de hoy (<?php echo date('d/m/Y', strtotime($fechaHoy)); ?>). Puede consultar los partidos anteriores y las próximas fechas en el fixture oficial.
                        </p>
                        <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-sm btn-primary rounded-pill font-weight-bold px-4 py-2" style="background-color: var(--cosmol-azul);">
                            <i class="fas fa-calendar-alt mr-1"></i> Ver Fixture Completo
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
