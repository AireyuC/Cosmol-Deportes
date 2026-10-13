<?php
/**
 * Parcial reutilizable: Renderiza una tabla de posiciones AdminLTE.
 *
 * Variables disponibles en el contexto del include:
 * @var array  $tablaPosiciones  Filas con estadísticas ordenadas
 * @var bool   $esVoleibol       Si la disciplina usa sets
 */
?>
<?php if (empty($tablaPosiciones)): ?>
    <div class="alert alert-light border text-muted py-2 mb-3">
        <i class="fas fa-info-circle mr-1"></i>
        No hay partidos finalizados en este grupo aún. Las posiciones se calcularán al cargar resultados.
    </div>
<?php else: ?>
<div class="card card-outline card-primary shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm table-bordered mb-0" id="tablaPosiciones">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" style="width:42px;">#</th>
                        <th>Equipo / Delegación</th>
                        <th class="text-center" title="Partidos Jugados">PJ</th>
                        <th class="text-center" title="Partidos Ganados">PG</th>
                        <th class="text-center" title="Partidos Empatados">PE</th>
                        <th class="text-center" title="Partidos Perdidos">PP</th>
                        <?php if (!empty($esVoleibol)): ?>
                            <th class="text-center" title="Sets a Favor">SF</th>
                            <th class="text-center" title="Sets en Contra">SC</th>
                        <?php else: ?>
                            <th class="text-center" title="Goles / Puntos a Favor">GF</th>
                            <th class="text-center" title="Goles / Puntos en Contra">GC</th>
                        <?php endif; ?>
                        <th class="text-center" title="Diferencia">DG</th>
                        <th class="text-center font-weight-bold" title="Puntos">PTS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tablaPosiciones as $fila): ?>
                        <?php
                        $pos   = (int)$fila['posicion'];
                        $color = $fila['color_representativo'] ?? '#6c757d';

                        // Estilo de zona (clasificación a playoffs)
                        $rowClass = '';
                        if ($pos === 1) { $rowClass = 'table-success'; }
                        elseif ($pos === 2) { $rowClass = 'table-info'; }
                        ?>
                        <tr class="<?php echo $rowClass; ?>">
                            <td class="text-center align-middle font-weight-bold">
                                <?php if ($pos === 1): ?>
                                    <span class="text-warning"><i class="fas fa-trophy"></i></span>
                                <?php elseif ($pos === 2): ?>
                                    <span class="text-secondary">2</span>
                                <?php elseif ($pos === 3): ?>
                                    <span class="text-warning" style="color:#cd7f32 !important;">3</span>
                                <?php else: ?>
                                    <?php echo $pos; ?>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <span class="badge mr-2" style="background-color:<?php echo htmlspecialchars($color, ENT_QUOTES, 'UTF-8'); ?>; width:12px; height:12px; border-radius:50%; padding:0; display:inline-block;">&nbsp;</span>
                                    <div>
                                        <span class="font-weight-bold">
                                            <?php echo htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars($fila['delegacion_nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center align-middle"><?php echo (int)$fila['pj']; ?></td>
                            <td class="text-center align-middle text-success font-weight-bold"><?php echo (int)$fila['pg']; ?></td>
                            <td class="text-center align-middle"><?php echo (int)$fila['pe']; ?></td>
                            <td class="text-center align-middle text-danger"><?php echo (int)$fila['pp']; ?></td>
                            <?php if (!empty($esVoleibol)): ?>
                                <td class="text-center align-middle"><?php echo (int)$fila['sf']; ?></td>
                                <td class="text-center align-middle"><?php echo (int)$fila['sc']; ?></td>
                            <?php else: ?>
                                <td class="text-center align-middle"><?php echo (int)$fila['gf']; ?></td>
                                <td class="text-center align-middle"><?php echo (int)$fila['gc']; ?></td>
                            <?php endif; ?>
                            <td class="text-center align-middle">
                                <?php
                                $dg = (int)$fila['dg'];
                                if ($dg > 0) { echo '<span class="text-success">+' . $dg . '</span>'; }
                                elseif ($dg < 0) { echo '<span class="text-danger">' . $dg . '</span>'; }
                                else { echo '<span class="text-muted">0</span>'; }
                                ?>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge badge-primary" style="font-size:0.95rem; padding:5px 10px;">
                                    <?php echo (int)$fila['pts']; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light py-1">
        <small class="text-muted">
            <span class="badge badge-success">&nbsp;</span> Clasificado 1º &nbsp;
            <span class="badge badge-info">&nbsp;</span> Clasificado 2º &nbsp;
            | PJ=Jugados, PG=Ganados, PE=Empatados, PP=Perdidos,
            <?php if (!empty($esVoleibol)): ?>SF=Sets Favor, SC=Sets Contra,<?php else: ?>GF=A Favor, GC=En Contra,<?php endif; ?>
            DG=Diferencia, PTS=Puntos
        </small>
    </div>
</div>
<?php endif; ?>
