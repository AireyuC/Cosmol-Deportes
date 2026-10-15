<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($pageTitle) ? e($pageTitle) : 'Olimpiadas COSMOL 2026 | Portal Oficial'; ?></title>

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
<body>

    <!-- 1. BARRA DE NAVEGACIÓN SUPERIOR (PÚBLICA) -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-public sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>/">
                <img src="<?php echo BASE_URL; ?>/images/logoSinFondo.png" alt="COSMOL R.L.">
                <span>COSMOL <small class="text-secondary font-weight-normal text-xs d-block">Olimpiadas Deportivas</small></span>
            </a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navPublic" aria-controls="navPublic" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navPublic">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#disciplinas"><i class="fas fa-running mr-1"></i> Disciplinas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fixture"><i class="fas fa-calendar-alt mr-1"></i> Partidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#medallero"><i class="fas fa-medal mr-1"></i> Medallero</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sedes"><i class="fas fa-map-marked-alt mr-1"></i> Sedes y Rutas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#delegaciones"><i class="fas fa-flag mr-1"></i> Delegaciones</a>
                    </li>
                </ul>

                <!-- BOTÓN PRINCIPAL DE ACCESO / INICIAR SESIÓN ADMIN -->
                <div class="navbar-nav ml-auto align-items-center">
                    <?php if ($isAdminLogged): ?>
                        <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-primary btn-sm px-3 font-weight-bold mr-2" style="background-color: #0066B2;">
                            <i class="fas fa-tachometer-alt mr-1"></i> Panel Admin
                        </a>
                        <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-outline-danger btn-sm font-weight-bold">
                            <i class="fas fa-sign-out-alt"></i> Salir
                        </a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/login" class="btn btn-admin-login text-sm">
                            <i class="fas fa-lock mr-1"></i> Iniciar Sesión
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- ============================================================== -->
    <!-- 2. HERO BANNER PRINCIPAL                                       -->
    <!-- ============================================================== -->
    <header class="hero-banner">
        <div class="container text-center py-4">
            <span class="hero-badge">
                <i class="fas fa-trophy mr-1"></i>Evento Deportivo Institucional
            </span>
            <h1 class="hero-title mb-3">
                <?php echo e($olimpiada['nombre']); ?>
            </h1>
            <p class="lead mb-4 mx-auto" style="max-width: 780px; font-weight: 300; opacity: 0.95;">
                "<?php echo e($olimpiada['lema']); ?>"<br>
            </p>

            <div class="d-flex justify-content-center flex-wrap gap-2 mt-4">
                <a href="#disciplinas" class="btn btn-light btn-lg px-4 mr-2 mb-2 font-weight-bold text-primary shadow-sm">
                    Ver Disciplinas Deportivas
                </a>
                <a href="#sedes" class="btn btn-outline-light btn-lg px-4 mb-2 font-weight-bold">
                    <i class="fas fa-location-arrow mr-2"></i> ¿Cómo llegar a las Sedes?
                </a>
            </div>
        </div>
    </header>

    <!-- ============================================================== -->
    <!-- 3. BARRA DE ESTADÍSTICAS RÁPIDAS                              -->
    <!-- ============================================================== -->
    <section class="container">
        <div class="stats-bar">
            <div class="row align-items-center">
                <div class="col-6 col-md-3 stat-item py-2">
                    <div class="stat-number"><?php echo count($disciplinas); ?></div>
                    <div class="stat-label"><i class="fas fa-dumbbell mr-1"></i> Disciplinas</div>
                </div>
                <div class="col-6 col-md-3 stat-item py-2">
                    <div class="stat-number"><?php echo count($delegaciones); ?></div>
                    <div class="stat-label"><i class="fas fa-users mr-1"></i> Delegaciones</div>
                </div>
                <div class="col-6 col-md-3 stat-item py-2">
                    <div class="stat-number"><?php echo count($sedes); ?></div>
                    <div class="stat-label"><i class="fas fa-map-marker-alt mr-1"></i> Sedes Habilitadas</div>
                </div>
                <div class="col-6 col-md-3 stat-item py-2">
                    <div class="stat-number"><?php echo count($proximosPartidos) + count($ultimosResultados); ?></div>
                    <div class="stat-label"><i class="fas fa-calendar-check mr-1"></i> Encuentros Programados</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. SECCIÓN: DISCIPLINAS DEPORTIVAS                             -->
    <section id="disciplinas" class="container py-5 mt-4">
        <div class="text-center mb-4">
            <span class="badge badge-pill badge-primary px-3 py-1 font-weight-bold" style="background-color: var(--cosmol-azul);">
                COMPETENCIAS OFICIALES
            </span>
            <h2 class="font-weight-bold mt-2" style="color: var(--cosmol-azul-deep);">
                Disciplinas de las Olimpiadas
            </h2>
            <p class="text-muted mx-auto" style="max-width: 650px;">
                Seleccione cualquiera de los deportes o juegos de confraternización para consultar su escenario, ubicación GPS, tabla de posiciones y calendario de encuentros.
            </p>
        </div>

        <?php
            $totalDisciplinas = count($disciplinas);
            $countCancha = 0;
            $countSalon = 0;
            foreach ($disciplinas as $item) {
                $n = mb_strtolower(trim($item['nombre']), 'UTF-8');
                if (in_array($n, ['cacho', 'billar', 'karaoke', 'juegos de salón', 'juego de salon', 'ajedrez'])) {
                    $countSalon++;
                } else {
                    $countCancha++;
                }
            }
        ?>

        <!-- Filtros Interactivos de Disciplinas -->
        <div class="sport-filters-container">
            <div class="sport-filters" role="group" aria-label="Filtro de Disciplinas">
                <button type="button" class="sport-filter-btn active" data-filter="all">
                    Todas
                </button>
                <button type="button" class="sport-filter-btn" data-filter="cancha">
                    Deportes de Cancha
                </button>
                <button type="button" class="sport-filter-btn" data-filter="salon">
                    Juegos de Salón
                </button>
            </div>
        </div>

        <!-- Parrilla Dinámica de Disciplinas -->
        <div class="row" id="sportGrid">
            <?php if (empty($disciplinas)): ?>
                <div class="col-12 text-center py-5">
                    <div class="p-4 bg-white rounded shadow-sm border mx-auto" style="max-width: 500px;">
                        <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                        <h5 class="font-weight-bold text-dark">No hay disciplinas registradas aún</h5>
                        <p class="text-muted text-sm mb-0">El comité organizador publicará el catálogo oficial de competencias próximamente.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($disciplinas as $d): ?>
                    <?php
                        $nLower = mb_strtolower(trim($d['nombre']), 'UTF-8');
                        $isSalon = in_array($nLower, ['cacho', 'billar', 'karaoke', 'juegos de salón', 'juego de salon', 'ajedrez']);
                        $catSlug = $isSalon ? 'salon' : 'cancha';
                        $catLabel = $isSalon ? 'Juego de Salón' : 'Deporte de Cancha';
                        $catClass = $isSalon ? 'sport-category-salon' : 'sport-category-cancha';
                        $catIcon = $isSalon ? 'fa-dice' : 'fa-running';

                        // Mapeo dinámico y temático de icono y estilo
                        $iconClass = 'sport-icon-default';
                        $iconName = !empty($d['icono']) ? e($d['icono']) : 'trophy';

                        if (strpos($nLower, 'fútbol') !== false || strpos($nLower, 'futbol') !== false) {
                            $iconClass = 'sport-icon-futbol';
                            $iconName = 'futbol';
                        } elseif (strpos($nLower, 'futsal') !== false) {
                            $iconClass = 'sport-icon-futsal';
                            $iconName = 'running';
                        } elseif (strpos($nLower, 'básquet') !== false || strpos($nLower, 'basquet') !== false) {
                            $iconClass = 'sport-icon-basquetbol';
                            $iconName = 'basketball-ball';
                        } elseif (strpos($nLower, 'voley') !== false || strpos($nLower, 'volei') !== false) {
                            $iconClass = 'sport-icon-voleibol';
                            $iconName = 'volleyball-ball';
                        } elseif (strpos($nLower, 'cacho') !== false) {
                            $iconClass = 'sport-icon-cacho';
                            $iconName = 'dice';
                        } elseif (strpos($nLower, 'billar') !== false) {
                            $iconClass = 'sport-icon-billar';
                            $iconName = 'dot-circle';
                        } elseif (strpos($nLower, 'karaoke') !== false) {
                            $iconClass = 'sport-icon-karaoke';
                            $iconName = 'microphone-alt';
                        }
                    ?>
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4 sport-col" data-category="<?php echo $catSlug; ?>">
                        <div class="sport-card">
                            <div>
                                <div class="sport-icon-wrapper <?php echo $iconClass; ?>">
                                    <i class="fas fa-<?php echo $iconName; ?>"></i>
                                </div>

                                <h3 class="sport-name"><?php echo e($d['nombre']); ?></h3>
                                <p class="sport-desc">
                                    <?php echo !empty($d['descripcion']) ? e($d['descripcion']) : 'Competencia oficial de las Olimpiadas COSMOL 2026.'; ?>
                                </p>
                            </div>

                            <div>
                                <a href="<?php echo BASE_URL; ?>/disciplina/<?php echo (int)$d['id']; ?>" class="btn-sport-action shadow-sm">
                                    <span>Ver Sede y Fixture</span>
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- ============================================================== -->
    <!-- 5. SECCIÓN: PARTIDOS DEL DÍA Y FIXTURE                         -->
    <!-- ============================================================== -->
    <section id="fixture" class="py-5" style="background: #FFFFFF;">
        <div class="container">
            <!-- Encabezado de la sección -->
            <div class="row align-items-center mb-4">
                <div class="col-md-7">
                    <h2 class="font-weight-bold mt-2" style="color: var(--cosmol-azul-deep);">
                        Partidos de la Olimpiada
                    </h2>
                    <p class="text-muted mb-0">
                        Consulte los enfrentamientos de hoy en tiempo real, revise los resultados de ayer y la programación de mañana.
                    </p>
                </div>
                <div class="col-md-5 text-md-right mt-3 mt-md-0">
                    <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-admin-login font-weight-bold rounded-pill px-4 shadow-sm" style="border-width: 2px;">
                        Ver Fixture Completo
                    </a>
                </div>
            </div>

            <!-- Botones de Filtrado por Fecha (Ayer, Hoy, Mañana, Todos) -->
            <div class="d-flex justify-content-center justify-content-md-start mb-4">
                <div class="match-day-filters">
                    <button type="button" class="match-day-btn" data-day-filter="ayer">
                        Ayer
                        <span class="match-day-count"><?php echo count($partidosAyer); ?></span>
                    </button>
                    <button type="button" class="match-day-btn active" data-day-filter="hoy">
                        Hoy
                        <span class="match-day-count"><?php echo count($partidosHoy); ?></span>
                    </button>
                    <button type="button" class="match-day-btn" data-day-filter="manana">
                        Mañana
                        <span class="match-day-count"><?php echo count($partidosManana); ?></span>
                    </button>
                    <button type="button" class="match-day-btn" data-day-filter="todos">
                        <i class="fas fa-list mr-1"></i> Todos
                        <span class="match-day-count"><?php echo count($todosPartidos); ?></span>
                    </button>
                </div>
            </div>

            <!-- Listado Dinámico de Partidos -->
            <div class="row" id="contenedor-partidos">
                <?php if (!empty($todosPartidos)): ?>
                    <?php foreach ($todosPartidos as $p): 
                        $cardDay = 'otro';
                        if ($p['fecha_partido'] === $fechaHoy) {
                            $cardDay = 'hoy';
                        } elseif ($p['fecha_partido'] === $fechaAyer) {
                            $cardDay = 'ayer';
                        } elseif ($p['fecha_partido'] === $fechaManana) {
                            $cardDay = 'manana';
                        }
                        
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
                        <div class="col-lg-6 mb-4 match-card-item" data-match-day="<?php echo $cardDay; ?>" style="<?php echo ($cardDay !== 'hoy') ? 'display: none;' : ''; ?>">
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
                                                <small class="text-muted"><?php echo !empty($p['delegacion_local']) ? e($p['delegacion_local']) : 'Local'; ?></small>
                                            </div>
                                        </div>

                                        <!-- Marcador / VS Central -->
                                        <div class="col-2 text-center px-0">
                                            <?php if ($esRecreativa): ?>
                                                <!-- Disciplinas Recreativas (Billar / Cacho): Sin marcador de goles, solo hora programada -->
                                                <div class="match-vs-pill d-inline-block">VS</div>
                                                <small class="text-muted d-block text-center mt-1 text-xs">
                                                    <?php echo $horaFormato; ?>
                                                </small>
                                            <?php elseif ($isFinalizado): ?>
                                                <!-- Partido Finalizado con Planilla Verificada por Admin: Muestra marcador oficial -->
                                                <div class="match-score-pill d-inline-block">
                                                    <?php echo (int)$p['marcador_local']; ?> - <?php echo (int)$p['marcador_visitante']; ?>
                                                </div>
                                            <?php elseif ($isLive): ?>
                                                <!-- Partido En Juego: No hay goles automáticos, muestra inicio según fixture -->
                                                <div class="match-vs-pill d-inline-block">VS</div>
                                            <?php else: ?>
                                                <!-- Partido Programado -->
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
                                                <small class="text-muted"><?php echo !empty($p['delegacion_visitante']) ? e($p['delegacion_visitante']) : 'Visitante'; ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pie de Tarjeta con Sede, Cancha y Botón GPS -->
                                <div class="match-box-footer">
                                    <div class="text-truncate mr-2">
                                        <span class="font-weight-bold text-dark"><?php echo !empty($p['sede_nombre']) ? e($p['sede_nombre']) : 'Sede por confirmar'; ?></span>
                                    </div>
                                    <?php if (!empty($p['sede_latitud']) && !empty($p['sede_longitud'])): ?>
                                        <button type="button" class="btn btn-sm btn-link p-0 text-primary font-weight-bold" onclick="abrirRutaGps(<?php echo (float)$p['sede_latitud']; ?>, <?php echo (float)$p['sede_longitud']; ?>, '<?php echo addslashes($p['sede_nombre']); ?>')">
                                            <i class="fas fa-location-arrow mr-1"></i> Ruta GPS
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Estados Vacíos Según Filtro -->
                <div id="empty-hoy" class="col-12 text-center py-5 match-empty-state" style="<?php echo empty($partidosHoy) ? '' : 'display: none;'; ?>">
                    <div class="p-4 bg-light rounded-lg border d-inline-block" style="max-width: 500px;">
                        <i class="far fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="font-weight-bold text-dark">No hay partidos programados para hoy</h5>
                        <p class="text-muted mb-3 text-sm">
                            No se registran encuentros a disputarse en la fecha de hoy (<?php echo date('d/m/Y', strtotime($fechaHoy)); ?>). Puede consultar los resultados de ayer o la jornada de mañana con los botones superiores.
                        </p>
                        <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-sm btn-primary rounded-pill font-weight-bold px-3">
                            <i class="fas fa-calendar-alt mr-1"></i> Ver Fixture Completo
                        </a>
                    </div>
                </div>

                <div id="empty-ayer" class="col-12 text-center py-5 match-empty-state" style="<?php echo empty($partidosAyer) ? '' : 'display: none;'; ?>">
                    <div class="p-4 bg-light rounded-lg border d-inline-block" style="max-width: 500px;">
                        <i class="far fa-calendar-check fa-3x text-muted mb-3"></i>
                        <h5 class="font-weight-bold text-dark">No se disputaron partidos ayer</h5>
                        <p class="text-muted mb-0 text-sm">
                            No se registraron encuentros oficiales en la fecha de ayer (<?php echo date('d/m/Y', strtotime($fechaAyer)); ?>).
                        </p>
                    </div>
                </div>

                <div id="empty-manana" class="col-12 text-center py-5 match-empty-state" style="<?php echo empty($partidosManana) ? '' : 'display: none;'; ?>">
                    <div class="p-4 bg-light rounded-lg border d-inline-block" style="max-width: 500px;">
                        <i class="far fa-calendar-plus fa-3x text-muted mb-3"></i>
                        <h5 class="font-weight-bold text-dark">No hay partidos programados para mañana</h5>
                        <p class="text-muted mb-3 text-sm">
                            Aún no se han publicado encuentros para la fecha de mañana (<?php echo date('d/m/Y', strtotime($fechaManana)); ?>).
                        </p>
                        <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-sm btn-primary rounded-pill font-weight-bold px-3">
                            <i class="fas fa-calendar-alt mr-1"></i> Ver Fixture Completo
                        </a>
                    </div>
                </div>

                <div id="empty-todos" class="col-12 text-center py-5 match-empty-state" style="<?php echo empty($todosPartidos) ? '' : 'display: none;'; ?>">
                    <div class="p-4 bg-light rounded-lg border d-inline-block" style="max-width: 500px;">
                        <i class="far fa-calendar fa-3x text-muted mb-3"></i>
                        <h5 class="font-weight-bold text-dark">No hay partidos registrados</h5>
                        <p class="text-muted mb-0 text-sm">
                            Por el momento no se han registrado partidos en la olimpiada.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================== -->
    <!-- 6. SECCIÓN: MEDALLERO OLÍMPICO GENERAL                          -->
    <!-- ============================================================== -->
    <section id="medallero" class="py-5" style="background: #F1F5F9;">
        <div class="container">
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <h2 class="font-weight-bold mt-2" style="color: var(--cosmol-azul-deep);">
                        Medallero Olímpico Oficial
                    </h2>
                    <p class="text-muted mb-0">
                        Tabla acumulada de medallas de oro, plata y bronce por delegación.
                    </p>
                </div>
                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                    <a href="<?php echo BASE_URL; ?>/portal/medallero" class="btn btn-primary font-weight-bold rounded-pill" style="background-color: #0066B2;">
                        <i class="fas fa-list-ol mr-1"></i> Ver Podios por Torneo
                    </a>
                </div>
            </div>

            <div class="table-responsive table-medallero">
                <table class="table table-hover mb-0 text-center">
                    <thead>
                        <tr>
                            <th style="width: 70px;">Pos</th>
                            <th class="text-left">Delegación / Departamento</th>
                            <th style="width: 110px;">Oro</th>
                            <th style="width: 110px;">Plata</th>
                            <th style="width: 110px;">Bronce</th>
                            <th style="width: 110px;">Total</th>
                            <th style="width: 110px;">Pts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($medallero)): ?>
                            <tr>
                                <td colspan="7" class="py-4 text-muted">
                                    <i class="fas fa-info-circle mr-1"></i> El campeonato está iniciando. Las medallas se publicarán en cuanto concluyan las finales de cada disciplina.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $pos = 1; foreach ($medallero as $m): ?>
                                <tr>
                                    <td class="font-weight-bold text-secondary">
                                        <?php if ($pos === 1): ?>
                                            <span class="badge badge-warning text-dark font-weight-bold px-2 py-1"><i class="fas fa-crown"></i> 1</span>
                                        <?php else: ?>
                                            #<?php echo $pos; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-left font-weight-bold">
                                        <span class="d-inline-block rounded-circle mr-2" style="width: 12px; height: 12px; background-color: <?php echo !empty($m['color_representativo']) ? e($m['color_representativo']) : '#007bff'; ?>;"></span>
                                        <?php echo e($m['delegacion_nombre']); ?>
                                    </td>
                                    <td><span class="badge-oro"><?php echo (int)$m['oro']; ?></span></td>
                                    <td><span class="badge-plata"><?php echo (int)$m['plata']; ?></span></td>
                                    <td><span class="badge-bronce"><?php echo (int)$m['bronce']; ?></span></td>
                                    <td class="font-weight-bold text-dark"><?php echo (int)$m['total_medallas']; ?></td>
                                    <td class="font-weight-bold text-primary"><?php echo (int)($m['puntos_olimpicos'] ?? $m['puntos_totales'] ?? 0); ?> pts</td>
                                </tr>
                            <?php $pos++; endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ============================================================== -->
    <!-- 6. SECCIÓN: SEDES DEPORTIVAS Y "CÓMO LLEGAR"                   -->
    <!-- ============================================================== -->
    <section id="sedes" class="container py-5">
        <div class="text-center mb-5">
            <span class="badge badge-pill badge-success px-3 py-1 font-weight-bold">
                <i class="fas fa-map-marker-alt mr-1"></i> GEOLOCALIZACIÓN
            </span>
            <h2 class="font-weight-bold mt-2" style="color: var(--cosmol-azul-deep);">
                Sedes Deportivas y Cómo Llegar (GPS)
            </h2>
            <p class="text-muted mx-auto" style="max-width: 650px;">
                Para que deportistas y visitantes de otros departamentos puedan ubicarse fácilmente, presione el botón <strong>"Cómo llegar"</strong> para abrir la ruta en tiempo real desde su teléfono móvil o computadora.
            </p>
        </div>

        <div class="row">
            <?php foreach ($sedes as $s): ?>
                <div class="col-md-6 mb-4">
                    <div class="venue-card h-100">
                        <div class="venue-header d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="font-weight-bold text-dark mb-1"><?php echo e($s['nombre']); ?></h4>
                                <span class="text-muted text-sm"><i class="fas fa-map-pin text-danger mr-1"></i> <?php echo e($s['direccion']); ?></span>
                            </div>
                            <span class="badge badge-primary px-2 py-1"><?php echo count(isset($s['canchas']) ? $s['canchas'] : []); ?> Canchas</span>
                        </div>
                        <div class="p-3">
                            <p class="text-sm text-secondary mb-3">
                                <strong>Teléfono de contacto:</strong> <?php echo !empty($s['telefono']) ? e($s['telefono']) : '33445566'; ?>
                            </p>

                            <!-- Canchas en esta sede -->
                            <h6 class="font-weight-bold text-xs text-uppercase text-muted mb-2">Campos de Juego:</h6>
                            <ul class="list-unstyled mb-4">
                                <?php if (!empty($s['canchas'])): ?>
                                    <?php foreach ($s['canchas'] as $c): ?>
                                        <li class="py-1 text-sm border-bottom d-flex justify-content-between">
                                            <span><i class="fas fa-check text-success mr-2"></i> <?php echo e($c['nombre_cancha']); ?></span>
                                            <span class="badge badge-light text-muted"><?php echo e($c['tipo_superficie']); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="text-muted text-sm">Canchas del recinto deportivo oficial.</li>
                                <?php endif; ?>
                            </ul>

                            <!-- Botón GPS Cómo Llegar -->
                            <?php 
                                $lat = !empty($s['latitud']) ? (float)$s['latitud'] : -17.7833;
                                $lng = !empty($s['longitud']) ? (float)$s['longitud'] : -63.1821;
                                $nombreSedeEsc = urlencode($s['nombre']);
                            ?>
                            <button type="button" class="btn btn-gps btn-block shadow-sm" onclick="abrirRutaGps(<?php echo $lat; ?>, <?php echo $lng; ?>, '<?php echo addslashes($s['nombre']); ?>')">
                                <i class="fas fa-location-arrow mr-2"></i> ¿Cómo llegar desde mi ubicación? (GPS)
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>



    <!-- ============================================================== -->
    <!-- 8. SECCIÓN: DELEGACIONES PARTICIPANTES                         -->
    <!-- ============================================================== -->
    <section id="delegaciones" class="container py-5">
        <div class="text-center mb-5">
            <span class="badge badge-pill badge-primary px-3 py-1 font-weight-bold" style="background-color: var(--cosmol-azul);">
                HERMANDAD DEPORTIVA
            </span>
            <h2 class="font-weight-bold mt-2" style="color: var(--cosmol-azul-deep);">
                Delegaciones de Distintos Departamentos
            </h2>
            <p class="text-muted mx-auto" style="max-width: 650px;">
                La Cooperativa COSMOL da una cordial bienvenida a todas las delegaciones participantes que forman parte de esta fiesta de integración y deporte.
            </p>
        </div>

        <div class="row justify-content-center">
            <?php foreach ($delegaciones as $del): ?>
                <div class="col-6 col-sm-4 col-md-3 mb-4 text-center">
                    <div class="p-3 bg-white rounded shadow-sm border h-100 d-flex flex-column justify-content-center align-items-center" style="border-radius: 14px !important;">
                        <div class="rounded-circle mb-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 55px; height: 55px; background-color: <?php echo !empty($del['color_representativo']) ? e($del['color_representativo']) : '#007bff'; ?>; color: #FFFFFF; font-size: 1.4rem;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h6 class="font-weight-bold text-dark mb-1"><?php echo e($del['nombre']); ?></h6>
                        <small class="text-muted"><?php echo !empty($del['responsable']) ? e($del['responsable']) : 'Delegación Oficial'; ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ============================================================== -->
    <!-- 9. FOOTER INFORMATIVO PÚBLICO                                  -->
    <!-- ============================================================== -->
    <footer class="footer-public">
        <div class="container">
            <div class="row">
                <div class="col-md-5 mb-4 mb-md-0">
                    <h5 class="text-white font-weight-bold mb-3">COSMOL R.L. - DEPORTES</h5>
                    <p class="text-sm pr-md-4">
                        Plataforma oficial de seguimiento y cronograma para las Olimpiadas Deportivas de la Cooperativa COSMOL. Diseñada para deportistas, delegaciones y público en general.
                    </p>
                    <p class="text-xs text-muted mb-0">
                        &copy; <?php echo date('Y'); ?> COSMOL R.L. Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h6 class="text-white font-weight-bold mb-3">Secciones del Portal</h6>
                    <ul class="list-unstyled text-sm">
                        <li class="mb-2"><a href="#disciplinas"><i class="fas fa-chevron-right text-xs mr-2"></i> Disciplinas Deportivas</a></li>
                        <li class="mb-2"><a href="#fixture"><i class="fas fa-chevron-right text-xs mr-2"></i> Partidos y Fixture</a></li>
                        <li class="mb-2"><a href="#medallero"><i class="fas fa-chevron-right text-xs mr-2"></i> Medallero Olímpico</a></li>
                        <li class="mb-2"><a href="#sedes"><i class="fas fa-chevron-right text-xs mr-2"></i> Sedes y Rutas GPS</a></li>
                        <li class="mb-2"><a href="#delegaciones"><i class="fas fa-chevron-right text-xs mr-2"></i> Delegaciones</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="text-white font-weight-bold mb-3">Comité Organizador</h6>
                    <p class="text-sm">
                        Si es miembro de la directiva o mesa técnica arbitral, puede acceder al sistema administrativo:
                    </p>
                    <a href="<?php echo BASE_URL; ?>/login" class="btn btn-outline-light btn-sm font-weight-bold px-3 py-2 rounded-pill">
                        <i class="fas fa-lock mr-1"></i> Acceso Administrativo (Login)
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 4 Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de Navegación GPS ("Cómo llegar") -->
    <script>
        function abrirRutaGps(latDestino, lngDestino, nombreSede) {
            if (!latDestino || !lngDestino) {
                alert('No se encontraron coordenadas configuradas para la sede "' + nombreSede + '".');
                return;
            }

            // Verificar si el navegador soporta geolocalización
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        var userLat = position.coords.latitude;
                        var userLng = position.coords.longitude;
                        // Abrir Google Maps con la ruta desde la ubicación actual del usuario hacia la sede
                        var url = "https://www.google.com/maps/dir/?api=1&origin=" + userLat + "," + userLng + "&destination=" + latDestino + "," + lngDestino + "&travelmode=driving";
                        window.open(url, "_blank");
                    },
                    function(error) {
                        // Si el usuario deniega o falla la geolocalización, abrir directamente las coordenadas de destino
                        var fallbackUrl = "https://www.google.com/maps/search/?api=1&query=" + latDestino + "," + lngDestino;
                        window.open(fallbackUrl, "_blank");
                    },
                    { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
                );
            } else {
                // Fallback sin geolocalización en navegador
                var url = "https://www.google.com/maps/search/?api=1&query=" + latDestino + "," + lngDestino;
                window.open(url, "_blank");
            }
        }

        // Filtrado interactivo de disciplinas deportivas y partidos por fecha
        $(document).ready(function() {
            // Filtrado de disciplinas
            $('.sport-filter-btn').on('click', function() {
                var filter = $(this).data('filter');
                $('.sport-filter-btn').removeClass('active');
                $(this).addClass('active');

                if (filter === 'all') {
                    $('.sport-col').fadeIn(200);
                } else {
                    $('.sport-col').each(function() {
                        if ($(this).data('category') === filter) {
                            $(this).fadeIn(200);
                        } else {
                            $(this).fadeOut(150);
                        }
                    });
                }
            });

            // Filtrado de partidos por fecha (Ayer, Hoy, Mañana, Todos)
            $('.match-day-btn').on('click', function() {
                var day = $(this).data('day-filter');
                $('.match-day-btn').removeClass('active');
                $(this).addClass('active');

                $('.match-empty-state').hide();

                if (day === 'todos') {
                    var total = $('.match-card-item').length;
                    if (total > 0) {
                        $('.match-card-item').fadeIn(200);
                    } else {
                        $('#empty-todos').fadeIn(200);
                    }
                } else {
                    var count = 0;
                    $('.match-card-item').each(function() {
                        if ($(this).data('match-day') === day) {
                            $(this).fadeIn(200);
                            count++;
                        } else {
                            $(this).hide();
                        }
                    });

                    if (count === 0) {
                        $('#empty-' + day).fadeIn(200);
                    }
                }
            });
        });
    </script>
</body>
</html>
