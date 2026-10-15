<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($pageTitle) ? e($pageTitle) : 'Disciplina Deportiva | COSMOL'; ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/logoSinFondo.png">

    <style>
        :root {
            --cosmol-azul: #0066B2;
            --cosmol-azul-dark: #004D87;
            --cosmol-celeste: #00A3E0;
            --cosmol-fondo: #F8FAFC;
        }
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: var(--cosmol-fondo);
            color: #1E293B;
        }
        h1, h2, h3, h4, h5 {
            font-family: 'Outfit', sans-serif;
        }
        .navbar-public {
            background: #FFFFFF;
            border-bottom: 2px solid #E2E8F0;
            box-shadow: 0 4px 15px rgba(0, 102, 178, 0.05);
        }
        .header-sport {
            background: linear-gradient(135deg, #002D52 0%, #004D87 60%, #0066B2 100%);
            color: #FFFFFF;
            padding: 3.5rem 0 2.5rem 0;
        }
        .sport-badge-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #FFFFFF;
            margin-bottom: 1rem;
        }
        .card-custom {
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            background: #FFFFFF;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .card-header-custom {
            background: #F8FAFC;
            border-bottom: 1px solid #E2E8F0;
            padding: 1.1rem 1.4rem;
            font-weight: 700;
        }
        .btn-gps {
            background: #10B981;
            color: #FFFFFF;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            padding: 0.35rem 0.9rem;
            font-size: 0.85rem;
        }
        .btn-gps:hover {
            background: #059669;
            color: #FFFFFF;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-public sticky-top">
        <div class="container">
            <a class="navbar-brand font-weight-bold text-primary" href="<?php echo BASE_URL; ?>/">
                <img src="<?php echo BASE_URL; ?>/images/logoSinFondo.png" height="38" class="mr-2" alt="COSMOL">
                COSMOL DEPORTES
            </a>
            <div class="ml-auto d-flex align-items-center">
                <a href="<?php echo BASE_URL; ?>/" class="btn btn-outline-secondary btn-sm mr-2 font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Volver a Portada
                </a>
                <?php if ($isAdminLogged): ?>
                    <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-primary btn-sm font-weight-bold" style="background-color: #0066B2;">
                        <i class="fas fa-tachometer-alt mr-1"></i> Admin
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/login" class="btn btn-outline-primary btn-sm font-weight-bold">
                        <i class="fas fa-lock mr-1"></i> Iniciar Sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Header de la Disciplina -->
    <header class="header-sport text-center">
        <div class="container">
            <div class="sport-badge-icon">
                <i class="fas fa-<?php echo !empty($disciplina['icono']) ? e($disciplina['icono']) : 'trophy'; ?>"></i>
            </div>
            <h1 class="font-weight-bold text-white mb-2"><?php echo e($disciplina['nombre']); ?></h1>
            <p class="lead mb-3" style="max-width: 680px; margin: 0 auto; opacity: 0.9; font-size: 1.1rem;">
                <?php echo !empty($disciplina['descripcion']) ? e($disciplina['descripcion']) : 'Competencia oficial de las Olimpiadas COSMOL 2026.'; ?>
            </p>
            <div>
                <span class="badge badge-light px-3 py-1 font-weight-bold mr-2">
                    <i class="fas fa-layer-group text-primary mr-1"></i> <?php echo count($datosTorneos); ?> Torneo(s) en disputa
                </span>
                <span class="badge badge-success px-3 py-1 font-weight-bold">
                    <i class="fas fa-check-circle mr-1"></i> Estado: <?php echo e($disciplina['estado']); ?>
                </span>
            </div>
        </div>
    </header>

    <div class="container py-5">
        
        <!-- 1. Sedes y Canchas donde se disputa esta disciplina -->
        <div class="card card-custom mb-5">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <span><i class="fas fa-map-marked-alt text-primary mr-2"></i> Sedes y Canchas donde se disputa <?php echo e($disciplina['nombre']); ?></span>
                <span class="badge badge-info"><?php echo count($sedes); ?> Sede(s)</span>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <?php if (empty($sedes)): ?>
                        <div class="col-12 text-muted">No hay sedes asignadas todavía para esta disciplina.</div>
                    <?php else: ?>
                        <?php foreach ($sedes as $s): ?>
                            <?php 
                                $lat = !empty($s['latitud']) ? (float)$s['latitud'] : -17.7833;
                                $lng = !empty($s['longitud']) ? (float)$s['longitud'] : -63.1821;
                            ?>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="font-weight-bold text-dark mb-1"><?php echo e($s['nombre']); ?></h5>
                                        <small class="text-muted d-block"><i class="fas fa-map-pin text-danger mr-1"></i> <?php echo e($s['direccion']); ?></small>
                                    </div>
                                    <button class="btn btn-gps ml-2" onclick="abrirRutaGps(<?php echo $lat; ?>, <?php echo $lng; ?>, '<?php echo addslashes($s['nombre']); ?>')">
                                        <i class="fas fa-location-arrow mr-1"></i> Cómo llegar
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 2. Detalle por Torneo (Ramas) de esta disciplina -->
        <?php if (empty($datosTorneos)): ?>
            <div class="alert alert-info shadow-sm p-4 text-center rounded">
                <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                <h5 class="font-weight-bold">Aún no se han programado torneos para <?php echo e($disciplina['nombre']); ?></h5>
                <p class="mb-0 text-sm">El comité organizador publicará el fixture y las delegaciones inscritas próximamente.</p>
            </div>
        <?php else: ?>
            <?php foreach ($datosTorneos as $item): ?>
                <?php 
                    $torneo = $item['torneo'];
                    $posiciones = $item['posiciones'];
                    $partidos = $item['partidos'];
                ?>
                <div class="mb-5">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h3 class="font-weight-bold text-dark mb-0">
                            <span class="badge badge-primary mr-2" style="background-color: #0066B2;"><?php echo e($torneo['rama']); ?></span>
                            <?php echo e($torneo['nombre']); ?>
                        </h3>
                        <span class="badge badge-light border text-muted px-2 py-1">Modalidad: <?php echo e(str_replace('_', ' ', $torneo['tipo_torneo'])); ?></span>
                    </div>

                    <div class="row">
                        <!-- Tabla de Posiciones -->
                        <div class="col-lg-7 mb-4">
                            <div class="card card-custom h-100">
                                <div class="card-header-custom d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-table text-success mr-2"></i> Tabla de Posiciones</span>
                                    <span class="badge badge-success"><?php echo count($posiciones); ?> Equipos</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0 text-center text-sm">
                                        <thead class="bg-light text-secondary">
                                            <tr>
                                                <th style="width: 45px;">#</th>
                                                <th class="text-left">Equipo</th>
                                                <th style="width: 45px;">PJ</th>
                                                <th style="width: 45px;">PG</th>
                                                <th style="width: 45px;">PE</th>
                                                <th style="width: 45px;">PP</th>
                                                <th style="width: 45px;">DG</th>
                                                <th style="width: 55px;" class="font-weight-bold text-primary">PTS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($posiciones)): ?>
                                                <tr>
                                                    <td colspan="8" class="py-4 text-muted">No hay partidos finalizados con puntuación aún.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php $pIndex = 1; foreach ($posiciones as $pos): ?>
                                                    <tr>
                                                        <td class="font-weight-bold text-muted"><?php echo $pIndex++; ?></td>
                                                        <td class="text-left font-weight-bold">
                                                            <span class="d-inline-block rounded-circle mr-1" style="width: 10px; height: 10px; background-color: <?php echo !empty($pos['color_representativo']) ? e($pos['color_representativo']) : '#007bff'; ?>;"></span>
                                                            <?php echo e($pos['nombre_corto'] ? $pos['nombre_corto'] : $pos['nombre']); ?>
                                                        </td>
                                                        <td><?php echo (int)$pos['pj']; ?></td>
                                                        <td><?php echo (int)$pos['pg']; ?></td>
                                                        <td><?php echo (int)$pos['pe']; ?></td>
                                                        <td><?php echo (int)$pos['pp']; ?></td>
                                                        <td class="font-weight-bold text-secondary"><?php echo (int)$pos['dg']; ?></td>
                                                        <td class="font-weight-bold text-primary" style="font-size: 1rem;"><?php echo (int)$pos['pts']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Fixture y Partidos -->
                        <div class="col-lg-5 mb-4">
                            <div class="card card-custom h-100">
                                <div class="card-header-custom d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-calendar-alt text-info mr-2"></i> Encuentros y Marcadores</span>
                                    <span class="badge badge-info"><?php echo count($partidos); ?> Partidos</span>
                                </div>
                                <div class="card-body p-3" style="max-height: 480px; overflow-y: auto;">
                                    <?php if (empty($partidos)): ?>
                                        <p class="text-muted text-center py-4 mb-0">No hay partidos asignados para esta rama.</p>
                                    <?php else: ?>
                                        <?php foreach ($partidos as $partido): ?>
                                            <div class="p-3 mb-2 bg-light rounded border">
                                                <div class="d-flex justify-content-between text-xs text-muted mb-1">
                                                    <span><?php echo e($partido['fase']); ?> - <?php echo e($partido['jornada']); ?></span>
                                                    <span><i class="fas fa-calendar mr-1"></i> <?php echo e($partido['fecha_partido']); ?></span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center font-weight-bold my-2">
                                                    <span class="text-truncate" style="max-width: 40%;"><?php echo e($partido['local_nombre']); ?></span>
                                                    <?php if ($partido['estado'] === 'Finalizado'): ?>
                                                        <span class="badge badge-primary px-2 py-1 font-weight-bold" style="background-color: #0066B2;">
                                                            <?php echo (int)$partido['marcador_local']; ?> - <?php echo (int)$partido['marcador_visitante']; ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary px-2 py-1 text-xs">VS</span>
                                                    <?php endif; ?>
                                                    <span class="text-truncate text-right" style="max-width: 40%;"><?php echo e($partido['visitante_nombre']); ?></span>
                                                </div>
                                                <div class="d-flex justify-content-between text-xs text-muted mt-2 border-top pt-1">
                                                    <span><i class="fas fa-map-marker-alt text-danger mr-1"></i> <?php echo e($partido['sede_nombre']); ?></span>
                                                    <span class="badge badge-<?php echo ($partido['estado'] === 'Finalizado') ? 'success' : (($partido['estado'] === 'En_Juego') ? 'warning' : 'info'); ?>">
                                                        <?php echo e($partido['estado']); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <!-- Script de GPS -->
    <script>
        function abrirRutaGps(latDestino, lngDestino, nombreSede) {
            if (!latDestino || !lngDestino) {
                alert('No se encontraron coordenadas configuradas para la sede.');
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
