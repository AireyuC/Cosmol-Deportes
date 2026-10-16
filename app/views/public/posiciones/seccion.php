<?php
/**
 * Sección: Tabla de Posiciones Olímpicas y Podios por Disciplina
 * Portal Oficial de las Olimpiadas COSMOL 2026
 * Compatible con PHP 7.3
 */
?>
<!-- 6. SECCIÓN: TABLA DE POSICIONES OLÍMPICAS -->
<section id="posiciones" class="py-5" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; border-bottom: 1px solid #E2E8F0;">
    <a id="medallero"></a>
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-lg-7">
                <h2 class="font-weight-bold mb-1" style="color: #002D52; text-align: center;">
                    Tabla de Posiciones
                </h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem; text-align: center;">
                    Puntuación acumulada por delegación según podios obtenidos en las distintas disciplinas.
                </p>
            </div>
            <div class="col-lg-5 text-lg-right mt-3 mt-lg-0">
                <ul class="nav pos-nav-pills d-inline-flex" id="posTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-general-link" data-toggle="pill" href="#tab-pos-general" role="tab" aria-controls="tab-pos-general" aria-selected="true">
                            Clasificación General
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-disciplinas-link" data-toggle="pill" href="#tab-pos-disciplinas" role="tab" aria-controls="tab-pos-disciplinas" aria-selected="false">
                            Por Disciplina
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="posTabContent">
            <!-- 1. CLASIFICACIÓN GENERAL -->
            <div class="tab-pane fade show active" id="tab-pos-general" role="tabpanel" aria-labelledby="tab-general-link">
                <div class="card-posiciones">
                    <div class="table-responsive">
                        <table class="table-posiciones-olimpicas text-center">
                            <thead>
                                <tr>
                                    <th class="pos-col-rank">#</th>
                                    <th class="pos-col-team">Delegación</th>
                                    <th class="pos-col-medal">Oro <span class="d-none d-sm-inline font-weight-normal text-xs" style="opacity: 0.85;"></span></th>
                                    <th class="pos-col-medal">Plata <span class="d-none d-sm-inline font-weight-normal text-xs" style="opacity: 0.85;"></span></th>
                                    <th class="pos-col-medal">Bronce <span class="d-none d-sm-inline font-weight-normal text-xs" style="opacity: 0.85;"></span></th>
                                    <th class="pos-col-pts" style="background-color: #003660 !important; color: #FFFFFF !important;">Pts</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($medallero)): ?>
                                    <tr>
                                        <td colspan="6" class="py-4 text-muted">
                                            Las posiciones se actualizarán automáticamente a medida que concluyan los encuentros.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php $pos = 1; foreach ($medallero as $m): ?>
                                        <?php
                                            $oro = (int)($m['oro'] ?? 0);
                                            $plata = (int)($m['plata'] ?? 0);
                                            $bronce = (int)($m['bronce'] ?? 0);
                                            $pts = ($oro * 5) + ($plata * 3) + ($bronce * 1);
                                        ?>
                                        <tr>
                                            <td class="pos-col-rank">
                                                <?php if ($pos === 1): ?>
                                                    <span class="pos-badge-rank pos-badge-top1">1</span>
                                                <?php elseif ($pos === 2): ?>
                                                    <span class="pos-badge-rank pos-badge-top2">2</span>
                                                <?php elseif ($pos === 3): ?>
                                                    <span class="pos-badge-rank pos-badge-top3">3</span>
                                                <?php else: ?>
                                                    <span class="pos-badge-other"><?php echo $pos; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="pos-col-team font-weight-bold">
                                                <div class="d-flex align-items-center">
                                                    <span class="d-inline-block rounded-circle mr-2 flex-shrink-0"></span>
                                                    <span class="pos-team-name text-dark"><?php echo e($m['delegacion_nombre']); ?></span>
                                                </div>
                                            </td>
                                            <td class="pos-col-medal">
                                                <?php if ($oro > 0): ?>
                                                    <span class="pos-medal-num pos-medal-oro"><?php echo $oro; ?></span>
                                                <?php else: ?>
                                                    <span class="pos-medal-zero">0</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="pos-col-medal">
                                                <?php if ($plata > 0): ?>
                                                    <span class="pos-medal-num pos-medal-plata"><?php echo $plata; ?></span>
                                                <?php else: ?>
                                                    <span class="pos-medal-zero">0</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="pos-col-medal">
                                                <?php if ($bronce > 0): ?>
                                                    <span class="pos-medal-num pos-medal-bronce"><?php echo $bronce; ?></span>
                                                <?php else: ?>
                                                    <span class="pos-medal-zero">0</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="pos-col-pts" style="background-color: #EEF6FF;">
                                                <span class="pos-total-pts"><?php echo $pts; ?></span>
                                            </td>
                                        </tr>
                                    <?php $pos++; endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-top text-center text-muted" style="background-color: #F8FAFC; font-size: 0.82rem; border-color: #E2E8F0 !important;">
                        <span><strong>Cómputo Olímpico Oficial:</strong> Oro = 5 pts &bull; Plata = 3 pts &bull; Bronce = 1 pt</span>
                    </div>
                </div>
            </div>

            <!-- 2. PODIOS POR DISCIPLINA -->
            <div class="tab-pane fade" id="tab-pos-disciplinas" role="tabpanel" aria-labelledby="tab-disciplinas-link">
                <div class="card-posiciones p-3 p-md-4">
                    <?php if (empty($podios)): ?>
                        <div class="text-center py-4 text-muted">
                            Los podios por disciplina se publicarán conforme concluyan las fases finales.
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($podios as $p): ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="border rounded p-3 h-100" style="background-color: #F8FAFC; border-color: #E2E8F0 !important;">
                                        <div class="font-weight-bold text-dark mb-2 pb-2 border-bottom" style="font-size: 0.92rem; color: #002D52 !important;">
                                            <?php echo e($p['disciplina_nombre']); ?>
                                            <small class="text-muted d-block font-weight-normal"><?php echo e($p['torneo_nombre']); ?> (<?php echo e($p['torneo_rama']); ?>)</small>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.86rem;">
                                            <span><span class="pos-medal-num pos-medal-oro mr-1">Oro</span> <?php echo !empty($p['ORO']) ? e($p['ORO']['delegacion_nombre']) : '<span class="text-muted">Por definir</span>'; ?></span>
                                            <strong style="color: #0066B2;">5 pts</strong>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.86rem;">
                                            <span><span class="pos-medal-num pos-medal-plata mr-1">Plata</span> <?php echo !empty($p['PLATA']) ? e($p['PLATA']['delegacion_nombre']) : '<span class="text-muted">Por definir</span>'; ?></span>
                                            <strong style="color: #0066B2;">3 pts</strong>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center" style="font-size: 0.86rem;">
                                            <span><span class="pos-medal-num pos-medal-bronce mr-1">Bronce</span> <?php echo !empty($p['BRONCE']) ? e($p['BRONCE']['delegacion_nombre']) : '<span class="text-muted">Por definir</span>'; ?></span>
                                            <strong style="color: #0066B2;">1 pt</strong>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
