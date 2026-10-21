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
        <!-- Logo Oficial de la Olimpiada en Contenedor Blanco Plano (Independiente) -->
        <div class="hero-emblema-wrapper">
            <div class="hero-emblema-box">
                <img src="<?php echo BASE_URL; ?>/images/image.png" alt="<?php echo e($olimpiada['nombre']); ?>" class="hero-emblema-img">
            </div>
        </div>

        <h1 class="hero-title mb-3">
            <?php echo e($olimpiada['nombre']); ?>
        </h1>
        <?php if (!empty($olimpiada['lema'])): ?>
            <p class="lead mb-4 mx-auto" style="max-width: 780px; font-weight: 300; opacity: 0.95;">
                
            </p>
        <?php endif; ?>

        <div class="d-flex justify-content-center flex-wrap gap-2 mt-4">
            <a href="#fixture" class="btn btn-light btn-lg px-4 mr-2 mb-2 font-weight-bold text-primary shadow-sm">
                Ver partidos de hoy
            </a>
            <a href="#cronograma" class="btn btn-outline-light btn-lg px-4 mr-2 mb-2 font-weight-bold">
                Cronograma General
            </a>
            <a href="#sedes" class="btn btn-outline-light btn-lg px-4 mb-2 font-weight-bold">
                <i class="fas fa-map-marked-alt mr-2"></i> Ver sedes
            </a>
        </div>
    </div>
</header>
