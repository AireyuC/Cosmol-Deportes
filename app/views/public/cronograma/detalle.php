<?php
/**
 * Vista Pública Oficial: Cronograma General de Actividades en Detalle
 * Portal Oficial de las Olimpiadas COSMOL 2026
 * Basado en Docs/cronograma.md
 * Diseño plano corporativo sin gradientes, alineado a cards oficiales.
 * Compatible con PHP 7.3
 */

if (!isset($dias)) {
    require_once APP_PATH . '/models/Cronograma.php';
    $dias = Cronograma::getDias();
}

if (!isset($sedes)) {
    require_once APP_PATH . '/models/Cronograma.php';
    $sedes = Cronograma::getSedes();
}

$filtroActivo = isset($diaFiltro) && !empty($diaFiltro) ? $diaFiltro : 'all';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($pageTitle) ? e($pageTitle) : 'Cronograma General de Actividades | COSMOL'; ?></title>
    
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap 4.6 (AdminLTE compatible) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/logoSinFondo.png">

    <!-- Estilos Oficiales del Portal Público -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/portal.css">
</head>
<body style="background-color: #F8FAFC;">

    <!-- Barra de Navegación -->
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
                <?php if (!empty($isAdminLogged)): ?>
                    <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-primary btn-sm font-weight-bold" style="background-color: var(--cosmol-azul);">
                        Panel Admin
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/login" class="btn btn-outline-primary btn-sm font-weight-bold">
                        Iniciar Sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Contenedor Principal -->
    <div class="container py-4">

        <!-- Encabezado de la Página -->
        <div class="text-center mb-4">
            <span class="badge badge-pill px-3 py-1 font-weight-bold text-uppercase" style="background-color: #EAF4FB; color: #0066B2; border: 1px solid #C9E2F5; font-size: 0.8rem;">
                Jornadas Deportivas ANESAPA - COSMOL
            </span>
            <h1 class="font-weight-bold text-dark mt-2 mb-1" style="font-size: 2.2rem; color: var(--cosmol-azul-deep) !important;">
                Cronograma General de Actividades
            </h1>
            <p class="text-muted text-sm mx-auto mb-0" style="max-width: 720px;">
                Distribución oficial de competencias deportivas, juegos de confraternización, capacitaciones y actos protocolares del Jueves 22 al Sábado 24.
            </p>
        </div>

        <!-- Filtros por Día y Pestañas -->
        <div class="text-center mb-4">
            <div class="cronograma-filter-tabs">
                <button type="button" class="cronograma-tab-btn <?php echo ($filtroActivo === 'all') ? 'active' : ''; ?>" data-target-tab="all">
                    Todos los Días (19)
                </button>
                <button type="button" class="cronograma-tab-btn <?php echo ($filtroActivo === 'jueves-22') ? 'active' : ''; ?>" data-target-tab="jueves-22">
                    Jueves 22 (6)
                </button>
                <button type="button" class="cronograma-tab-btn <?php echo ($filtroActivo === 'viernes-23') ? 'active' : ''; ?>" data-target-tab="viernes-23">
                    Viernes 23 (9)
                </button>
                <button type="button" class="cronograma-tab-btn <?php echo ($filtroActivo === 'sabado-24') ? 'active' : ''; ?>" data-target-tab="sabado-24">
                    Sábado 24 (4)
                </button>
                <button type="button" class="cronograma-tab-btn <?php echo ($filtroActivo === 'sedes') ? 'active' : ''; ?>" data-target-tab="sedes">
                    Sedes Oficiales (10)
                </button>
            </div>
        </div>

        <!-- Contenedor de Días -->
        <div id="cronograma-dias-container">
            <?php foreach ($dias as $dia): ?>
                <div class="cronograma-day-block mb-4" id="bloque-<?php echo e($dia['dia_id']); ?>" data-day="<?php echo e($dia['dia_id']); ?>">
                    <div class="cronograma-detail-card">
                        <!-- Cabecera del Día -->
                        <div class="cronograma-day-header">
                            <div>
                                <h3 class="cronograma-day-title">
                                    <?php echo e($dia['dia_nombre']); ?>
                                </h3>
                                <small class="text-muted d-block" style="font-size: 0.8rem;">
                                    <?php echo e($dia['subtitulo']); ?>
                                </small>
                            </div>
                            <span class="cronograma-day-badge">
                                <?php echo count($dia['actividades']); ?> Actividades Programadas
                            </span>
                        </div>

                        <!-- Tabla de Actividades del Día -->
                        <div class="table-responsive">
                            <table class="table cronograma-table">
                                <thead>
                                    <tr>
                                        <th style="width: 170px;">Horario</th>
                                        <th style="width: 250px;">Actividad</th>
                                        <th style="width: 140px;">Tipo</th>
                                        <th>Lugar / Escenario</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                        <tr>
                                            <td>
                                                <span class="cronograma-time">
                                                    <?php echo e($act['horario']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                                    <?php echo e($act['actividad']); ?>
                                                </div>
                                                <?php if (!empty($act['detalle'])): ?>
                                                    <small class="text-muted d-block" style="font-size: 0.8rem;">
                                                        <?php echo e($act['detalle']); ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="cronograma-badge-category <?php echo $catClass; ?>">
                                                    <?php echo e($act['categoria']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="cronograma-venue font-weight-normal">
                                                    <?php echo e($act['lugar']); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bloque de Sedes Oficiales -->
        <div id="bloque-sedes" class="mb-5" data-day="sedes">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: var(--cosmol-azul-deep); font-size: 1.4rem;">
                        Sedes y Recintos Oficiales
                    </h3>
                    <small class="text-muted">
                        Escenarios habilitados para las competencias deportivas y actividades del cronograma
                    </small>
                </div>
                <a href="<?php echo BASE_URL; ?>/#sedes" class="btn btn-sm btn-outline-secondary font-weight-bold">
                    Ver Mapa en Portada
                </a>
            </div>

            <div class="row">
                <?php foreach ($sedes as $s): ?>
                    <div class="col-lg-6 col-md-6 mb-3">
                        <div class="cronograma-venue-box d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                                        <?php echo e($s['nombre']); ?>
                                    </h5>
                                </div>
                                <div class="text-muted text-sm mb-2">
                                    <?php echo e($s['direccion']); ?>
                                </div>
                                <div class="p-2 rounded bg-light border text-sm mb-3">
                                    <strong class="text-dark d-block mb-1" style="font-size: 0.76rem; text-transform: uppercase;">
                                        Actividades asignadas:
                                    </strong>
                                    <span class="text-secondary" style="font-size: 0.83rem;">
                                        <?php echo e($s['actividades']); ?>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-gps btn-sm btn-block font-weight-bold" onclick="abrirRutaGps(<?php echo (float)$s['latitud']; ?>, <?php echo (float)$s['longitud']; ?>, '<?php echo addslashes($s['nombre']); ?>')">
                                    <i class="fas fa-location-arrow mr-1"></i> Cómo llegar (GPS)
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <!-- Footer Institucional -->
    <?php require __DIR__ . '/../layout/footer.php'; ?>

    <!-- Scripts e Interactividad -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función universal para abrir GPS sin dependencias complejas
        function abrirRutaGps(lat, lng, nombreSede) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        var miLat = position.coords.latitude;
                        var miLng = position.coords.longitude;
                        var url = 'https://www.google.com/maps/dir/?api=1&origin=' + miLat + ',' + miLng + '&destination=' + lat + ',' + lng + '&travelmode=driving';
                        window.open(url, '_blank');
                    },
                    function(error) {
                        var url = 'https://www.google.com/maps/search/?api=1&query=' + lat + ',' + lng;
                        window.open(url, '_blank');
                    },
                    { enableHighAccuracy: true, timeout: 6000 }
                );
            } else {
                var url = 'https://www.google.com/maps/search/?api=1&query=' + lat + ',' + lng;
                window.open(url, '_blank');
            }
        }

        // Control de Pestañas / Filtros de Días
        $(document).ready(function() {
            var urlParams = new URLSearchParams(window.location.search);
            var diaParam = urlParams.get('dia');

            function aplicarFiltro(target) {
                $('.cronograma-tab-btn').removeClass('active');
                $('.cronograma-tab-btn[data-target-tab="' + target + '"]').addClass('active');

                if (target === 'all') {
                    $('.cronograma-day-block').show();
                    $('#bloque-sedes').show();
                } else if (target === 'sedes') {
                    $('.cronograma-day-block').hide();
                    $('#bloque-sedes').show();
                } else {
                    $('.cronograma-day-block').hide();
                    $('#bloque-' + target).show();
                    $('#bloque-sedes').hide();
                }
            }

            $('.cronograma-tab-btn').on('click', function() {
                var target = $(this).data('target-tab');
                aplicarFiltro(target);
            });

            if (diaParam && (diaParam === 'jueves-22' || diaParam === 'viernes-23' || diaParam === 'sabado-24' || diaParam === 'sedes')) {
                aplicarFiltro(diaParam);
            }
        });
    </script>
</body>
</html>
