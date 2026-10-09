<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo BASE_URL; ?>/" class="brand-link bg-dark">
        <i class="fas fa-trophy brand-image ml-2 text-warning" style="font-size: 1.5rem; line-height: 1.8;"></i>
        <span class="brand-text font-weight-bold">COSMOL <span class="text-warning">DEPORTES</span></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <?php if (AuthHelper::isAdmin()): ?>
                    <i class="fas fa-user-shield text-warning fa-2x"></i>
                <?php elseif (AuthHelper::isLogged()): ?>
                    <i class="fas fa-user text-info fa-2x"></i>
                <?php else: ?>
                    <i class="fas fa-user-circle text-muted fa-2x"></i>
                <?php endif; ?>
            </div>
            <div class="info">
                <a href="<?php echo AuthHelper::isLogged() ? '#' : BASE_URL . '/login'; ?>" class="d-block font-weight-bold">
                    <?php echo e(AuthHelper::nombre()); ?>
                </a>
                <span class="badge badge-<?php echo AuthHelper::isAdmin() ? 'danger' : (AuthHelper::isLogged() ? 'success' : 'secondary'); ?>">
                    <i class="fas fa-circle text-xs mr-1"></i> <?php echo e(AuthHelper::rol()); ?>
                </span>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt text-info"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header font-weight-bold text-uppercase text-secondary">Gestión Olímpica</li>

                <!-- Macro-Evento Olimpiada -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/olimpiadas" class="nav-link">
                        <i class="nav-icon fas fa-medal text-warning"></i>
                        <p>Olimpiadas</p>
                    </a>
                </li>

                <!-- Medallero General -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/medallero" class="nav-link">
                        <i class="nav-icon fas fa-award text-danger"></i>
                        <p>Medallero Oficial</p>
                    </a>
                </li>

                <!-- Disciplinas y Reglas -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/disciplinas" class="nav-link">
                        <i class="nav-icon fas fa-volleyball-ball text-success"></i>
                        <p>Disciplinas & Reglas</p>
                    </a>
                </li>

                <!-- Sedes y Canchas -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/sedes" class="nav-link">
                        <i class="nav-icon fas fa-map-marker-alt text-primary"></i>
                        <p>Sedes & Canchas</p>
                    </a>
                </li>

                <li class="nav-header font-weight-bold text-uppercase text-secondary">Torneos y Equipos</li>

                <!-- Torneos y Grupos -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/torneos" class="nav-link">
                        <i class="nav-icon fas fa-sitemap text-warning"></i>
                        <p>Torneos & Grupos</p>
                    </a>
                </li>

                <!-- Delegaciones -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/delegaciones" class="nav-link">
                        <i class="nav-icon fas fa-shield-alt text-info"></i>
                        <p>Delegaciones</p>
                    </a>
                </li>

                <!-- Nómina de Jugadores -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/jugadores" class="nav-link">
                        <i class="nav-icon fas fa-users text-light"></i>
                        <p>Nómina de Jugadores</p>
                    </a>
                </li>

                <li class="nav-header font-weight-bold text-uppercase text-secondary">Competencia y Disciplina</li>

                <!-- Fixture y Partidos -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/partidos" class="nav-link">
                        <i class="nav-icon fas fa-calendar-alt text-primary"></i>
                        <p>Fixture & Partidos</p>
                    </a>
                </li>

                <!-- Régimen Disciplinario / Sanciones -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/sanciones" class="nav-link">
                        <i class="nav-icon fas fa-exclamation-circle text-danger"></i>
                        <p>
                            Sanciones
                            <span class="badge badge-danger right">Control</span>
                        </p>
                    </a>
                </li>

                <!-- Tablas de Posiciones -->
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>/posiciones" class="nav-link">
                        <i class="nav-icon fas fa-list-ol text-success"></i>
                        <p>Tablas de Posiciones</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
