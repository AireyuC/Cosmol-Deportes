<?php
/**
 * Sección: Delegaciones Participantes
 * Portal Oficial de las Olimpiadas COSMOL 2026
 * Compatible con PHP 7.3
 */
?>
<!-- 8. SECCIÓN: DELEGACIONES PARTICIPANTES -->
<section id="delegaciones" class="container py-5">
    <div class="text-center mb-5">
        <h2 class="font-weight-bold mt-2 mb-1" style="color: #002D52;">
            Delegaciones Participantes
        </h2>
        <p class="text-muted mx-auto mb-0" style="max-width: 650px; font-size: 0.95rem;">
            Representaciones oficiales y áreas institucionales en competencia en las Olimpiadas.
        </p>
    </div>

    <div class="row justify-content-center">
        <?php foreach ($delegaciones as $del): ?>
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="delegacion-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge font-weight-bold" style="background-color: #EBF4FA; color: #0066B2; font-size: 0.75rem; border: 1px solid #C9E2F5;">
                                <?php echo (int)($del['total_equipos'] ?? 0); ?> <?php echo ((int)($del['total_equipos'] ?? 0) === 1) ? 'Equipo' : 'Equipos'; ?>
                            </span>
                            <?php if (!empty($del['alias'])): ?>
                                <small class="text-muted text-xs font-weight-bold"><?php echo e($del['alias']); ?></small>
                            <?php endif; ?>
                        </div>

                        <h5 class="font-weight-bold mb-1" style="font-size: 1.05rem; color: #002D52; text-align: center;">
                            <?php echo e($del['nombre']); ?>
                        </h5>
                        
                        <?php if (!empty($del['responsable'])): ?>
                            <small class="text-muted d-block mb-3" style="font-size: 0.82rem;">
                                Delegado: <?php echo e($del['responsable']); ?>
                            </small>
                        <?php else: ?>
                            <div class="mb-3"></div>
                        <?php endif; ?>
                    </div>
                    <div class="pt-2 border-top">
                        <span class="text-xs font-weight-bold text-muted text-uppercase d-block mb-2">Disciplinas:</span>
                        <?php if (!empty($del['disciplinas_nombres'])): ?>
                            <?php $listaDisc = explode(', ', $del['disciplinas_nombres']); ?>
                            <div class="d-flex flex-wrap justify-content-center">
                                <?php foreach ($listaDisc as $discItem): ?>
                                    <span class="badge" style="background-color: #F8FAFC; color: #334155; border: 1px solid #E2E8F0; font-size: 0.74rem; font-weight: 600; padding: 4px 8px; margin: 2px;">
                                        <?php echo e(trim($discItem)); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-muted text-xs">Por registrar disciplinas</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
