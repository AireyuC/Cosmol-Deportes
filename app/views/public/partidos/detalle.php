<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($pageTitle) ? e($pageTitle) : 'Detalle del Encuentro | COSMOL Deportes'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/logoSinFondo.png">
    <!-- Estilos Oficiales del Portal Público -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/portal.css">
    <!-- Estilos Específicos para el Detalle del Partido -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/partido_detalle.css">
</head>
<body>
    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-public sticky-top">
        <div class="container">
            <a class="navbar-brand font-weight-bold text-primary" href="<?php echo BASE_URL; ?>/">
                <img src="<?php echo BASE_URL; ?>/images/logoSinFondo.png" height="38" class="mr-2" alt="COSMOL">
                COSMOL
            </a>
            <div class="ml-auto d-flex align-items-center">
                <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-outline-secondary btn-sm font-weight-bold">
                    Volver al Fixture
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <?php
            $isLive       = ($partido['estado'] === 'En_Juego');
            $isFinalizado = ($partido['estado'] === 'Finalizado');
            $nombreDiscNorm = mb_strtolower(trim($partido['disciplina_nombre']), 'UTF-8');
            $esRecreativa   = in_array($nombreDiscNorm, ['billar', 'cacho', 'karaoke']);
            $horaFormato    = !empty($partido['hora_partido']) ? substr($partido['hora_partido'], 0, 5) : '--:--';
        ?>

        <!-- Tarjeta Principal del Encuentro -->
        <div class="match-detail-card shadow-sm mb-4">
            <!-- Cabecera plana -->
            <div class="match-detail-header d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex align-items-center flex-wrap">
                    <span class="badge badge-light text-primary font-weight-bold px-2 py-1 mr-2 text-xs text-uppercase match-detail-disc-badge">
                        <?php echo e($partido['disciplina_nombre']); ?>
                    </span>
                    <span class="font-weight-bold text-white mr-2"><?php echo e($partido['torneo_nombre']); ?></span>
                    <?php if (!empty($partido['nombre_grupo'])): ?>
                        <span class="badge match-detail-group-badge px-2 py-1 text-xs">
                            <?php echo e($partido['nombre_grupo']); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="mt-2 mt-md-0">
                    <?php if ($isLive): ?>
                        <span class="badge badge-danger text-xs px-2 py-1 font-weight-bold">EN JUEGO</span>
                    <?php elseif ($isFinalizado): ?>
                        <span class="badge badge-success text-xs px-2 py-1 font-weight-bold">FINALIZADO</span>
                    <?php elseif ($partido['estado'] === 'Suspendido' || $partido['estado'] === 'WO'): ?>
                        <span class="badge badge-warning text-xs px-2 py-1 font-weight-bold"><?php echo e($partido['estado']); ?></span>
                    <?php else: ?>
                        <span class="badge badge-light border text-xs px-2 py-1 font-weight-bold text-secondary">PROGRAMADO</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Cuerpo: Equipos y Marcador (Sin escudos ni iconos) -->
            <div class="p-4 p-md-5">
                <div class="row align-items-center text-center">
                    <!-- Equipo Local -->
                    <div class="col-12 col-md-5 mb-3 mb-md-0">
                        <h2 class="font-weight-bold match-detail-team-title mb-1">
                            <?php echo e($partido['local_nombre']); ?>
                        </h2>
                        <span class="text-muted font-weight-bold d-block text-sm mb-1">
                            <?php echo !empty($partido['delegacion_local']) ? e($partido['delegacion_local']) : 'Delegación Local'; ?>
                        </span>
                    </div>

                    <!-- Marcador / VS Central -->
                    <div class="col-12 col-md-2 mb-3 mb-md-0">
                        <div class="match-score-box mb-2">
                            <?php if ($esRecreativa): ?>
                                <div class="font-weight-bold match-score-vs-num">
                                    VS
                                </div>
                                <small class="text-muted font-weight-bold d-block mt-1 text-xs"><?php echo $horaFormato; ?> hrs</small>
                            <?php elseif ($isFinalizado): ?>
                                <div class="font-weight-bold match-score-final-num">
                                    <?php echo (int)$partido['marcador_local']; ?> - <?php echo (int)$partido['marcador_visitante']; ?>
                                </div>
                                <small class="text-muted font-weight-bold d-block mt-1 text-xs">Marcador Final</small>
                            <?php elseif ($isLive): ?>
                                <div class="font-weight-bold match-score-live-num">
                                    <?php if ($partido['marcador_local'] !== null && $partido['marcador_visitante'] !== null): ?>
                                        <?php echo (int)$partido['marcador_local']; ?> - <?php echo (int)$partido['marcador_visitante']; ?>
                                    <?php else: ?>
                                        VS
                                    <?php endif; ?>
                                </div>
                                <small class="text-danger font-weight-bold d-block mt-1 text-xs">En juego</small>
                            <?php else: ?>
                                <div class="font-weight-bold match-score-vs-num">
                                    VS
                                </div>
                                <small class="text-muted font-weight-bold d-block mt-1 text-xs"><?php echo $horaFormato; ?> hrs</small>
                            <?php endif; ?>
                        </div>

                        <!-- Fecha y Hora del Encuentro -->
                        <div class="text-muted text-xs font-weight-bold">
                            <?php echo date('d/m/Y', strtotime($partido['fecha_partido'])); ?>
                        </div>
                    </div>

                    <!-- Equipo Visitante -->
                    <div class="col-12 col-md-5">
                        <h2 class="font-weight-bold match-detail-team-title mb-1">
                            <?php echo e($partido['visitante_nombre']); ?>
                        </h2>
                        <span class="text-muted font-weight-bold d-block text-sm mb-1">
                            <?php echo !empty($partido['delegacion_visitante']) ? e($partido['delegacion_visitante']) : 'Delegación Visitante'; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Detalle de Sets (solo si existen sets registrados, por ejemplo en Voleibol) -->
            <?php if (!empty($sets)): ?>
                <div class="col-lg-6 mb-4">
                    <div class="venue-card shadow-sm h-100">
                        <div class="card-header bg-light border-bottom py-3">
                            <h6 class="font-weight-bold mb-0 text-dark">
                                Puntuación por Sets
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-hover mb-0 text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Set</th>
                                        <th class="text-left"><?php echo e($partido['local_nombre']); ?></th>
                                        <th class="text-right"><?php echo e($partido['visitante_nombre']); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sets as $set): ?>
                                        <tr>
                                            <td class="font-weight-bold text-muted">Set <?php echo (int)$set['numero_set']; ?></td>
                                            <td class="text-left font-weight-bold text-dark"><?php echo (int)$set['puntos_local']; ?></td>
                                            <td class="text-right font-weight-bold text-dark"><?php echo (int)$set['puntos_visitante']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Información de la Cancha y Sede -->
            <div class="<?php echo !empty($sets) ? 'col-lg-6' : 'col-12'; ?> mb-4">
                <div class="venue-card shadow-sm h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="card-header bg-light border-bottom py-3">
                            <h6 class="font-weight-bold mb-0 text-dark">
                                Información de la Cancha y Sede
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <span class="text-xs font-weight-bold text-uppercase text-muted d-block">Sede Deportiva</span>
                                <h4 class="font-weight-bold text-dark mb-1">
                                    <?php echo !empty($partido['sede_nombre']) ? e($partido['sede_nombre']) : 'Sede por confirmar'; ?>
                                </h4>
                                <p class="text-muted text-sm mb-0">
                                    <?php echo !empty($partido['sede_direccion']) ? e($partido['sede_direccion']) : 'Ubicación oficial en Santa Cruz / Sedes COSMOL'; ?>
                                </p>
                            </div>

                            <?php if (!empty($partido['nombre_cancha'])): ?>
                                <div class="p-3 mb-3 rounded border d-flex justify-content-between align-items-center bg-light">
                                    <div>
                                        <span class="text-xs text-muted d-block">Campo de Juego</span>
                                        <span class="font-weight-bold text-dark"><?php echo e($partido['nombre_cancha']); ?></span>
                                    </div>
                                    <?php if (!empty($partido['tipo_superficie'])): ?>
                                        <span class="badge badge-secondary px-2 py-1 text-xs"><?php echo e($partido['tipo_superficie']); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($partido['sede_latitud']) && !empty($partido['sede_longitud'])): ?>
                        <div class="p-4 pt-0">
                            <button type="button" class="btn btn-gps-flat-lg btn-block" onclick="abrirRutaGps(<?php echo (float)$partido['sede_latitud']; ?>, <?php echo (float)$partido['sede_longitud']; ?>, '<?php echo addslashes($partido['sede_nombre']); ?>')">
                                Abrir Ruta GPS en Google Maps
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Botones de Acción Inferiores -->
        <div class="text-center mt-3">
            <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-outline-secondary btn-sm px-4 font-weight-bold mr-2 btn-action-rounded">
                Volver al Fixture
            </a>
            <a href="<?php echo BASE_URL; ?>/" class="btn btn-light border btn-sm px-4 font-weight-bold btn-action-rounded">
                Portada Principal
            </a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function abrirRutaGps(latDestino, lngDestino, nombreSede) {
            if (!latDestino || !lngDestino) {
                alert('No se encontraron coordenadas configuradas para la sede "' + nombreSede + '".');
                return;
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        var userLat = position.coords.latitude;
                        var userLng = position.coords.longitude;
                        var url = "https://www.google.com/maps/dir/?api=1&origin=" + userLat + "," + userLng + "&destination=" + latDestino + "," + lngDestino + "&travelmode=driving";
                        window.open(url, "_blank");
                    },
                    function(error) {
                        var fallbackUrl = "https://www.google.com/maps/search/?api=1&query=" + latDestino + "," + lngDestino;
                        window.open(fallbackUrl, "_blank");
                    },
                    { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
                );
            } else {
                var url = "https://www.google.com/maps/search/?api=1&query=" + latDestino + "," + lngDestino;
                window.open(url, "_blank");
            }
        }
    </script>
</body>
</html>
