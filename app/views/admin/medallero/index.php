<?php
/**
 * Vista: Medallero Olímpico General
 *
 * @var array|null  $olimpiada
 * @var array       $resumen       Resumen por delegación ordenado olímpicamente
 * @var array       $podios        Podios por torneo [{torneo_id, torneo_nombre, ORO, PLATA, BRONCE}]
 * @var string      $mensajeInfo
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-7">
                <h1 class="card-title mb-0 card-title-cosmol">
                    <i class="fas fa-medal mr-2 text-warning"></i>Medallero Olímpico Oficial
                </h1>
                <?php if ($olimpiada): ?>
                    <small class="text-muted">
                        <?php echo htmlspecialchars($olimpiada['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                        <?php echo (int)$olimpiada['anio']; ?>
                    </small>
                <?php endif; ?>
            </div>
            <div class="col-sm-5 text-right">
                <a href="<?php echo BASE_URL; ?>/posiciones" class="btn btn-outline-primary font-weight-bold mr-1">
                    <i class="fas fa-table mr-1"></i> Posiciones
                </a>
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/medallero/gestion" class="btn btn-warning font-weight-bold">
                        <i class="fas fa-cog mr-1"></i> Gestionar Medallas
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <?php if (!empty($mensajeInfo)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                <?php echo htmlspecialchars($mensajeInfo, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($resumen)): ?>

        <!-- Podio TOP 3 -->
        <?php
        $oro    = isset($resumen[0]) ? $resumen[0] : null;
        $plata  = isset($resumen[1]) ? $resumen[1] : null;
        $bronce = isset($resumen[2]) ? $resumen[2] : null;
        ?>
        <div class="row justify-content-center mb-4" id="podio-olimpico">

            <!-- 2do lugar (Plata) -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                <?php if ($plata): ?>
                <div class="card shadow text-center border-0" style="border-top: 5px solid #adb5bd !important;">
                    <div class="card-body py-4">
                        <div class="mb-2" style="font-size:2.5rem;">🥈</div>
                        <div class="badge mb-2 font-weight-bold" style="background-color:<?php echo htmlspecialchars($plata['color_representativo'] ?? '#adb5bd', ENT_QUOTES, 'UTF-8'); ?>; font-size:0.8rem; padding:5px 10px;">&nbsp;</div>
                        <h5 class="font-weight-bold mb-1"><?php echo htmlspecialchars($plata['delegacion_nombre'], ENT_QUOTES, 'UTF-8'); ?></h5>
                        <small class="text-muted d-block mb-2"><?php echo htmlspecialchars($plata['delegacion_alias'] ?? '', ENT_QUOTES, 'UTF-8'); ?></small>
                        <div class="d-flex justify-content-center" style="gap:8px;">
                            <span title="Oros" class="font-weight-bold text-warning">🥇 <?php echo (int)$plata['oro']; ?></span>
                            <span title="Platas" class="font-weight-bold text-secondary">🥈 <?php echo (int)$plata['plata']; ?></span>
                            <span title="Bronces" class="font-weight-bold" style="color:#cd7f32;">🥉 <?php echo (int)$plata['bronce']; ?></span>
                        </div>
                        <small class="text-muted"><?php echo (int)$plata['puntos_olimpicos']; ?> pts olímpicos</small>
                    </div>
                    <div class="card-footer bg-secondary text-white py-1 font-weight-bold">2° Lugar — PLATA</div>
                </div>
                <?php else: ?>
                    <div class="card shadow-sm text-center border-0 bg-light py-4">
                        <div style="font-size:2rem;">🥈</div>
                        <p class="text-muted mb-0 mt-2">Por definir</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- 1er lugar (Oro) -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                <?php if ($oro): ?>
                <div class="card shadow-lg text-center border-0" style="border-top: 5px solid #ffc107 !important; transform: scale(1.04);">
                    <div class="card-body py-4">
                        <div class="mb-2" style="font-size:3rem;">🥇</div>
                        <div class="badge mb-2 font-weight-bold" style="background-color:<?php echo htmlspecialchars($oro['color_representativo'] ?? '#ffc107', ENT_QUOTES, 'UTF-8'); ?>; font-size:0.85rem; padding:6px 12px;">&nbsp;</div>
                        <h4 class="font-weight-bold mb-1"><?php echo htmlspecialchars($oro['delegacion_nombre'], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <small class="text-muted d-block mb-2"><?php echo htmlspecialchars($oro['delegacion_alias'] ?? '', ENT_QUOTES, 'UTF-8'); ?></small>
                        <div class="d-flex justify-content-center" style="gap:8px;">
                            <span title="Oros" class="font-weight-bold text-warning">🥇 <?php echo (int)$oro['oro']; ?></span>
                            <span title="Platas" class="font-weight-bold text-secondary">🥈 <?php echo (int)$oro['plata']; ?></span>
                            <span title="Bronces" class="font-weight-bold" style="color:#cd7f32;">🥉 <?php echo (int)$oro['bronce']; ?></span>
                        </div>
                        <small class="text-muted"><?php echo (int)$oro['puntos_olimpicos']; ?> pts olímpicos</small>
                    </div>
                    <div class="card-footer font-weight-bold py-1" style="background-color:#ffc107; color:#000;">
                        1° Lugar — ORO 🏆
                    </div>
                </div>
                <?php else: ?>
                    <div class="card shadow-sm text-center border-0 bg-light py-4">
                        <div style="font-size:2.5rem;">🥇</div>
                        <p class="text-muted mb-0 mt-2">Por definir</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- 3er lugar (Bronce) -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                <?php if ($bronce): ?>
                <div class="card shadow text-center border-0" style="border-top: 5px solid #cd7f32 !important;">
                    <div class="card-body py-4">
                        <div class="mb-2" style="font-size:2.5rem;">🥉</div>
                        <div class="badge mb-2 font-weight-bold" style="background-color:<?php echo htmlspecialchars($bronce['color_representativo'] ?? '#cd7f32', ENT_QUOTES, 'UTF-8'); ?>; font-size:0.8rem; padding:5px 10px;">&nbsp;</div>
                        <h5 class="font-weight-bold mb-1"><?php echo htmlspecialchars($bronce['delegacion_nombre'], ENT_QUOTES, 'UTF-8'); ?></h5>
                        <small class="text-muted d-block mb-2"><?php echo htmlspecialchars($bronce['delegacion_alias'] ?? '', ENT_QUOTES, 'UTF-8'); ?></small>
                        <div class="d-flex justify-content-center" style="gap:8px;">
                            <span title="Oros" class="font-weight-bold text-warning">🥇 <?php echo (int)$bronce['oro']; ?></span>
                            <span title="Platas" class="font-weight-bold text-secondary">🥈 <?php echo (int)$bronce['plata']; ?></span>
                            <span title="Bronces" class="font-weight-bold" style="color:#cd7f32;">🥉 <?php echo (int)$bronce['bronce']; ?></span>
                        </div>
                        <small class="text-muted"><?php echo (int)$bronce['puntos_olimpicos']; ?> pts olímpicos</small>
                    </div>
                    <div class="card-footer text-white py-1 font-weight-bold" style="background-color:#cd7f32;">3° Lugar — BRONCE</div>
                </div>
                <?php else: ?>
                    <div class="card shadow-sm text-center border-0 bg-light py-4">
                        <div style="font-size:2rem;">🥉</div>
                        <p class="text-muted mb-0 mt-2">Por definir</p>
                    </div>
                <?php endif; ?>
            </div>

        </div><!-- fin podio -->

        <!-- Tabla General del Medallero -->
        <div class="card card-outline card-warning shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-list-ol mr-1 text-warning"></i> Clasificación General por Delegación
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-sm mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center" style="width:45px;">Pos.</th>
                                <th>Delegación</th>
                                <th class="text-center" title="Medallas de Oro">🥇</th>
                                <th class="text-center" title="Medallas de Plata">🥈</th>
                                <th class="text-center" title="Medallas de Bronce">🥉</th>
                                <th class="text-center" title="Total de Medallas">Total</th>
                                <th class="text-center" title="Puntos Olímpicos Acumulados">Pts Olímpicos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resumen as $pos => $row): ?>
                                <?php
                                $pos1 = $pos + 1;
                                $rowCls = '';
                                if ($pos1 === 1) { $rowCls = 'table-warning'; }
                                elseif ($pos1 === 2) { $rowCls = 'table-secondary'; }
                                elseif ($pos1 === 3) { $rowCls = ''; }
                                $colorDel = $row['color_representativo'] ?? '#6c757d';
                                ?>
                                <tr class="<?php echo $rowCls; ?>">
                                    <td class="text-center align-middle font-weight-bold">
                                        <?php
                                        if ($pos1 === 1) echo '🥇';
                                        elseif ($pos1 === 2) echo '🥈';
                                        elseif ($pos1 === 3) echo '🥉';
                                        else echo $pos1;
                                        ?>
                                    </td>
                                    <td class="align-middle">
                                        <span class="d-inline-block mr-2" style="width:12px;height:12px;border-radius:50%;background-color:<?php echo htmlspecialchars($colorDel, ENT_QUOTES, 'UTF-8'); ?>;vertical-align:middle;"></span>
                                        <strong><?php echo htmlspecialchars($row['delegacion_nombre'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <?php if (!empty($row['delegacion_alias'])): ?>
                                            <small class="text-muted ml-1">(<?php echo htmlspecialchars($row['delegacion_alias'], ENT_QUOTES, 'UTF-8'); ?>)</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-middle font-weight-bold text-warning" style="font-size:1.1rem;"><?php echo (int)$row['oro']; ?></td>
                                    <td class="text-center align-middle font-weight-bold text-secondary" style="font-size:1.1rem;"><?php echo (int)$row['plata']; ?></td>
                                    <td class="text-center align-middle font-weight-bold" style="color:#cd7f32;font-size:1.1rem;"><?php echo (int)$row['bronce']; ?></td>
                                    <td class="text-center align-middle"><?php echo (int)$row['total_medallas']; ?></td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-primary" style="font-size:0.9rem;padding:5px 10px;"><?php echo (int)$row['puntos_olimpicos']; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Detalle del Podio por Torneo/Disciplina -->
        <?php if (!empty($podios)): ?>
        <div class="card card-outline card-info shadow-sm">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-trophy mr-1 text-info"></i> Podios por Disciplina y Torneo</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm table-bordered mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Disciplina / Torneo</th>
                                <th class="text-center">🥇 ORO</th>
                                <th class="text-center">🥈 PLATA</th>
                                <th class="text-center">🥉 BRONCE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($podios as $podio): ?>
                                <tr>
                                    <td class="align-middle">
                                        <i class="fas fa-<?php echo htmlspecialchars($podio['disciplina_icono'] ?? 'trophy', ENT_QUOTES, 'UTF-8'); ?> mr-1 text-primary"></i>
                                        <strong><?php echo htmlspecialchars($podio['disciplina_nombre'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        — <?php echo htmlspecialchars($podio['torneo_nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                        <span class="badge badge-light ml-1"><?php echo htmlspecialchars($podio['torneo_rama'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                    </td>
                                    <?php foreach (['ORO', 'PLATA', 'BRONCE'] as $tipo): ?>
                                        <td class="text-center align-middle">
                                            <?php if (!empty($podio[$tipo])): ?>
                                                <span class="font-weight-bold" style="color:<?php
                                                    if ($tipo === 'ORO') echo '#ffc107';
                                                    elseif ($tipo === 'PLATA') echo '#adb5bd';
                                                    else echo '#cd7f32';
                                                ?>;">
                                                    <?php echo htmlspecialchars($podio[$tipo]['delegacion_nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                                </span>
                                                <br><small class="text-muted"><?php echo (int)$podio[$tipo]['puntos_olimpicos']; ?> pts</small>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php endif; // fin !empty($resumen) ?>

    </div>
</div>
