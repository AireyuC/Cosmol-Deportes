<?php
/**
 * Vista de Fixture y Programación de Partidos
 *
 * @var array $partidos
 * @var array $torneos
 * @var array $estadisticas
 * @var int|null $filtroTorneo
 * @var string|null $filtroEstado
 * @var string|null $filtroFecha
 * @var string|null $filtroJornada
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Fixture y Programación de Partidos
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (AuthHelper::isAdmin()): ?>
                    <a href="<?php echo BASE_URL; ?>/partidos/crear<?php echo $filtroTorneo ? '?torneo_id=' . $filtroTorneo : ''; ?>" class="btn btn-primary font-weight-bold">
                        Programar Partido
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Métricas Rápidas del Fixture -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-white shadow-sm border">
                    <div class="inner">
                        <h3 class="font-weight-bold text-dark"><?php echo e($estadisticas['total']); ?></h3>
                        <p class="text-muted mb-0">Total Encuentros</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt text-muted" style="opacity: 0.25;"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-white shadow-sm border">
                    <div class="inner">
                        <h3 class="font-weight-bold text-info"><?php echo e($estadisticas['programados']); ?></h3>
                        <p class="text-muted mb-0">Por Disputar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock text-info" style="opacity: 0.25;"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-white shadow-sm border">
                    <div class="inner">
                        <h3 class="font-weight-bold" style="color: #00A3E0;"><?php echo e($estadisticas['en_juego']); ?></h3>
                        <p class="text-muted mb-0">En Juego</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-play text-primary" style="opacity: 0.25;"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-white shadow-sm border">
                    <div class="inner">
                        <h3 class="font-weight-bold text-success"><?php echo e($estadisticas['finalizados']); ?></h3>
                        <p class="text-muted mb-0">Finalizados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle text-success" style="opacity: 0.25;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros de Búsqueda -->
        <div class="card shadow-sm mb-4">
            <div class="card-header py-2">
                <h3 class="card-title text-sm font-weight-bold text-dark mb-0">
                    <i class="fas fa-filter text-muted mr-1"></i> Filtrar Cronogramas
                </h3>
            </div>
            <div class="card-body py-2">
                <form action="<?php echo BASE_URL; ?>/partidos" method="get" class="form-row align-items-end">
                    <div class="form-group col-md-3 mb-2">
                        <label class="text-xs font-weight-bold text-muted mb-1">Torneo</label>
                        <select name="torneo_id" class="form-control form-control-sm">
                            <option value="">Todos los Torneos</option>
                            <?php foreach ($torneos as $t): ?>
                                <option value="<?php echo e($t['id']); ?>" <?php echo ($filtroTorneo == $t['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($t['nombre']); ?> (<?php echo e($t['disciplina_nombre']); ?> - <?php echo e($t['rama']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3 mb-2">
                        <label class="text-xs font-weight-bold text-muted mb-1">Estado</label>
                        <select name="estado" class="form-control form-control-sm">
                            <option value="">Todos los Estados</option>
                            <option value="Programado" <?php echo ($filtroEstado === 'Programado') ? 'selected' : ''; ?>>Programado</option>
                            <option value="En_Juego" <?php echo ($filtroEstado === 'En_Juego') ? 'selected' : ''; ?>>En Juego</option>
                            <option value="Finalizado" <?php echo ($filtroEstado === 'Finalizado') ? 'selected' : ''; ?>>Finalizado</option>
                            <option value="Suspendido" <?php echo ($filtroEstado === 'Suspendido') ? 'selected' : ''; ?>>Suspendido</option>
                            <option value="WO" <?php echo ($filtroEstado === 'WO') ? 'selected' : ''; ?>>W.O.</option>
                        </select>
                    </div>

                    <div class="form-group col-md-2 mb-2">
                        <label class="text-xs font-weight-bold text-muted mb-1">Fecha</label>
                        <input type="date" name="fecha" class="form-control form-control-sm" value="<?php echo e($filtroFecha ?? ''); ?>">
                    </div>

                    <div class="form-group col-md-2 mb-2">
                        <label class="text-xs font-weight-bold text-muted mb-1">Jornada</label>
                        <input type="text" name="jornada" class="form-control form-control-sm" placeholder="Ej. Jornada 1" value="<?php echo e($filtroJornada ?? ''); ?>">
                    </div>

                    <div class="form-group col-md-2 mb-2 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-search mr-1"></i> Filtrar
                        </button>
                        <a href="<?php echo BASE_URL; ?>/partidos" class="btn btn-sm btn-outline-secondary ml-1" title="Limpiar Filtros">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de Encuentros -->
        <div class="card card-cosmol shadow-sm mb-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold mb-0">Encuentros Deportivos</h3>
                <span class="badge badge-light border text-muted">
                    <?php echo count($partidos); ?> registrados
                </span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-cosmol">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 140px;">Fecha y Hora</th>
                            <th>Torneo y Disciplina</th>
                            <th>Fase / Jornada</th>
                            <th class="text-center" style="min-width: 260px;">Encuentro</th>
                            <th>Sede y Cancha</th>
                            <th class="text-center" style="width: 120px;">Marcador</th>
                            <th class="text-center" style="width: 110px;">Estado</th>
                            <th class="text-center text-nowrap" style="width: 270px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($partidos)): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="fas fa-calendar-times fa-2x mb-2 d-block text-secondary"></i>
                                    No se encontraron partidos programados con los criterios seleccionados.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($partidos as $p): ?>
                                <tr>
                                    <td class="text-secondary font-weight-bold align-middle">
                                        #<?php echo e($p['id']); ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php if (!empty($p['fecha_partido'])): ?>
                                            <div class="font-weight-bold text-dark text-sm">
                                                <?php echo date('d/m/Y', strtotime($p['fecha_partido'])); ?>
                                            </div>
                                            <div class="text-muted text-xs">
                                                <i class="far fa-clock mr-1"></i>
                                                <?php echo !empty($p['hora_partido']) ? substr($p['hora_partido'], 0, 5) : 'Por definir'; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted text-xs italic">Fecha por definir</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle">
                                        <div class="font-weight-bold text-dark text-sm">
                                            <?php echo e($p['torneo_nombre']); ?>
                                        </div>
                                        <div class="text-muted text-xs">
                                            <?php echo e($p['disciplina_nombre']); ?> &bull; <?php echo e($p['torneo_rama']); ?>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge badge-light border text-secondary font-weight-normal px-2 py-1">
                                            <?php echo e($p['fase']); ?>
                                        </span>
                                        <div class="text-muted text-xs mt-1">
                                            <?php echo e($p['jornada']); ?>
                                            <?php if (!empty($p['nombre_grupo'])): ?>
                                                &bull; <?php echo e($p['nombre_grupo']); ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <?php 
                                            $nomDiscMatch = mb_strtolower(($p['torneo_nombre'] ?? '') . ' ' . ($p['disciplina_nombre'] ?? ''), 'UTF-8');
                                            $esColectivoMatch = (
                                                strpos($nomDiscMatch, 'populares') !== false || 
                                                strpos($nomDiscMatch, 'tradicionales') !== false ||
                                                strpos($nomDiscMatch, 'karaoke') !== false ||
                                                strpos($nomDiscMatch, 'maratón') !== false ||
                                                strpos($nomDiscMatch, 'maraton') !== false
                                            );
                                        ?>
                                        <?php if ($esColectivoMatch): ?>
                                            <?php
                                                $badgeIcon = 'fa-users';
                                                $subtext = 'Competencia simultánea &bull; Prueba única';
                                                if (strpos($nomDiscMatch, 'karaoke') !== false) {
                                                    $badgeIcon = 'fa-microphone-alt';
                                                    $subtext = 'Presentación general &bull; Noche de Talentos';
                                                } elseif (strpos($nomDiscMatch, 'maratón') !== false || strpos($nomDiscMatch, 'maraton') !== false) {
                                                    $badgeIcon = 'fa-running';
                                                    $subtext = 'Carrera general &bull; Pedestre 5K';
                                                }
                                            ?>
                                            <div class="badge badge-info px-3 py-2 text-sm shadow-sm" style="border-radius: 8px;">
                                                <i class="fas <?php echo $badgeIcon; ?> mr-1"></i> Todas las Delegaciones
                                            </div>
                                            <div class="text-xs text-muted mt-1 font-italic">
                                                <?php echo $subtext; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <span class="font-weight-bold text-right" style="flex: 1; color: <?php echo e($p['local_color'] ?? '#1E293B'); ?>;">
                                                    <?php echo e($p['local_nombre']); ?>
                                                </span>
                                                <span class="badge badge-light border mx-2 px-2 py-1 text-xs font-weight-bold">
                                                    VS
                                                </span>
                                                <span class="font-weight-bold text-left" style="flex: 1; color: <?php echo e($p['visitante_color'] ?? '#1E293B'); ?>;">
                                                    <?php echo e($p['visitante_nombre']); ?>
                                                </span>
                                            </div>
                                            <div class="text-xs text-muted mt-1">
                                                <span class="mr-2"><?php echo e($p['delegacion_local']); ?></span>
                                                &bull;
                                                <span class="ml-2"><?php echo e($p['delegacion_visitante']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <?php if (!empty($p['cancha_nombre'])): ?>
                                            <div class="font-weight-bold text-dark">
                                                <?php echo e($p['cancha_nombre']); ?>
                                            </div>
                                            <div class="text-muted text-xs">
                                                <?php echo e($p['sede_nombre']); ?>
                                                <?php if (!empty($p['sede_latitud']) && !empty($p['sede_longitud'])): ?>
                                                    <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $p['sede_latitud']; ?>,<?php echo $p['sede_longitud']; ?>" target="_blank" class="ml-1 text-primary" title="Cómo llegar">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted text-xs">Sin cancha asignada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php 
                                        $hasMarcador = ($p['marcador_local'] !== null || $p['marcador_visitante'] !== null);
                                        $esTerminado = in_array($p['estado'], ['Finalizado', 'WO']);
                                        ?>
                                        <?php if ($hasMarcador || $esTerminado): ?>
                                            <span class="badge badge-light border font-weight-bold text-dark px-2 py-1" style="font-size: 0.95rem;">
                                                <?php echo isset($p['marcador_local']) && $p['marcador_local'] !== null ? (int)$p['marcador_local'] : 0; ?> - <?php echo isset($p['marcador_visitante']) && $p['marcador_visitante'] !== null ? (int)$p['marcador_visitante'] : 0; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted text-xs">&mdash;</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php
                                        $badgeClass = 'secondary';
                                        switch ($p['estado']) {
                                            case 'Programado':
                                                $badgeClass = 'info';
                                                break;
                                            case 'En_Juego':
                                                $badgeClass = 'primary';
                                                break;
                                            case 'Finalizado':
                                                $badgeClass = 'success';
                                                break;
                                            case 'Suspendido':
                                                $badgeClass = 'danger';
                                                break;
                                            case 'WO':
                                                $badgeClass = 'dark';
                                                break;
                                        }
                                        ?>
                                        <span class="badge badge-<?php echo $badgeClass; ?> px-2 py-1">
                                            <?php echo str_replace('_', ' ', e($p['estado'])); ?>
                                        </span>
                                    </td>
                                    <td class="text-center align-middle text-nowrap">
                                        <div class="d-inline-flex align-items-center justify-content-center" style="gap: 6px;">
                                            <?php if (AuthHelper::isAdmin()): ?>
                                                <button type="button" class="btn btn-xs btn-warning font-weight-bold px-2 py-1" data-toggle="modal" data-target="#modalScore<?php echo $p['id']; ?>" title="Carga Rápida de Marcador">
                                                    <i class="fas fa-edit mr-1"></i> Marcador
                                                </button>
                                            <?php endif; ?>
                                            <a href="<?php echo BASE_URL; ?>/partidos/<?php echo $p['id']; ?>/acta" class="btn btn-xs btn-outline-success font-weight-bold px-2 py-1" title="Acta Digital de Juego">
                                                Acta
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>/partidos/<?php echo $p['id']; ?>" class="btn btn-xs btn-outline-primary font-weight-bold px-2 py-1" title="Ficha del Partido y Ruta GPS">
                                                Detalle
                                            </a>
                                            <?php if (AuthHelper::isAdmin()): ?>
                                                <a href="<?php echo BASE_URL; ?>/partidos/<?php echo $p['id']; ?>/editar" class="btn btn-xs btn-info font-weight-bold px-2 py-1" title="Reprogramar Encuentro">
                                                    Editar
                                                </a>
                                                <form action="<?php echo BASE_URL; ?>/partidos/<?php echo $p['id']; ?>/eliminar" method="post" class="d-inline m-0 p-0" onsubmit="return confirm('¿Seguro de eliminar este encuentro del fixture?');">
                                                    <?php echo CsrfHelper::field(); ?>
                                                    <button type="submit" class="btn btn-xs btn-danger font-weight-bold px-2 py-1" title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (AuthHelper::isAdmin()): ?>
                                            <!-- Modal Carga Rápida de Marcador -->
                                            <div class="modal fade" id="modalScore<?php echo $p['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title font-weight-bold">
                                                                <i class="fas fa-edit mr-2"></i> Marcador: Encuentro #<?php echo $p['id']; ?>
                                                            </h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <form action="<?php echo BASE_URL; ?>/partidos/<?php echo $p['id']; ?>/resultado-rapido" method="post">
                                                            <?php echo CsrfHelper::field(); ?>
                                                            <input type="hidden" name="redirect_to" value="<?php echo BASE_URL; ?>/partidos">
                                                            <div class="modal-body">
                                                                <div class="text-center mb-3">
                                                                    <span class="badge badge-light border text-muted font-weight-bold px-2 py-1">
                                                                        <?php echo e($p['torneo_nombre']); ?> &bull; <?php echo e($p['fase']); ?> (<?php echo e($p['jornada']); ?>)
                                                                    </span>
                                                                </div>
                                                                <div class="d-flex align-items-center justify-content-center text-center mb-3">
                                                                    <div style="flex: 1;">
                                                                        <h6 class="font-weight-bold mb-1" style="color: <?php echo e($p['local_color'] ?? '#0066B2'); ?>;">
                                                                            <?php echo e($p['local_nombre']); ?>
                                                                        </h6>
                                                                        <small class="text-muted d-block mb-2"><?php echo e($p['delegacion_local']); ?></small>
                                                                        <input type="number" min="0" max="999" name="marcador_local" value="<?php echo ($p['marcador_local'] !== null) ? (int)$p['marcador_local'] : (($p['estado'] === 'Finalizado' || $p['estado'] === 'WO') ? 0 : ''); ?>" class="form-control form-control-lg text-center font-weight-bold mx-auto shadow-sm" style="max-width: 90px;" placeholder="0" autofocus>
                                                                    </div>
                                                                    <div class="px-3 font-weight-bold text-muted" style="font-size: 1.5rem;">
                                                                        VS
                                                                    </div>
                                                                    <div style="flex: 1;">
                                                                        <h6 class="font-weight-bold mb-1" style="color: <?php echo e($p['visitante_color'] ?? '#28A745'); ?>;">
                                                                            <?php echo e($p['visitante_nombre']); ?>
                                                                        </h6>
                                                                        <small class="text-muted d-block mb-2"><?php echo e($p['delegacion_visitante']); ?></small>
                                                                        <input type="number" min="0" max="999" name="marcador_visitante" value="<?php echo ($p['marcador_visitante'] !== null) ? (int)$p['marcador_visitante'] : (($p['marcador_local'] !== null || $p['estado'] === 'Finalizado' || $p['estado'] === 'WO') ? 0 : ''); ?>" class="form-control form-control-lg text-center font-weight-bold mx-auto shadow-sm" style="max-width: 90px;" placeholder="0">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mb-0">
                                                                    <label class="font-weight-bold text-xs text-muted">Estado del Partido</label>
                                                                    <select name="estado" class="form-control font-weight-bold">
                                                                        <option value="Finalizado" <?php echo ($p['estado'] === 'Finalizado' || $p['estado'] === 'Programado') ? 'selected' : ''; ?>>Finalizado</option>
                                                                        <option value="En_Juego" <?php echo ($p['estado'] === 'En_Juego') ? 'selected' : ''; ?>>En Juego</option>
                                                                        <option value="Programado" <?php echo ($p['estado'] === 'Programado') ? 'selected' : ''; ?>>Programado</option>
                                                                        <option value="WO" <?php echo ($p['estado'] === 'WO') ? 'selected' : ''; ?>>W.O.</option>
                                                                        <option value="Suspendido" <?php echo ($p['estado'] === 'Suspendido') ? 'selected' : ''; ?>>Suspendido</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light d-flex justify-content-between">
                                                                <a href="<?php echo BASE_URL; ?>/partidos/<?php echo $p['id']; ?>/acta" class="btn btn-sm btn-outline-secondary">
                                                                    <i class="fas fa-file-alt mr-1"></i> Abrir Acta Completa
                                                                </a>
                                                                <div>
                                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                                                                    <button type="submit" class="btn btn-primary btn-sm font-weight-bold">
                                                                        <i class="fas fa-save mr-1"></i> Guardar Marcador
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
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
