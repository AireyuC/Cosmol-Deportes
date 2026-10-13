<?php
$currentUri = Router::currentUri();
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo Institucional -->
    <a href="<?php echo BASE_URL; ?>/" class="brand-link">
        <img src="<?php echo BASE_URL; ?>/images/logoSinFondo.png" alt="COSMOL" class="brand-image">
        <span class="brand-text font-weight-bold">
            Cosmol <span class="brand-accent">Deportes</span>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar px-2">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-1 mb-1 d-flex align-items-center">
            <div class="info w-100 p-0">
                <a href="<?php echo AuthHelper::isLogged() ? '#' : BASE_URL . '/login'; ?>" class="d-block font-weight-bold text-truncate mb-1">
                    <?php echo e(AuthHelper::nombre()); ?>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/" class="nav-link <?php echo ($currentUri === '/' || $currentUri === '/dashboard') ? 'active' : ''; ?>">
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
                    <a href="<?php echo BASE_URL; ?>/medallero" class="nav-link <?php echo (strpos($currentUri, '/medallero') === 0) ? 'active' : ''; ?>">
                        <p>Medallero</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/disciplinas" class="nav-link <?php echo (strpos($currentUri, '/disciplinas') === 0) ? 'active' : ''; ?>">
                        <p>Disciplinas y Reglas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/sedes" class="nav-link <?php echo (strpos($currentUri, '/sedes') === 0) ? 'active' : ''; ?>">
                        <p>Sedes y Canchas</p>
                    </a>
                </li>

                <!-- SECCIÓN 2: Torneos y Equipos -->
                <li class="nav-header nav-header-cosmol">
                    <span class="nav-header-title">Torneos y Equipos</span>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/torneos" class="nav-link <?php echo (strpos($currentUri, '/torneos') === 0) ? 'active' : ''; ?>">
                        <p>Torneos y Grupos</p>
                    </a>
                </li>

                <!-- Equipos Deportivos -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/equipos" class="nav-link <?php echo (strpos($currentUri, '/equipos') === 0) ? 'active' : ''; ?>">
                        <p>Equipos</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/delegaciones" class="nav-link <?php echo (strpos($currentUri, '/delegaciones') === 0) ? 'active' : ''; ?>">
                        <p>Delegaciones</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/jugadores" class="nav-link <?php echo (strpos($currentUri, '/jugadores') === 0) ? 'active' : ''; ?>">
                        <p>Nómina de Jugadores</p>
                    </a>
                </li>

                <!-- SECCIÓN 3: Competencia y Disciplina -->
                <li class="nav-header nav-header-cosmol">
                    <span class="nav-header-title">Competencia y Disciplina</span>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/partidos" class="nav-link <?php echo (strpos($currentUri, '/partidos') === 0) ? 'active' : ''; ?>">
                        <p>Fixture y Partidos</p>
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

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/posiciones" class="nav-link <?php echo (strpos($currentUri, '/posiciones') === 0) ? 'active' : ''; ?>">
                        <p>Tablas de Posiciones</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
