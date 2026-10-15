<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 shadow-sm">
    <!-- Enlaces de navegación izquierda -->
    <ul class="navbar-nav align-items-center">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Enlaces de navegación derecha -->
    <ul class="navbar-nav ml-auto align-items-center">

        <!-- Menú de Usuario -->
        <?php if (AuthHelper::isLogged()): ?>
            <li class="nav-item mr-2">
                <a href="<?php echo BASE_URL; ?>/" target="_blank" class="btn btn-outline-primary btn-sm my-1 font-weight-bold" title="Abrir portal público en nueva pestaña">
                    <i class="fas fa-globe mr-1"></i> Ver Portal Público
                </a>
            </li>
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <span class="d-none d-md-inline font-weight-bold text-dark mr-1"><?php echo e(AuthHelper::nombre()); ?></span>
                    <span class="badge badge-primary font-weight-normal" style="background-color: #0066B2;">
                        <?php echo e(AuthHelper::rol()); ?>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow-sm border-0">
                    <!-- User header -->
                    <li class="user-header" style="background-color: #0066B2; color: #FFFFFF;">
                        <p class="pt-2">
                            <strong class="d-block text-lg"><?php echo e(AuthHelper::nombre()); ?></strong>
                            <small class="badge badge-light mt-1"><?php echo e(AuthHelper::rol()); ?></small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer bg-light">
                        <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-sm btn-outline-danger float-right font-weight-bold">
                            <i class="fas fa-sign-out-alt mr-1"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/login" class="btn btn-primary btn-sm my-1 font-weight-bold" style="background-color: #0066B2; border-color: #0066B2;">
                    <i class="fas fa-lock mr-1"></i> Iniciar Sesión (Admin)
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<!-- /.navbar -->
