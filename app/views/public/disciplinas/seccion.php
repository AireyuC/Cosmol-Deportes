<?php
/**
 * Sección: Disciplinas de las Olimpiadas
 * Portal Oficial de las Olimpiadas COSMOL 2026
 * Compatible con PHP 7.3
 */
?>
<!-- 5. SECCIÓN: DISCIPLINAS DEPORTIVAS -->
<section id="disciplinas" class="container py-5 mt-4">
    <div class="text-center mb-4">
        <h2 class="font-weight-bold mt-2" style="color: var(--cosmol-azul-deep);">
            Disciplinas de las Olimpiadas
        </h2>
        <p class="text-muted mx-auto" style="max-width: 650px;">
            Seleccione cualquiera de los deportes para consultar su escenario y calendario de encuentros.
        </p>
    </div>

    <?php
        $totalDisciplinas = count($disciplinas);
        $countCancha = 0;
        $countSalon = 0;
        foreach ($disciplinas as $item) {
            $n = mb_strtolower(trim($item['nombre']), 'UTF-8');
            if (in_array($n, ['cacho', 'billar', 'karaoke', 'juegos de salón', 'juego de salon', 'ajedrez'])) {
                $countSalon++;
            } else {
                $countCancha++;
            }
        }
    ?>

    <!-- Filtros Interactivos de Disciplinas -->
    <div class="sport-filters-container">
        <div class="sport-filters" role="group" aria-label="Filtro de Disciplinas">
            <button type="button" class="sport-filter-btn active" data-filter="all">
                Todas
            </button>
            <button type="button" class="sport-filter-btn" data-filter="cancha">
                Deportes de Cancha
            </button>
            <button type="button" class="sport-filter-btn" data-filter="salon">
                Juegos de Salón
            </button>
        </div>
    </div>

    <!-- Parrilla Dinámica de Disciplinas -->
    <div class="row" id="sportGrid">
        <?php if (empty($disciplinas)): ?>
            <div class="col-12 text-center py-5">
                <div class="p-4 bg-white rounded shadow-sm border mx-auto" style="max-width: 500px;">
                    <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                    <h5 class="font-weight-bold text-dark">No hay disciplinas registradas aún</h5>
                    <p class="text-muted text-sm mb-0">El comité organizador publicará el catálogo oficial de competencias próximamente.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($disciplinas as $d): ?>
                <?php
                    $nLower = mb_strtolower(trim($d['nombre']), 'UTF-8');
                    $isSalon = in_array($nLower, ['cacho', 'billar', 'karaoke', 'juegos de salón', 'juego de salon', 'ajedrez']);
                    $catSlug = $isSalon ? 'salon' : 'cancha';
                    $catLabel = $isSalon ? 'Juego de Salón' : 'Deporte de Cancha';
                    $catClass = $isSalon ? 'sport-category-salon' : 'sport-category-cancha';
                    $catIcon = $isSalon ? 'fa-dice' : 'fa-running';

                    // Mapeo dinámico y temático de icono y estilo
                    $iconClass = 'sport-icon-default';
                    $iconName = !empty($d['icono']) ? e($d['icono']) : 'trophy';
                    $iconImg = '';

                    if (strpos($nLower, 'fútbol') !== false || strpos($nLower, 'futbol') !== false) {
                        $iconClass = 'sport-icon-futbol';
                        $iconName = 'futbol';
                        $iconImg = 'image copy 2.png';
                    } elseif (strpos($nLower, 'futsal') !== false) {
                        $iconClass = 'sport-icon-futsal';
                        $iconName = 'running';
                        $iconImg = 'image copy.png';
                    } elseif (strpos($nLower, 'básquet') !== false || strpos($nLower, 'basquet') !== false || strpos($nLower, 'basket') !== false) {
                        $iconClass = 'sport-icon-basquetbol';
                        $iconName = 'basketball-ball';
                        $iconImg = 'image copy 7.png';
                    } elseif (strpos($nLower, 'voley') !== false || strpos($nLower, 'volei') !== false) {
                        $iconClass = 'sport-icon-voleibol';
                        $iconName = 'volleyball-ball';
                        $iconImg = 'image copy 5.png';
                    } elseif (strpos($nLower, 'cacho') !== false) {
                        $iconClass = 'sport-icon-cacho';
                        $iconName = 'dice';
                        $iconImg = 'image copy 4.png';
                    } elseif (strpos($nLower, 'billar') !== false) {
                        $iconClass = 'sport-icon-billar';
                        $iconName = 'dot-circle';
                        $iconImg = 'image copy 3.png';
                    } elseif (strpos($nLower, 'karaoke') !== false) {
                        $iconClass = 'sport-icon-karaoke';
                        $iconName = 'microphone-alt';
                        $iconImg = 'image.png';
                    } elseif (strpos($nLower, 'frontón') !== false || strpos($nLower, 'fronton') !== false) {
                        $iconClass = 'sport-icon-voleibol';
                        $iconName = 'table-tennis';
                        $iconImg = 'image copy 6.png';
                    } elseif (strpos($nLower, 'maratón') !== false || strpos($nLower, 'maraton') !== false || strpos($nLower, 'atletismo') !== false) {
                        $iconClass = 'sport-icon-futsal';
                        $iconName = 'running';
                        $iconImg = '';
                    }
                ?>
                <div class="col-6 col-md-4 col-lg-3 mb-3 sport-col" data-category="<?php echo $catSlug; ?>">
                    <div class="sport-card">
                        <div>
                            <div class="sport-icon-wrapper <?php echo $iconClass; ?>">
                                <?php if (!empty($iconImg)): ?>
                                    <img src="<?php echo BASE_URL; ?>/icons/<?php echo rawurlencode($iconImg); ?>" alt="<?php echo e($d['nombre']); ?>" class="sport-icon-img">
                                <?php else: ?>
                                    <i class="fas fa-<?php echo $iconName; ?>"></i>
                                <?php endif; ?>
                            </div>

                            <h3 class="sport-name"><?php echo e($d['nombre']); ?></h3>
                            <p class="sport-desc">
                                <?php echo !empty($d['descripcion']) ? e($d['descripcion']) : 'Competencia oficial de las Olimpiadas COSMOL 2026.'; ?>
                            </p>
                        </div>

                        <div>
                            <?php if (strpos($nLower, 'maratón') !== false || strpos($nLower, 'maraton') !== false || strpos($nLower, 'atletismo') !== false): ?>
                                <a href="<?php echo BASE_URL; ?>/portal/fixture?disciplina_id=<?php echo (int)$d['id']; ?>" class="btn-sport-action shadow-sm mb-1">
                                    <span>Ver Fixture</span>
                                </a>
                                <a href="#maraton" class="btn btn-sm btn-link p-0 text-primary font-weight-bold d-block text-center mt-1" style="font-size: 0.78rem;">
                                    <i class="fas fa-map-marked-alt mr-1"></i> Ver Circuito en Portada
                                </a>
                            <?php else: ?>
                                <a href="<?php echo BASE_URL; ?>/portal/fixture?disciplina_id=<?php echo (int)$d['id']; ?>" class="btn-sport-action shadow-sm">
                                    <span>Ver Fixture</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
