<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Nómina Oficial de Atletas
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/jugadores/crear" class="btn btn-primary font-weight-bold">
                        Inscribir Atleta
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
                <form action="<?php echo BASE_URL; ?>/jugadores" method="get" class="form-row align-items-center">
                    <div class="col-md-4 my-1">
                        <label class="sr-only">Equipo</label>
                        <select name="equipo_id" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Todos los Equipos</option>
                            <?php foreach ($equipos as $eq): ?>
                                <option value="<?php echo e($eq['id']); ?>" <?php echo ($filtroEquipo == $eq['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($eq['nombre']); ?> (<?php echo e($eq['delegacion_nombre']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4 my-1">
                        <label class="sr-only">Torneo</label>
                        <select name="torneo_id" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Todas las Disciplinas</option>
                            <?php foreach ($torneos as $tor): ?>
                                <option value="<?php echo e($tor['id']); ?>" <?php echo ($filtroTorneo == $tor['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($tor['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2 my-1">
                        <label class="sr-only">Estado</label>
                        <select name="estado" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Todos los Estados</option>
                            <option value="Habilitado" <?php echo ($filtroEstado === 'Habilitado') ? 'selected' : ''; ?>>Habilitado</option>
                            <option value="Suspendido" <?php echo ($filtroEstado === 'Suspendido') ? 'selected' : ''; ?>>Suspendido</option>
                            <option value="Inactivo" <?php echo ($filtroEstado === 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                    </div>

                    <div class="col-md-2 my-1 text-right">
                        <?php if ($filtroEquipo || $filtroTorneo || $filtroEstado): ?>
                            <a href="<?php echo BASE_URL; ?>/jugadores" class="btn btn-sm btn-outline-secondary btn-block">Limpiar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-id-card mr-1 text-primary"></i>Deportistas Inscritos
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 60px;" class="text-center">Dorsal</th>
                            <th>Atleta</th>
                            <th>Equipo y Delegación</th>
                            <th>Disciplina</th>
                            <th>Posición</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center text-nowrap" style="width: 240px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($jugadores)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-user-slash fa-2x mb-2 text-secondary d-block"></i>
                                    No se encontraron atletas registrados con los criterios seleccionados.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($jugadores as $j): ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge badge-dark font-weight-bold px-2 py-1" style="font-size: 1.1rem; min-width: 36px;">
                                            #<?php echo e($j['dorsal']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/jugadores/<?php echo e($j['id']); ?>" class="font-weight-bold text-dark d-block">
                                            <?php echo e($j['nombres']); ?> <?php echo e($j['apellidos']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($j['equipo_id']); ?>/jugadores" class="font-weight-bold text-primary">
                                            <?php echo e($j['equipo_nombre']); ?>
                                        </a>
                                        <span class="badge ml-1" style="background-color: <?php echo e($j['color_representativo']); ?>; color: #fff;">
                                            <?php echo e($j['delegacion_nombre']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-sm font-weight-bold">
                                            <i class="fas fa-<?php echo e(!empty($j['disciplina_icono']) ? $j['disciplina_icono'] : 'trophy'); ?> text-warning mr-1"></i>
                                            <?php echo e($j['torneo_nombre']); ?>
                                        </div>
                                        <small class="text-muted"><?php echo e($j['torneo_rama']); ?></small>
                                    </td>
                                    <td>
                                        <span class="text-sm">
                                            <?php echo !empty($j['posicion']) ? e($j['posicion']) : '<span class="text-muted font-italic">No especificada</span>'; ?>
                                        </span>
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
                                    <td class="text-center text-nowrap">
                                        <div class="d-inline-flex align-items-center justify-content-center" style="gap: 8px;">
                                            <a href="<?php echo BASE_URL; ?>/jugadores/<?php echo e($j['id']); ?>" class="btn btn-xs btn-outline-primary font-weight-bold px-2 py-1" title="Ver Ficha">
                                                Perfil
                                            </a>
                                            <?php if (AuthHelper::isAdmin()): ?>
                                                <a href="<?php echo BASE_URL; ?>/jugadores/<?php echo e($j['id']); ?>/editar" class="btn btn-xs btn-info font-weight-bold px-2 py-1" title="Editar">
                                                    Editar
                                                </a>
                                                <button type="button" class="btn btn-xs btn-danger font-weight-bold px-2 py-1" data-toggle="modal" data-target="#modalDeleteJug<?php echo e($j['id']); ?>" title="Eliminar">
                                                    Eliminar
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (AuthHelper::isAdmin()): ?>
                                            <!-- Modal Confirmar Eliminación -->
                                            <div class="modal fade" id="modalDeleteJug<?php echo e($j['id']); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title font-weight-bold">
                                                                <i class="fas fa-exclamation-triangle mr-2"></i> Retirar Atleta
                                                            </h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body text-left">
                                                            <p>¿Está seguro de retirar de la nómina a <strong><?php echo e($j['nombres']); ?> <?php echo e($j['apellidos']); ?></strong> (Dorsal #<?php echo e($j['dorsal']); ?>)?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                            <form action="<?php echo BASE_URL; ?>/jugadores/<?php echo e($j['id']); ?>/eliminar" method="post" class="d-inline">
                                                                <?php echo CsrfHelper::field(); ?>
                                                                <button type="submit" class="btn btn-danger font-weight-bold">Sí, Retirar</button>
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
