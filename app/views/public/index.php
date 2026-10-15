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

    <style>
        :root {
            --cosmol-azul: #0066B2;
            --cosmol-azul-dark: #004D87;
            --cosmol-azul-deep: #002D52;
            --cosmol-celeste: #00A3E0;
            --cosmol-celeste-light: #EAF4FB;
            --cosmol-amarillo-oro: #F59E0B;
            --cosmol-plata: #94A3B8;
            --cosmol-bronce: #B45309;
            --cosmol-fondo: #F8FAFC;
            --cosmol-card-bg: #FFFFFF;
            --cosmol-texto: #1E293B;
            --cosmol-muted: #64748B;
        }

        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: var(--cosmol-fondo);
            color: var(--cosmol-texto);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, .brand-font {
            font-family: 'Outfit', sans-serif;
        }

        /* Navbar Público */
        .navbar-public {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #E2E8F0;
            box-shadow: 0 4px 20px rgba(0, 102, 178, 0.06);
            transition: all 0.3s ease;
        }

        .navbar-public .navbar-brand {
            font-weight: 800;
            color: var(--cosmol-azul);
            display: flex;
            align-items: center;
            font-size: 1.35rem;
        }

        .navbar-public .navbar-brand img {
            height: 42px;
            margin-right: 10px;
        }

        .navbar-public .nav-link {
            color: #334155;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.6rem 1rem !important;
            transition: color 0.2s ease;
        }

        .navbar-public .nav-link:hover {
            color: var(--cosmol-azul);
        }

        .btn-admin-login {
            background: var(--cosmol-azul);
            color: #FFFFFF !important;
            font-weight: 700;
            border-radius: 50px;
            padding: 0.5rem 1.4rem;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 102, 178, 0.25);
            transition: all 0.25s ease;
        }

        .btn-admin-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 102, 178, 0.35);
            color: #FFFFFF !important;
        }

        /* Hero Section */
        .hero-banner {
            background: var(--cosmol-azul);
            color: #FFFFFF;
            padding: 5rem 0 4rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-banner::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(0, 163, 224, 0.25) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-title {
            font-size: 2.8rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.5px;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(0, 163, 224, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #EAF4FB;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            margin-bottom: 1rem;
        }

        /* Barra de Estadísticas Rápidas */
        .stats-bar {
            background: #FFFFFF;
            border-radius: 16px;
            margin-top: -2.5rem;
            box-shadow: 0 10px 30px rgba(0, 102, 178, 0.08);
            border: 1px solid #E2E8F0;
            padding: 1.5rem 1rem;
            position: relative;
            z-index: 10;
        }

        .stat-item {
            text-align: center;
            border-right: 1px solid #E2E8F0;
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--cosmol-azul);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.82rem;
            text-transform: uppercase;
            color: var(--cosmol-muted);
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 0.3rem;
        }

        /* Tarjetas de Disciplinas */
        .sport-card {
            background: #FFFFFF;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            padding: 1.6rem 1.2rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .sport-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(0, 102, 178, 0.12);
            border-color: var(--cosmol-celeste);
        }

        .sport-icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: var(--cosmol-celeste-light);
            color: var(--cosmol-azul);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1.2rem auto;
            transition: all 0.3s ease;
        }

        .sport-card:hover .sport-icon-wrapper {
            background: var(--cosmol-azul);
            color: #FFFFFF;
            transform: scale(1.08);
        }

        .sport-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 0.5rem;
        }

        .sport-desc {
            font-size: 0.85rem;
            color: var(--cosmol-muted);
            margin-bottom: 1.2rem;
            min-height: 40px;
        }

        /* Sección Medallero */
        .table-medallero {
            background: #FFFFFF;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            border: 1px solid #E2E8F0;
        }

        .table-medallero thead {
            background: linear-gradient(135deg, #004D87 0%, #0066B2 100%);
            color: #FFFFFF;
        }

        .badge-oro {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: #FFFFFF;
            font-weight: 700;
            border-radius: 50px;
            padding: 0.3rem 0.7rem;
        }

        .badge-plata {
            background: linear-gradient(135deg, #94A3B8 0%, #64748B 100%);
            color: #FFFFFF;
            font-weight: 700;
            border-radius: 50px;
            padding: 0.3rem 0.7rem;
        }

        .badge-bronce {
            background: linear-gradient(135deg, #D97706 0%, #B45309 100%);
            color: #FFFFFF;
            font-weight: 700;
            border-radius: 50px;
            padding: 0.3rem 0.7rem;
        }

        /* Tarjetas de Sedes y GPS */
        .venue-card {
            background: #FFFFFF;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .venue-card:hover {
            box-shadow: 0 10px 25px rgba(0, 102, 178, 0.12);
            transform: translateY(-4px);
        }

        .venue-header {
            background: #F1F5F9;
            padding: 1.2rem;
            border-bottom: 1px solid #E2E8F0;
        }

        .btn-gps {
            background: #10B981;
            color: #FFFFFF;
            font-weight: 700;
            border-radius: 50px;
            padding: 0.45rem 1.2rem;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-gps:hover {
            background: #059669;
            color: #FFFFFF;
            transform: translateY(-2px);
        }

        /* Footer Público */
        .footer-public {
            background: #002D52;
            color: #CBD5E1;
            padding: 3.5rem 0 2rem 0;
            margin-top: 5rem;
            border-top: 4px solid var(--cosmol-celeste);
        }

        .footer-public a {
            color: #93C5FD;
            text-decoration: none;
        }

        .footer-public a:hover {
            color: #FFFFFF;
            text-decoration: underline;
        }

        /* Tarjeta de Partido */
        .match-badge {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.25rem 0.6rem;
            border-radius: 50px;
        }
    </style>
</head>
<body>

    <!-- ============================================================== -->
    <!-- 1. BARRA DE NAVEGACIÓN SUPERIOR (PÚBLICA)                       -->
    <!-- ============================================================== -->
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
                        <a class="nav-link" href="#medallero"><i class="fas fa-medal mr-1"></i> Medallero</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sedes"><i class="fas fa-map-marked-alt mr-1"></i> Sedes y Rutas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fixture"><i class="fas fa-calendar-alt mr-1"></i> Fixture</a>
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

    <!-- ============================================================== -->
    <!-- 4. SECCIÓN: DISCIPLINAS DEPORTIVAS                             -->
    <!-- ============================================================== -->
    <section id="disciplinas" class="container py-5 mt-4">
        <div class="text-center mb-5">
            <span class="badge badge-pill badge-primary px-3 py-1 font-weight-bold" style="background-color: var(--cosmol-azul);">
                COMPETENCIAS
            </span>
            <h2 class="font-weight-bold mt-2" style="color: var(--cosmol-azul-deep);">
                Disciplinas de las Olimpiadas
            </h2>
            <p class="text-muted mx-auto" style="max-width: 650px;">
                Seleccione cualquiera de los deportes para consultar su sede, mapa interactivo GPS ("Cómo llegar"), tabla de posiciones actualizada y fixture de encuentros.
            </p>
        </div>

        <div class="row">
            <?php foreach ($disciplinas as $d): ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                    <div class="sport-card">
                        <div>
                            <div class="sport-icon-wrapper">
                                <i class="fas fa-<?php echo !empty($d['icono']) ? e($d['icono']) : 'trophy'; ?>"></i>
                            </div>
                            <h3 class="sport-name"><?php echo e($d['nombre']); ?></h3>
                            <p class="sport-desc">
                                <?php echo !empty($d['descripcion']) ? e($d['descripcion']) : 'Competencia oficial de las Olimpiadas COSMOL 2026.'; ?>
                            </p>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3 px-2 py-1 bg-light rounded text-xs text-muted">
                                <span><i class="fas fa-layer-group text-primary mr-1"></i> <?php echo (int)$d['torneos_count']; ?> Torneos</span>
                                <span><i class="fas fa-check-circle text-success mr-1"></i> <?php echo e($d['estado']); ?></span>
                            </div>
                            <a href="<?php echo BASE_URL; ?>/disciplina/<?php echo (int)$d['id']; ?>" class="btn btn-outline-primary btn-block btn-sm font-weight-bold rounded-pill">
                                Ver Sede, Posiciones y Fixture <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ============================================================== -->
    <!-- 5. SECCIÓN: MEDALLERO OLÍMPICO GENERAL                          -->
    <!-- ============================================================== -->
    <section id="medallero" class="py-5" style="background: #F1F5F9;">
        <div class="container">
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <span class="badge badge-pill badge-warning px-3 py-1 font-weight-bold text-dark">
                        <i class="fas fa-medal mr-1"></i> CLASIFICACIÓN GENERAL
                    </span>
                    <h2 class="font-weight-bold mt-2" style="color: var(--cosmol-azul-deep);">
                        Medallero Olímpico Oficial
                    </h2>
                    <p class="text-muted mb-0">
                        Tabla acumulada de medallas de oro, plata y bronce por delegación departamental.
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
                            <th style="width: 110px;"><i class="fas fa-medal text-warning mr-1"></i> Oro</th>
                            <th style="width: 110px;"><i class="fas fa-medal text-secondary mr-1"></i> Plata</th>
                            <th style="width: 110px;"><i class="fas fa-medal mr-1" style="color: #CD7F32;"></i> Bronce</th>
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
                                    <td class="font-weight-bold text-primary"><?php echo (int)$m['puntos_totales']; ?> pts</td>
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
    <!-- 7. SECCIÓN: FIXTURE Y ENCUENTROS RECIENTES                      -->
    <!-- ============================================================== -->
    <section id="fixture" class="py-5" style="background: #FFFFFF;">
        <div class="container">
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <span class="badge badge-pill badge-info px-3 py-1 font-weight-bold">
                        <i class="fas fa-clock mr-1"></i> CRONOGRAMA
                    </span>
                    <h2 class="font-weight-bold mt-2" style="color: var(--cosmol-azul-deep);">
                        Próximos Partidos y Resultados
                    </h2>
                    <p class="text-muted mb-0">
                        Consulte los enfrentamientos programados y los marcadores de las distintas disciplinas.
                    </p>
                </div>
                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                    <a href="<?php echo BASE_URL; ?>/portal/fixture" class="btn btn-outline-primary font-weight-bold rounded-pill">
                        <i class="fas fa-calendar-alt mr-1"></i> Ver Fixture Completo
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Próximos Encuentros -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; background: #F8FAFC;">
                        <div class="card-header bg-white border-bottom font-weight-bold text-primary">
                            <i class="fas fa-calendar-plus mr-1"></i> Próximos Partidos a Disputarse
                        </div>
                        <div class="card-body p-3">
                            <?php if (empty($proximosPartidos)): ?>
                                <p class="text-muted text-center py-4 mb-0">No hay partidos pendientes programados por el momento.</p>
                            <?php else: ?>
                                <?php foreach ($proximosPartidos as $p): ?>
                                    <div class="p-3 mb-2 bg-white rounded shadow-sm border">
                                        <div class="d-flex justify-content-between text-xs text-muted mb-2">
                                            <span><strong class="text-dark"><?php echo e($p['disciplina_nombre']); ?></strong> - <?php echo e($p['torneo_nombre']); ?></span>
                                            <span><i class="fas fa-calendar mr-1"></i> <?php echo e($p['fecha_partido']); ?> <?php echo !empty($p['hora_partido']) ? substr($p['hora_partido'], 0, 5) : ''; ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center font-weight-bold text-sm">
                                            <span class="text-truncate" style="max-width: 42%;"><?php echo e($p['local_nombre']); ?></span>
                                            <span class="badge badge-light px-2 py-1 text-muted">VS</span>
                                            <span class="text-truncate text-right" style="max-width: 42%;"><?php echo e($p['visitante_nombre']); ?></span>
                                        </div>
                                        <div class="mt-2 text-xs text-muted d-flex justify-content-between">
                                            <span><i class="fas fa-map-marker-alt text-danger mr-1"></i> <?php echo e($p['sede_nombre']); ?> (<?php echo e($p['nombre_cancha']); ?>)</span>
                                            <a href="javascript:void(0)" onclick="abrirRutaGps(<?php echo (float)$p['sede_latitud']; ?>, <?php echo (float)$p['sede_longitud']; ?>, '<?php echo addslashes($p['sede_nombre']); ?>')" class="text-primary font-weight-bold">
                                                Ruta GPS <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Últimos Resultados -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; background: #F8FAFC;">
                        <div class="card-header bg-white border-bottom font-weight-bold text-success">
                            <i class="fas fa-check-circle mr-1"></i> Marcadores y Resultados Recientes
                        </div>
                        <div class="card-body p-3">
                            <?php if (empty($ultimosResultados)): ?>
                                <p class="text-muted text-center py-4 mb-0">Aún no se han completado partidos en la olimpiada.</p>
                            <?php else: ?>
                                <?php foreach ($ultimosResultados as $p): ?>
                                    <div class="p-3 mb-2 bg-white rounded shadow-sm border">
                                        <div class="d-flex justify-content-between text-xs text-muted mb-2">
                                            <span><strong class="text-dark"><?php echo e($p['disciplina_nombre']); ?></strong> - <?php echo e($p['torneo_nombre']); ?></span>
                                            <span class="badge badge-success px-2 py-1">FINALIZADO</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center font-weight-bold">
                                            <span class="text-truncate" style="max-width: 38%;"><?php echo e($p['local_nombre']); ?></span>
                                            <span class="badge badge-primary px-3 py-1 font-weight-bold" style="background-color: #0066B2; font-size: 1rem;">
                                                <?php echo (int)$p['marcador_local']; ?> - <?php echo (int)$p['marcador_visitante']; ?>
                                            </span>
                                            <span class="text-truncate text-right" style="max-width: 38%;"><?php echo e($p['visitante_nombre']); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
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
                        <li class="mb-2"><a href="#medallero"><i class="fas fa-chevron-right text-xs mr-2"></i> Medallero Olímpico</a></li>
                        <li class="mb-2"><a href="#sedes"><i class="fas fa-chevron-right text-xs mr-2"></i> Sedes y Rutas GPS</a></li>
                        <li class="mb-2"><a href="#fixture"><i class="fas fa-chevron-right text-xs mr-2"></i> Fixture y Marcadores</a></li>
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
    </script>
</body>
</html>
