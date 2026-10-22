<?php
/**
 * Vista de Acta Digital de Juego (Marcador en vivo, sets e incidencias)
 *
 * @var array $partido
 * @var array $sets
 * @var array $incidencias
 * @var array $jugadoresLocal
 * @var array $jugadoresVisitante
 */
?>
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="card-title mb-0 card-title-cosmol">
                    Acta Digital de Juego #<?php echo e($partido['id']); ?>
                </h1>
                <span class="text-muted text-sm ml-2">
                    <?php echo e($partido['local_nombre']); ?> vs <?php echo e($partido['visitante_nombre']); ?>
                </span>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?php echo BASE_URL; ?>/partidos/<?php echo $partido['id']; ?>" class="btn btn-outline-primary font-weight-bold mr-1">
                    Ver Ficha
                </a>
                <a href="<?php echo BASE_URL; ?>/partidos" class="btn btn-secondary font-weight-bold">
                    Fixture
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Banner Informativo del Encuentro -->
        <div class="card card-cosmol shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="row align-items-center text-center text-md-left">
                    <div class="col-md-5 mb-2 mb-md-0">
                        <span class="badge badge-light border text-secondary font-weight-normal mr-2">
                            <?php echo e($partido['disciplina_nombre']); ?> &bull; <?php echo e($partido['torneo_rama']); ?>
                        </span>
                        <span class="badge badge-light border text-secondary font-weight-normal">
                            <?php echo e($partido['fase']); ?> - <?php echo e($partido['jornada']); ?>
                        </span>
                        <div class="font-weight-bold text-dark mt-1" style="font-size: 1.15rem;">
                            <span style="color: <?php echo e($partido['local_color'] ?? '#0066B2'); ?>;"><?php echo e($partido['local_nombre']); ?></span>
                            <span class="text-muted mx-2">vs</span>
                            <span style="color: <?php echo e($partido['visitante_color'] ?? '#64748B'); ?>;"><?php echo e($partido['visitante_nombre']); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0 text-md-center">
                        <div class="text-xs text-muted">Recinto y Campo:</div>
                        <div class="font-weight-bold text-dark text-sm">
                            <?php echo e($partido['sede_nombre'] ?? 'Sin sede'); ?> &mdash; <?php echo e($partido['nombre_cancha'] ?? 'Sin cancha'); ?>
                        </div>
                        <div class="text-xs text-muted">
                            <?php echo !empty($partido['fecha_partido']) ? date('d/m/Y', strtotime($partido['fecha_partido'])) : 'Fecha pendiente'; ?>
                            <?php echo !empty($partido['hora_partido']) ? ' &bull; ' . substr($partido['hora_partido'], 0, 5) . ' hrs' : ''; ?>
                        </div>
                    </div>
                    <div class="col-md-3 text-md-right">
                        <div class="text-xs text-muted mb-1">Estado del Partido:</div>
                        <?php
                        $badgeClass = 'secondary';
                        switch ($partido['estado']) {
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
                        <span class="badge badge-<?php echo $badgeClass; ?> px-3 py-1 font-weight-bold text-sm">
                            <?php echo str_replace('_', ' ', e($partido['estado'])); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Bloque 1: Control de Marcador Oficial y Estado -->
            <div class="col-lg-5">
                <div class="card card-outline card-primary shadow-sm mb-4">
                    <div class="card-header py-2">
                        <h3 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-scoreboard text-primary mr-1"></i> Marcador Oficial y Cierre
                        </h3>
                    </div>
                    <form action="<?php echo BASE_URL; ?>/partidos/<?php echo $partido['id']; ?>/resultado" method="post" enctype="multipart/form-data">
                        <?php echo CsrfHelper::field(); ?>
                        <div class="card-body">
                            
                            <div class="row align-items-center mb-3">
                                <div class="col-5 text-center">
                                    <label class="text-xs font-weight-bold mb-1 text-truncate d-block" style="color: <?php echo e($partido['local_color'] ?? '#0066B2'); ?>;">
                                        <?php echo e($partido['local_nombre']); ?>
                                    </label>
                                    <input type="number" min="0" max="999" name="marcador_local" class="form-control form-control-lg text-center font-weight-bold" style="font-size: 2rem; height: 60px;" value="<?php echo ($partido['marcador_local'] !== null) ? (int)$partido['marcador_local'] : ''; ?>" placeholder="0">
                                </div>
                                <div class="col-2 text-center text-muted font-weight-bold" style="font-size: 1.5rem;">
                                    -
                                </div>
                                <div class="col-5 text-center">
                                    <label class="text-xs font-weight-bold mb-1 text-truncate d-block" style="color: <?php echo e($partido['visitante_color'] ?? '#64748B'); ?>;">
                                        <?php echo e($partido['visitante_nombre']); ?>
                                    </label>
                                    <input type="number" min="0" max="999" name="marcador_visitante" class="form-control form-control-lg text-center font-weight-bold" style="font-size: 2rem; height: 60px;" value="<?php echo ($partido['marcador_visitante'] !== null) ? (int)$partido['marcador_visitante'] : ''; ?>" placeholder="0">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="estado" class="font-weight-bold text-xs text-dark mb-1">Estado Oficial del Partido</label>
                                <select name="estado" id="estado" class="form-control form-control-sm">
                                    <option value="Programado" <?php echo ($partido['estado'] === 'Programado') ? 'selected' : ''; ?>>Programado</option>
                                    <option value="En_Juego" <?php echo ($partido['estado'] === 'En_Juego') ? 'selected' : ''; ?>>En Juego</option>
                                    <option value="Finalizado" <?php echo ($partido['estado'] === 'Finalizado') ? 'selected' : ''; ?>>Finalizado (Cerrar Acta)</option>
                                    <option value="Suspendido" <?php echo ($partido['estado'] === 'Suspendido') ? 'selected' : ''; ?>>Suspendido</option>
                                    <option value="WO" <?php echo ($partido['estado'] === 'WO') ? 'selected' : ''; ?>>W.O. (No Presentación)</option>
                                </select>
                                <small class="form-text text-muted">Al marcar "Finalizado" o "W.O.", el resultado computa para las tablas de posiciones (en caso de W.O., coloque el marcador a favor del equipo que asistió; el que no asistió se queda con 0 puntos).</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="observaciones" class="font-weight-bold text-xs text-dark mb-1">Observaciones de Mesa / Árbitro</label>
                                <textarea name="observaciones" id="observaciones" rows="2" class="form-control form-control-sm" placeholder="Aclaraciones del encuentro..."><?php echo e($partido['observaciones'] ?? ''); ?></textarea>
                            </div>

                            <hr class="my-3">

                            <!-- Respaldo Digital: Foto de Planilla Oficial -->
                            <div class="form-group mb-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="font-weight-bold text-xs text-dark mb-0">
                                        <i class="fas fa-camera text-primary mr-1"></i> Foto de Planilla Oficial (Respaldo Físico)
                                    </label>
                                    <?php if (!empty($partido['foto_planilla'])): ?>
                                        <span class="badge badge-success px-2 py-1">
                                            <i class="fas fa-check-circle mr-1"></i> Planilla Adjunta
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary px-2 py-1 font-weight-normal">
                                            <i class="fas fa-file-upload mr-1"></i> Pendiente
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php
                                $tieneFoto = !empty($partido['foto_planilla']);
                                $esPdf     = $tieneFoto && (strtolower(pathinfo($partido['foto_planilla'], PATHINFO_EXTENSION)) === 'pdf');
                                $fotoUrl   = $tieneFoto ? (BASE_URL . '/' . htmlspecialchars($partido['foto_planilla'])) : '';
                                ?>

                                <?php if ($tieneFoto): ?>
                                    <div class="border rounded p-2 mb-2 bg-white shadow-sm">
                                        <?php if ($esPdf): ?>
                                            <div class="text-center py-3 bg-light rounded mb-2">
                                                <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                                <div class="text-xs font-weight-bold text-dark">Planilla Oficial en PDF</div>
                                                <div class="text-xs text-muted"><?php echo basename($partido['foto_planilla']); ?></div>
                                            </div>
                                        <?php else: ?>
                                            <div class="position-relative text-center mb-2" style="max-height: 160px; overflow: hidden; border-radius: 4px; background: #0F172A;">
                                                <img src="<?php echo $fotoUrl; ?>" alt="Planilla Oficial" class="img-fluid" style="max-height: 160px; width: auto; object-fit: contain; cursor: pointer;" data-toggle="modal" data-target="#modalFotoPlanilla" title="Clic para ampliar y verificar acta">
                                            </div>
                                        <?php endif; ?>

                                        <div class="d-flex flex-wrap align-items-center justify-content-between pt-1" style="gap: 5px;">
                                            <div>
                                                <button type="button" class="btn btn-xs btn-primary font-weight-bold" data-toggle="modal" data-target="#modalFotoPlanilla">
                                                    <i class="fas fa-search-plus mr-1"></i> Ver Planilla Completa
                                                </button>
                                                <a href="<?php echo $fotoUrl; ?>" download class="btn btn-xs btn-outline-secondary font-weight-bold ml-1">
                                                    <i class="fas fa-download mr-1"></i> Descargar
                                                </a>
                                            </div>
                                            <button type="submit" name="eliminar_foto_planilla" value="1" class="btn btn-xs btn-outline-danger" onclick="return confirm('¿Está seguro de eliminar el archivo de la planilla física?');" title="Eliminar planilla actual">
                                                <i class="fas fa-trash-alt mr-1"></i> Quitar
                                            </button>
                                        </div>

                                        <div class="mt-2 pt-2 border-top">
                                            <a href="#collapseReemplazarFoto" data-toggle="collapse" class="text-xs text-primary font-weight-bold">
                                                <i class="fas fa-sync-alt mr-1"></i> Reemplazar foto por una nueva...
                                            </a>
                                            <div class="collapse mt-2" id="collapseReemplazarFoto">
                                                <div class="custom-file">
                                                    <input type="file" name="foto_planilla" id="foto_planilla_replace" class="custom-file-input custom-file-input-sm" accept=".jpg,.jpeg,.png,.webp,.pdf" onchange="previewPlanillaSeleccionada(this, 'label_foto_replace', 'preview_replace_box', 'img_replace_preview');">
                                                    <label class="custom-file-label text-truncate text-xs" for="foto_planilla_replace" id="label_foto_replace">Seleccionar nueva foto o PDF...</label>
                                                </div>
                                                <div id="preview_replace_box" class="mt-2 text-center" style="display: none;">
                                                    <img id="img_replace_preview" src="" alt="Nueva planilla" class="img-thumbnail" style="max-height: 120px;">
                                                    <div class="text-xs text-success font-weight-bold mt-1">Nueva imagen seleccionada lista para guardar</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="border rounded p-2 bg-light">
                                        <div class="custom-file">
                                            <input type="file" name="foto_planilla" id="foto_planilla" class="custom-file-input custom-file-input-sm" accept=".jpg,.jpeg,.png,.webp,.pdf" onchange="previewPlanillaSeleccionada(this, 'label_foto_planilla', 'preview_nueva_foto', 'img_preview_nueva');">
                                            <label class="custom-file-label text-truncate text-xs" for="foto_planilla" id="label_foto_planilla">Seleccionar foto o PDF de planilla...</label>
                                        </div>
                                        <div id="preview_nueva_foto" class="mt-2 text-center" style="display: none;">
                                            <img id="img_preview_nueva" src="" alt="Vista previa de planilla" class="img-thumbnail shadow-sm mb-1" style="max-height: 140px;">
                                            <div class="text-xs text-success font-weight-bold">Vista previa de la planilla seleccionada</div>
                                        </div>
                                        <small class="form-text text-muted text-xs mt-1">
                                            <i class="fas fa-info-circle mr-1"></i> Foto nítida o escaneo del acta física con firmas (Máx. 8 MB: JPG, PNG, WEBP, PDF).
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                        <div class="card-footer bg-light text-right py-2">
                            <button type="submit" class="btn btn-sm btn-primary font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Guardar Marcador y Estado
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Bloque Sets (Voleibol y Disciplinas por Sets) -->
                <?php if (!empty($partido['formato_sets']) || !empty($sets)): ?>
                    <div class="card card-cosmol shadow-sm mb-4">
                        <div class="card-header py-2">
                            <h3 class="card-title font-weight-bold text-dark text-sm mb-0">
                                <i class="fas fa-volleyball-ball text-primary mr-1"></i> Control de Sets / Tiempos
                            </h3>
                        </div>
                        <div class="card-body pb-2">
                            
                            <!-- Formulario de nuevo set -->
                            <form action="<?php echo BASE_URL; ?>/partidos/<?php echo $partido['id']; ?>/sets/guardar" method="post" class="form-row align-items-end mb-3">
                                <?php echo CsrfHelper::field(); ?>
                                <div class="col-3 form-group mb-0">
                                    <label class="text-xs font-weight-bold text-muted mb-1">N° Set</label>
                                    <select name="numero_set" class="form-control form-control-sm" required>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php echo (count($sets) + 1 == $i) ? 'selected' : ''; ?>>
                                                Set <?php echo $i; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-3 form-group mb-0">
                                    <label class="text-xs font-weight-bold text-muted mb-1 text-truncate d-block">Local</label>
                                    <input type="number" min="0" max="150" name="puntos_local" class="form-control form-control-sm text-center font-weight-bold" placeholder="0" required>
                                </div>
                                <div class="col-3 form-group mb-0">
                                    <label class="text-xs font-weight-bold text-muted mb-1 text-truncate d-block">Visita</label>
                                    <input type="number" min="0" max="150" name="puntos_visitante" class="form-control form-control-sm text-center font-weight-bold" placeholder="0" required>
                                </div>
                                <div class="col-3 form-group mb-0">
                                    <button type="submit" class="btn btn-sm btn-primary btn-block font-weight-bold" title="Guardar Parcial de Set">
                                        + Set
                                    </button>
                                </div>
                            </form>

                            <?php if (!empty($partido['formato_sets'])): ?>
                                <div class="text-xs text-muted mb-2">
                                    <i class="fas fa-info-circle mr-1"></i> En voleibol, el marcador global de sets se sincroniza automáticamente.
                                </div>
                            <?php endif; ?>

                            <!-- Tabla de Sets -->
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0 text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Set</th>
                                            <th><?php echo e($partido['local_nombre']); ?></th>
                                            <th><?php echo e($partido['visitante_nombre']); ?></th>
                                            <th style="width: 50px;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($sets)): ?>
                                            <tr>
                                                <td colspan="4" class="text-muted py-2 text-xs">Sin sets registrados aún.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($sets as $st): ?>
                                                <tr>
                                                    <td class="font-weight-bold text-sm">Set <?php echo e($st['numero_set']); ?></td>
                                                    <td class="font-weight-bold <?php echo ((int)$st['puntos_local'] > (int)$st['puntos_visitante']) ? 'text-success' : 'text-dark'; ?>">
                                                        <?php echo e($st['puntos_local']); ?>
                                                    </td>
                                                    <td class="font-weight-bold <?php echo ((int)$st['puntos_visitante'] > (int)$st['puntos_local']) ? 'text-success' : 'text-dark'; ?>">
                                                        <?php echo e($st['puntos_visitante']); ?>
                                                    </td>
                                                    <td>
                                                        <form action="<?php echo BASE_URL; ?>/partidos/<?php echo $partido['id']; ?>/sets/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar este set?');">
                                                            <?php echo CsrfHelper::field(); ?>
                                                            <input type="hidden" name="set_id" value="<?php echo e($st['id']); ?>">
                                                            <button type="submit" class="btn btn-xs btn-outline-danger" title="Eliminar set">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Bloque 2: Registro y Cronología de Incidencias -->
            <div class="col-lg-7">
                <div class="card card-cosmol shadow-sm mb-4">
                    <div class="card-header py-2">
                        <h3 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-stopwatch text-primary mr-1"></i> Registro de Incidencias del Encuentro
                        </h3>
                    </div>
                    <div class="card-body">

                        <!-- Formulario para agregar Incidencia -->
                        <form action="<?php echo BASE_URL; ?>/partidos/<?php echo $partido['id']; ?>/incidencias/guardar" method="post" class="p-3 rounded mb-3" style="background-color: #F8FAFC; border: 1px solid #E2E8F0;">
                            <?php echo CsrfHelper::field(); ?>

                            <div class="row">
                                <div class="col-md-4 form-group mb-2">
                                    <label class="text-xs font-weight-bold text-dark mb-1">Equipo <span class="text-danger">*</span></label>
                                    <select name="equipo_id" id="incidencia_equipo_id" class="form-control form-control-sm" required onchange="actualizarJugadoresPorEquipo(this.value);">
                                        <option value="<?php echo e($partido['equipo_local_id']); ?>" selected>
                                            <?php echo e($partido['local_nombre']); ?> (Local)
                                        </option>
                                        <option value="<?php echo e($partido['equipo_visitante_id']); ?>">
                                            <?php echo e($partido['visitante_nombre']); ?> (Visita)
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-5 form-group mb-2">
                                    <label class="text-xs font-weight-bold text-dark mb-1">Jugador Involucrado</label>
                                    <select name="jugador_id" id="incidencia_jugador_id" class="form-control form-control-sm">
                                        <option value="">-- Sin asignar / General del equipo --</option>
                                        <optgroup label="Plantilla <?php echo e($partido['local_nombre']); ?>" id="optgroup_local">
                                            <?php foreach ($jugadoresLocal as $jl): ?>
                                                <option value="<?php echo e($jl['id']); ?>">
                                                    #<?php echo e($jl['dorsal']); ?> <?php echo e($jl['nombres']); ?> <?php echo e($jl['apellidos']); ?> (<?php echo e($jl['posicion'] ?? 'Jugador'); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                        <optgroup label="Plantilla <?php echo e($partido['visitante_nombre']); ?>" id="optgroup_visitante" style="display: none;">
                                            <?php foreach ($jugadoresVisitante as $jv): ?>
                                                <option value="<?php echo e($jv['id']); ?>">
                                                    #<?php echo e($jv['dorsal']); ?> <?php echo e($jv['nombres']); ?> <?php echo e($jv['apellidos']); ?> (<?php echo e($jv['posicion'] ?? 'Jugador'); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    </select>
                                </div>

                                <div class="col-md-3 form-group mb-2">
                                    <label class="text-xs font-weight-bold text-dark mb-1">Tipo Suceso <span class="text-danger">*</span></label>
                                    <select name="tipo" class="form-control form-control-sm" required>
                                        <option value="GOL">Gol Anotado</option>
                                        <option value="PUNTO">Punto Canasta/Set</option>
                                        <option value="AMARILLA">Tarjeta Amarilla</option>
                                        <option value="ROJA">Tarjeta Roja / Expulsión</option>
                                        <option value="FALTA">Falta / Infracción</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-3 form-group mb-2">
                                    <label class="text-xs font-weight-bold text-dark mb-1">Minuto / Tiempo</label>
                                    <input type="number" min="0" max="180" name="minuto_tiempo" class="form-control form-control-sm" placeholder="Ej. 25">
                                </div>

                                <div class="col-md-6 form-group mb-2">
                                    <label class="text-xs font-weight-bold text-dark mb-1">Detalle / Motivo</label>
                                    <input type="text" name="observaciones" class="form-control form-control-sm" placeholder="Tiro libre, falta grave, reclamo...">
                                </div>

                                <div class="col-md-3 form-group mb-2 text-right">
                                    <label class="d-none d-md-block text-xs mb-1">&nbsp;</label>
                                    <button type="submit" class="btn btn-sm btn-success btn-block font-weight-bold">
                                        <i class="fas fa-plus mr-1"></i> Anotar
                                    </button>
                                </div>
                            </div>

                            <?php if (empty($partido['formato_sets'])): ?>
                                <div class="form-check text-xs mt-1">
                                    <input class="form-check-input" type="checkbox" name="auto_sumar_marcador" value="1" id="autoSumarMarcador" checked>
                                    <label class="form-check-label text-muted" for="autoSumarMarcador">
                                        Sumar automáticamente al marcador global si es Gol
                                    </label>
                                </div>
                            <?php endif; ?>
                        </form>

                        <!-- Tabla de Incidencias Registradas -->
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="thead-cosmol">
                                    <tr>
                                        <th style="width: 50px;">Min</th>
                                        <th style="width: 100px;">Evento</th>
                                        <th>Equipo y Jugador</th>
                                        <th>Detalle / Motivo</th>
                                        <th class="text-center" style="width: 60px;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($incidencias)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                No se han registrado incidencias para este partido aún.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($incidencias as $inc): ?>
                                            <tr>
                                                <td class="font-weight-bold text-muted text-sm align-middle">
                                                    <?php echo ($inc['minuto_tiempo'] !== null) ? $inc['minuto_tiempo'] . "'" : "-"; ?>
                                                </td>
                                                <td class="align-middle">
                                                    <?php
                                                    switch ($inc['tipo']) {
                                                        case 'GOL':
                                                            echo '<span class="badge badge-success px-2 py-1"><i class="fas fa-futbol mr-1"></i>Gol</span>';
                                                            break;
                                                        case 'PUNTO':
                                                            echo '<span class="badge badge-info px-2 py-1">Punto</span>';
                                                            break;
                                                        case 'AMARILLA':
                                                            echo '<span class="badge badge-warning px-2 py-1"><i class="fas fa-square mr-1"></i>Amarilla</span>';
                                                            break;
                                                        case 'ROJA':
                                                            echo '<span class="badge badge-danger px-2 py-1"><i class="fas fa-square mr-1"></i>Roja</span>';
                                                            break;
                                                        case 'FALTA':
                                                            echo '<span class="badge badge-secondary px-2 py-1">Falta</span>';
                                                            break;
                                                        default:
                                                            echo '<span class="badge badge-light border px-2 py-1">' . e($inc['tipo']) . '</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="font-weight-bold text-dark text-sm"><?php echo e($inc['equipo_nombre']); ?></div>
                                                    <?php if (!empty($inc['jugador_nombres'])): ?>
                                                        <div class="text-xs text-muted">
                                                            #<?php echo e($inc['jugador_dorsal']); ?> <?php echo e($inc['jugador_nombres']); ?> <?php echo e($inc['jugador_apellidos']); ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="text-xs text-muted italic">Registro general del equipo</div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-sm text-muted align-middle">
                                                    <?php echo e($inc['observaciones'] ?? '-'); ?>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <form action="<?php echo BASE_URL; ?>/partidos/<?php echo $partido['id']; ?>/incidencias/eliminar" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta incidencia?');">
                                                        <?php echo CsrfHelper::field(); ?>
                                                        <input type="hidden" name="incidencia_id" value="<?php echo e($inc['id']); ?>">
                                                        <button type="submit" class="btn btn-xs btn-outline-danger" title="Eliminar incidencia">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
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
</div>

<!-- Modal de Inspección a Pantalla Completa: Foto de Planilla Oficial -->
<?php if (!empty($partido['foto_planilla'])): ?>
    <?php
    $esPdfModal   = (strtolower(pathinfo($partido['foto_planilla'], PATHINFO_EXTENSION)) === 'pdf');
    $fotoUrlModal = BASE_URL . '/' . htmlspecialchars($partido['foto_planilla']);
    ?>
    <div class="modal fade" id="modalFotoPlanilla" tabindex="-1" role="dialog" aria-labelledby="modalFotoPlanillaLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 95vw;">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header py-2" style="background-color: #003366; color: #fff;">
                    <h5 class="modal-title font-weight-bold text-sm" id="modalFotoPlanillaLabel">
                        <i class="fas fa-file-signature mr-2 text-warning"></i> Planilla Oficial Firmada &mdash; Encuentro #<?php echo (int)$partido['id']; ?>: <?php echo e($partido['local_nombre']); ?> vs <?php echo e($partido['visitante_nombre']); ?>
                    </h5>
                    <div class="d-flex align-items-center ml-auto mr-2">
                        <?php if (!$esPdfModal): ?>
                            <div class="btn-group mr-2">
                                <button type="button" class="btn btn-xs btn-outline-light" onclick="zoomPlanilla(0.2);" title="Acercar (Zoom +)">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-light" onclick="zoomPlanilla(-0.2);" title="Alejar (Zoom -)">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-light" onclick="rotarPlanilla(90);" title="Rotar 90°">
                                    <i class="fas fa-redo"></i>
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-light" onclick="resetZoomPlanilla();" title="Restablecer tamaño original">
                                    <i class="fas fa-undo"></i> 100%
                                </button>
                            </div>
                        <?php endif; ?>
                        <a href="<?php echo $fotoUrlModal; ?>" download class="btn btn-xs btn-light mr-2 font-weight-bold">
                            <i class="fas fa-download mr-1"></i> Descargar
                        </a>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="outline: none;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body p-2 text-center" style="background: #0F172A; min-height: 60vh; max-height: 80vh; overflow: auto;">
                    <?php if ($esPdfModal): ?>
                        <iframe src="<?php echo $fotoUrlModal; ?>" style="width: 100%; height: 75vh; border: none; border-radius: 4px; background: #fff;"></iframe>
                    <?php else: ?>
                        <div id="contenedorZoomPlanilla" style="display: inline-block; transition: transform 0.2s ease;">
                            <img id="imgPlanillaZoom" src="<?php echo $fotoUrlModal; ?>" alt="Planilla Oficial" style="max-width: 100%; height: auto; border-radius: 4px; box-shadow: 0 4px 20px rgba(0,0,0,0.5);">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer py-2 bg-light d-flex justify-content-between align-items-center">
                    <span class="text-xs text-muted">
                        <i class="fas fa-info-circle mr-1 text-primary"></i> 
                        <?php if ($esPdfModal): ?>
                            Documento PDF oficial cargado. Puede consultar, imprimir o descargarlo directamente.
                        <?php else: ?>
                            Use los botones superiores para acercar y rotar, inspeccionando números de goles/sets y firmas de capitanes y árbitros.
                        <?php endif; ?>
                    </span>
                    <div>
                        <a href="<?php echo $fotoUrlModal; ?>" target="_blank" class="btn btn-sm btn-outline-primary mr-1">
                            <i class="fas fa-external-link-alt mr-1"></i> Pestaña Nueva
                        </a>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
/**
 * Alterna la visibilidad de los optgroups según el equipo seleccionado
 */
function actualizarJugadoresPorEquipo(equipoId) {
    var optLocal = document.getElementById('optgroup_local');
    var optVis   = document.getElementById('optgroup_visitante');
    var localId  = "<?php echo (int)$partido['equipo_local_id']; ?>";

    if (equipoId === localId) {
        optLocal.style.display = '';
        optVis.style.display   = 'none';
    } else {
        optLocal.style.display = 'none';
        optVis.style.display   = '';
    }
    document.getElementById('incidencia_jugador_id').value = '';
}

/**
 * Variables y funciones de zoom/rotación para la foto de la planilla
 */
var zoomLevel = 1;
var rotateDeg = 0;

function zoomPlanilla(delta) {
    zoomLevel = Math.max(0.5, Math.min(3.5, zoomLevel + delta));
    aplicarTransformPlanilla();
}

function resetZoomPlanilla() {
    zoomLevel = 1;
    rotateDeg = 0;
    aplicarTransformPlanilla();
}

function rotarPlanilla(grados) {
    rotateDeg = (rotateDeg + grados) % 360;
    aplicarTransformPlanilla();
}

function aplicarTransformPlanilla() {
    var cont = document.getElementById('contenedorZoomPlanilla');
    if (cont) {
        cont.style.transform = 'scale(' + zoomLevel + ') rotate(' + rotateDeg + 'deg)';
    }
}

/**
 * Vista previa instantánea al seleccionar un archivo en la vista de acta
 */
function previewPlanillaSeleccionada(input, labelId, previewBoxId, imgPreviewId) {
    var label = document.getElementById(labelId);
    var previewBox = document.getElementById(previewBoxId);
    var previewImg = document.getElementById(imgPreviewId);

    if (input.files && input.files[0]) {
        var file = input.files[0];
        if (label) {
            label.textContent = file.name;
        }
        if (file.type && file.type.indexOf('image') !== -1 && previewBox && previewImg) {
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewBox.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else if (previewBox) {
            previewBox.style.display = 'none';
        }
    }
}
</script>
