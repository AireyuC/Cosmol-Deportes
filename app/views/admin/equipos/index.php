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

        <!-- Banner Informativo para Sorteos -->
        <div class="callout callout-info bg-white shadow-sm mb-3 border-left border-info" style="border-left-width: 4px !important;">
            <div class="d-flex align-items-center">
                <i class="fas fa-random fa-2x text-info mr-3"></i>
                <div>
                    <h6 class="font-weight-bold mb-1 text-dark">Asignación de Equipos por Sorteo (A2, A3, B2, B3, B4)</h6>
                    <p class="mb-0 text-sm text-muted">
                        Una vez realizado el sorteo oficial previo, haga clic en <strong>"Definir Equipo"</strong> para renombrar el equipo y asignarle su delegación (ej. SAGUAPAC, SEMAPA, SELA, etc.).
                        <strong>Todos los partidos del fixture, tablas de posiciones y vistas públicas se actualizarán de inmediato</strong> sin modificar código.
                    </p>
                </div>
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
                            <th class="text-center text-nowrap" style="width: 240px;">Acciones</th>
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
                            <?php foreach ($equipos as $eq): 
                                $esSorteo = ((int)$eq['delegacion_id'] === 10 || stripos($eq['nombre'], 'sorteo') !== false || stripos($eq['nombre'], 'definir') !== false);
                            ?>
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">
                                            <?php echo e($eq['nombre']); ?>
                                            <?php if ($esSorteo): ?>
                                                <span class="badge badge-warning text-dark font-weight-bold ml-1">
                                                    <i class="fas fa-random mr-1"></i>Pendiente Sorteo
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($eq['nombre_corto'])): ?>
                                            <small class="text-muted">Sigla: <?php echo e($eq['nombre_corto']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="text-dark">
                                            <?php if ($esSorteo && (int)$eq['delegacion_id'] === 10): ?>
                                                <span class="text-warning font-weight-bold">
                                                    <i class="fas fa-clock mr-1"></i>Por Asignar
                                                </span>
                                            <?php else: ?>
                                                <?php echo e($eq['delegacion_nombre']); ?>
                                            <?php endif; ?>
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
                                    <td class="text-center text-nowrap">
                                        <div class="d-inline-flex align-items-center justify-content-center" style="gap: 8px;">
                                            <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/jugadores" class="btn btn-xs btn-outline-primary font-weight-bold px-2 py-1" title="Ver Plantilla de Jugadores">
                                                Nómina
                                            </a>
                                            <?php if (AuthHelper::isAdmin()): ?>
                                                <?php if ($esSorteo): ?>
                                                    <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/editar" class="btn btn-xs btn-warning font-weight-bold px-2 py-1 text-dark" title="Definir quién ocupará este cupo tras el sorteo">
                                                        <i class="fas fa-edit mr-1"></i>Definir Equipo
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?php echo BASE_URL; ?>/equipos/<?php echo e($eq['id']); ?>/editar" class="btn btn-xs btn-info font-weight-bold px-2 py-1" title="Editar">
                                                        Editar
                                                    </a>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-xs btn-danger font-weight-bold px-2 py-1" data-toggle="modal" data-target="#modalDeleteEq<?php echo e($eq['id']); ?>" title="Eliminar">
                                                    Eliminar
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (AuthHelper::isAdmin()): ?>
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
