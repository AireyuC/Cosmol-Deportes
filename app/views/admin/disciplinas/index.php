<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Disciplina y Reglas
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/reglas-puntuacion" class="btn btn-outline-primary font-weight-bold mr-2" title="Consultar o editar esquema de puntos y formatos de juego">
                    <i class="fas fa-sliders-h mr-1"></i> Reglas de Puntuación
                </a>
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/disciplinas/crear" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-plus mr-1"></i> Nueva Disciplina
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Filtro por Olimpiada -->
        <div class="card card-outline shadow-sm mb-3">
            <div class="card-body py-2">
                <form action="<?php echo BASE_URL; ?>/disciplinas" method="get" class="form-inline">
                    <label class="mr-2 text-muted font-weight-bold">Filtrar por Olimpiada:</label>
                    <select name="olimpiada_id" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                        <option value="">Todas las Olimpiadas</option>
                        <?php foreach ($olimpiadas as $olimp): ?>
                            <option value="<?php echo e($olimp['id']); ?>" <?php echo ($filtroOlimpiada == $olimp['id']) ? 'selected' : ''; ?>>
                                <?php echo e($olimp['nombre']); ?> (<?php echo e($olimp['anio']); ?>) - <?php echo e($olimp['estado']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($filtroOlimpiada): ?>
                        <a href="<?php echo BASE_URL; ?>/disciplinas" class="btn btn-sm btn-outline-secondary">Limpiar Filtro</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="card card-outline card-cosmol shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-trophy mr-1 text-warning"></i> Disciplinas Registradas
                </h3>
                <div class="card-tools">
                    <span class="badge badge-secondary"><?php echo count($torneos); ?> Disciplinas</span>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">Deporte</th>
                            <th>Disciplina</th>
                            <th>Rama</th>
                            <th>Modalidad</th>
                            <th class="text-center">Grupos</th>
                            <th class="text-center">Equipos</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center text-nowrap" style="width: 240px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($torneos)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fas fa-folder-open fa-2x mb-2 text-secondary d-block"></i>
                                    No hay disciplinas registradas para esta olimpiada.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($torneos as $t): ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge badge-warning p-2 rounded-circle" title="<?php echo e($t['disciplina_nombre']); ?>">
                                            <i class="fas fa-<?php echo e(!empty($t['disciplina_icono']) ? $t['disciplina_icono'] : 'trophy'); ?> fa-lg text-dark"></i>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/disciplinas/<?php echo e($t['id']); ?>" class="font-weight-bold text-dark d-block">
                                            <?php echo e($t['nombre']); ?>
                                        </a>
                                        <small class="text-muted">
                                            <i class="fas fa-medal mr-1"></i> <?php echo e($t['olimpiada_nombre']); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if ($t['rama'] === 'Masculino'): ?>
                                            <span class="badge badge-primary px-2"><i class="fas fa-mars mr-1"></i> Varones</span>
                                        <?php elseif ($t['rama'] === 'Femenino'): ?>
                                            <span class="badge badge-danger px-2"><i class="fas fa-venus mr-1"></i> Damas</span>
                                        <?php else: ?>
                                            <span class="badge badge-info px-2"><i class="fas fa-venus-mars mr-1"></i> Mixto</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="text-sm font-weight-bold">
                                            <?php 
                                                $modLabels = [
                                                    'Grupos_Playoff' => 'Fase de Grupos',
                                                    'Liga'           => 'Clasificación General',
                                                    'Eliminatoria'   => 'Eliminación Directa'
                                                ];
                                                echo isset($modLabels[$t['tipo_torneo']]) ? $modLabels[$t['tipo_torneo']] : e($t['tipo_torneo']);
                                            ?>
                                        </span>
                                        <div class="text-xs text-muted">
                                            <?php echo ($t['modalidad'] === 'Solo_Ida') ? 'Solo Ida' : 'Ida y Vuelta'; ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info px-2 py-1">
                                            <i class="fas fa-layer-group mr-1"></i> <?php echo e($t['total_grupos']); ?> Grupos
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success px-2 py-1">
                                            <i class="fas fa-shield-alt mr-1"></i> <?php echo e($t['total_equipos']); ?> Equipos
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php 
                                            $badgeColors = [
                                                'Borrador'      => 'secondary',
                                                'Inscripciones' => 'info',
                                                'Fase_Grupos'   => 'primary',
                                                'Playoffs'      => 'warning',
                                                'Finalizado'    => 'success'
                                            ];
                                            $estadoColor = isset($badgeColors[$t['estado']]) ? $badgeColors[$t['estado']] : 'secondary';
                                        ?>
                                        <span class="badge badge-<?php echo $estadoColor; ?> px-2 py-1">
                                            <?php echo str_replace('_', ' ', e($t['estado'])); ?>
                                        </span>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <div class="d-inline-flex align-items-center justify-content-center" style="gap: 6px;">
                                            <a href="<?php echo BASE_URL; ?>/disciplinas/<?php echo e($t['id']); ?>#resultados" class="btn btn-xs btn-outline-success font-weight-bold px-2 py-1" title="Cargar Resultados y Ver Partidos">
                                                <i class="fas fa-clipboard-check mr-1"></i> Resultados
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>/disciplinas/<?php echo e($t['id']); ?>" class="btn btn-xs btn-outline-primary font-weight-bold px-2 py-1" title="Ver Grupos y Equipos">
                                                Grupos
                                            </a>
                                            <?php if (AuthHelper::isAdmin()): ?>
                                                <?php if ($t['estado'] !== 'Finalizado'): ?>
                                                    <button type="button" class="btn btn-xs btn-warning font-weight-bold px-2 py-1" data-toggle="modal" data-target="#modalConcluir<?php echo e($t['id']); ?>" title="Concluir disciplina y actualizar medallero olímpico">
                                                        <i class="fas fa-flag-checkered mr-1"></i> Concluir
                                                    </button>
                                                <?php else: ?>
                                                    <form action="<?php echo BASE_URL; ?>/disciplinas/<?php echo (int)$t['id']; ?>/concluir" method="post" class="d-inline" title="Recalcular automáticamente medallas">
                                                        <?php echo CsrfHelper::field(); ?>
                                                        <button type="submit" class="btn btn-xs btn-outline-warning font-weight-bold px-2 py-1">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <a href="<?php echo BASE_URL; ?>/disciplinas/<?php echo e($t['id']); ?>/editar" class="btn btn-xs btn-info font-weight-bold px-2 py-1" title="Editar">
                                                    Editar
                                                </a>
                                                <button type="button" class="btn btn-xs btn-danger font-weight-bold px-2 py-1" data-toggle="modal" data-target="#modalDelete<?php echo e($t['id']); ?>" title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (AuthHelper::isAdmin()): ?>
                                            <!-- Modal Concluir Disciplina -->
                                            <div class="modal fade" id="modalConcluir<?php echo e($t['id']); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning text-dark">
                                                            <h5 class="modal-title font-weight-bold">
                                                                <i class="fas fa-flag-checkered mr-2"></i> Concluir Disciplina
                                                            </h5>
                                                            <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <form action="<?php echo BASE_URL; ?>/disciplinas/<?php echo (int)$t['id']; ?>/concluir" method="post">
                                                            <?php echo CsrfHelper::field(); ?>
                                                            <div class="modal-body text-left">
                                                                <p class="mb-2">¿Dar por concluida la disciplina <strong><?php echo e($t['nombre']); ?></strong>?</p>
                                                                <div class="alert alert-info py-2 px-3 text-sm mb-0">
                                                                    <i class="fas fa-medal mr-1 text-warning"></i>
                                                                    El sistema calculará automáticamente las delegaciones acreedoras a las medallas de <strong>Oro 🥇</strong>, <strong>Plata 🥈</strong> y <strong>Bronce 🥉</strong> según los encuentros disputados o la tabla de posiciones, actualizando el Medallero Olímpico para todos los usuarios.
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-warning font-weight-bold text-dark">
                                                                    <i class="fas fa-trophy mr-1"></i> Sí, Concluir y Proclamar Medallero
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Confirmar Eliminación -->
                                            <div class="modal fade" id="modalDelete<?php echo e($t['id']); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title font-weight-bold">
                                                                <i class="fas fa-exclamation-triangle mr-2"></i> Confirmar Eliminación
                                                            </h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            <p>¿Está seguro de que desea eliminar la disciplina <strong><?php echo e($t['nombre']); ?></strong>?</p>
                                                            <p class="text-sm text-danger mb-0">Esta acción eliminará sus grupos, asignaciones de equipos y fixtures asociados.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                            <form action="<?php echo BASE_URL; ?>/disciplinas/<?php echo e($t['id']); ?>/eliminar" method="post" class="d-inline">
                                                                <?php echo CsrfHelper::field(); ?>
                                                                <button type="submit" class="btn btn-danger font-weight-bold">Sí, Eliminar Disciplina</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
