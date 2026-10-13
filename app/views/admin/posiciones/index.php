<?php
/**
 * Vista: Tablas de Posiciones por Torneo y Grupo
 *
 * @var array       $torneos          Lista de torneos disponibles
 * @var array|null  $torneoActual     Torneo seleccionado con sus reglas
 * @var array       $grupos           Grupos del torneo seleccionado
 * @var array       $tablasPorGrupo   Tablas separadas por grupo [{grupo, tabla}]
 * @var array       $tablaPosiciones  Tabla única (cuando se filtra por grupo)
 * @var int         $torneoIdFiltro
 * @var int         $grupoIdFiltro
 * @var array|null  $olimpiada
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    <i class="fas fa-table mr-2 text-primary"></i>Tablas de Posiciones
                </h1>
                <?php if ($olimpiada): ?>
                    <small class="text-muted"><?php echo htmlspecialchars($olimpiada['nombre'], ENT_QUOTES, 'UTF-8'); ?></small>
                <?php endif; ?>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/medallero" class="btn btn-outline-warning font-weight-bold">
                    <i class="fas fa-medal mr-1"></i> Ver Medallero
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <?php if (empty($torneos)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                No hay torneos con equipos registrados. Crea torneos y equipos primero.
            </div>
        <?php else: ?>

        <!-- Filtros de Torneo y Grupo -->
        <div class="card card-outline card-primary shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-filter mr-1"></i> Seleccionar Torneo
                </h3>
            </div>
            <div class="card-body pb-2">
                <form method="GET" action="<?php echo BASE_URL; ?>/posiciones" id="formFiltroPosiciones">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group mb-2">
                                <label for="torneo_id" class="font-weight-bold mb-1">Torneo / Disciplina</label>
                                <select name="torneo_id" id="torneo_id" class="form-control" onchange="this.form.submit()">
                                    <option value="0">— Selecciona un torneo —</option>
                                    <?php foreach ($torneos as $t): ?>
                                        <?php $sel = ((int)$t['id'] === $torneoIdFiltro) ? 'selected' : ''; ?>
                                        <option value="<?php echo (int)$t['id']; ?>" <?php echo $sel; ?>>
                                            <?php echo htmlspecialchars($t['disciplina_nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                            — <?php echo htmlspecialchars($t['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                            (<?php echo htmlspecialchars($t['rama'], ENT_QUOTES, 'UTF-8'); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php if (!empty($grupos)): ?>
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label for="grupo_id" class="font-weight-bold mb-1">Filtrar por Grupo</label>
                                <select name="grupo_id" id="grupo_id" class="form-control" onchange="this.form.submit()">
                                    <option value="0">— Todos los Grupos —</option>
                                    <?php foreach ($grupos as $g): ?>
                                        <?php $sel = ((int)$g['id'] === $grupoIdFiltro) ? 'selected' : ''; ?>
                                        <option value="<?php echo (int)$g['id']; ?>" <?php echo $sel; ?>>
                                            <?php echo htmlspecialchars($g['nombre_grupo'], ENT_QUOTES, 'UTF-8'); ?>
                                            (<?php echo (int)$g['total_equipos']; ?> equipos)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-block mb-2">
                                <i class="fas fa-sync-alt mr-1"></i> Actualizar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($torneoIdFiltro > 0 && $torneoActual): ?>

            <!-- Info del torneo seleccionado -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-light border shadow-sm mb-0 py-2">
                        <span class="font-weight-bold">
                            <i class="fas fa-<?php echo htmlspecialchars($torneoActual['disciplina_icono'] ?? 'trophy', ENT_QUOTES, 'UTF-8'); ?> mr-1 text-primary"></i>
                            <?php echo htmlspecialchars($torneoActual['disciplina_nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            — <?php echo htmlspecialchars($torneoActual['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            (<?php echo htmlspecialchars($torneoActual['rama'], ENT_QUOTES, 'UTF-8'); ?>)
                        </span>
                        &nbsp;|&nbsp;
                        <span class="badge badge-<?php
                            $est = $torneoActual['estado'] ?? '';
                            if ($est === 'Finalizado') { echo 'secondary'; }
                            elseif ($est === 'Playoffs') { echo 'danger'; }
                            elseif ($est === 'Fase_Grupos') { echo 'primary'; }
                            elseif ($est === 'Inscripciones') { echo 'info'; }
                            else { echo 'dark'; }
                        ?>">
                            <?php echo htmlspecialchars($est, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        &nbsp;|&nbsp;
                        <small class="text-muted">
                            Puntuación:
                            Vic=<strong><?php echo (int)($torneoActual['pts_victoria'] ?? 3); ?></strong>
                            Emp=<strong><?php echo (int)($torneoActual['pts_empate'] ?? 1); ?></strong>
                            Der=<strong><?php echo (int)($torneoActual['pts_derrota'] ?? 0); ?></strong>
                            <?php if (!empty($torneoActual['formato_sets'])): ?>
                                | <span class="badge badge-info">Sistema por Sets</span>
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
            </div>

            <?php
            // Determinar qué mostrar: tabla única (grupo filtrado) o tablas por grupo separadas
            $esVoleibol = !empty($torneoActual['formato_sets']);

            if ($grupoIdFiltro > 0 || empty($tablasPorGrupo)):
                // Tabla única
                $renderTablo = [$tablaPosiciones];
                $grupoLabel = $grupoIdFiltro > 0 ? '' : 'General';
                echo '<div class="mb-4">';
                if ($grupoLabel) echo "<h5 class='font-weight-bold text-uppercase mb-2'>{$grupoLabel}</h5>";
                ?>
                <?php include __DIR__ . '/_tabla_posiciones.php'; ?>
                <?php echo '</div>';
            else:
                // Tablas por grupo separadas
                foreach ($tablasPorGrupo as $bloque):
                    $tablaPosiciones = $bloque['tabla'];
                    $grupoNombre = htmlspecialchars($bloque['grupo']['nombre_grupo'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <h5 class="font-weight-bold text-uppercase mt-3 mb-2">
                        <i class="fas fa-layer-group mr-1 text-primary"></i><?php echo $grupoNombre; ?>
                    </h5>
                    <?php include __DIR__ . '/_tabla_posiciones.php'; ?>
                <?php endforeach; ?>
            <?php endif; ?>

        <?php elseif ($torneoIdFiltro > 0): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                No se encontró información del torneo seleccionado.
            </div>
        <?php endif; ?>

        <?php endif; // fin !empty($torneos) ?>
    </div>
</div>
