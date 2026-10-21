<?php
$currentUri = Router::currentUri();
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo Institucional -->
    <a href="<?php echo BASE_URL; ?>/dashboard" class="brand-link">
        <img src="<?php echo BASE_URL; ?>/images/logoSinFondo.png" alt="COSMOL" class="brand-image">
        <span class="brand-text font-weight-bold">
            Cosmol <span class="brand-accent">Deportes</span>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar px-2">
        <!-- Sidebar Menu -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- Enlace al Portal Público -->
                <li class="nav-item mb-2">
                    <a href="<?php echo BASE_URL; ?>/" target="_blank" class="nav-link bg-primary text-white font-weight-bold shadow-sm" style="background-color: #0066B2 !important;">
                        <i class="nav-icon fas fa-external-link-alt"></i>
                        <p>Ver Portal Público</p>
                    </a>
                </li>

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/dashboard" class="nav-link <?php echo ($currentUri === '/dashboard') ? 'active' : ''; ?>">
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- SECCIÓN 1: Gestión Olímpica -->
                <li class="nav-header nav-header-cosmol">
                    <span class="nav-header-title">Gestión de Olimpíadas</span>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/olimpiadas" class="nav-link <?php echo (strpos($currentUri, '/olimpiadas') === 0) ? 'active' : ''; ?>">
                        <p>Olimpiadas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/sedes" class="nav-link <?php echo (strpos($currentUri, '/sedes') === 0) ? 'active' : ''; ?>">
                        <p>Sedes y Canchas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/medallero" class="nav-link <?php echo (strpos($currentUri, '/medallero') === 0) ? 'active' : ''; ?>">
                        <p>Medallero</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/disciplinas" class="nav-link <?php echo (strpos($currentUri, '/disciplinas') === 0 || strpos($currentUri, '/reglas-puntuacion') === 0) ? 'active' : ''; ?>">
                        <p>Disciplina y Reglas</p>
                    </a>
                </li>

                <!-- SECCIÓN 3: Delegaciones y Equipos -->
                <li class="nav-header nav-header-cosmol">
                    <span class="nav-header-title">Delegación y Equipos</span>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/delegaciones" class="nav-link <?php echo (strpos($currentUri, '/delegaciones') === 0) ? 'active' : ''; ?>">
                        <p>Delegaciones</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/equipos" class="nav-link <?php echo (strpos($currentUri, '/equipos') === 0) ? 'active' : ''; ?>">
                        <p>Equipos</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/jugadores" class="nav-link <?php echo (strpos($currentUri, '/jugadores') === 0) ? 'active' : ''; ?>">
                        <p>Nómina de Jugadores</p>
                    </a>
                </li>

                <!-- SECCIÓN 4: Competencia y Fixture -->
                <li class="nav-header nav-header-cosmol">
                    <span class="nav-header-title">Competencia y Partidos</span>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/partidos" class="nav-link <?php echo (strpos($currentUri, '/partidos') === 0) ? 'active' : ''; ?>">
                        <p>Fixture y Partidos</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/posiciones" class="nav-link <?php echo (strpos($currentUri, '/posiciones') === 0) ? 'active' : ''; ?>">
                        <p>Tablas de Posiciones</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/sanciones" class="nav-link <?php echo (strpos($currentUri, '/sanciones') === 0) ? 'active' : ''; ?>">
                        <p>
                            Sanciones
                            <span class="badge badge-danger right" style="background-color: #EF4444;">Control</span>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
