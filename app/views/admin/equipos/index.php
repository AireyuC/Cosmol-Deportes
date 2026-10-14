<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Equipos Deportivos
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/equipos/crear" class="btn btn-primary font-weight-bold">
                        Registrar Equipo
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Barra de Filtros -->
        <div class="card card-outline shadow-sm mb-3">
            <div class="card-body py-2">
                <form action="<?php echo BASE_URL; ?>/equipos" method="get" class="form-row align-items-center">
                    <div class="col-md-5 my-1">
                        <label class="sr-only">Torneo</label>
                        <select name="torneo_id" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Todas las Disciplinas</option>
                            <?php foreach ($torneos as $tor): ?>
                                <option value="<?php echo e($tor['id']); ?>" <?php echo ($filtroTorneo == $tor['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($tor['nombre']); ?> (<?php echo e($tor['disciplina_nombre']); ?> - <?php echo e($tor['rama']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5 my-1">
                        <label class="sr-only">Delegación</label>
                        <select name="delegacion_id" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Todas las Delegaciones</option>
                            <?php foreach ($delegaciones as $del): ?>
                                <option value="<?php echo e($del['id']); ?>" <?php echo ($filtroDelegacion == $del['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($del['nombre']); ?> (<?php echo e($del['alias']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 my-1 text-right">
                        <?php if ($filtroTorneo || $filtroDelegacion): ?>
                            <a href="<?php echo BASE_URL; ?>/equipos" class="btn btn-sm btn-outline-secondary btn-block">Limpiar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-outline card-cosmol shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-list mr-1 text-cosmol"></i> Directorio de Equipos
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Equipo</th>
                            <th>Delegación</th>
                            <th>Disciplina</th>
                            <th class="text-center">Grupo</th>
                            <th class="text-center">Plantilla</th>
                            <th>Delegado / Contacto</th>
                            <th class="text-center" style="width: 170px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($equipos)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-shield-alt fa-2x mb-2 text-secondary d-block"></i>
                                    No se encontraron equipos registrados con los filtros aplicados.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($equipos as $eq): ?>
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">
                                            <?php echo e($eq['nombre']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-dark">
                                            <?php echo e($eq['delegacion_nombre']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-sm font-weight-bold text-dark">
                                            <i class="fas fa-<?php echo e(!empty($eq['disciplina_icono']) ? $eq['disciplina_icono'] : 'trophy'); ?> text-warning mr-1"></i>
                                            <?php echo e($eq['torneo_nombre']); ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!empty($eq['nombre_grupo'])): ?>
                                            <span class="badge badge-primary px-2 py-1 font-weight-bold">
                                                <?php echo e($eq['nombre_grupo']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary px-2 py-1">Sin Asignar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/jugadores" class="btn btn-sm btn-outline-info font-weight-bold" title="Gestionar Atletas">
                                            <?php echo e($eq['total_jugadores']); ?> atletas
                                        </a>
                                    </td>
                                    <td>
                                        <div class="text-sm font-weight-bold">
                                            <?php echo !empty($eq['delegado_contacto']) ? e($eq['delegado_contacto']) : '<span class="text-muted font-italic">No asignado</span>'; ?>
                                        </div>
                                        <?php if (!empty($eq['telefono_contacto'])): ?>
                                            <?php echo e($eq['telefono_contacto']); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/jugadores" class="btn btn-xs btn-info" title="Ver Plantilla de Jugadores">
                                            Nómina
                                        </a>
                                        <?php if (AuthHelper::isAdmin()): ?>
                                            <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/editar" class="btn btn-xs btn-primary ml-1" title="Editar">
                                                Editar
                                            </a>
                                            <button type="button" class="btn btn-xs btn-danger ml-1" data-toggle="modal" data-target="#modalDeleteEq<?php echo e($eq['id']); ?>" title="Eliminar">
                                                Eliminar
                                            </button>

                                            <!-- Modal Confirmar Eliminación -->
                                            <div class="modal fade" id="modalDeleteEq<?php echo e($eq['id']); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title font-weight-bold">
                                                                <i class="fas fa-exclamation-triangle mr-2"></i> Eliminar Equipo
                                                            </h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            <p>¿Está seguro de eliminar al equipo <strong><?php echo e($eq['nombre']); ?></strong>?</p>
                                                            <p class="text-sm text-danger mb-0">Esta acción eliminará la nómina de jugadores y registros del equipo en este torneo.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                            <form action="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/eliminar" method="post" class="d-inline">
                                                                <?php echo CsrfHelper::field(); ?>
                                                                <button type="submit" class="btn btn-danger font-weight-bold">Sí, Eliminar</button>
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
