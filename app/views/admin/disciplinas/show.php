<?php
/**
 * Ficha detallada de Torneo, Grupos y Equipos
 *
 * @var string $pageTitle
 * @var array  $torneo
 * @var array  $grupos
 * @var array  $equiposSinGrupo
 * @var array  $todosEquipos
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-7">
                <h1 class="card-title mb-0 card-title-cosmol">
                    <?php echo e($torneo['nombre']); ?>
                </h1>
                <span class="text-muted text-sm ml-2">
                    <?php echo e($torneo['olimpiada_nombre']); ?> &bull; <?php echo e($torneo['disciplina_nombre']); ?>
                </span>
            </div>
            <div class="col-sm-5 text-right">
                <a href="<?php echo BASE_URL; ?>/disciplinas" class="btn btn-secondary font-weight-bold mr-1">
                    <i class="fa fa-arrow-left mr-1"></i> Volver
                </a>
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/disciplinas/<?php echo e($torneo['id']); ?>/editar" class="btn btn-primary font-weight-bold">
                        Editar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Barra de Estado y Metadatos -->
        <div class="card card-outline shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="row text-center text-md-left align-items-center">
                    <div class="col-md-2 mb-2 mb-md-0">
                        <span class="text-muted d-block text-xs text-uppercase font-weight-bold">Categoría</span>
                        <?php if ($torneo['rama'] === 'Masculino'): ?>
                            <span class="badge badge-primary px-3 py-1"><i class="fas fa-mars mr-1"></i> Masculino</span>
                        <?php elseif ($torneo['rama'] === 'Femenino'): ?>
                            <span class="badge badge-danger px-3 py-1"><i class="fas fa-venus mr-1"></i> Femenino</span>
                        <?php else: ?>
                            <span class="badge badge-info px-3 py-1"><i class="fas fa-venus-mars mr-1"></i> Mixto</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <span class="text-muted d-block text-xs text-uppercase font-weight-bold">Formato y Modalidad</span>
                        <span class="font-weight-bold text-dark">
                            <?php echo str_replace('_', ' ', e($torneo['tipo_torneo'])); ?>
                        </span>
                        <small class="text-muted d-block">(<?php echo ($torneo['modalidad'] === 'Solo_Ida') ? 'Solo Ida' : 'Ida y Vuelta'; ?>)</small>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <span class="text-muted d-block text-xs text-uppercase font-weight-bold">Estado Actual</span>
                        <?php 
                            $badgeColors = [
                                'Borrador'      => 'secondary',
                                'Inscripciones' => 'info',
                                'Fase_Grupos'   => 'primary',
                                'Playoffs'      => 'warning',
                                'Finalizado'    => 'success'
                            ];
                            $colorEst = isset($badgeColors[$torneo['estado']]) ? $badgeColors[$torneo['estado']] : 'secondary';
                        ?>
                        <span class="badge badge-<?php echo $colorEst; ?> px-3 py-1 font-weight-bold">
                            <?php echo str_replace('_', ' ', e($torneo['estado'])); ?>
                        </span>
                    </div>
                    <div class="col-md-5 text-md-right">
                        <a href="<?php echo BASE_URL; ?>/partidos?torneo_id=<?php echo e($torneo['id']); ?>" class="btn btn-sm btn-outline-info font-weight-bold mr-1">
                            Fixture
                        </a>
                        <?php if (AuthHelper::isAdmin()): ?>
                            <a href="<?php echo BASE_URL; ?>/partidos/crear?torneo_id=<?php echo e($torneo['id']); ?>" class="btn btn-sm btn-outline-primary font-weight-bold mr-1">
                                Programar Partido
                            </a>
                            <button type="button" class="btn btn-sm btn-success font-weight-bold mr-1" data-toggle="modal" data-target="#modalNuevoGrupo">
                                Crear Grupo
                            </button>
                            <a href="<?php echo BASE_URL; ?>/equipos/crear?torneo_id=<?php echo e($torneo['id']); ?>" class="btn btn-sm btn-primary font-weight-bold">
                                Inscribir Equipo
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipos Sin Grupo Asignado (Alerta de atención) -->
        <?php if (!empty($equiposSinGrupo)): ?>
            <div class="card card-warning card-outline shadow-sm mb-4">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Equipos pendientes de asignación de Grupo (<?php echo count($equiposSinGrupo); ?>)
                    </h3>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Equipo</th>
                                <th>Delegación</th>
                                <th class="text-right" style="width: 280px;">Asignar a un Grupo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($equiposSinGrupo as $eqSin): ?>
                                <tr>
                                    <td class="font-weight-bold"><?php echo e($eqSin['nombre']); ?></td>
                                    <td>
                                        <span class="badge" style="background-color: <?php echo e($eqSin['color_representativo']); ?>; color: #fff;">
                                            <?php echo e($eqSin['delegacion_nombre']); ?>
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <?php if (!empty($grupos)): ?>
                                            <form action="<?php echo BASE_URL; ?>/grupos/asignar-equipo" method="post" class="form-inline justify-content-end">
                                                <?php echo CsrfHelper::field(); ?>
                                                <input type="hidden" name="equipo_id" value="<?php echo e($eqSin['id']); ?>">
                                                <input type="hidden" name="torneo_id" value="<?php echo e($torneo['id']); ?>">
                                                <select name="grupo_id" class="form-control form-control-sm mr-2" required>
                                                    <option value="">Seleccionar Grupo...</option>
                                                    <?php foreach ($grupos as $gOption): ?>
                                                        <option value="<?php echo e($gOption['id']); ?>"><?php echo e($gOption['nombre_grupo']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-warning text-dark font-weight-bold">
                                                    Asignar
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted text-sm">Cree un grupo primero</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <!-- Sección de Grupos de Competencia -->
        <div class="row">
            <?php if (empty($grupos)): ?>
                <div class="col-12">
                    <div class="card card-outline card-secondary py-5 text-center shadow-sm">
                        <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                        <h4 class="font-weight-bold text-dark">Aún no hay grupos creados para esta disciplina</h4>
                        <p class="text-muted">Crea grupos como <strong>Grupo A</strong>, <strong>Grupo B</strong> para organizar a los equipos participantes.</p>
                        <?php if (AuthHelper::isAdmin()): ?>
                            <div class="mt-2">
                                <button type="button" class="btn btn-warning text-dark font-weight-bold px-4" data-toggle="modal" data-target="#modalNuevoGrupo">
                                    <i class="fas fa-plus mr-1"></i> Crear Primer Grupo
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($grupos as $grupo): ?>
                    <div class="col-lg-6 mb-4">
                        <div class="card card-outline card-primary shadow-sm h-100">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h3 class="card-title font-weight-bold text-primary mb-0">
                                    <i class="fas fa-users mr-1"></i> <?php echo e($grupo['nombre_grupo']); ?>
                                </h3>
                                <div>
                                    <span class="badge badge-primary mr-2"><?php echo count($grupo['equipos']); ?> Equipos</span>
                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <button type="button" class="btn btn-tool text-danger" data-toggle="modal" data-target="#modalDelGrupo<?php echo e($grupo['id']); ?>" title="Eliminar Grupo">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Equipo</th>
                                            <th>Delegación</th>
                                            <th class="text-center">Jugadores</th>
                                            <?php if (AuthHelper::isAdmin()): ?>
                                                <th class="text-center" style="width: 80px;">Acción</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($grupo['equipos'])): ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    <em>No hay equipos asignados a este grupo aún.</em>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($grupo['equipos'] as $eq): ?>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/jugadores" class="text-dark">
                                                            <?php echo e($eq['nombre']); ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span class="badge" style="background-color: <?php echo e($eq['color_representativo']); ?>; color: #fff;">
                                                            <?php echo e($eq['delegacion_nombre']); ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/jugadores" class="badge badge-info px-2 py-1" title="Ver nómina">
                                                            <i class="fas fa-user-friends mr-1"></i> <?php echo e($eq['total_jugadores']); ?> atletas
                                                        </a>
                                                    </td>
                                                    <?php if (AuthHelper::isAdmin()): ?>
                                                        <td class="text-center">
                                                            <form action="<?php echo BASE_URL; ?>/grupos/desasignar-equipo/<?php echo e($eq['id']); ?>" method="post" class="d-inline">
                                                                <?php echo CsrfHelper::field(); ?>
                                                                <input type="hidden" name="torneo_id" value="<?php echo e($torneo['id']); ?>">
                                                                <button type="submit" class="btn btn-xs btn-outline-danger" title="Quitar del Grupo" onclick="return confirm('¿Quitar este equipo del grupo?')">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modal Confirmar Eliminación de Grupo -->
                        <div class="modal fade" id="modalDelGrupo<?php echo e($grupo['id']); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title font-weight-bold">
                                            <i class="fas fa-exclamation-triangle mr-2"></i> Eliminar <?php echo e($grupo['nombre_grupo']); ?>
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Está seguro de eliminar el <strong><?php echo e($grupo['nombre_grupo']); ?></strong>?</p>
                                        <p class="text-muted text-sm mb-0">Los equipos que pertenecen a este grupo quedarán sin grupo asignado, listos para ser reubicados.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <form action="<?php echo BASE_URL; ?>/grupos/<?php echo e($grupo['id']); ?>/eliminar" method="post" class="d-inline">
                                            <?php echo CsrfHelper::field(); ?>
                                            <input type="hidden" name="torneo_id" value="<?php echo e($torneo['id']); ?>">
                                            <button type="submit" class="btn btn-danger font-weight-bold">Sí, Eliminar Grupo</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Modal Crear Nuevo Grupo -->
<div class="modal fade" id="modalNuevoGrupo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-layer-group mr-2"></i> Crear Grupo en esta Disciplina
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?php echo BASE_URL; ?>/disciplinas/<?php echo e($torneo['id']); ?>/grupos/guardar" method="post">
                <?php echo CsrfHelper::field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_grupo">Nombre del Grupo <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_grupo" id="nombre_grupo" class="form-control" placeholder="Ej. Grupo A, Grupo B, Grupo C..." required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="orden">Orden de Visualización</label>
                        <input type="number" name="orden" id="orden" class="form-control" value="<?php echo count($grupos) + 1; ?>" min="1">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning font-weight-bold text-dark">
                        <i class="fas fa-save mr-1"></i> Guardar Grupo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
