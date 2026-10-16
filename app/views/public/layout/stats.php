<?php
/**
 * Barra de Estadísticas Rápidas
 * Portal Oficial de las Olimpiadas COSMOL 2026
 * Compatible con PHP 7.3
 */
?>
<!-- 3. BARRA DE ESTADÍSTICAS RÁPIDAS -->
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
                <div class="stat-number"><?php echo count($todosPartidos); ?></div>
                <div class="stat-label"><i class="fas fa-calendar-check mr-1"></i> Encuentros Programados</div>
            </div>
        </div>
    </div>
</section>
