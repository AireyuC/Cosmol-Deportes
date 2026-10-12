<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-7">
                <h1 class="m-0 font-weight-bold text-dark">
                    <i class="fas fa-users text-primary mr-2"></i> Plantilla: <?php echo e($equipo['nombre']); ?>
                </h1>
                <div class="text-muted text-sm mt-1">
                    <span class="badge px-2 py-1 mr-2" style="background-color: <?php echo e($equipo['color_representativo']); ?>; color: #fff;">
                        <?php echo e($equipo['delegacion_nombre']); ?>
                    </span>
                    Torneo: <strong><?php echo e($equipo['torneo_nombre']); ?></strong> &bull; <?php echo e($equipo['disciplina_nombre']); ?> (<?php echo e($equipo['torneo_rama']); ?>)
                </div>
            </div>
            <div class="col-sm-5 text-right pt-2">
                <a href="<?php echo BASE_URL; ?>/torneos/<?php echo e($equipo['torneo_id']); ?>" class="btn btn-outline-secondary mr-1">
                    <i class="fas fa-sitemap mr-1"></i> Ver Torneo
                </a>
                <a href="<?php echo BASE_URL; ?>/equipos" class="btn btn-secondary mr-1">
                    <i class="fas fa-arrow-left mr-1"></i> Equipos
                </a>
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/jugadores/crear?equipo_id=<?php echo e($equipo['id']); ?>" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-user-plus mr-1"></i> Inscribir Atleta
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Ficha Resumen del Equipo -->
        <div class="card card-outline card-primary shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="row">
                    <div class="col-md-3 mb-2 mb-md-0">
                        <span class="text-muted d-block text-xs font-weight-bold text-uppercase">Grupo Asignado</span>
                        <span class="font-weight-bold text-dark">
                            <?php echo !empty($equipo['nombre_grupo']) ? e($equipo['nombre_grupo']) : '<span class="badge badge-secondary">Sin Grupo</span>'; ?>
                        </span>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <span class="text-muted d-block text-xs font-weight-bold text-uppercase">Uniforme / Colores</span>
                        <span class="text-dark">
                            <i class="fas fa-tshirt mr-1"></i> <?php echo !empty($equipo['color_indumentaria']) ? e($equipo['color_indumentaria']) : 'No especificado'; ?>
                        </span>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <span class="text-muted d-block text-xs font-weight-bold text-uppercase">Delegado Responsable</span>
                        <span class="text-dark font-weight-bold">
                            <?php echo !empty($equipo['delegado_contacto']) ? e($equipo['delegado_contacto']) : 'No asignado'; ?>
                        </span>
                        <?php if (!empty($equipo['telefono_contacto'])): ?>
                            <small class="text-muted d-block"><i class="fas fa-phone mr-1"></i> <?php echo e($equipo['telefono_contacto']); ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3 text-md-right mb-2 mb-md-0">
                        <span class="text-muted d-block text-xs font-weight-bold text-uppercase">Total Inscritos</span>
                        <span class="badge badge-primary px-3 py-1 font-weight-bold" style="font-size: 1rem;">
                            <i class="fas fa-users mr-1"></i> <?php echo count($jugadores); ?> Atletas
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Jugadores de la Plantilla -->
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-clipboard-list mr-1 text-primary"></i> Nómina Oficial de Jugadores Habilitados
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 70px;" class="text-center">Dorsal</th>
                            <th>Nombres y Apellidos</th>
                            <th>Documento de Identidad</th>
                            <th>Posición</th>
                            <th>Fecha Nacimiento</th>
                            <th class="text-center">Estado</th>
                            <?php if (AuthHelper::isAdmin()): ?>
                                <th class="text-center" style="width: 200px;">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($jugadores)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-user-plus fa-3x mb-2 text-secondary d-block"></i>
                                    Este equipo aún no tiene atletas inscritos en su nómina.
                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <div class="mt-2">
                                            <a href="<?php echo BASE_URL; ?>/jugadores/crear?equipo_id=<?php echo e($equipo['id']); ?>" class="btn btn-sm btn-primary font-weight-bold">
                                                <i class="fas fa-plus mr-1"></i> Inscribir Primer Atleta
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($jugadores as $j): ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge badge-dark font-weight-bold px-3 py-2" style="font-size: 1.15rem; min-width: 44px;">
                                            #<?php echo e($j['dorsal']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/jugadores/<?php echo e($j['id']); ?>" class="font-weight-bold text-dark d-block">
                                            <?php echo e($j['nombres']); ?> <?php echo e($j['apellidos']); ?>
                                        </a>
                                    </td>
                                    <td class="font-weight-bold text-muted">
                                        <i class="fas fa-id-card mr-1"></i> <?php echo e($j['documento']); ?>
                                    </td>
                                    <td>
                                        <?php echo !empty($j['posicion']) ? e($j['posicion']) : '<span class="text-muted font-italic">No asignada</span>'; ?>
                                    </td>
                                    <td>
                                        <?php echo !empty($j['fecha_nacimiento']) ? e($j['fecha_nacimiento']) : '<span class="text-muted">No registrada</span>'; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($j['estado'] === 'Habilitado'): ?>
                                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Habilitado</span>
                                        <?php elseif ($j['estado'] === 'Suspendido'): ?>
                                            <span class="badge badge-danger px-2 py-1"><i class="fas fa-ban mr-1"></i> Suspendido</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary px-2 py-1"><i class="fas fa-clock mr-1"></i> Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if (AuthHelper::isAdmin()): ?>
                                        <td class="text-center">
                                            <!-- Botón Cambiar Estado Rápido -->
                                            <button type="button" class="btn btn-xs btn-outline-secondary" data-toggle="modal" data-target="#modalEstado<?php echo e($j['id']); ?>" title="Cambiar Estado">
                                                <i class="fas fa-sync-alt mr-1"></i> Estado
                                            </button>

                                            <!-- Botón Editar -->
                                            <a href="<?php echo BASE_URL; ?>/jugadores/<?php echo e($j['id']); ?>/editar" class="btn btn-xs btn-warning text-dark ml-1" title="Editar Atleta">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Botón Eliminar -->
                                            <button type="button" class="btn btn-xs btn-danger ml-1" data-toggle="modal" data-target="#modalDelJugEq<?php echo e($j['id']); ?>" title="Retirar de Nómina">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <!-- Modal Cambio de Estado -->
                                            <div class="modal fade" id="modalEstado<?php echo e($j['id']); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header bg-secondary text-white py-2">
                                                            <h6 class="modal-title font-weight-bold">Cambiar Estado</h6>
                                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <form action="<?php echo BASE_URL; ?>/jugadores/<?php echo e($j['id']); ?>/cambiar-estado" method="post">
                                                            <?php echo CsrfHelper::field(); ?>
                                                            <input type="hidden" name="equipo_id" value="<?php echo e($equipo['id']); ?>">
                                                            <div class="modal-body py-3">
                                                                <label class="text-sm font-weight-bold">Estado para <?php echo e($j['nombres']); ?>:</label>
                                                                <select name="estado" class="form-control form-control-sm font-weight-bold">
                                                                    <option value="Habilitado" <?php echo ($j['estado'] === 'Habilitado') ? 'selected' : ''; ?>>Habilitado (Puede Jugar)</option>
                                                                    <option value="Suspendido" <?php echo ($j['estado'] === 'Suspendido') ? 'selected' : ''; ?>>Suspendido (Inhabilitado)</option>
                                                                    <option value="Inactivo" <?php echo ($j['estado'] === 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                                                                </select>
                                                            </div>
                                                            <div class="modal-footer py-2">
                                                                <button type="submit" class="btn btn-sm btn-primary btn-block font-weight-bold">Guardar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Eliminar Jugador -->
                                            <div class="modal fade" id="modalDelJugEq<?php echo e($j['id']); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title font-weight-bold">
                                                                <i class="fas fa-exclamation-triangle mr-2"></i> Retirar Atleta
                                                            </h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            ¿Desea retirar a <strong><?php echo e($j['nombres']); ?> <?php echo e($j['apellidos']); ?></strong> (Dorsal #<?php echo e($j['dorsal']); ?>) de la nómina del equipo?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                            <form action="<?php echo BASE_URL; ?>/jugadores/<?php echo e($j['id']); ?>/eliminar" method="post" class="d-inline">
                                                                <?php echo CsrfHelper::field(); ?>
                                                                <input type="hidden" name="equipo_id" value="<?php echo e($equipo['id']); ?>">
                                                                <button type="submit" class="btn btn-danger font-weight-bold">Sí, Retirar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
