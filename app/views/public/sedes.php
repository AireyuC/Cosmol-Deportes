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
    <style>
        :root { --cosmol-azul: #0066B2; --cosmol-fondo: #F8FAFC; }
        body { font-family: 'Source Sans Pro', sans-serif; background-color: var(--cosmol-fondo); }
        h1, h2, h3, h4, h5 { font-family: 'Outfit', sans-serif; }
        .navbar-public { background: #FFFFFF; border-bottom: 2px solid #E2E8F0; }
        .btn-gps { background: #10B981; color: #FFFFFF; font-weight: 700; border-radius: 50px; border: none; padding: 0.45rem 1.2rem; }
        .btn-gps:hover { background: #059669; color: #FFFFFF; }
    </style>
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
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
                        <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                            <h4 class="font-weight-bold text-dark mb-0"><?php echo e($s['nombre']); ?></h4>
                            <span class="badge badge-primary"><?php echo count(isset($s['canchas']) ? $s['canchas'] : []); ?> Canchas</span>
                        </div>
                        <div class="card-body p-4">
                            <p class="text-muted mb-2"><i class="fas fa-map-pin text-danger mr-2"></i> <?php echo e($s['direccion']); ?></p>
                            <p class="text-muted text-sm mb-3"><i class="fas fa-phone text-primary mr-2"></i> Contacto: <?php echo !empty($s['telefono']) ? e($s['telefono']) : '33445566'; ?></p>

                            <h6 class="font-weight-bold text-xs text-uppercase text-secondary mb-2">Campos de Juego Habilitados:</h6>
                            <ul class="list-unstyled mb-4">
                                <?php if (!empty($s['canchas'])): ?>
                                    <?php foreach ($s['canchas'] as $c): ?>
                                        <li class="py-1 text-sm border-bottom d-flex justify-content-between">
                                            <span><i class="fas fa-check text-success mr-2"></i> <?php echo e($c['nombre_cancha']); ?></span>
                                            <span class="badge badge-light text-muted"><?php echo e($c['tipo_superficie']); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="text-muted text-sm">Canchas oficiales del complejo.</li>
                                <?php endif; ?>
                            </ul>

                            <button type="button" class="btn btn-gps btn-block shadow-sm" onclick="abrirRutaGps(<?php echo $lat; ?>, <?php echo $lng; ?>, '<?php echo addslashes($s['nombre']); ?>')">
                                <i class="fas fa-location-arrow mr-2"></i> ¿Cómo llegar desde mi ubicación? (GPS)
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
