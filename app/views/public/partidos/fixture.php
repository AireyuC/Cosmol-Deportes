<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($pageTitle) ? e($pageTitle) : 'Fixture Deportivo | COSMOL'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/images/logoSinFondo.png">
    <!-- Estilos Oficiales del Portal Público -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/portal.css">
    <style>
        /* Estructura limpia y plana para las cards del fixture (Diseno plano) */
        .match-card-item {
            cursor: pointer;
        }
        .match-simple-card {
            background: #FFFFFF;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 110px;
        }
        .match-card-item:hover .match-simple-card {
            border-color: #0066B2;
            box-shadow: 0 4px 12px rgba(0, 102, 178, 0.1);
        }
        .match-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.4rem;
            padding-bottom: 0.35rem;
            border-bottom: 1px solid #F1F5F9;
        }
        .match-card-disc {
            font-weight: 700;
            font-size: 0.75rem;
            color: #0066B2;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 50%;
        }
        .match-card-time {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748B;
            white-space: nowrap;
        }
        .match-card-middle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.35rem 0;
        }
        .match-team {
            flex: 1;
            font-size: 0.92rem;
            font-weight: 700;
            color: #0F172A;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            min-width: 0;
        }
        .match-team-local {
            text-align: left;
        }
        .match-team-visit {
            text-align: right;
        }
        .match-score-center {
            flex-shrink: 0;
            padding: 0 0.5rem;
            text-align: center;
        }
        /* Marcador final más pequeño */
        .match-score-mini {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.15rem 0.45rem;
            border-radius: 4px;
            background: #F1F5F9;
            border: 1px solid #CBD5E1;
            color: #0F172A;
            display: inline-block;
            line-height: 1.2;
        }
        .match-vs-text {
            font-size: 0.75rem;
            font-weight: 600;
            color: #94A3B8;
            text-transform: lowercase;
        }
        .match-vs-live {
            font-size: 0.68rem;
            font-weight: 700;
            color: #DC2626;
            background: #FEE2E2;
            padding: 0.1rem 0.4rem;
            border-radius: 4px;
            display: inline-block;
            text-transform: uppercase;
        }
        .match-card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.4rem;
            padding-top: 0.35rem;
            border-top: 1px solid #F1F5F9;
        }
        .match-card-cancha {
            font-size: 0.75rem;
            color: #475569;
            font-weight: 500;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            flex: 1;
            min-width: 0;
            margin-right: 0.5rem;
        }
        .btn-gps-flat {
            font-size: 0.7rem;
            font-weight: 700;
            color: #047857;
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            border-radius: 4px;
            padding: 0.15rem 0.55rem;
            cursor: pointer;
            flex-shrink: 0;
            line-height: 1.2;
            transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }
        .btn-gps-flat:hover {
            background: #047857;
            border-color: #047857;
            color: #FFFFFF;
        }
        .discipline-header-pill {
            background: #0066B2;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 0.35rem 0.85rem;
            border-radius: 6px;
            display: inline-block;
        }
    </style>
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
                <a href="<?php echo BASE_URL; ?>/" class="btn btn-outline-secondary btn-sm mr-2 font-weight-bold">
                    Volver a Portada
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="text-center mb-4">
            <h1 class="font-weight-bold mt-2 text-dark" style="font-size: 2rem;">Fixture</h1>
        </div>

        <!-- Botones de Filtrado Rápido por Fecha (Ayer, Hoy, Mañana, Todos) -->
        <div class="d-flex justify-content-center mb-4">
            <div class="match-day-filters">
                <button type="button" class="match-day-btn" data-day-filter="ayer">
                    Ayer <span class="match-day-count"><?php echo isset($partidosAyer) ? count($partidosAyer) : 0; ?></span>
                </button>
                <button type="button" class="match-day-btn" data-day-filter="hoy">
                    Hoy <span class="match-day-count"><?php echo isset($partidosHoy) ? count($partidosHoy) : 0; ?></span>
                </button>
                <button type="button" class="match-day-btn" data-day-filter="manana">
                    Mañana <span class="match-day-count"><?php echo isset($partidosManana) ? count($partidosManana) : 0; ?></span>
                </button>
                <button type="button" class="match-day-btn active" data-day-filter="todos">
                    Todos <span class="match-day-count"><?php echo isset($partidos) ? count($partidos) : 0; ?></span>
                </button>
            </div>
        </div>

        <!-- Filtro Único por Disciplina -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-body p-3">
                <form action="<?php echo BASE_URL; ?>/portal/fixture" method="get" class="form-row align-items-center justify-content-between">
                    <div class="col-md-8 col-sm-12 mb-2 mb-md-0 d-flex align-items-center">
                        <label class="font-weight-bold text-dark text-sm mr-2 mb-0 d-none d-sm-inline" style="white-space: nowrap;">
                            Filtrar Disciplina:
                        </label>
                        <select name="disciplina_id" class="form-control" onchange="this.form.submit()" style="border-radius: 8px;">
                            <option value="">Todas las disciplinas</option>
                            <?php foreach ($disciplinas as $d): ?>
                                <option value="<?php echo (int)$d['id']; ?>" <?php echo ($disciplinaFiltro === (int)$d['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($d['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if ($disciplinaFiltro): ?>
                        <div class="col-md-4 col-sm-12 text-md-right text-left mt-2 mt-md-0">
                            <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-outline-secondary btn-sm rounded px-3">
                                Ver todas las disciplinas
                            </a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php
            // Agrupar partidos por disciplina conservando el orden de prioridad oficial
            $partidosPorDisciplina = [];
            foreach ($partidos as $p) {
                $discNombre = $p['disciplina_nombre'];
                $partidosPorDisciplina[$discNombre][] = $p;
            }
        ?>

        <!-- Listado de Partidos Ordenado por Disciplina -->
        <div id="contenedor-fixture">
            <?php if (empty($partidos)): ?>
                <div id="empty-todos" class="alert alert-light border text-center py-5 match-empty-state" style="border-radius: 12px;">
                    <h5 class="text-dark font-weight-bold mb-1">No se encontraron encuentros con los filtros seleccionados.</h5>
                    <p class="text-muted mb-0 text-sm">Pruebe seleccionando otra disciplina para consultar los encuentros.</p>
                </div>
            <?php else: ?>
                <?php foreach ($partidosPorDisciplina as $nombreDisciplina => $listaPartidos): ?>
                    <div class="discipline-section mb-4" data-discipline-name="<?php echo e($nombreDisciplina); ?>">
                        <!-- Título de la Disciplina -->
                        <div class="d-flex align-items-center mb-3 pt-2">
                            <div class="discipline-header-pill">
                                <?php echo e($nombreDisciplina); ?>
                            </div>
                            <div class="flex-grow-1 border-bottom ml-3" style="border-color: #E2E8F0 !important;"></div>
                        </div>

                        <!-- Tarjetas de Partidos en Cuadrícula Responsiva -->
                        <div class="row">
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
                                $fechaFormato = !empty($p['fecha_partido']) ? date('d/m/Y', strtotime($p['fecha_partido'])) : '--/--/----';
                                $canchaNombre = !empty($p['nombre_cancha']) ? $p['nombre_cancha'] : (!empty($p['sede_nombre']) ? $p['sede_nombre'] : 'Cancha por definir');
                            ?>
                                <div class="col-lg-6 col-12 mb-3 match-card-item" data-match-day="<?php echo $cardDay; ?>" data-match-date="<?php echo $fechaSolo; ?>" onclick="window.location.href='<?php echo BASE_URL; ?>/portal/partido/<?php echo (int)$p['id']; ?>'">
                                    <div class="match-simple-card">
                                        <!-- Fila Superior: Disciplina, Fecha y Hora -->
                                        <div class="match-card-top">
                                            <span class="match-card-disc">
                                                <?php echo e($p['disciplina_nombre']); ?>
                                            </span>
                                            <span class="match-card-time">
                                                <?php echo $fechaFormato; ?> &bull; <?php echo $horaFormato; ?>
                                            </span>
                                        </div>

                                        <!-- Fila Central: Equipos y Marcador Final Pequeño / VS -->
                                        <div class="match-card-middle">
                                            <div class="match-team match-team-local" title="<?php echo e($p['local_nombre']); ?>">
                                                <?php echo e($p['local_nombre']); ?>
                                            </div>

                                            <div class="match-score-center">
                                                <?php if ($isFinalizado && !$esRecreativa): ?>
                                                    <span class="match-score-mini">
                                                        <?php echo (int)$p['marcador_local']; ?> - <?php echo (int)$p['marcador_visitante']; ?>
                                                    </span>
                                                <?php elseif ($isLive): ?>
                                                    <span class="match-vs-live">En juego</span>
                                                <?php else: ?>
                                                    <span class="match-vs-text">vs</span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="match-team match-team-visit" title="<?php echo e($p['visitante_nombre']); ?>">
                                                <?php echo e($p['visitante_nombre']); ?>
                                            </div>
                                        </div>

                                        <!-- Fila Inferior: Nombre de Cancha y Botón GPS -->
                                        <div class="match-card-bottom">
                                            <div class="match-card-cancha" title="<?php echo e($canchaNombre); ?>">
                                                <?php echo e($canchaNombre); ?>
                                            </div>
                                            <div class="match-card-gps">
                                                <?php if (!empty($p['sede_latitud']) && !empty($p['sede_longitud'])): ?>
                                                    <button type="button" class="btn-gps-flat" onclick="event.stopPropagation(); abrirRutaGps(<?php echo (float)$p['sede_latitud']; ?>, <?php echo (float)$p['sede_longitud']; ?>, '<?php echo addslashes(!empty($p['sede_nombre']) ? $p['sede_nombre'] : $canchaNombre); ?>')">
                                                        Ubicacion
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Estados Vacíos Según Filtro de Fecha -->
            <div id="empty-ayer" class="alert alert-light border text-center py-5 match-empty-state" style="display: none; border-radius: 12px;">
                <h5 class="text-dark font-weight-bold mb-1">No se disputaron partidos ayer</h5>
                <p class="text-muted mb-0 text-sm">
                    No se registraron encuentros oficiales en la fecha de ayer (<?php echo date('d/m/Y', strtotime($fechaAyer)); ?>).
                </p>
            </div>

            <div id="empty-hoy" class="alert alert-light border text-center py-5 match-empty-state" style="display: none; border-radius: 12px;">
                <h5 class="text-dark font-weight-bold mb-1">No hay partidos programados para hoy</h5>
                <p class="text-muted mb-0 text-sm">
                    No se registran encuentros a disputarse en la fecha de hoy (<?php echo date('d/m/Y', strtotime($fechaHoy)); ?>).
                </p>
            </div>

            <div id="empty-manana" class="alert alert-light border text-center py-5 match-empty-state" style="display: none; border-radius: 12px;">
                <h5 class="text-dark font-weight-bold mb-1">No hay partidos programados para mañana</h5>
                <p class="text-muted mb-0 text-sm">
                    Aún no se han publicado encuentros para la fecha de mañana (<?php echo date('d/m/Y', strtotime($fechaManana)); ?>).
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap 4 Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de Navegación GPS e Interactividad de Filtros -->
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

        function aplicarFiltroDia(dia) {
            // 1. Actualizar estado visual de los botones
            $('.match-day-btn').removeClass('active');
            $('.match-day-btn[data-day-filter="' + dia + '"]').addClass('active');

            // 2. Ocultar todos los avisos de días vacíos
            $('.match-empty-state').hide();

            var totalVisibles = 0;

            // 3. Evaluar cada disciplina y sus respectivos partidos
            $('.discipline-section').each(function() {
                var $seccion = $(this);
                var countEnSeccion = 0;

                $seccion.find('.match-card-item').each(function() {
                    var $card = $(this);
                    var matchDay = $card.attr('data-match-day');

                    if (dia === 'todos' || matchDay === dia) {
                        $card.show();
                        countEnSeccion++;
                        totalVisibles++;
                    } else {
                        $card.hide();
                    }
                });

                // Si la disciplina tiene al menos un partido visible para el día, mostrar la sección; sino ocultarla
                if (countEnSeccion > 0) {
                    $seccion.show();
                } else {
                    $seccion.hide();
                }
            });

            // 4. Si ningún partido de ninguna disciplina coincide con el filtro, mostrar el aviso correspondiente
            if (totalVisibles === 0) {
                if (dia === 'todos') {
                    $('#empty-todos').show();
                } else {
                    $('#empty-' + dia).show();
                }
            }
        }

        $(document).ready(function() {
            // Evento click en botones de día (Ayer, Hoy, Mañana, Todos)
            $('.match-day-btn').on('click', function(e) {
                e.preventDefault();
                var day = $(this).attr('data-day-filter');
                aplicarFiltroDia(day);
            });

            // Si se pasa un filtro por URL (?dia=ayer, ?dia=hoy, ?dia=manana)
            var urlParams = new URLSearchParams(window.location.search);
            var diaParam = urlParams.get('dia');
            if (diaParam && ['ayer', 'hoy', 'manana', 'todos'].indexOf(diaParam) !== -1) {
                aplicarFiltroDia(diaParam);
            }
        });
    </script>
</body>
</html>
