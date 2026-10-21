<?php
/**
 * Barra de Navegación Pública Superior
 * Portal Oficial de las Olimpiadas COSMOL 2026
 * Compatible con PHP 7.3
 */
?>
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
                    <a class="nav-link" href="#cronograma">Cronograma</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#fixture">Partidos de Hoy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#maraton">Maratón</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sedes">Sedes y Rutas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#disciplinas">Disciplinas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#delegaciones">Delegaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#posiciones">Posiciones</a>
                </li>
            </ul>

            <!-- ACCESOS DE ADMINISTRADOR (SOLO VISIBLE SI YA INICIÓ SESIÓN) -->
            <?php if (!empty($isAdminLogged)): ?>
                <div class="navbar-nav ml-auto align-items-center">
                    <a href="<?php echo BASE_URL; ?>/dashboard" class="btn btn-primary btn-sm px-3 font-weight-bold mr-2" style="background-color: #0066B2;">
                        <i class="fas fa-tachometer-alt mr-1"></i> Panel Admin
                    </a>
                    <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-outline-danger btn-sm font-weight-bold">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
