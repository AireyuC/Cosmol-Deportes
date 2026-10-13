<?php
/**
 * Ficha detallada de sanción disciplinaria y expediente del atleta
 *
 * @var array $sancion
 * @var array $historialJugador
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-7">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Expediente Disciplinario #<?php echo e($sancion['id']); ?>
                </h1>
                <span class="text-muted text-sm ml-2">
                    <?php echo e($sancion['jugador_nombres']); ?> <?php echo e($sancion['jugador_apellidos']); ?> &bull; <?php echo e($sancion['equipo_nombre']); ?>
                </span>
            </div>
            <div class="col-sm-5 text-right">
                <a href="<?php echo BASE_URL; ?>/sanciones" class="btn btn-secondary font-weight-bold mr-1">
                    Volver al Boletín
                </a>
                <?php if (AuthHelper::isAdmin()): ?>
                    <?php if ($sancion['estado'] === 'Activa'): ?>
                        <button type="button" class="btn btn-warning font-weight-bold mr-1" data-toggle="modal" data-target="#modalLevantarSancion">
                            <i class="fas fa-undo-alt mr-1"></i> Levantar Sanción
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <div class="row">
            <!-- Columna Izquierda: Información Detallada de la Sanción -->
            <div class="col-lg-7">
                
                <!-- Tarjeta Principal del Dictamen -->
                <div class="card card-cosmol shadow-sm mb-4">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold mb-0">
                            <i class="fas fa-gavel text-primary mr-1"></i> Dictamen y Resolución
                        </h3>
                        <div>
                            <?php if ($sancion['estado'] === 'Activa'): ?>
                                <span class="badge badge-danger px-3 py-1 font-weight-bold">Activa &bull; Inhabilitado</span>
                            <?php elseif ($sancion['estado'] === 'Levantada'): ?>
                                <span class="badge badge-primary px-3 py-1 font-weight-bold">Levantada / Anulada</span>
                            <?php else: ?>
                                <span class="badge badge-success px-3 py-1 font-weight-bold">Cumplida</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <span class="text-muted text-xs text-uppercase font-weight-bold d-block">Tipo de Infracción</span>
                                <div class="mt-1">
                                    <?php
                                    switch ($sancion['tipo_sancion']) {
                                        case 'ROJA':
                                            echo '<span class="badge badge-danger px-2 py-1"><i class="fas fa-square mr-1"></i>Tarjeta Roja</span>';
                                            break;
                                        case 'AMARILLA':
                                            echo '<span class="badge badge-warning px-2 py-1"><i class="fas fa-square mr-1"></i>Tarjeta Amarilla</span>';
                                            break;
                                        case 'PARTIDOS_SUSPENSION':
                                            echo '<span class="badge badge-danger px-2 py-1"><i class="fas fa-calendar-times mr-1"></i>Suspensión por Fechas</span>';
                                            break;
                                        case 'EXPULSION':
                                            echo '<span class="badge badge-dark px-2 py-1"><i class="fas fa-ban mr-1"></i>Expulsión Definitiva</span>';
                                            break;
                                        default:
                                            echo '<span class="badge badge-secondary px-2 py-1">' . e($sancion['tipo_sancion']) . '</span>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <span class="text-muted text-xs text-uppercase font-weight-bold d-block">Fechas de Suspensión</span>
                                <div class="font-weight-bold text-dark mt-1" style="font-size: 1.1rem;">
                                    <?php if ($sancion['tipo_sancion'] === 'AMARILLA' && (int)$sancion['partidos_suspension'] === 0): ?>
                                        <span class="text-muted text-sm">Amonestación de advertencia</span>
                                    <?php elseif ($sancion['tipo_sancion'] === 'EXPULSION'): ?>
                                        <span class="text-danger">Expulsión del Torneo</span>
                                    <?php else: ?>
                                        <span><?php echo (int)$sancion['partidos_cumplidos']; ?> cumplida(s) de <?php echo (int)$sancion['partidos_suspension']; ?> fecha(s)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <span class="text-muted text-xs text-uppercase font-weight-bold d-block">Torneo y Disciplina</span>
                                <div class="font-weight-bold text-dark mt-1">
                                    <?php echo e($sancion['torneo_nombre']); ?>
                                </div>
                                <small class="text-muted"><?php echo e($sancion['disciplina_nombre']); ?> (<?php echo e($sancion['torneo_rama']); ?>)</small>
                            </div>

                            <div class="col-sm-6">
                                <span class="text-muted text-xs text-uppercase font-weight-bold d-block">Partido de Origen</span>
                                <div class="mt-1">
                                    <?php if (!empty($sancion['partido_id'])): ?>
                                        <a href="<?php echo BASE_URL; ?>/partidos/<?php echo e($sancion['partido_id']); ?>" class="font-weight-bold text-primary">
                                            Partido #<?php echo e($sancion['partido_id']); ?> (<?php echo e($sancion['partido_jornada']); ?>)
                                        </a>
                                        <?php if (!empty($sancion['fecha_partido'])): ?>
                                            <small class="d-block text-muted">
                                                Fecha: <?php echo date('d/m/Y', strtotime($sancion['fecha_partido'])); ?>
                                            </small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted text-sm">Resolución Administrativa de Mesa</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <span class="text-muted text-xs text-uppercase font-weight-bold d-block mb-1">Motivo e Informe Oficial</span>
                            <div class="p-3 bg-light rounded text-dark text-sm border">
                                <?php echo nl2br(e($sancion['motivo'])); ?>
                            </div>
                        </div>

                        <!-- Gestión de Fechas Cumplidas si está Activa -->
                        <?php if (AuthHelper::isAdmin() && $sancion['estado'] === 'Activa' && (int)$sancion['partidos_suspension'] > 0 && $sancion['tipo_sancion'] !== 'EXPULSION'): ?>
                            <div class="p-3 rounded border border-info bg-light d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="text-dark d-block">Control de Cumplimiento de Fechas</strong>
                                    <small class="text-muted">Registre cuando el atleta cumpla una fecha sin jugar.</small>
                                </div>
                                <form action="<?php echo BASE_URL; ?>/sanciones/<?php echo e($sancion['id']); ?>/cumplir-fecha" method="post" onsubmit="return confirm('¿Registrar cumplimiento de 1 fecha para este atleta?');">
                                    <?php echo CsrfHelper::field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-info font-weight-bold">
                                        <i class="fas fa-check mr-1"></i> +1 Fecha Cumplida
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Bloque de Auditoría de Levantamiento (Si fue Levantada) -->
                <?php if ($sancion['estado'] === 'Levantada'): ?>
                    <div class="card card-outline card-success shadow-sm mb-4">
                        <div class="card-header py-2">
                            <h3 class="card-title font-weight-bold text-success mb-0">
                                <i class="fas fa-check-circle mr-1"></i> Auditoría de Sanción Levantada
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <span class="text-muted text-xs text-uppercase font-weight-bold d-block">Fecha y Hora de Levantamiento</span>
                                    <span class="font-weight-bold text-dark">
                                        <?php echo !empty($sancion['fecha_levantamiento']) ? date('d/m/Y H:i:s', strtotime($sancion['fecha_levantamiento'])) : 'Fecha no registrada'; ?>
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted text-xs text-uppercase font-weight-bold d-block">Autorizado Por</span>
                                    <span class="badge badge-light border text-dark font-weight-bold">
                                        <i class="fas fa-user-shield text-primary mr-1"></i>
                                        <?php echo e($sancion['levantado_por_nombre'] ?? 'Administrador General'); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span class="text-muted text-xs text-uppercase font-weight-bold d-block mb-1">Resolución y Justificación Registrada:</span>
                                <div class="p-3 bg-light rounded text-dark text-sm border-left border-success" style="border-left-width: 4px !important;">
                                    <?php echo nl2br(e($sancion['motivo_levantamiento'] ?? 'Sin detalle')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Columna Derecha: Ficha del Atleta e Historial Disciplinario -->
            <div class="col-lg-5">
                
                <!-- Ficha del Jugador -->
                <div class="card card-cosmol shadow-sm mb-4">
                    <div class="card-header py-2">
                        <h3 class="card-title font-weight-bold mb-0">
                            <i class="fas fa-user text-primary mr-1"></i> Atleta Sancionado
                        </h3>
                    </div>
                    <div class="card-body text-center py-4">
                        <div class="d-inline-block p-3 rounded-circle mb-3" style="background-color: <?php echo e($sancion['color_representativo'] ?? '#0066B2'); ?>22; border: 2px solid <?php echo e($sancion['color_representativo'] ?? '#0066B2'); ?>;">
                            <i class="fas fa-user-circle fa-4x" style="color: <?php echo e($sancion['color_representativo'] ?? '#0066B2'); ?>;"></i>
                        </div>

                        <h4 class="font-weight-bold text-dark mb-1">
                            <?php echo e($sancion['jugador_nombres']); ?> <?php echo e($sancion['jugador_apellidos']); ?>
                        </h4>
                        
                        <div class="mb-2">
                            <span class="badge badge-dark px-2 py-1">Dorsal #<?php echo e($sancion['jugador_dorsal']); ?></span>
                            <span class="badge badge-light border px-2 py-1"><?php echo e($sancion['jugador_posicion'] ?? 'Atleta'); ?></span>
                            <span class="badge badge-light border px-2 py-1">CI: <?php echo e($sancion['jugador_documento']); ?></span>
                        </div>

                        <div class="mb-3">
                            <strong class="d-block text-dark"><?php echo e($sancion['equipo_nombre']); ?></strong>
                            <span class="badge" style="background-color: <?php echo e($sancion['color_representativo'] ?? '#0066B2'); ?>; color: #fff;">
                                <?php echo e($sancion['delegacion_nombre']); ?>
                            </span>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-center px-3">
                            <span class="text-muted text-sm">Estado actual en la nómina:</span>
                            <?php if ($sancion['jugador_estado'] === 'Habilitado'): ?>
                                <span class="badge badge-success px-3 py-1 font-weight-bold">Habilitado</span>
                            <?php elseif ($sancion['jugador_estado'] === 'Suspendido'): ?>
                                <span class="badge badge-danger px-3 py-1 font-weight-bold">Suspendido</span>
                            <?php else: ?>
                                <span class="badge badge-secondary px-3 py-1 font-weight-bold">Inactivo</span>
                            <?php endif; ?>
                        </div>

                        <div class="mt-3 text-right">
                            <a href="<?php echo BASE_URL; ?>/jugadores/<?php echo e($sancion['jugador_id']); ?>" class="btn btn-sm btn-outline-primary font-weight-bold">
                                Ver Ficha del Atleta
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Historial Disciplinario del Jugador en el Torneo -->
                <div class="card card-cosmol shadow-sm mb-4">
                    <div class="card-header py-2">
                        <h3 class="card-title font-weight-bold mb-0">
                            <i class="fas fa-history text-primary mr-1"></i> Historial Disciplinario del Atleta
                        </h3>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-sm table-striped mb-0 text-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Tipo</th>
                                    <th>Torneo / Partido</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($historialJugador)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Sin antecedentes disciplinarios.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($historialJugador as $h): ?>
                                        <tr class="<?php echo ($h['id'] == $sancion['id']) ? 'table-warning' : ''; ?>">
                                            <td class="align-middle font-weight-bold">
                                                <a href="<?php echo BASE_URL; ?>/sanciones/<?php echo e($h['id']); ?>">#<?php echo e($h['id']); ?></a>
                                            </td>
                                            <td class="align-middle">
                                                <?php
                                                switch ($h['tipo_sancion']) {
                                                    case 'ROJA':
                                                        echo '<span class="badge badge-danger">Roja</span>';
                                                        break;
                                                    case 'AMARILLA':
                                                        echo '<span class="badge badge-warning">Amarilla</span>';
                                                        break;
                                                    case 'PARTIDOS_SUSPENSION':
                                                        echo '<span class="badge badge-danger">Suspensión</span>';
                                                        break;
                                                    case 'EXPULSION':
                                                        echo '<span class="badge badge-dark">Expulsión</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge badge-secondary">' . e($h['tipo_sancion']) . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="align-middle">
                                                <div class="text-truncate" style="max-width: 140px;"><?php echo e($h['torneo_nombre']); ?></div>
                                                <small class="text-muted"><?php echo e($h['partido_jornada'] ?? 'Resolución'); ?></small>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php if ($h['estado'] === 'Activa'): ?>
                                                    <span class="badge badge-danger">Activa</span>
                                                <?php elseif ($h['estado'] === 'Levantada'): ?>
                                                    <span class="badge badge-primary">Levantada</span>
                                                <?php else: ?>
                                                    <span class="badge badge-success">Cumplida</span>
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

    </div>
</div>

<!-- Modal para Levantar Sanción -->
<?php if (AuthHelper::isAdmin() && $sancion['estado'] === 'Activa'): ?>
    <div class="modal fade" id="modalLevantarSancion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-undo-alt mr-1"></i> Levantar Sanción Disciplinaria #<?php echo e($sancion['id']); ?>
                    </h5>
                    <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo BASE_URL; ?>/sanciones/<?php echo e($sancion['id']); ?>/levantar" method="post">
                    <?php echo CsrfHelper::field(); ?>
                    
                    <div class="modal-body">
                        <div class="callout callout-info mb-3">
                            <h6 class="font-weight-bold mb-1">
                                Atleta: <?php echo e($sancion['jugador_nombres']); ?> <?php echo e($sancion['jugador_apellidos']); ?>
                            </h6>
                            <p class="text-xs text-muted mb-0">
                                Sanción: <strong><?php echo e($sancion['tipo_sancion']); ?></strong> &bull; Fechas pendientes: <strong><?php echo (int)$sancion['partidos_suspension'] - (int)$sancion['partidos_cumplidos']; ?></strong>
                            </p>
                        </div>

                        <p class="text-sm text-dark mb-3">
                            Esta acción <strong>anulará la sanción activa</strong> y restaurará al atleta al estado <strong>Habilitado</strong> en la nómina oficial, guardando trazabilidad legal con su usuario.
                        </p>

                        <div class="form-group">
                            <label for="tipo_resolucion" class="font-weight-bold text-dark text-xs text-uppercase">
                                Tipo de Resolución <span class="text-danger">*</span>
                            </label>
                            <select name="tipo_resolucion" id="tipo_resolucion" class="form-control form-control-sm" required>
                                <option value="Apelación aprobada">Apelación aprobada por el Comité Disciplinario</option>
                                <option value="Error arbitral corregido">Error arbitral corregido (identidad errónea)</option>
                                <option value="Indulto reglamentario">Indulto reglamentario / Cumplimiento anticipado</option>
                                <option value="Resolución de Mesa de Control">Resolución de Mesa de Control</option>
                            </select>
                        </div>

                        <div class="form-group mb-0">
                            <label for="motivo_levantamiento" class="font-weight-bold text-dark text-xs text-uppercase">
                                Justificación / Número de Resolución <span class="text-danger">*</span>
                            </label>
                            <textarea name="motivo_levantamiento" id="motivo_levantamiento" rows="3" class="form-control form-control-sm" placeholder="Detalle el sustento reglamentario, número de acta o informe arbitral..." required minlength="5"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-warning font-weight-bold">
                            <i class="fas fa-check mr-1"></i> Confirmar y Levantar Sanción
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
