<?php
/**
 * Ficha detallada de un encuentro deportivo y navegación GPS
 *
 * @var array $partido
 * @var array $sets
 * @var array $incidencias
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="color: #0066B2;">
                    Encuentro #<?php echo e($partido['id']); ?>: <?php echo e($partido['local_nombre']); ?> vs <?php echo e($partido['visitante_nombre']); ?>
                </h1>
                <span class="text-muted text-sm">
                    <?php echo e($partido['torneo_nombre']); ?> &bull; <?php echo e($partido['disciplina_nombre']); ?> (<?php echo e($partido['torneo_rama']); ?>) &bull; <?php echo e($partido['fase']); ?> - <?php echo e($partido['jornada']); ?>
                </span>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/partidos/<?php echo $partido['id']; ?>/acta" class="btn btn-success font-weight-bold mr-1">
                    <i class="fas fa-clipboard-list mr-1"></i> Acta Digital de Juego
                </a>
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/partidos/<?php echo $partido['id']; ?>/editar" class="btn btn-outline-primary font-weight-bold mr-1">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>/partidos" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Fixture
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Marcador Central del Encuentro -->
        <div class="card card-cosmol shadow-sm mb-4">
            <div class="card-body py-4">
                <div class="row align-items-center text-center">
                    
                    <!-- Equipo Local -->
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="d-inline-block p-2 rounded-circle mb-2" style="background-color: <?php echo e($partido['local_color'] ?? '#E2E8F0'); ?>22; border: 2px solid <?php echo e($partido['local_color'] ?? '#CBD5E1'); ?>;">
                            <i class="fas fa-shield-alt fa-3x" style="color: <?php echo e($partido['local_color'] ?? '#0066B2'); ?>;"></i>
                        </div>
                        <h3 class="font-weight-bold mb-1 text-dark"><?php echo e($partido['local_nombre']); ?></h3>
                        <span class="badge py-1 px-2" style="background-color: #EAF4FB; color: #0066B2; border: 1px solid #BFE2F7;">
                            <?php echo e($partido['delegacion_local']); ?>
                        </span>
                        <?php if (!empty($partido['local_indumentaria'])): ?>
                            <div class="text-xs text-muted mt-1">Indumentaria: <?php echo e($partido['local_indumentaria']); ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Marcador y Estado Central -->
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="mb-2">
                            <?php
                            $badgeClass = 'secondary';
                            switch ($partido['estado']) {
                                case 'Programado':
                                    $badgeClass = 'info';
                                    break;
                                case 'En_Juego':
                                    $badgeClass = 'primary';
                                    break;
                                case 'Finalizado':
                                    $badgeClass = 'success';
                                    break;
                                case 'Suspendido':
                                    $badgeClass = 'danger';
                                    break;
                                case 'WO':
                                    $badgeClass = 'dark';
                                    break;
                            }
                            ?>
                            <span class="badge badge-<?php echo $badgeClass; ?> px-3 py-1 font-weight-bold text-uppercase" style="letter-spacing: 1px;">
                                <?php echo str_replace('_', ' ', e($partido['estado'])); ?>
                            </span>
                        </div>

                        <div class="display-4 font-weight-bold text-dark my-2">
                            <?php if ($partido['marcador_local'] !== null && $partido['marcador_visitante'] !== null): ?>
                                <?php echo (int)$partido['marcador_local']; ?> <span class="text-muted text-sm">&mdash;</span> <?php echo (int)$partido['marcador_visitante']; ?>
                            <?php else: ?>
                                <span class="text-muted" style="font-size: 2.2rem;">VS</span>
                            <?php endif; ?>
                        </div>

                        <div class="text-muted text-sm mt-2">
                            <?php if (!empty($partido['fecha_partido'])): ?>
                                <i class="far fa-calendar-alt mr-1"></i> <?php echo date('d/m/Y', strtotime($partido['fecha_partido'])); ?>
                                <?php if (!empty($partido['hora_partido'])): ?>
                                    &bull; <i class="far fa-clock mr-1"></i> <?php echo substr($partido['hora_partido'], 0, 5); ?> hrs
                                <?php endif; ?>
                            <?php else: ?>
                                <span>Fecha y hora por definir</span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($partido['nombre_grupo'])): ?>
                            <div class="text-xs text-muted mt-1">
                                <span class="badge badge-light border"><?php echo e($partido['nombre_grupo']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Equipo Visitante -->
                    <div class="col-md-4">
                        <div class="d-inline-block p-2 rounded-circle mb-2" style="background-color: <?php echo e($partido['visitante_color'] ?? '#E2E8F0'); ?>22; border: 2px solid <?php echo e($partido['visitante_color'] ?? '#CBD5E1'); ?>;">
                            <i class="fas fa-shield-alt fa-3x" style="color: <?php echo e($partido['visitante_color'] ?? '#64748B'); ?>;"></i>
                        </div>
                        <h3 class="font-weight-bold mb-1 text-dark"><?php echo e($partido['visitante_nombre']); ?></h3>
                        <span class="badge py-1 px-2" style="background-color: #F1F5F9; color: #475569; border: 1px solid #CBD5E1;">
                            <?php echo e($partido['delegacion_visitante']); ?>
                        </span>
                        <?php if (!empty($partido['visitante_indumentaria'])): ?>
                            <div class="text-xs text-muted mt-1">Indumentaria: <?php echo e($partido['visitante_indumentaria']); ?></div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <!-- Columna Izquierda: Información de Sede, Cancha y Navegación GPS -->
            <div class="col-lg-6">
                <div class="card card-cosmol shadow-sm mb-4">
                    <div class="card-header py-2">
                        <h3 class="card-title font-weight-bold mb-0">
                            <i class="fas fa-map-marked-alt text-primary mr-1"></i> Recinto Deportivo y Cómo Llegar
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($partido['sede_nombre'])): ?>
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-light p-3 rounded mr-3 text-center" style="min-width: 60px;">
                                    <i class="fas fa-building fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="font-weight-bold text-dark mb-1"><?php echo e($partido['sede_nombre']); ?></h5>
                                    <p class="text-muted text-sm mb-1">
                                        <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                        <?php echo e($partido['sede_direccion'] ?? 'Dirección no registrada'); ?>
                                    </p>
                                    <p class="text-sm mb-0">
                                        <strong>Campo de juego:</strong>
                                        <span class="text-primary font-weight-bold"><?php echo e($partido['nombre_cancha']); ?></span>
                                        <?php if (!empty($partido['tipo_superficie'])): ?>
                                            <span class="badge badge-light border ml-1"><?php echo e($partido['tipo_superficie']); ?></span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Botón de Navegación GPS Interactiva -->
                            <div class="p-3 rounded mb-3" style="background-color: #F8FAFC; border: 1px solid #E2E8F0;">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="mb-2 mb-sm-0">
                                        <strong class="text-dark d-block">Ruta GPS en tiempo real</strong>
                                        <small class="text-muted">Calcula el trayecto desde tu ubicación actual hasta este recinto.</small>
                                    </div>
                                    <div>
                                        <?php if (!empty($partido['sede_latitud']) && !empty($partido['sede_longitud'])): ?>
                                            <button type="button" class="btn btn-sm btn-primary font-weight-bold" onclick="iniciarRutaGPS(<?php echo $partido['sede_latitud']; ?>, <?php echo $partido['sede_longitud']; ?>);">
                                                <i class="fas fa-location-arrow mr-1"></i> Cómo llegar (GPS)
                                            </button>
                                        <?php elseif (!empty($partido['sede_direccion'])): ?>
                                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo urlencode($partido['sede_direccion'] . ' ' . $partido['sede_nombre']); ?>" target="_blank" class="btn btn-sm btn-primary font-weight-bold">
                                                <i class="fas fa-directions mr-1"></i> Ver en Google Maps
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted text-xs italic">Coordenadas no disponibles</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div id="gpsStatus" class="text-xs text-info mt-2" style="display: none;"></div>
                            </div>

                        <?php else: ?>
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-map-marker-slash fa-2x mb-2 d-block"></i>
                                Este encuentro aún no tiene sede o cancha asignada.
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($partido['observaciones'])): ?>
                            <div class="mt-3">
                                <h6 class="text-xs font-weight-bold text-muted text-uppercase mb-1">Notas / Observaciones:</h6>
                                <p class="text-sm bg-light p-2 rounded mb-0 text-dark"><?php echo nl2br(e($partido['observaciones'])); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Desglose de Sets (si aplica) e Incidencias del Partido -->
            <div class="col-lg-6">

                <!-- Desglose de Sets (Voleibol o Deportes por Sets) -->
                <?php if (!empty($partido['formato_sets']) || !empty($sets)): ?>
                    <div class="card card-cosmol shadow-sm mb-4">
                        <div class="card-header py-2 d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold mb-0">
                                <i class="fas fa-volleyball-ball text-primary mr-1"></i> Desglose por Sets
                            </h3>
                            <a href="<?php echo BASE_URL; ?>/partidos/<?php echo $partido['id']; ?>/acta" class="btn btn-xs btn-outline-primary">
                                Cargar Sets
                            </a>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-sm table-striped mb-0 text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Set</th>
                                        <th><?php echo e($partido['local_nombre']); ?></th>
                                        <th><?php echo e($partido['visitante_nombre']); ?></th>
                                        <th>Ganador del Set</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($sets)): ?>
                                        <tr>
                                            <td colspan="4" class="text-muted py-3">No hay parciales de sets registrados aún.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($sets as $s): ?>
                                            <?php
                                            $ganadorSet = '';
                                            if ((int)$s['puntos_local'] > (int)$s['puntos_visitante']) {
                                                $ganadorSet = $partido['local_nombre'];
                                            } elseif ((int)$s['puntos_visitante'] > (int)$s['puntos_local']) {
                                                $ganadorSet = $partido['visitante_nombre'];
                                            } else {
                                                $ganadorSet = 'Empate';
                                            }
                                            ?>
                                            <tr>
                                                <td class="font-weight-bold">Set <?php echo e($s['numero_set']); ?></td>
                                                <td class="font-weight-bold text-dark"><?php echo e($s['puntos_local']); ?></td>
                                                <td class="font-weight-bold text-dark"><?php echo e($s['puntos_visitante']); ?></td>
                                                <td>
                                                    <span class="badge badge-light border text-dark font-weight-bold">
                                                        <?php echo e($ganadorSet); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Incidencias y Registro de Sucesos -->
                <div class="card card-cosmol shadow-sm mb-4">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold mb-0">
                            <i class="fas fa-list-alt text-primary mr-1"></i> Cronología de Incidencias
                        </h3>
                        <a href="<?php echo BASE_URL; ?>/partidos/<?php echo $partido['id']; ?>/acta" class="btn btn-xs btn-outline-success">
                            Gestionar en Acta
                        </a>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px;">Min</th>
                                    <th style="width: 80px;">Tipo</th>
                                    <th>Equipo / Jugador</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($incidencias)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            No hay incidencias registradas en este encuentro.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($incidencias as $inc): ?>
                                        <tr>
                                            <td class="font-weight-bold text-muted text-sm align-middle">
                                                <?php echo ($inc['minuto_tiempo'] !== null) ? $inc['minuto_tiempo'] . "'" : "-"; ?>
                                            </td>
                                            <td class="align-middle">
                                                <?php
                                                switch ($inc['tipo']) {
                                                    case 'GOL':
                                                        echo '<span class="badge badge-success px-2 py-1"><i class="fas fa-futbol mr-1"></i>Gol</span>';
                                                        break;
                                                    case 'PUNTO':
                                                        echo '<span class="badge badge-info px-2 py-1">Punto</span>';
                                                        break;
                                                    case 'AMARILLA':
                                                        echo '<span class="badge badge-warning px-2 py-1"><i class="fas fa-square mr-1"></i>Amarilla</span>';
                                                        break;
                                                    case 'ROJA':
                                                        echo '<span class="badge badge-danger px-2 py-1"><i class="fas fa-square mr-1"></i>Roja</span>';
                                                        break;
                                                    case 'FALTA':
                                                        echo '<span class="badge badge-secondary px-2 py-1">Falta</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge badge-light border px-2 py-1">' . e($inc['tipo']) . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="align-middle">
                                                <div class="font-weight-bold text-dark text-sm"><?php echo e($inc['equipo_nombre']); ?></div>
                                                <?php if (!empty($inc['jugador_nombres'])): ?>
                                                    <div class="text-xs text-muted">
                                                        #<?php echo e($inc['jugador_dorsal']); ?> <?php echo e($inc['jugador_nombres']); ?> <?php echo e($inc['jugador_apellidos']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-sm text-muted align-middle">
                                                <?php echo e($inc['observaciones'] ?? '-'); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
/**
 * Inicia la geolocalización del navegador del usuario y abre la ruta en Google Maps
 */
function iniciarRutaGPS(destLat, destLng) {
    var statusEl = document.getElementById('gpsStatus');
    statusEl.style.display = 'block';
    statusEl.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Obteniendo tu ubicación actual...';

    if (!navigator.geolocation) {
        statusEl.innerHTML = 'La geolocalización no es soportada por este navegador. Abriendo mapa directo...';
        window.open('https://www.google.com/maps/dir/?api=1&destination=' + destLat + ',' + destLng, '_blank');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            var origLat = position.coords.latitude;
            var origLng = position.coords.longitude;
            statusEl.innerHTML = '<i class="fas fa-check text-success mr-1"></i> Ubicación obtenida. Trazando ruta GPS...';

            var mapsUrl = 'https://www.google.com/maps/dir/?api=1&origin=' + origLat + ',' + origLng + '&destination=' + destLat + ',' + destLng + '&travelmode=driving';
            window.open(mapsUrl, '_blank');
        },
        function(error) {
            statusEl.innerHTML = '<i class="fas fa-info-circle mr-1"></i> Permiso de ubicación no otorgado. Abriendo destino en mapa...';
            window.open('https://www.google.com/maps/dir/?api=1&destination=' + destLat + ',' + destLng, '_blank');
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}
</script>
