<?php
/**
 * Hero Banner Principal
 * Portal Oficial de las Olimpiadas COSMOL 2026
 * Compatible con PHP 7.3
 */
?>
<!-- 2. HERO BANNER PRINCIPAL -->
<header class="hero-banner">
    <div class="container text-center py-4">
        <h1 class="hero-title mb-3">
            <?php echo e($olimpiada['nombre']); ?>
        </h1>
        <p class="lead mb-4 mx-auto" style="max-width: 780px; font-weight: 300; opacity: 0.95;">
            "Gestión, Unidad y Transparencia"<br>
        </p>

        <div class="d-flex justify-content-center flex-wrap gap-2 mt-4">
            <a href="#fixture" class="btn btn-light btn-lg px-4 mr-2 mb-2 font-weight-bold text-primary shadow-sm">
                <i class="fas fa-calendar-alt mr-2"></i> Ver partidos de hoy
            </a>
            <a href="#maraton" class="btn btn-warning btn-lg px-4 mr-2 mb-2 font-weight-bold text-dark shadow-sm">
                <i class="fas fa-route mr-2"></i> Circuito Maratón (5.0K)
            </a>
            <a href="#sedes" class="btn btn-outline-light btn-lg px-4 mb-2 font-weight-bold">
                <i class="fas fa-map-marked-alt mr-2"></i> Ver sedes
            </a>
        </div>
    </div>
</header>
