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
                    Medallero Olímpico Oficial
                </h1>
            </div>
            <div class="col-sm-5 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/medallero/gestion" class="btn btn-warning font-weight-bold">
                        Gestionar Medallas
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
                        
                        <small class="text-muted"><?php echo (int)$plata['puntos_olimpicos']; ?> pts olímpicos</small>
                    </div>
                    <div class="card-footer bg-secondary text-white py-1 font-weight-bold">2° Lugar - PLATA</div>
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
                       
                        <small class="text-muted"><?php echo (int)$oro['puntos_olimpicos']; ?> pts olímpicos</small>
                    </div>
                    <div class="card-footer font-weight-bold py-1" style="background-color:#ffc107; color:#000;">
                        1° Lugar - ORO 
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
                        
                        <small class="text-muted"><?php echo (int)$bronce['puntos_olimpicos']; ?> pts olímpicos</small>
                    </div>
                    <div class="card-footer text-white py-1 font-weight-bold" style="background-color:#cd7f32;">3° Lugar - BRONCE</div>
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
        <div class="card card-outline card-cosmol shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    Clasificación  Delegación
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-sm mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center" style="width:45px;">Pos.</th>
                                <th>Delegación</th>
                                <th class="text-center" title="Medallas de Oro">Primero</th>
                                <th class="text-center" title="Medallas de Plata">Segundo</th>
                                <th class="text-center" title="Medallas de Bronce">Tercero</th>
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
                                        if ($pos1 === 1) echo '1';
                                        elseif ($pos1 === 2) echo '2';
                                        elseif ($pos1 === 3) echo '3';
                                        else echo $pos1;
                                        ?>
                                    </td>
                                    <td class="align-middle">
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
        <div class="card card-outline card-cosmol shadow-sm">
            <div class="card-header">
                <h3 class="card-title">
                    Podios
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm table-bordered mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Disciplina</th>
                                <th class="text-center">1</th>
                                <th class="text-center">2</th>
                                <th class="text-center">3</th>
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
                                                    if ($tipo === 'ORO') echo '#0a0a0aff';
                                                    elseif ($tipo === 'PLATA') echo '#0d0d0eff';
                                                    else echo '#0c0c0cff';
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
