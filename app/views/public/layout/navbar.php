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
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>/" aria-label="Inicio COSMOL Deportes">
            <img src="<?php echo BASE_URL; ?>/images/image.png" alt="COSMOL R.L." class="navbar-logo-img">
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
                        Panel Admin
                    </a>
                    <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-outline-danger btn-sm font-weight-bold">
                        Salir
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
