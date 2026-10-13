<?php
/**
 * Boletín Disciplinario y Control de Sanciones
 *
 * @var array $sanciones
 * @var array $torneos
 * @var array $estadisticas
 * @var int|null $filtroTorneo
 * @var string|null $filtroEstado
 * @var string|null $filtroTipo
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Boletín Disciplinario y Control de Sanciones
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/sanciones/crear" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-plus mr-1"></i> Registrar Sanción
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Métricas KPI Rápidas del Tribunal Disciplinario -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-light shadow-sm border">
                    <div class="inner">
                        <h3 class="text-dark"><?php echo (int)($estadisticas['total'] ?? 0); ?></h3>
                        <p class="text-muted mb-0 font-weight-bold">Total Sanciones Registradas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-gavel text-secondary"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-light shadow-sm border border-danger">
                    <div class="inner">
                        <h3 class="text-danger"><?php echo (int)($estadisticas['activas'] ?? 0); ?></h3>
                        <p class="text-muted mb-0 font-weight-bold">Sanciones Activas (Inhabilitados)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-ban text-danger" style="opacity: 0.3;"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-light shadow-sm border border-primary">
                    <div class="inner">
                        <h3 class="text-primary"><?php echo (int)($estadisticas['levantadas'] ?? 0); ?></h3>
                        <p class="text-muted mb-0 font-weight-bold">Sanciones Levantadas (Indultos)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-undo-alt text-primary" style="opacity: 0.3;"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-light shadow-sm border border-success">
                    <div class="inner">
                        <h3 class="text-success"><?php echo (int)($estadisticas['cumplidas'] ?? 0); ?></h3>
                        <p class="text-muted mb-0 font-weight-bold">Sanciones Cumplidas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle text-success" style="opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros de Consulta -->
        <div class="card card-cosmol shadow-sm mb-4">
            <div class="card-header py-2">
                <h3 class="card-title font-weight-bold mb-0">
                    <i class="fas fa-filter text-primary mr-1"></i> Filtrar Expedientes Disciplinarios
                </h3>
            </div>
            <div class="card-body py-3">
                <form action="<?php echo BASE_URL; ?>/sanciones" method="get" class="row align-items-end">
                    
                    <div class="col-md-4 form-group mb-2">
                        <label for="torneo_id" class="text-xs font-weight-bold text-muted text-uppercase mb-1">Torneo / Disciplina</label>
                        <select name="torneo_id" id="torneo_id" class="form-control form-control-sm">
                            <option value="">-- Todos los Torneos --</option>
                            <?php foreach ($torneos as $t): ?>
                                <option value="<?php echo e($t['id']); ?>" <?php echo ($filtroTorneo == $t['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($t['nombre']); ?> (<?php echo e($t['disciplina_nombre']); ?> - <?php echo e($t['rama']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3 form-group mb-2">
                        <label for="estado" class="text-xs font-weight-bold text-muted text-uppercase mb-1">Estado de la Sanción</label>
                        <select name="estado" id="estado" class="form-control form-control-sm">
                            <option value="">-- Todos los Estados --</option>
                            <option value="Activa" <?php echo ($filtroEstado === 'Activa') ? 'selected' : ''; ?>>Activa (Inhabilitado)</option>
                            <option value="Levantada" <?php echo ($filtroEstado === 'Levantada') ? 'selected' : ''; ?>>Levantada (Anulada/Indulto)</option>
                            <option value="Cumplida" <?php echo ($filtroEstado === 'Cumplida') ? 'selected' : ''; ?>>Cumplida</option>
                        </select>
                    </div>

                    <div class="col-md-3 form-group mb-2">
                        <label for="tipo" class="text-xs font-weight-bold text-muted text-uppercase mb-1">Tipo de Falta / Tarjeta</label>
                        <select name="tipo" id="tipo" class="form-control form-control-sm">
                            <option value="">-- Todos los Tipos --</option>
                            <option value="ROJA" <?php echo ($filtroTipo === 'ROJA') ? 'selected' : ''; ?>>Tarjeta Roja</option>
                            <option value="AMARILLA" <?php echo ($filtroTipo === 'AMARILLA') ? 'selected' : ''; ?>>Tarjeta Amarilla</option>
                            <option value="PARTIDOS_SUSPENSION" <?php echo ($filtroTipo === 'PARTIDOS_SUSPENSION') ? 'selected' : ''; ?>>Suspensión por Fechas</option>
                            <option value="EXPULSION" <?php echo ($filtroTipo === 'EXPULSION') ? 'selected' : ''; ?>>Expulsión del Torneo</option>
                        </select>
                    </div>

                    <div class="col-md-2 form-group mb-2 text-right">
                        <button type="submit" class="btn btn-sm btn-primary font-weight-bold mr-1">
                            <i class="fas fa-search mr-1"></i> Filtrar
                        </button>
                        <a href="<?php echo BASE_URL; ?>/sanciones" class="btn btn-sm btn-outline-secondary font-weight-bold">
                            Limpiar
                        </a>
                    </div>

                </form>
            </div>
        </div>

        <!-- Tabla del Boletín Disciplinario -->
        <div class="card card-cosmol shadow-sm mb-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold mb-0">
                    <i class="fas fa-clipboard-list text-primary mr-1"></i> Expedientes Registrados (<?php echo count($sanciones); ?>)
                </h3>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-cosmol">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Atleta Sancionado</th>
                            <th>Equipo & Delegación</th>
                            <th>Torneo & Disciplina</th>
                            <th class="text-center">Tipo Sanción</th>
                            <th class="text-center">Fechas / Suspensión</th>
                            <th class="text-center">Estado</th>
                            <th class="text-right" style="width: 220px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sanciones)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fas fa-check-double fa-2x mb-2 d-block text-success"></i>
                                    No se encontraron sanciones registradas bajo los criterios seleccionados.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($sanciones as $s): ?>
                                <tr>
                                    <td class="align-middle font-weight-bold text-muted">
                                        #<?php echo e($s['id']); ?>
                                    </td>

                                    <td class="align-middle">
                                        <div class="font-weight-bold text-dark">
                                            <a href="<?php echo BASE_URL; ?>/sanciones/<?php echo e($s['id']); ?>" class="text-dark">
                                                <?php echo e($s['jugador_nombres']); ?> <?php echo e($s['jugador_apellidos']); ?>
                                            </a>
                                        </div>
                                        <small class="text-muted">
                                            Dorsal #<?php echo e($s['jugador_dorsal']); ?> &bull; CI: <?php echo e($s['jugador_documento']); ?>
                                        </small>
                                    </td>

                                    <td class="align-middle">
                                        <div class="font-weight-bold text-dark text-sm"><?php echo e($s['equipo_nombre']); ?></div>
                                        <span class="badge" style="background-color: <?php echo e($s['color_representativo'] ?? '#0066B2'); ?>22; color: <?php echo e($s['color_representativo'] ?? '#0066B2'); ?>; border: 1px solid <?php echo e($s['color_representativo'] ?? '#0066B2'); ?>55;">
                                            <?php echo e($s['delegacion_nombre']); ?>
                                        </span>
                                    </td>

                                    <td class="align-middle">
                                        <div class="text-sm font-weight-bold text-dark"><?php echo e($s['torneo_nombre']); ?></div>
                                        <small class="text-muted"><?php echo e($s['disciplina_nombre']); ?> (<?php echo e($s['torneo_rama']); ?>)</small>
                                    </td>

                                    <td class="text-center align-middle">
                                        <?php
                                        switch ($s['tipo_sancion']) {
                                            case 'ROJA':
                                                echo '<span class="badge badge-danger px-2 py-1"><i class="fas fa-square mr-1"></i>Tarjeta Roja</span>';
                                                break;
                                            case 'AMARILLA':
                                                echo '<span class="badge badge-warning px-2 py-1"><i class="fas fa-square mr-1"></i>Tarjeta Amarilla</span>';
                                                break;
                                            case 'PARTIDOS_SUSPENSION':
                                                echo '<span class="badge badge-danger px-2 py-1"><i class="fas fa-calendar-times mr-1"></i>Suspensión</span>';
                                                break;
                                            case 'EXPULSION':
                                                echo '<span class="badge badge-dark px-2 py-1"><i class="fas fa-ban mr-1"></i>Expulsión</span>';
                                                break;
                                            default:
                                                echo '<span class="badge badge-secondary px-2 py-1">' . e($s['tipo_sancion']) . '</span>';
                                        }
                                        ?>
                                    </td>

                                    <td class="text-center align-middle">
                                        <?php if ($s['tipo_sancion'] === 'AMARILLA' && (int)$s['partidos_suspension'] === 0): ?>
                                            <span class="text-muted text-xs">Amonestación</span>
                                        <?php elseif ($s['tipo_sancion'] === 'EXPULSION'): ?>
                                            <span class="text-danger font-weight-bold text-xs">Definitiva</span>
                                        <?php else: ?>
                                            <span class="font-weight-bold text-dark">
                                                <?php echo (int)$s['partidos_cumplidos']; ?> / <?php echo (int)$s['partidos_suspension']; ?>
                                            </span>
                                            <small class="d-block text-muted text-xs">fechas</small>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center align-middle">
                                        <?php if ($s['estado'] === 'Activa'): ?>
                                            <span class="badge badge-danger px-2 py-1">Activa</span>
                                        <?php elseif ($s['estado'] === 'Levantada'): ?>
                                            <span class="badge badge-primary px-2 py-1">Levantada</span>
                                        <?php else: ?>
                                            <span class="badge badge-success px-2 py-1">Cumplida</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-right align-middle">
                                        <a href="<?php echo BASE_URL; ?>/sanciones/<?php echo e($s['id']); ?>" class="btn btn-sm btn-outline-primary" title="Ver Expediente">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <?php if (AuthHelper::isAdmin()): ?>
                                            <?php if ($s['estado'] === 'Activa'): ?>
                                                <!-- Botón para abrir modal de Levantar Sanción -->
                                                <button type="button" class="btn btn-sm btn-outline-warning font-weight-bold" data-toggle="modal" data-target="#modalLevantar_<?php echo e($s['id']); ?>" title="Levantar / Quitar Sanción">
                                                    <i class="fas fa-undo-alt mr-1"></i> Levantar
                                                </button>

                                                <!-- Modal Levantar Sanción -->
                                                <div class="modal fade text-left" id="modalLevantar_<?php echo e($s['id']); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content border-0 shadow">
                                                            <div class="modal-header bg-warning text-dark">
                                                                <h5 class="modal-title font-weight-bold">
                                                                    <i class="fas fa-balance-scale mr-1"></i> Levantar / Anular Sanción #<?php echo e($s['id']); ?>
                                                                </h5>
                                                                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Cerrar">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="<?php echo BASE_URL; ?>/sanciones/<?php echo e($s['id']); ?>/levantar" method="post">
                                                                <?php echo CsrfHelper::field(); ?>
                                                                <input type="hidden" name="volver_a_listado" value="1">
                                                                
                                                                <div class="modal-body">
                                                                    <div class="callout callout-info mb-3">
                                                                        <h6 class="font-weight-bold mb-1">
                                                                            Atleta: <?php echo e($s['jugador_nombres']); ?> <?php echo e($s['jugador_apellidos']); ?>
                                                                        </h6>
                                                                        <p class="text-xs text-muted mb-0">
                                                                            Equipo: <strong><?php echo e($s['equipo_nombre']); ?></strong> &bull; Sanción: <strong><?php echo e($s['tipo_sancion']); ?></strong>
                                                                        </p>
                                                                    </div>

                                                                    <p class="text-sm text-dark mb-3">
                                                                        Al levantar esta sanción, el atleta <strong>recuperará inmediatamente su estado Habilitado</strong> (salvo que mantenga otras sanciones activas) y quedará registrada la justificación en el expediente de auditoría.
                                                                    </p>

                                                                    <div class="form-group">
                                                                        <label for="tipo_resolucion_<?php echo e($s['id']); ?>" class="font-weight-bold text-dark text-xs text-uppercase">
                                                                            Tipo de Resolución <span class="text-danger">*</span>
                                                                        </label>
                                                                        <select name="tipo_resolucion" id="tipo_resolucion_<?php echo e($s['id']); ?>" class="form-control form-control-sm" required>
                                                                            <option value="Apelación aprobada">Apelación aprobada por el Comité</option>
                                                                            <option value="Error arbitral corregido">Error arbitral corregido (identidad errónea)</option>
                                                                            <option value="Indulto reglamentario">Indulto / Cumplimiento anticipado</option>
                                                                            <option value="Resolución de Mesa de Control">Resolución de Mesa de Control</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group mb-0">
                                                                        <label for="motivo_levantamiento_<?php echo e($s['id']); ?>" class="font-weight-bold text-dark text-xs text-uppercase">
                                                                            Motivo y Justificación <span class="text-danger">*</span>
                                                                        </label>
                                                                        <textarea name="motivo_levantamiento" id="motivo_levantamiento_<?php echo e($s['id']); ?>" rows="3" class="form-control form-control-sm" placeholder="Detalle el sustento de la resolución, número de acta o informe arbitral..." required minlength="5"></textarea>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer bg-light py-2">
                                                                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                    <button type="submit" class="btn btn-sm btn-warning font-weight-bold">
                                                                        <i class="fas fa-check mr-1"></i> Confirmar Levantamiento
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Formulario de eliminación -->
                                            <form action="<?php echo BASE_URL; ?>/sanciones/<?php echo e($s['id']); ?>/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar definitivamente esta sanción del registro?');">
                                                <?php echo CsrfHelper::field(); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar registro">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
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
