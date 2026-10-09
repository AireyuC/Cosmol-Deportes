<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 shadow-sm">
    <!-- Enlaces de navegación izquierda -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo BASE_URL; ?>/" class="nav-link font-weight-bold text-primary">
                <i class="fas fa-running mr-1"></i> Cosmol-Deportes
            </a>
        </li>
        <li class="nav-item d-none d-md-inline-block">
            <span class="nav-link text-muted font-italic">
                <i class="fas fa-medal text-warning mr-1"></i> Olimpiadas Cosmol
            </span>
        </li>
    </ul>

    <!-- Enlaces de navegación derecha -->
    <ul class="navbar-nav ml-auto">
        <!-- Notificaciones de Sanciones o Partidos -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header font-weight-bold">Avisos de la Olimpiada</span>
                <div class="dropdown-divider"></div>
                <a href="<?php echo BASE_URL; ?>/partidos" class="dropdown-item">
                    <i class="fas fa-calendar-check mr-2 text-info"></i> Partidos de la jornada listos
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?php echo BASE_URL; ?>/sanciones" class="dropdown-item">
                    <i class="fas fa-exclamation-triangle mr-2 text-danger"></i> 2 jugadores inhabilitados
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?php echo BASE_URL; ?>/medallero" class="dropdown-item dropdown-footer">Ver Medallero Oficial</a>
            </div>
        </li>

        <!-- Pantalla completa -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- Menú de Usuario -->
        <?php if (AuthHelper::isLogged()): ?>
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-user-circle fa-lg text-primary mr-1"></i>
                    <span class="d-none d-md-inline font-weight-bold"><?php echo e(AuthHelper::nombre()); ?></span>
                    <span class="badge badge-<?php echo AuthHelper::isAdmin() ? 'danger' : 'info'; ?> ml-1"><?php echo e(AuthHelper::rol()); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow">
                    <!-- User header -->
                    <li class="user-header bg-<?php echo AuthHelper::isAdmin() ? 'primary' : 'info'; ?>">
                        <i class="fas fa-user-circle fa-4x text-light mb-2"></i>
                        <p>
                            <?php echo e(AuthHelper::nombre()); ?>
                            <small class="badge badge-light mt-1"><?php echo e(AuthHelper::rol()); ?></small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-default btn-flat float-right text-danger font-weight-bold">
                            <i class="fas fa-sign-out-alt mr-1"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/login" class="btn btn-outline-primary btn-sm my-1 mr-2 font-weight-bold">
                    <i class="fas fa-sign-in-alt mr-1"></i> Iniciar Sesión
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/registro" class="btn btn-primary btn-sm my-1 font-weight-bold">
                    <i class="fas fa-user-plus mr-1"></i> Registrarse
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<!-- /.navbar -->
