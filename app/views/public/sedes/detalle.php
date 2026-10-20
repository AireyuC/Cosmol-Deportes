<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($pageTitle) ? e($pageTitle) : 'Sedes Deportivas | COSMOL'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/logoSinFondo.png">
    <!-- Estilos Oficiales del Portal Público -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/portal.css">
</head>
<body>
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
                        <i class="fas fa-tachometer-alt mr-1"></i> Panel Admin
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/login" class="btn btn-outline-primary btn-sm font-weight-bold">
                        <i class="fas fa-lock mr-1"></i> Iniciar Sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="badge badge-pill badge-success px-3 py-1 font-weight-bold">GUÍA DE SEDES</span>
            <h1 class="font-weight-bold mt-2" style="color: #002D52;">Sedes Deportivas y Cómo Llegar (GPS)</h1>
            <p class="text-muted mx-auto" style="max-width: 650px;">
                Ubicación exacta y rutas de navegación para delegaciones y público visitante de todos los departamentos.
            </p>
        </div>

        <div class="row">
            <?php foreach ($sedes as $s): ?>
                <?php 
                    $lat = !empty($s['latitud']) ? (float)$s['latitud'] : -17.7833;
                    $lng = !empty($s['longitud']) ? (float)$s['longitud'] : -63.1821;
                ?>
                <div class="col-md-6 mb-4">
                    <div class="venue-card h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="venue-header d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="font-weight-bold text-dark mb-1" style="font-size: 1.1rem;"><?php echo e($s['nombre']); ?></h5>
                                    <span class="text-muted text-sm d-block">
                                        <i class="fas fa-map-marker-alt text-danger mr-1"></i> <?php echo e($s['direccion']); ?>
                                    </span>
                                </div>
                                <span class="badge font-weight-bold px-2 py-1 ml-2 flex-shrink-0" style="background-color: #EBF4FA; color: #0066B2; border: 1px solid #C9E2F5; font-size: 0.78rem;">
                                    <?php echo count(isset($s['canchas']) ? $s['canchas'] : []); ?> Canchas
                                </span>
                            </div>

                            <div class="p-3">
                                <!-- Canchas en esta sede -->
                                <span class="font-weight-bold text-xs text-uppercase text-muted d-block mb-2">Campos de Juego:</span>
                                <div class="mb-3">
                                    <?php if (!empty($s['canchas'])): ?>
                                        <?php foreach ($s['canchas'] as $c): ?>
                                            <div class="py-1 text-sm border-bottom d-flex justify-content-between align-items-center">
                                                <span class="font-weight-bold text-dark">
                                                    <i class="fas fa-circle mr-1" style="font-size: 6px; color: #0066B2; vertical-align: middle;"></i>
                                                    <?php echo e($c['nombre_cancha']); ?>
                                                </span>
                                                <span class="badge" style="background-color: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; font-size: 0.72rem;">
                                                    <?php echo e($c['tipo_superficie']); ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-muted text-sm py-1">Canchas oficiales del complejo.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="px-3 pb-3">
                            <button type="button" class="btn btn-gps btn-block font-weight-bold" onclick="abrirRutaGps(<?php echo $lat; ?>, <?php echo $lng; ?>, '<?php echo addslashes($s['nombre']); ?>')">
                                <i class="fas fa-location-arrow mr-1"></i> Cómo llegar
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Sede Especial: Circuito Urb. Celina Muyurina (Media Maratón) -->
            <div class="col-md-6 mb-4">
                <div class="venue-card h-100 d-flex flex-column justify-content-between" style="border: 1.5px solid rgba(0, 102, 178, 0.35); box-shadow: 0 4px 15px rgba(0, 102, 178, 0.08);">
                    <div>
                        <div class="venue-header d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="font-weight-bold text-dark mb-1" style="font-size: 1.1rem;">
                                    Circuito Urb. Celina Muyurina
                                </h5>
                                <span class="text-muted text-sm d-block">
                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i> Muyurina, Montero (Santa Cruz)
                                </span>
                            </div>
                            <span class="badge font-weight-bold px-2 py-1 ml-2 flex-shrink-0" style="background-color: #EBF4FA; color: #0066B2; border: 1px solid #C9E2F5; font-size: 0.78rem;">
                                Circuito 5.0 km
                            </span>
                        </div>

                        <div class="p-3">
                            <span class="font-weight-bold text-xs text-uppercase text-muted d-block mb-2">Escenario de Carrera Pedestre:</span>
                            <div class="mb-3">
                                <div class="py-1 text-sm border-bottom d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold text-dark">
                                        <i class="fas fa-play text-success mr-1"></i> Largada (Punto 1 - Recta SE)
                                    </span>
                                    <span class="badge badge-light border">Partida</span>
                                </div>
                                <div class="py-1 text-sm border-bottom d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold text-dark">
                                        <i class="fas fa-flag-checkered text-danger mr-1"></i> Llegada (Punto 12 - Noreste)
                                    </span>
                                    <span class="badge badge-light border">Meta</span>
                                </div>
                                <div class="py-1 text-sm d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        <i class="fas fa-road text-primary mr-1"></i> Recorrido Asfalto/Tierra
                                    </span>
                                    <span class="badge badge-info">5.0 km Oficial</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-3 pb-3">
                        <div class="row no-gutters">
                            <div class="col-6 pr-1">
                                <a href="<?php echo BASE_URL; ?>/#maraton" class="btn btn-outline-primary btn-block font-weight-bold text-truncate" style="border-radius: 8px;">
                                    <i class="fas fa-map-marked-alt mr-1"></i> Ver Trazado
                                </a>
                            </div>
                            <div class="col-6 pl-1">
                                <button type="button" class="btn btn-gps btn-block font-weight-bold text-truncate" onclick="abrirRutaGps(-17.360332, -63.258289, 'Partida Maratón Olimpiada 33 - Urb. Celina')">
                                    <i class="fas fa-location-arrow mr-1"></i> GPS Partida
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function abrirRutaGps(latDestino, lngDestino, nombreSede) {
            if (!latDestino || !lngDestino) {
                alert('No se encontraron coordenadas configuradas.');
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
